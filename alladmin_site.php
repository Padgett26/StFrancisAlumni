<?php
if (isset ( $_FILES ['image'] ['name'] )) {
	$folder = filter_input ( INPUT_POST, 'folder', FILTER_SANITIZE_STRING );
	$picname = filter_input ( INPUT_POST, 'picname', FILTER_SANITIZE_STRING );
	$picsize = filter_input ( INPUT_POST, 'picsize', FILTER_SANITIZE_NUMBER_INT );
	if (! is_dir ( "$folder" )) {
		mkdir ( "$folder", 0777, true );
	}
	$saveto = "$folder/$picname.jpg";
	move_uploaded_file ( $_FILES ['image'] ['tmp_name'], $saveto );
	$typeok = "1";

	switch ($_FILES ['image'] ['type']) {
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
		list ( $w, $h ) = (getimagesize ( $saveto ) != null) ? getimagesize ( $saveto ) : null;
		$max = $picsize;
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
	} else {
		unlink ( "$folder/$picname.jpg" );
		echo "Your picture was not uploaded. The accepted file types are: gif, jpg, jpeg, pjpeg, and png.";
	}
}

$site = filter_input ( INPUT_GET, 'site', FILTER_SANITIZE_STRING );
if ($site == "colors") {
	$stmt = $db_conn->prepare ( "SELECT schoolColorLight, schoolColorDark FROM sitesettings WHERE id='1'" );
	$stmt->execute ();
	$rowcolor = $stmt->fetch ();
	$light = $rowcolor ['schoolColorLight'];
	$dark = $rowcolor ['schoolColorDark'];
	echo "<form method='post' action='index.php?page=alladmin&choice=site'>What are your school colors?<br />Lighter color:<br /><select name='schoolcolorlight' size='1'>
        <option value='custom' style='background-color:#ffffff; color:#000000;'>Custom Color</option>
        <option value='#00FFFF' style='background-color:#00FFFF; color:#000000;'";
	if ($light == "#00FFFF")
		echo " selected='selected'";
	echo ">Aqua</option><option value='#000000' style='background-color:#000000; color:#ffffff;'";
	if ($light == "#000000")
		echo " selected='selected'";
	echo ">Black</option><option value='#0000FF' style='background-color:#0000FF; color:#ffffff;'";
	if ($light == "#0000FF")
		echo " selected='selected'";
	echo ">Blue</option><option value='#A52A2A' style='background-color:#A52A2A; color:#ffffff;'";
	if ($light == "#A52A2A")
		echo " selected='selected'";
	echo ">Brown</option><option value='#FFD700' style='background-color:#FFD700; color:#000000;'";
	if ($light == "#FFD700")
		echo " selected='selected'";
	echo ">Gold</option><option value='#808080' style='background-color:#808080; color:#ffffff;'";
	if ($light == "#808080")
		echo " selected='selected'";
	echo ">Grey</option><option value='#008000' style='background-color:#008000; color:#ffffff;'";
	if ($light == "#008000")
		echo " selected='selected'";
	echo ">Green</option><option value='#00FF00' style='background-color:#00FF00; color:#000000;'";
	if ($light == "#00FF00")
		echo " selected='selected'";
	echo ">Lime</option><option value='#800000' style='background-color:#800000; color:#ffffff;'";
	if ($light == "#800000")
		echo " selected='selected'";
	echo ">Maroon</option><option value='#000080' style='background-color:#000080; color:#ffffff;'";
	if ($light == "#000080")
		echo " selected='selected'";
	echo ">Navy</option><option value='#808000' style='background-color:#808000; color:#000000;'";
	if ($light == "#808000")
		echo " selected='selected'";
	echo ">Olive</option><option value='#FFA500' style='background-color:#FFA500; color:#000000;'";
	if ($light == "#FFA500")
		echo " selected='selected'";
	echo ">Orange</option><option value='#800080' style='background-color:#800080; color:#ffffff;'";
	if ($light == "#800080")
		echo " selected='selected'";
	echo ">Purple</option><option value='#FF0000' style='background-color:#FF0000; color:#000000;'";
	if ($light == "#FF0000")
		echo " selected='selected'";
	echo ">Red</option><option value='#C0C0C0' style='background-color:#C0C0C0; color:#000000;'";
	if ($light == "#C0C0C0")
		echo " selected='selected'";
	echo ">Silver</option><option value='#D2B48C' style='background-color:#D2B48C; color:#000000;'";
	if ($light == "#D2B48C")
		echo " selected='selected'";
	echo ">Tan</option><option value='#008080' style='background-color:#008080; color:#ffffff;'";
	if ($light == "#008080")
		echo " selected='selected'";
	echo ">Teal</option><option value='#ffffff' style='background-color:#ffffff; color:#000000;'";
	if ($light == "#ffffff")
		echo " selected='selected'";
	echo ">White</option><option value='#FFFF00' style='background-color:#FFFF00; color:#000000;'";
	if ($light == "#FFFF00")
		echo " selected='selected'";
	echo ">Yellow</option></select><br />
            If you selected to enter a custom (lighter) color, enter it here: (Must be in hexidecimal format, example #121212)<br />
            <input type='text' name='customcolorlight' value='#' maxlength='7' size='10' /><br /><br />
            Darker color:<br />
            <select name='schoolcolordark' size='1'>
            <option value='custom' style='background-color:#ffffff; color:#000000;'>Custom Color</option>
            <option value='#00FFFF' style='background-color:#00FFFF; color:#000000;'";
	if ($dark == "#00FFFF")
		echo " selected='selected'";
	echo ">Aqua</option><option value='#000000' style='background-color:#000000; color:#ffffff;'";
	if ($dark == "#000000")
		echo " selected='selected'";
	echo ">Black</option><option value='#0000FF' style='background-color:#0000FF; color:#ffffff;'";
	if ($dark == "#0000FF")
		echo " selected='selected'";
	echo ">Blue</option><option value='#A52A2A' style='background-color:#A52A2A; color:#ffffff;'";
	if ($dark == "#A52A2A")
		echo " selected='selected'";
	echo ">Brown</option><option value='#FFD700' style='background-color:#FFD700; color:#000000;'";
	if ($dark == "#FFD700")
		echo " selected='selected'";
	echo ">Gold</option><option value='#808080' style='background-color:#808080; color:#ffffff;'";
	if ($dark == "#808080")
		echo " selected='selected'";
	echo ">Grey</option><option value='#008000' style='background-color:#008000; color:#ffffff;'";
	if ($dark == "#008000")
		echo " selected='selected'";
	echo ">Green</option><option value='#00FF00' style='background-color:#00FF00; color:#000000;'";
	if ($dark == "#00FF00")
		echo " selected='selected'";
	echo ">Lime</option><option value='#800000' style='background-color:#800000; color:#ffffff;'";
	if ($dark == "#800000")
		echo " selected='selected'";
	echo ">Maroon</option><option value='#000080' style='background-color:#000080; color:#ffffff;'";
	if ($dark == "#000080")
		echo " selected='selected'";
	echo ">Navy</option><option value='#808000' style='background-color:#808000; color:#000000;'";
	if ($dark == "#808000")
		echo " selected='selected'";
	echo ">Olive</option><option value='#FFA500' style='background-color:#FFA500; color:#000000;'";
	if ($dark == "#FFA500")
		echo " selected='selected'";
	echo ">Orange</option><option value='#800080' style='background-color:#800080; color:#ffffff;'";
	if ($dark == "#800080")
		echo " selected='selected'";
	echo ">Purple</option><option value='#FF0000' style='background-color:#FF0000; color:#000000;'";
	if ($dark == "#FF0000")
		echo " selected='selected'";
	echo ">Red</option><option value='#C0C0C0' style='background-color:#C0C0C0; color:#000000;'";
	if ($dark == "#C0C0C0")
		echo " selected='selected'";
	echo ">Silver</option><option value='#D2B48C' style='background-color:#D2B48C; color:#000000;'";
	if ($dark == "#D2B48C")
		echo " selected='selected'";
	echo ">Tan</option><option value='#008080' style='background-color:#008080; color:#ffffff;'";
	if ($dark == "#008080")
		echo " selected='selected'";
	echo ">Teal</option><option value='#ffffff' style='background-color:#ffffff; color:#000000;'";
	if ($dark == "#ffffff")
		echo " selected='selected'";
	echo ">White</option><option value='#FFFF00' style='background-color:#FFFF00; color:#000000;'";
	if ($dark == "#FFFF00")
		echo " selected='selected'";
	echo ">Yellow</option>
        </select><br />
        If you selected to enter a custom (darker) color, enter it here: (Must be in hexidecimal format, example #121212)<br />
        <input type='text' name='customcolordark' value='#' maxlength='7' size='10' /><br /><br />
        <input type='hidden' name='colorchange' value='1' /><input type='submit' value=' -Change the colors- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}
if ($site == "logo") {
	echo "Currently loaded logo:<br /><img src='img/logo.jpg' alt='' /><br />Load a new logo:<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><br /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='logo' /><input type='hidden' name='picsize' value='150' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form>";
}
if ($site == "headtext") {
	$stmt = $db_conn->prepare ( "SELECT headText, headFontSize FROM sitesettings WHERE id='1'" );
	$stmt->execute ();
	$rowhead = $stmt->fetch ();
	$headText = $rowhead ['headText'];
	$headFontSize = $rowhead ['headFontSize'];
	echo "<form method='post' action='index.php?page=alladmin&choice=site'>Edit the head text:<br /><input type='text' name='headText' value='$headText' size='40' maxlength='38' /><br /><br />Font size: <input type='text' name='headFontSize' value='$headFontSize' maxlength='5' size='5' />em<br /><br /><input type='hidden' name='changehead' value='1' /><input type='submit' value=' -Submit- ' style='border-radius:8px; -moz-border-radius:8px;' /></form>";
}
if ($site == "homepics") {
	echo "Currently loaded home pictures:<br /><br />
        <img src='img/home1.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='home1' /><input type='hidden' name='picsize' value='400' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <img src='img/home2.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='home2' /><input type='hidden' name='picsize' value='400' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}
if ($site == "histpics") {
	echo "Currently loaded history pictures:<br /><br />
        <img src='img/history/hist1.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img/history' /><input type='hidden' name='picname' value='hist1' /><input type='hidden' name='picsize' value='800' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <img src='img/history/hist2.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img/history' /><input type='hidden' name='picname' value='hist2' /><input type='hidden' name='picsize' value='800' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <img src='img/history/hist3.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img/history' /><input type='hidden' name='picname' value='hist3' /><input type='hidden' name='picsize' value='800' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <img src='img/history/hist4.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img/history' /><input type='hidden' name='picname' value='hist4' /><input type='hidden' name='picsize' value='800' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}
if ($site == "homeheadpics") {
	echo "Currently loaded pictures for the home page slide show:<br />Uploaded pics need to be at least 805px wide to look good on the site.<br /><br />
        <img src='img/s1.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='s1' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <img src='img/s2.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='s2' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <img src='img/s3.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='s3' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <img src='img/s4.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='s4' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <img src='img/s5.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='s5' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <img src='img/s6.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='s6' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}
if ($site == "headpics") {
	echo "Currently loaded pictures for the heads of each page:<br />Uploaded pics need to be 805px wide and 140px tall to look good on the site.<br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>Modadmin and Alladmin</span><br /><img src='img/admin.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='admin' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>Bulletin Board</span><br /><img src='img/bboard.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='bboard' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>History</span><br /><img src='img/history.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='history' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>Links</span><br /><img src='img/links.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='links' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>Contact</span><br /><img src='img/contact.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='contact' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>Messages</span><br /><img src='img/messages.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='messages' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>Profile</span><br /><img src='img/profile.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='profile' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>Report</span><br /><img src='img/report.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='report' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>Pictures</span><br /><img src='img/pictures.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='pictures' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>Classmates</span><br /><img src='img/classmates.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='classmates' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />
        <span style='font-size:1.25em; font-weight:bold;'>Default</span><br /><img src='img/default.jpg' alt='' /><br />Upload a different picture<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='default' /><input type='hidden' name='picsize' value='805' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}
if ($site == "profile") {
	echo "Currently loaded default profile icon:<br /><img src='img/face.jpg' alt='' /><br />Load a new default profile icon:<br /><form method='post' action='index.php?page=alladmin&choice=site' enctype='multipart/form-data'><input type='file' name='image' size='40' /><br /><input type='hidden' name='folder' value='img' /><input type='hidden' name='picname' value='face' /><input type='hidden' name='picsize' value='80' /><input type='submit' value=' -Upload- ' style='border-radius:8px; -moz-border-radius:8px;' /></form>";
}
?>
