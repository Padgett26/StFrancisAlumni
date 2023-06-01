<?php
if (filter_input ( INPUT_POST, 'changeThen', FILTER_SANITIZE_NUMBER_INT ))
	$changeThen = filter_input ( INPUT_POST, 'changeThen', FILTER_SANITIZE_NUMBER_INT );
if (filter_input ( INPUT_POST, 'changeNow', FILTER_SANITIZE_NUMBER_INT ))
	$changeNow = filter_input ( INPUT_POST, 'changeNow', FILTER_SANITIZE_NUMBER_INT );

if ($changeThen == "1") {
	$saveto = "pics/thenpic/$userid.jpg";
	$tempsaveto = "pics/thenpic/temp$userid.jpg";
	move_uploaded_file ( $_FILES ['thenimage'] ['tmp_name'], $tempsaveto );
	$typeok = "1";

	switch ($_FILES ['thenimage'] ['type']) {
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
		unlink ( "$tempsaveto" );
	}
	if ($typeok == "0") {
		unlink ( "$tempsaveto" );
		echo "Your picture was not uploaded. The accepted file types are: gif, jpg, jpeg, pjpeg, and png.<br /><br />";
	}
}

if ($changeNow == "1") {
	$saveto = "pics/nowpic/$userid.jpg";
	$tempsaveto = "pics/nowpic/temp$userid.jpg";
	move_uploaded_file ( $_FILES ['nowimage'] ['tmp_name'], $tempsaveto );
	$typeok = "1";

	switch ($_FILES ['nowimage'] ['type']) {
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
		unlink ( "$tempsaveto" );
	}
	if ($typeok == "0") {
		unlink ( "$tempsaveto" );
		echo "Your picture was not uploaded. The accepted file types are: gif, jpg, jpeg, pjpeg, and png.<br /><br />";
	}
}

if (filter_input ( INPUT_POST, 'deleteProfile', FILTER_SANITIZE_NUMBER_INT )) {
	$hideid = md5 ( "$salt1$userid$salt2" );
	echo "Are you sure you want to delete your account?  Clicking the Delete button will delete all of your information, all of the messages you have posted, and all of your bulletin board posts.<br /><span style='font-weight:bold; color:red;'>This action cannot be undone, so please be sure before clicking the delete button.</span><br /><br />";
	echo "<div style='margin-left:40px; float:left;'><form method='post' action='index.php?page=profile'><input type='submit' value=' -Cancel- ' /></form></div><div style='margin-left:40px; float:left;'><form method='post' action='index.php?page=home&logout=yep'><input type='hidden' name='deleteUser' value='$userid' /><input type='hidden' name='sec' value='$hideid' /><input type='submit' value=' -Delete- ' /></form></div><br /><br />";
}

if (filter_input ( INPUT_POST, 'profileupdate', FILTER_SANITIZE_NUMBER_INT )) {
	$user = filter_input ( INPUT_POST, 'user', FILTER_SANITIZE_STRING );
	$pwd = filter_input ( INPUT_POST, 'pwd', FILTER_SANITIZE_STRING );
	$hidepwd = md5 ( "$salt1$pwd$salt2" );
	$firstname = filter_input ( INPUT_POST, 'firstname', FILTER_SANITIZE_STRING );
	$miname = filter_input ( INPUT_POST, 'miname', FILTER_SANITIZE_STRING );
	$lastname = filter_input ( INPUT_POST, 'lastname', FILTER_SANITIZE_STRING );
	$maidenname = filter_input ( INPUT_POST, 'maidenname', FILTER_SANITIZE_STRING );
	$address = filter_input ( INPUT_POST, 'address', FILTER_SANITIZE_STRING );
	$cityst = filter_input ( INPUT_POST, 'cityst', FILTER_SANITIZE_STRING );
	$zip = filter_input ( INPUT_POST, 'zip', FILTER_SANITIZE_NUMBER_INT );
	$email = filter_input ( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
	$stmt = $db_conn->prepare ( "SELECT email FROM users WHERE id=?" );
	$stmt->execute ( array (
			$userid
	) );
	$emailrow = $stmt->fetch ();
	$emailold = $emailrow [0];
	if ($emailold != $email) {
		$stmt = $db_conn->prepare ( "UPDATE users SET bademail='0' WHERE id=?" );
		$stmt->execute ( array (
				$userid
		) );
	}
	$phone = filter_input ( INPUT_POST, 'phone', FILTER_SANITIZE_STRING );
	$descpara = filter_input ( INPUT_POST, 'descpara', FILTER_SANITIZE_STRING );
	$deceased = filter_input ( INPUT_POST, 'deceased', FILTER_SANITIZE_NUMBER_INT );
	if ($deceased != "1")
		$deceased = "0";
	$notmsg = filter_input ( INPUT_POST, 'notmsg', FILTER_SANITIZE_NUMBER_INT );
	$notbb = filter_input ( INPUT_POST, 'notbb', FILTER_SANITIZE_NUMBER_INT );
	$notassoc = filter_input ( INPUT_POST, 'notassoc', FILTER_SANITIZE_NUMBER_INT );
	$notpic = filter_input ( INPUT_POST, 'notpic', FILTER_SANITIZE_NUMBER_INT );
	if ($notpic != "1")
		$notpic = "0";
	if ($notmsg != "1")
		$notmsg = "0";
	if ($notbb != "1")
		$notbb = "0";
	if ($notassoc != "1")
		$notassoc = "0";
	$userprof = filter_input ( INPUT_POST, 'userprof', FILTER_SANITIZE_STRING );
	$removeThen = filter_input ( INPUT_POST, 'removeThen', FILTER_SANITIZE_NUMBER_INT );
	if ($removeThen == "1")
		unlink ( "pics/thenpic/$userid.jpg" );
	$removeNow = filter_input ( INPUT_POST, 'removeNow', FILTER_SANITIZE_NUMBER_INT );
	if ($removeNow == "1")
		unlink ( "pics/nowpic/$userid.jpg" );
	if ($user == "" || $email == "" || $firstname == "" || $lastname == "")
		$errorprof = "One or more required items were not filled in...<br />";
	else {
		if ($user != $userprof) {
			$stmt = $db_conn->query ( "SELECT COUNT(id) FROM users WHERE user='$user'" );
			$initnum1 = $stmt->fetchColumn ();
			$stmt->closeCursor ();
			if ($initnum1 >= 1) {
				echo "<span style='color:red;'>**The log on user name has already been taken. Please select another**</span>";
			} else {
				$stmt = $db_conn->prepare ( "UPDATE users SET user=?, firstname=?, miname=?, lastname=?, maidenname=?, address=?, cityst=?, zip=?, email=?, phone=?, descpara=?, deceased=? WHERE id=?" );
				$stmt->execute ( array (
						$user,
						$firstname,
						$miname,
						$lastname,
						$maidenname,
						$address,
						$cityst,
						$zip,
						$email,
						$phone,
						$descpara,
						$deceased,
						$userid
				) );
				$substmt = $db_conn->prepare ( "UPDATE usersettings SET notmsg=?, notbb=?, notassoc=?, notpic=? WHERE userid=?" );
				$substmt->execute ( array (
						$notmsg,
						$notbb,
						$notassoc,
						$notpic,
						$userid
				) );
				if ($pwd) {
					$subsubstmt = $db_conn->prepare ( "UPDATE users SET pwd=? WHERE id=?" );
					$subsubstmt->execute ( array (
							$hidepwd,
							$userid
					) );
				}
				echo "Your profile has been updated...<br />";
			}
		} else {
			$stmt = $db_conn->prepare ( "UPDATE users SET user=?, firstname=?, miname=?, lastname=?, maidenname=?, address=?, cityst=?, zip=?, email=?, phone=?, descpara=?, deceased=? WHERE id=?" );
			$stmt->execute ( array (
					$user,
					$firstname,
					$miname,
					$lastname,
					$maidenname,
					$address,
					$cityst,
					$zip,
					$email,
					$phone,
					$descpara,
					$deceased,
					$userid
			) );
			$substmt = $db_conn->prepare ( "UPDATE usersettings SET notmsg=?, notbb=?, notassoc=?, notpic=? WHERE userid=?" );
			$substmt->execute ( array (
					$notmsg,
					$notbb,
					$notassoc,
					$notpic,
					$userid
			) );
			if ($pwd) {
				$subsubstmt = $db_conn->prepare ( "UPDATE users SET pwd=? WHERE id=?" );
				$subsubstmt->execute ( array (
						$hidepwd,
						$userid
				) );
			}
			echo "Your profile has been updated...<br />";
		}
	}
}

echo "$errorprof";
echo "<div style='font-size:3em; text-align:center;'>Edit Your Profile</div><br /><div style='font-size:1em; text-align:center;'>Items marked with an * are required</div>";
$stmt = $db_conn->prepare ( "SELECT * FROM users WHERE id=?" );
$stmt->execute ( array (
		$userid
) );
$rowprof = $stmt->fetch ();
$userprof = $rowprof [1];
$firstnameprof = $rowprof [3];
$minameprof = $rowprof [4];
$lastnameprof = $rowprof [5];
$maidennameprof = $rowprof [6];
$addressprof = $rowprof [7];
$citystprof = $rowprof [8];
$zipprof = $rowprof [9];
$emailprof = $rowprof [10];
$phoneprof = $rowprof [11];
$descparaprof = $rowprof [15];
$deceasedprof = $rowprof [16];
$bademailprof = $rowprof [19];

$substmt = $db_conn->prepare ( "SELECT * FROM usersettings WHERE userid=?" );
$substmt->execute ( array (
		$userid
) );
$rowprof2 = $substmt->fetch ();
$notmsg = $rowprof2 [2];
$notbb = $rowprof2 [3];
$notassoc = $rowprof2 [4];
$notpic = $rowprof2 [5];

echo <<<_END
<form method="post" action="index.php?page=profile" enctype='multipart/form-data'>
<table cellspacing="0" cellpadding="10px" border="0">
    <tr>
        <td style="text-align:right;"><input type="text" name="user" value="$userprof" size="40" maxlength="40" rel='none' /></td>
        <td style="text-align:left;">* Username</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="password" name="pwd" size="40" maxlength="40" rel='none' /></td>
        <td style="text-align:left;">Password</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="text" name="firstname" value="$firstnameprof" size="40" maxlength="30" rel='none' /></td>
        <td style="text-align:left;">* First Name</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="text" name="miname" value="$minameprof" size="2" maxlength="2" rel='none' /></td>
        <td style="text-align:left;">Middle Initial</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="text" name="lastname" value="$lastnameprof" size="40" maxlength="30" rel='none' /></td>
        <td style="text-align:left;">* Last Name</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="text" name="maidenname" value="$maidennameprof" size="40" maxlength="30" rel='none' /></td>
        <td style="text-align:left;">Maiden Name</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="text" name="address" value="$addressprof" size="40" maxlength="60" rel='none' /></td>
        <td style="text-align:left;">Address</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="text" name="cityst" value="$citystprof" size="40" maxlength="60" rel='none' /></td>
        <td style="text-align:left;">City, ST</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="text" name="zip" value="$zipprof" size="5" maxlength="5" rel='none' /></td>
        <td style="text-align:left;">Zip</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="text" name="email" value="$emailprof" size="40" maxlength="60" rel='none' />
_END;
if ($bademailprof == "1")
	echo "<br /><span style='color:red; font-size:.75em;'>Emails sent to this address have been returned as undeliverable. Please update your email address.</span>";
echo <<<_END
        </td>
        <td style="text-align:left;">* Email</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="text" name="phone" value="$phoneprof" size="15" maxlength="13" rel='none' /></td>
        <td style="text-align:left;">Phone</td>
    </tr>
    <tr>
        <td style="text-align:right;"><input type="checkbox" name="deceased" value="1" rel='none'
_END;
if ($deceasedprof == "1")
	echo " checked='checked' ";
echo <<<_END
/></td>
        <td style="text-align:left;">Is this user deceased?</td>
    </tr>
    <tr>
        <td style="text-align:right;"><textarea name="descpara" cols="50" rows="15" maxlength="1500" rel='none'>$descparaprof</textarea></td>
        <td style="text-align:left;">Descriptive Paragraph</td>
    </tr>
    <tr>
        <td style="text-align:center;"><a name='profilepics' style='text-decoration:none;'>Current &#39;THEN&#39; Pic</a><br /><img src='pics/thenpic/$userid.jpg' alt='' /><br /><input type='checkbox' name='removeThen' value='1' id='removeThen' /> <label for='removeThen'>Remove this picture.</label><br /><br /><input type='checkbox' name='changeThen' value='1' id='changeThen' rel="showThen" /> <label for='changeThen'>Change this picture.</label><br /><div rel="showThen"><input type="file" name="thenimage" size="40" /></div></td>
        <td style="text-align:center;">Current &#39;NOW&#39; Pic<br /><img src='pics/nowpic/$userid.jpg' alt='' /><br /><input type='checkbox' name='removeNow' value='1' id='removeNow' /> <label for='removeNow'>Remove this picture.</label><br /><br /><input type='checkbox' name='changeNow' value='1' id='changeNow' rel="showNow" /> <label for='changeNow'>Change this picture.</label><br /><div rel="showNow"><input type="file" name="nowimage" size="40" /></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center;">Pictures may take a while to upload and resize, please be patient.</td>
    </tr>
    <tr>
        <td colspan="2"><div style="text-align:center; font-size:2em;">Notifications</div><div style="text-align:left; margin-right:40px;"><input type="checkbox" name="notmsg" value="1" id="notmsg" rel='none'
_END;
if ($notmsg == "1")
	echo " checked='checked' ";
echo <<<_END
/><label for="notmsg">Notify me by email whenever someone sends me a private message</label><br /><br /><input type="checkbox" name="notpic" value="1" id="notpic" rel='none'
_END;
if ($notpic == "1")
	echo " checked='checked' ";
echo <<<_END
/><label for="notpic">Notify me by email whenever someone submits a new picture on my year's Pictures Page</label><br /><br /><input type="checkbox" name="notbb" value="1" id="notbb" rel='none'
_END;
if ($notbb == "1")
	echo " checked='checked' ";
echo <<<_END
/><label for="notbb">Notify me by email whenever someone submits a new post on my year's Bulliten Board</label><br /><br /><input type="checkbox" name="notassoc" value="1" id="notassoc" rel='none'
_END;
if ($notassoc == "1")
	echo " checked='checked' ";
echo <<<_END
/><label for="notassoc">Allow the Alumni Association of St Francis to contact me by email</label><br /><br /></div></td>
    </tr>
        <tr>
        <td colspan="2" style="text-align:center;"><input type="checkbox" name="deleteProfile" value="1" id="deleteProfile" rel='none' /> <label for="deleteProfile">Delete my account.  Choosing this option will delete all of your information, all of the messages you have posted, and all of your bulletin board posts.</label><br /><span style='font-weight:bold; color:red;'>This action cannot be undone, so please be sure before checking this box.</span></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center;"><input type="hidden" name="userprof" value="$userprof" /><input type="hidden" name="profileupdate" value="1" /><input type="submit" value=" -Update Profile- " style='border-radius:8px; -moz-border-radius:8px;' /></td>
    </tr>
</table>
</form><br /><br />
_END;
?>
