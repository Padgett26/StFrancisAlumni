<?php
if (isset ( $_FILES ['image'] ['name'] )) {
	$folder = "pics/yearbooks/$adminyear";
	$picname = filter_input ( INPUT_POST, 'picname', FILTER_SANITIZE_NUMBER_INT );
	if (! is_dir ( "$folder" )) {
		mkdir ( "$folder", 0777, true );
	}
	$saveto = "$folder/$picname.jpg";
	move_uploaded_file ( $_FILES ['image'] ['tmp_name'], $saveto );
	$typeok = "1";

	switch ($_FILES ['image'] ['type']) {
		case "image/gif" :
			$src = imagecreatefromgif ( $saveto );
			break;
		case "image/jpg" :
			$src = imagecreatefromjpeg ( $saveto );
			break;
		case "image/jpeg" :
			$src = imagecreatefromjpeg ( $saveto );
			break;
		case "image/pjpeg" :
			$src = imagecreatefromjpeg ( $saveto );
			break;
		case "image/png" :
			$src = imagecreatefrompng ( $saveto );
			break;
		default :
			$typeok = "0";
			break;
	}

	if ($typeok == "1") {
		list ( $w, $h ) = (getimagesize ( $saveto ) != null) ? getimagesize ( $saveto ) : null;
		$max = 800;
		$tw = $w;
		$th = $h;

		if ($w > $h && $max < $w) {
			$th = $max / $w * $h;
			$tw = $max;
		} elseif ($h > $w && $max < $h) {
			$tw = $max / $h * $w;
			$th = $max;
		} elseif ($max < $w) {
			$tw = $th = $max;
		}

		$tmp = imagecreatetruecolor ( $tw, $th );
		imagecopyresampled ( $tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h );
		imageconvolution ( $tmp, array ( // Sharpen image
				array (
						- 1,
						- 1,
						- 1
				),
				array (
						- 1,
						16,
						- 1
				),
				array (
						- 1,
						- 1,
						- 1
				)
		), 8, 0 );
		imagejpeg ( $tmp, $saveto );
		imagedestroy ( $tmp );
		imagedestroy ( $src );
	}
	if ($typeok == "0") {
		unlink ( "$folder/$picname.jpg" );
		echo "Your picture was not uploaded. The accepted file types are: gif, jpg, jpeg, pjpeg, and png.";
	}
}

if (filter_input ( INPUT_POST, 'editybook', FILTER_SANITIZE_NUMBER_INT )) {
	$pic = filter_input ( INPUT_POST, 'editybook', FILTER_SANITIZE_NUMBER_INT );
	$delpic = filter_input ( INPUT_POST, 'delybookpic', FILTER_SANITIZE_STRING );
	$rename = filter_input ( INPUT_POST, 'renameybook', FILTER_SANITIZE_NUMBER_INT );
	if ($rename) {
		$old = "/home/alumni/public_html/pics/yearbooks/$adminyear/$pic.jpg";
		$new = "/home/alumni/public_html/pics/yearbooks/$adminyear/$rename.jpg";
		rename ( $old, $new ) or die ();
	}
	if ($delpic) {
		$file = "/home/alumni/public_html/pics/yearbooks/$adminyear/$delpic";
		unlink ( $file ) or die ();
	}
}

if (! is_dir ( "pics/yearbooks/$adminyear" )) {
	mkdir ( "pics/yearbooks/$adminyear", 0777, true );
}
echo "Add a yearbook page:<br />";
echo "<form method='post' action='index.php?page=alladmin&choice=ybook' enctype='multipart/form-data'>Number the page, do not use a number already being used by another page, it will overwrite it.<br />1-999 <input type='text' name='picname' size='3' maxlength='3' /><br /><input type='file' name='image' size='20' /><br /><input type='submit' value=' -Upload- ' /></form>";
echo "Currently loaded yearbook pictures:<br />";
$pages = array ();
foreach ( new DirectoryIterator ( "pics/yearbooks/$adminyear" ) as $j ) {
	if (! $j->isDot ()) {
		$pages [] = "$j";
	}
}
sort ( $pages, SORT_NUMERIC );
foreach ( $pages as $j ) {
	preg_match ( "/^([1-9][0-9]*)/", $j, $match );
	$name = $match [0];
	echo <<<_END
	    <form method='post' action='index.php?page=alladmin&choice=ybook' enctype='multipart/form-data'>
	    <div style='border:1px solid #000000; padding:5px; margin:5px; float:left;'>
	        <img src='pics/yearbooks/$adminyear/$j' style='max-width:250px; margin:10px auto;' alt='' />
	        <div style='text-align:center;'>$name<br />
	            Rename the page.<br />Must use numbers to put them in order:<br /><input type='text' name='renameybook' value='$name' maxlength='3' size='3' /><br /><br />
	            <input type='checkbox' name='delybookpic' value='$j' id='delybookpic' /><label for='delybookpic'>Delete this page.</label><br /><br />
	            -Or replace the page-<br /><input type='file' name='image' size='20' /><br />
	            <input type='hidden' name='picname' value='$name' />
	            <input type='hidden' name='editybook' value='$name' />
	            <input type='submit' value='Edit page' />
	        </div>
	    </div>
	    </form>
	_END;
}
?>
