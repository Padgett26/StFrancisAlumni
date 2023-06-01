<?php
if (filter_input ( INPUT_POST, 'picup', FILTER_SANITIZE_NUMBER_INT ))
	$picup = "1";

if ($picup == "1") {
	$folder = "pics/seniorpics/$adminyear";
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
		$max = 150;
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

if (filter_input ( INPUT_POST, 'senioredit', FILTER_SANITIZE_STRING )) {
	$seniorid = filter_input ( INPUT_POST, 'senioredit', FILTER_SANITIZE_STRING );
	if ($seniorid == "new") {
		echo "<form method='post' action='index.php?page=alladmin&choice=senior'>
            Name:<br />
            <input type='text' name='seniorcaption' value='' maxlength='78' /><br />
            <input type='hidden' name='createsenior' value='1' />
            <input type='submit' value=' -Update- ' /></form><br /><br />";
	} else {
		$stmt = $db_conn->prepare ( "SELECT * FROM seniorpics WHERE id=?" );
		$stmt->execute ( array (
				$seniorid
		) );
		$rowsen = $stmt->fetch ();
		$yearsen = $rowsen [1];
		$captionsen = $rowsen [2];
		echo "<form method='post' action='index.php?page=alladmin&choice=senior' enctype='multipart/form-data'>
        Currently loaded picture:<br />
        <img src='pics/seniorpics/$adminyear/$seniorid.jpg' alt='' /><br />
        <input type='checkbox' name='picup' value=1' rel='picup' /> Change the picture:<br />
        <div rel='picup'><input type='file' name='image' size='40' rel='none' /></div>
        <input type='hidden' name='picname' value='$seniorid' /><br />
        Name:<br />
        <input type='text' name='seniorcaption' value='$captionsen' maxlength='78' rel='none' /><br />
        <input type='hidden' name='updatesenior' value='$seniorid' /><input type='submit' value=' -Update- ' /></form><br /><br />";
	}
}

if (filter_input ( INPUT_POST, 'createsenior', FILTER_SANITIZE_NUMBER_INT )) {
	$seniorcaption = filter_input ( INPUT_POST, 'seniorcaption', FILTER_SANITIZE_STRING );
	$stmt = $db_conn->prepare ( "INSERT INTO seniorpics VALUES" . "(NULL, ?, ?, '0', '0', '0')" );
	$stmt->execute ( array (
			$adminyear,
			$seniorcaption
	) );
	$substmt = $db_conn->prepare ( "SELECT id FROM seniorpics WHERE year=? AND caption=?" );
	$substmt->execute ( array (
			$adminyear,
			$seniorcaption
	) );
	$row = $substmt->fetch ();
	$getsenior = $row ['id'];
	echo "<form method='post' action='index.php?page=alladmin&choice=senior' enctype='multipart/form-data'>
    Upload the picture for $seniorcaption:<br />
    <input type='file' name='image' size='40' />
    <input type='hidden' name='picname' value='$getsenior' /><input type='hidden' name='picup' value='1' /><br />
    <input type='submit' value=' -Upload- ' /></form><br /><br />";
}

if (filter_input ( INPUT_POST, 'updatesenior', FILTER_SANITIZE_NUMBER_INT )) {
	$seniorid = filter_input ( INPUT_POST, 'updatesenior', FILTER_SANITIZE_NUMBER_INT );
	$seniorcaption = filter_input ( INPUT_POST, 'seniorcaption', FILTER_SANITIZE_STRING );
	$stmt = $db_conn->prepare ( "UPDATE seniorpics SET year=?, caption=? WHERE id=?" );
	$stmt->execute ( array (
			$adminyear,
			$seniorcaption,
			$seniorid
	) );
}

echo "Currently installed senior pictures:<br />";
$stmt = $db_conn->prepare ( "SELECT * FROM seniorpics WHERE year=?" );
$stmt->execute ( array (
		$adminyear
) );
echo "<form method='post' action='index.php?page=alladmin&choice=senior'><input type='radio' name='senioredit' value='new' id='senioredit' /> <label for='senioredit'>Add a new senior picture</label><span style='margin-left:40px;'><input type='submit' value=' -Go- ' /></span><br />";
while ( $rowsenior = $stmt->fetch () ) {
	$idsenior = $rowsenior [0];
	$captionsenior = $rowsenior [2];
	echo "<div style='border:1px solid #000000; float:left; padding:5px; margin:5px;'>
    <center>
    <img src='pics/seniorpics/$adminyear/$idsenior.jpg' alt='' />
    </center>
    <div style='text-align:center;'>$captionsenior<br /><input type='radio' name='senioredit' value='$idsenior' />Edit this senior</div></div>";
}
echo "</form>";
?>
