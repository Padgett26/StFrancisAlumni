<?php
if (isset ( $_FILES ['image'] ['name'] )) {
	$imgcreated = filter_input ( INPUT_POST, 'created', FILTER_SANITIZE_NUMBER_INT );
	$dir = "";
	if (! is_dir ( "pics/$userid" )) {
		mkdir ( "pics/$userid", 0777, true );
	}
	$saveto = "pics/$userid/$imgcreated.jpg";
	move_uploaded_file ( $_FILES ['image'] ['tmp_name'], $saveto );
	$typeok = TRUE;

	switch ($_FILES ['image'] ['type']) {
		case "image/gif" :
			$src = imagecreatefromgif ( $saveto );
			break;

		case "image/jpeg" : // Both regular and progressive jpegs
		case "image/pjpeg" :
			$src = imagecreatefromjpeg ( $saveto );
			break;

		case "image/png" :
			$src = imagecreatefrompng ( $saveto );
			break;

		default :
			$typeok = FALSE;
			break;
	}

	if ($typeok) {
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
}

if (filter_input ( INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT )) {
	$limitpics = filter_input ( INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT );
} else {
	$limitpics = 10;
}

if (filter_input ( INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT )) {
	$delete = filter_input ( INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT );
	$item = filter_input ( INPUT_GET, 'item', FILTER_SANITIZE_NUMBER_INT );
	$name = filter_input ( INPUT_GET, 'name', FILTER_SANITIZE_NUMBER_INT );
	if ($item == $userid || $userlvl == "3" || ($userlvl == "2" && $selectyear == $useryear)) {
		echo "<div style='color:red; font-size:1.5em; float:left;'>Are you sure you want to delete this picture?</div><div style='float:left; margin-left:10px;'><form method='post' action='index.php?page=pictures'><input type='submit' value=' -No- ' /></form></div><div style='float:left; margin-left:10px;'><form method='post' action='index.php?page=pictures'><input type='hidden' name='delete2' value='$delete' /><input type='hidden' name='item' value='$item' /><input type='hidden' name='name' value='$name' /><input type='submit' value=' -Delete- ' /></form></div><br /><br />";
	} else
		echo "Nothing changed<br />";
}

if (filter_input ( INPUT_POST, 'delete2', FILTER_SANITIZE_NUMBER_INT )) {
	$delete = filter_input ( INPUT_POST, 'delete2', FILTER_SANITIZE_NUMBER_INT );
	$item = filter_input ( INPUT_POST, 'item', FILTER_SANITIZE_NUMBER_INT );
	$name = filter_input ( INPUT_POST, 'name', FILTER_SANITIZE_NUMBER_INT );
	if ($item == $userid || $userlvl == "3" || ($userlvl == "2" && $selectyear == $useryear)) {
		$stmt = $db_conn->prepare ( "DELETE FROM pictures WHERE id=?" );
		$stmt->execute ( array (
				$delete
		) );
		unlink ( "pics/$item/$name.jpg" );
	} else
		echo "Nothing changed<br />";
}

if (filter_input ( INPUT_POST, 'newpicup', FILTER_SANITIZE_NUMBER_INT )) {
	$caption = filter_input ( INPUT_POST, 'newpicture', FILTER_SANITIZE_STRING );
	$created = filter_input ( INPUT_POST, 'created', FILTER_SANITIZE_NUMBER_INT );
	$stmt = $db_conn->prepare ( "INSERT INTO pictures VALUES" . "(NULL,?,?,?,?)" );
	$stmt->execute ( array (
			$selectyear,
			$caption,
			$userid,
			$created
	) );
	echo "Picture uploaded...<br />";
}
if ($selectyear == $useryear || $userlvl == "3") {
	$piccreated = time ();
	echo "Post a new picture:<form method='post' action='index.php?page=pictures' enctype='multipart/form-data'><input type='file' name='image' size='20' /><br />Caption:<br /><textarea name='newpicture' cols='70' rows='5' maxlength='775'></textarea><br />Pictures may take a while to upload and resize, please be patient.<br /><input type='hidden' name='created' value='$piccreated' /><input type='hidden' name='newpicup' value='1' /><input type='submit' value=' -Submit- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}

echo "<div style='margin-right:10px;'>Display: ";
if ($limitpics == 10) {
	echo "10";
} else {
	echo "<a href='index.php?page=pictures&limit=10'>10</a>";
}
echo " | ";
if ($limitpics == 25) {
	echo "25";
} else {
	echo "<a href='index.php?page=pictures&limit=25'>25</a>";
}
echo " | ";
if ($limitpics == 50) {
	echo "50";
} else {
	echo "<a href='index.php?page=pictures&limit=50'>50</a>";
}
echo " | ";
if ($limitpics == 100) {
	echo "100";
} else {
	echo "<a href='index.php?page=pictures&limit=100'>100</a>";
}
echo "</div>";

$stmt = $db_conn->prepare ( "SELECT * FROM pictures WHERE year=? ORDER BY created DESC LIMIT $limitpics" );
$stmt->execute ( array (
		$selectyear
) );
while ( $rowpics = $stmt->fetch () ) {
	$idpics = $rowpics [0];
	$yearpics = $rowpics [1];
	$captionpics = nl2br ( $rowpics [2] );
	$fromuserpics = $rowpics [3];
	$createdpics = $rowpics [4];
	$substmt = $db_conn->prepare ( "SELECT firstname, miname, lastname, COUNT(*) FROM users WHERE id=$fromuserpics" );
	$substmt->execute ();
	$subrowpics = $substmt->fetch ();
	$countpics = $subrowpics [3];
	if ($countpics == "1") {
		$namepics = $subrowpics [0];
		if ($subrowpics [1])
			$namepics .= " " . $subrowpics [1];
		$namepics .= " " . $subrowpics [2];
		$msgpics = $fromuserpics;
	} else {
		$namepics = "System Admin";
		$msgpics = "1";
	}

	echo <<<_END
	   <table cellpadding="10px" cellspacing="0px" width="100%" style="margin-top:10px; border:2px solid #000000; border-radius:25px; -moz-border-radius:25px;">
	       <tr>
	       <td style="border-right:2px solid #000000; vertcal-align:top; width:25%;">
	           <div style="margin-top:20px;">$namepics<br />
	_END;
	if ($fromuserpics == $userid || $userlvl == "3" || ($userlvl == "2" && $selectyear == $useryear))
		echo "<a href='index.php?page=pictures&delete=$idpics&item=$fromuserpics&name=$createdpics'>Delete this picture</a><br />";
	if ($fromuserpics != $userid && $userlvl != '0')
		echo "<a href='index.php?page=messages&to=$msgpics'>Send private message</a><br />";
	if ($fromuserpics != $userid && $userlvl == "1")
		echo "<a href='index.php?page=report&repuserid=$fromuserpics&repbbid=$idpics&reptext=$captionpics'>Report this picture.</a><br />";
	if ($fromuserpics != $userid && $selectyear != $useryear && $userlvl == "2")
		echo "<a href='index.php?page=report&repuserid=$fromuserpics&reppicid=$idpics&reptext=$captionpics'>Report this picture.</a><br />";
	echo "</td><td style='vertical-align:top; width:75%;'>";
	echo "<img src='pics/$fromuserpics/$createdpics.jpg' style='margin-left:40px; margin-right:40px; max-width:500px;' alt='' /><br /><br /><div style='text-align:justify; font-size:1.25em;'>$captionpics</div><br />";
	echo "</td></tr></table>";
}
echo "<br /><br />";
?>