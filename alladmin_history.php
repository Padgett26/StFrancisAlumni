<?php
if (isset ( $_FILES ['image1'] ['name'] )) {
	$picname = filter_input ( INPUT_POST, 'picname1', FILTER_SANITIZE_NUMBER_INT );
	$saveto = "img/history/$picname.jpg";
	$tempsaveto = "img/history/temp$picname.jpg";
	move_uploaded_file ( $_FILES ['image1'] ['tmp_name'], $tempsaveto );
	$typeok = "1";

	switch ($_FILES ['image1'] ['type']) {
		case "image/gif" :
			$src = imagecreatefromgif ( $tempsaveto );
			break;
		case "image/jpg" :
			$src = imagecreatefromjpeg ( $tempsaveto );
			break;
		case "image/jpeg" :
			$src = imagecreatefromjpeg ( $tempsaveto );
			break;
		case "image/pjpeg" :
			$src = imagecreatefromjpeg ( $tempsaveto );
			break;
		case "image/png" :
			$src = imagecreatefrompng ( $tempsaveto );
			break;
		default :
			$typeok = "0";
			break;
	}

	if ($typeok == "1") {
		list ( $w, $h ) = (getimagesize ( $tempsaveto ) != null) ? getimagesize ( $tempsaveto ) : null;
		$max = 500;
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
		unlink ( "$tempsaveto" );
	}
	if ($typeok == "0") {
		unlink ( "$tempsaveto" );
		echo "Your picture was not uploaded. The accepted file types are: gif, jpg, jpeg, pjpeg, and png.<br /><br />";
	}
}

if (isset ( $_FILES ['image2'] ['name'] )) {
	$picname = filter_input ( INPUT_POST, 'picname2', FILTER_SANITIZE_NUMBER_INT );
	$saveto = "img/history/$picname.jpg";
	$tempsaveto = "img/history/temp$picname.jpg";
	move_uploaded_file ( $_FILES ['image2'] ['tmp_name'], $tempsaveto );
	$typeok = "1";

	switch ($_FILES ['image2'] ['type']) {
		case "image/gif" :
			$src = imagecreatefromgif ( $tempsaveto );
			break;
		case "image/jpg" :
			$src = imagecreatefromjpeg ( $tempsaveto );
			break;
		case "image/jpeg" :
			$src = imagecreatefromjpeg ( $tempsaveto );
			break;
		case "image/pjpeg" :
			$src = imagecreatefromjpeg ( $tempsaveto );
			break;
		case "image/png" :
			$src = imagecreatefrompng ( $tempsaveto );
			break;
		default :
			$typeok = "0";
			break;
	}

	if ($typeok == "1") {
		list ( $w, $h ) = (getimagesize ( $tempsaveto ) != null) ? getimagesize ( $tempsaveto ) : null;
		$max = 500;
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
		unlink ( "$tempsaveto" );
	}
	if ($typeok == "0") {
		unlink ( "$tempsaveto" );
		echo "Your picture was not uploaded. The accepted file types are: gif, jpg, jpeg, pjpeg, and png.<br /><br />";
	}
}

if (filter_input ( INPUT_GET, 'del', FILTER_SANITIZE_NUMBER_INT )) {
	$del = filter_input ( INPUT_GET, 'del', FILTER_SANITIZE_NUMBER_INT );
	if ($userlvl == "3") {
		$stmt = $db_conn->prepare ( "DELETE FROM history WHERE id=?" );
		$stmt->execute ( array (
				$del
		) );
		if (file_exists ( "img/history/$del.jpg" ))
			unlink ( "img/history/$del.jpg" );
	}
}

if (filter_input ( INPUT_GET, 'title', FILTER_SANITIZE_STRING )) {
	$title = filter_input ( INPUT_GET, 'title', FILTER_SANITIZE_STRING );
	$stmt = $db_conn->prepare ( "SELECT id FROM history WHERE title=?" );
	$stmt->execute ( array (
			$title
	) );
	$row = $stmt->fetch ();
	$id = $row ['id'];
	echo "<form method='post' action='index.php?page=alladmin&choice=history' enctype='multipart/form-data'><input type='file' name='image'><input type=''hidden' name='picname' value='$id' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}

if (filter_input ( INPUT_POST, 'histpageup', FILTER_SANITIZE_STRING )) {
	$idhistup = filter_input ( INPUT_POST, 'histpageup', FILTER_SANITIZE_STRING );
	$titlehistup = filter_input ( INPUT_POST, 'title', FILTER_SANITIZE_STRING );
	$texthistup = filter_input ( INPUT_POST, 'text', FILTER_SANITIZE_STRING );
	$delhist = filter_input ( INPUT_POST, 'delhist', FILTER_SANITIZE_NUMBER_INT );
	if ($idhistup == "new") {
		$stmt = $db_conn->prepare ( "INSERT INTO history VALUES" . "(NULL,?,?)" );
		$stmt->execute ( array (
				$titlehistup,
				$texthistup
		) );
		echo "The page has been uploaded...<br />If you have a picture to upload with this article <a href='index.php?page=alladmin&choice=history&title=$titlehistup'>Click Here</a>";
	} else {
		if ($delhist == '1') {
			echo "Are you sure you want to delete this article? <a href='index.php?page=alladmin&choice=history&del=$idhistup'>YES</a> <a href='index.php?page=alladmin&choice=history'>NO</a>";
		} else {
			$stmt = $db_conn->prepare ( "UPDATE history SET title=?, text=? WHERE id=?" );
			$stmt->execute ( array (
					$titlehistup,
					$texthistup,
					$idhistup
			) );
			echo "The page has been updated...";
		}
	}
}

echo "Select the article to edit or select new:<br /><br /><a href='index.php?page=alladmin&choice=history&article=new'>Create a new article</a><br />";
$sql = "SELECT id,title FROM history";
foreach ( $db_conn->query ( $sql ) as $row ) {
	$idhist = $row ['id'];
	$titlehist = $row ['title'];
	echo "<a href='index.php?page=alladmin&choice=history&article=$idhist'>$titlehist</a><br />";
}

if (filter_input ( INPUT_GET, 'article', FILTER_SANITIZE_STRING )) {
	$article = filter_input ( INPUT_GET, 'article', FILTER_SANITIZE_STRING );
	$stmt = $db_conn->prepare ( "SELECT * FROM history WHERE id=?" );
	$stmt->execute ( array (
			$article
	) );
	$row = $stmt->fetch ();
	$title = $row [1];
	$text = $row [2];
	echo "<form method='post' action='index.php?page=alladmin&choice=history' enctype='multipart/form-data'>
    Title:<br />
    <input type='text' name='title' value='$title' size='40' maxlength='40' /><br /><br />
    Text:<br />
    <textarea name='text' cols='60' rows='10' maxlength='20000'>$text</textarea><br /><br />";
	if ($article != 'new')
		echo "Currently installed picture 1:<br /><img src='img/history/" . $article . "1.jpg' alt='' /><br /><br />Upload a new picture:<br /><input type='file' name='image1' /><input type='hidden' name='picname1' value='" . $article . "1' /><br /><br />
        Currently installed picture 2:<br /><img src='img/history/" . $article . "2.jpg' alt='' /><br /><br />Upload a new picture:<br /><input type='file' name='image2' /><input type='hidden' name='picname2' value='" . $article . "2' /><br /><br />
        Delete this article: <input type='checkbox' name='delhist' value='1' />";
	echo "<input type='hidden' name='histpageup' value='$article' />
    <input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' />
    </form><br /><br />";
}

?>
