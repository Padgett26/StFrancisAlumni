<?php
if (filter_input ( INPUT_POST, 'changeThen', FILTER_SANITIZE_NUMBER_INT ))
	$changeThen = filter_input ( INPUT_POST, 'changeThen', FILTER_SANITIZE_NUMBER_INT );
if (filter_input ( INPUT_POST, 'changeNow', FILTER_SANITIZE_NUMBER_INT ))
	$changeNow = filter_input ( INPUT_POST, 'changeNow', FILTER_SANITIZE_NUMBER_INT );

if ($changeThen == "1") {
	$useridup = filter_input ( INPUT_POST, 'userid', FILTER_SANITIZE_NUMBER_INT );
	$saveto = "pics/thenpic/$useridup.jpg";
	$tempsaveto = "pics/thenpic/temp$useridup.jpg";
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
	$useridup = filter_input ( INPUT_POST, 'userid', FILTER_SANITIZE_NUMBER_INT );
	$saveto = "pics/nowpic/$useridup.jpg";
	$tempsaveto = "pics/nowpic/temp$useridup.jpg";
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
	$iddelete = filter_input ( INPUT_POST, 'deleteProfile', FILTER_SANITIZE_NUMBER_INT );
	$hideid = md5 ( "$salt1$iddelete$salt2" );
	echo "Are you sure you want to delete your account?  Clicking the Delete button will delete all of your information, all of the messages you have posted, and all of your bulletin board posts.<br /><span style='font-weight:bold; color:red;'>This action cannot be undone, so please be sure before clicking the delete button.</span><br /><br />";
	echo "<div style='margin-left:40px; float:left;'><form method='post' action='index.php?page=alladmin'><input type='submit' value=' -Cancel- ' /></form></div><div style='margin-left:40px; float:left;'><form method='post' action='index.php?page=alladmin'><input type='hidden' name='deleteUser' value='$iddelete' /><input type='hidden' name='sec' value='$hideid' /><input type='submit' value=' -Delete- ' /></form></div><br /><br />";
}

if (filter_input ( INPUT_POST, 'pwdreset', FILTER_SANITIZE_NUMBER_INT )) {
	$idpwdreset = filter_input ( INPUT_POST, 'pwdreset', FILTER_SANITIZE_NUMBER_INT );
	$emailpwdreset = filter_input ( INPUT_POST, 'pwdresetemail', FILTER_SANITIZE_EMAIL );
	$timepwdreset = time ();
	$stmt = $db_conn->prepare ( "UPDATE users SET resetpwd=? WHERE id=?" );
	$stmt->execute ( array (
			$timepwdreset,
			$idpwdreset
	) );
	$timemd5 = md5 ( "$timepwdreset" );
	$message = "
        <html><head></head><body>
        Here is your password reset link for the St Francis Alumni website.<br /><br />
        This link will only work once, so please complete the process of reseting your password.  If you are unable to complete the process, you will have to contact and administrator to requset another password reset email.<br /><br />
        When you are ready, click this link to get started<br /><br />
        <a href='index.php?page=pwdreset&user=$idpwdreset&status=$timemd5'>index.php?page=initprofile&user=$idpwdreset&status=$timemd5</a><br /><br />
        If the above link does not work by clicking it, you can copy and paste it into your browser address bar.
        </body></html>";
	// In case any of our lines are larger than 70 characters, we should use wordwrap()
	$message = wordwrap ( $message, 70 );
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
	// Send
	mail ( $emailpwdreset, "Password reset for stfrancisalumni.org", $message, $headers );
	echo "Password reset email sent.<br />";
}

if (filter_input ( INPUT_POST, 'userid', FILTER_SANITIZE_NUMBER_INT )) {
	$useridup = filter_input ( INPUT_POST, 'userid', FILTER_SANITIZE_STRING );
	$userverup = filter_input ( INPUT_POST, 'useruser', FILTER_SANITIZE_STRING );
	if (preg_match ( "/^[a-zA-Z0-9][a-zA-Z0-9.-_ ]*/", $userverup )) {
		$userup = $userverup;
	} else {
		$userup = "";
	}
	$firstnameup = filter_input ( INPUT_POST, 'firstnameuser', FILTER_SANITIZE_STRING );
	$minameup = filter_input ( INPUT_POST, 'minameuser', FILTER_SANITIZE_STRING );
	$lastnameup = filter_input ( INPUT_POST, 'lastnameuser', FILTER_SANITIZE_STRING );
	$maidennameup = filter_input ( INPUT_POST, 'maidennameuser', FILTER_SANITIZE_STRING );
	$addyup = filter_input ( INPUT_POST, 'addyuser', FILTER_SANITIZE_STRING );
	$citystup = filter_input ( INPUT_POST, 'citystuser', FILTER_SANITIZE_STRING );
	$zipup = filter_input ( INPUT_POST, 'zipuser', FILTER_SANITIZE_NUMBER_INT );
	$emailup = filter_input ( INPUT_POST, 'emailuser', FILTER_SANITIZE_EMAIL );
	$emailstmt = $db_conn->prepare ( "SELECT email FROM users WHERE id=?" );
	$emailstmt->execute ( array (
			$useridup
	) );
	$emailrow = $emailstmt->fetch ();
	$emailold = $emailrow ['email'];
	if ($emailold != $emailup) {
		$stmt = $db_conn->prepare ( "UPDATE users SET bademail=? WHERE id=?" );
		$stmt->execute ( array (
				'0',
				$useridup
		) );
	}
	$phoneup = filter_input ( INPUT_POST, 'phoneuser', FILTER_SANITIZE_NUMBER_INT );
	$yearup = filter_input ( INPUT_POST, 'yearuser', FILTER_SANITIZE_NUMBER_INT );
	$userlvlup = filter_input ( INPUT_POST, 'userlvluser', FILTER_SANITIZE_NUMBER_INT );
	if ($userlvlup == "2") {
		$stmt = $db_conn->prepare ( "SELECT userlvl,email FROM users WHERE id=?" );
		$stmt->execute ( array (
				$useridup
		) );
		$rowmodcheck = $stmt->fetch ();
		$returnlvl = $rowmodcheck ['userlvl'];
		$returnemail = $rowmodcheck ['email'];
		if ($returnlvl != "2") {
			if ($yearup == "9999")
				$textyr = "Faculty";
			else
				$textyr = "$yearup class";
			$createdup = time () + (2 * 60 * 60);
			$msgmodcheck = "$firstnameup $lastnameup has just been made a moderator for the $textyr.\n\n$firstnameup is here to help you with any questions or issues you may have.\n\nAnd $firstnameup can be contacted by using the contact form in the menu, or sending a private message.";
			$substmt = $db_conn->prepare ( "INSERT INTO bboard VALUES" . "(NULL, ?, ?, '1', ?,'0','1','0','0')" );
			$substmt->execute ( array (
					$yearup,
					$createdup,
					$msgmodcheck
			) );
			$message = "
            <html><head></head><body>
            Thank you for becoming the $textyr moderator.<br /><br />
            You will notice your menu has changed on the alumni website. You now have an administration link, and with it you now have more control over your garduation year.<br /><br />
            There are seceral options that come up when you go to the administration page:<br /><br />
            Changing a persons profile settings is something you will rarely need to do since each user has their own profile page, but some people may need help with their settings or may not feel comfortable making the changes themselves. Also, if a person cannot remember their password, there is a password reset button at the top of the page. Clicking that button sends the user an email that contains a password reset link.<br/><br />
            You will be shown if there are any users that need to be approved.  The alumni that we are approving are not just the graduates, but anyone that has gone to this school at any point.<br /><br />
            You will also see if there are any inquiries that need to be resolved.  These are any input or questions from your graduating class that was submitted through the contact an admin form in the menu. You can answer the questions and help the person out the best you can, and if you check the mark the inquiry as resolved checkbox, then the inquiry will not pop up again. If you are having trouble answering a question or helping someone out, please let an administrator know, and between us, we will get it resolved.<br /><br />
            People can also report items on the site, and if there is anything reported in your year, you will be told here.  Select this option and the reported item come up, and you can use your best judgement as to whether the item should be deleted or left alone.  You also have the ability to delete bulletin board posts and pictures if you find them offensive. Please be careful with this, we want to keep the site clean and comfortable for all of the users, but we dont want to do a lot of censoring. A catch 22 I know, just use your best judgement.<br /><br />
            Next, you can send everyone in your year a private message. Or you can send everyone in your year an email, if they have their notifications set to receive emails from the site.<br /><br />
            We thank you for your willingness to help make this site a wonderful tool for all St Francis Alumni.  Please check the admin page often to make sure there are no actions you need to take.  And again, if you get stuck let us know, the adminstrators may be able to make a change that you cannot, and we can make decisions or answer questions that you may not feel comfortable answering.<br /><br />
            Thank you again,
            Jason Padgett
            Support - stfrancisalumni.org
            </body></html>";
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap ( $message, 70 );
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
			// Send
			mail ( $returnemail, "Thank you for becoming a St Francis Alumni moderator", $message, $headers );
		}
	}
	$approvedup = filter_input ( INPUT_POST, 'approveduser', FILTER_SANITIZE_NUMBER_INT );
	$descparaup = filter_input ( INPUT_POST, 'descparauser', FILTER_SANITIZE_STRING );
	$deceasedup = filter_input ( INPUT_POST, 'deceaseduser', FILTER_SANITIZE_NUMBER_INT );
	if ($deceasedup != "1")
		$deceasedup = "0";
	$notmsg = filter_input ( INPUT_POST, 'notmsg', FILTER_SANITIZE_NUMBER_INT );
	$notbb = filter_input ( INPUT_POST, 'notbb', FILTER_SANITIZE_NUMBER_INT );
	$notassoc = filter_input ( INPUT_POST, 'notassoc', FILTER_SANITIZE_NUMBER_INT );
	if ($notmsg != "1")
		$notmsg = "0";
	if ($notbb != "1")
		$notbb = "0";
	if ($notassoc != "1")
		$notassoc = "0";
	$userprofup = filter_input ( INPUT_POST, 'userprofup', FILTER_SANITIZE_STRING );
	$usercreatedup = filter_input ( INPUT_POST, 'usercreateduser', FILTER_SANITIZE_NUMBER_INT );
	$removeThen = filter_input ( INPUT_POST, 'removeThen', FILTER_SANITIZE_NUMBER_INT );
	if ($removeThen == "1")
		unlink ( "pics/thenpic/$userid.jpg" );
	$removeNow = filter_input ( INPUT_POST, 'removeNow', FILTER_SANITIZE_NUMBER_INT );
	if ($removeNow == "1")
		unlink ( "pics/nowpic/$userid.jpg" );
	if ($useridup == "new") {
		if ($firstnameup == "" || $lastnameup == "")
			$errorprof = "One or more required items were not filled in...<br />";
		else {
			$stmt = $db_conn->prepare ( "SELECT id FROM users WHERE user=?" );
			$stmt->execute ( array (
					$userup
			) );
			$initnum1 = $stmt->fetch ();
			if ($initnum1) {
				echo "<span style='color:red;'>**The log on user name has already been taken. Please select another**</span>";
			} else {
				$stmt = $db_conn->prepare ( "INSERT INTO users VALUES" . "(NULL, NULL, NULL, ?, ?, ?, '0', '0', '0', '0', '0', '0', '0', ?, '1', NULL, ?, '0', '0', '0', '0')" );
				$stmt->execute ( array (
						$firstnameup,
						$minameup,
						$lastnameup,
						$yearup,
						$deceasedup
				) );
				$substmt = $db_conn->prepare ( "SELECT id FROM users WHERE firstname=? AND miname=? AND lastname=? AND year=? AND deceased=?" );
				$substmt->execute ( array (
						$firstnameup,
						$minameup,
						$lastnameup,
						$yearup,
						$deceasedup
				) );
				$subrow = $substmt->fetch ();
				$getuserup = $subrow ['id'];
				$subsubstmt = $db_conn->prepare ( "INSERT INTO usersettings VALUES" . "(NULL, ?, ?, ?, ?, '0', '0', '0', '0', '0')" );
				$subsubstmt->execute ( array (
						$getuserup,
						$notmsg,
						$notbb,
						$notassoc
				) );
				echo "The user has been uploaded... If you need to add profile pictures to this user, just open him/her again.<br />";
			}
		}
	} else {
		if (($userup == "" && $usercreatedup == "1") || ($emailup == "" && $usercreatedup == "1") || $firstnameup == "" || $lastnameup == "")
			$errorprof = "One or more required items were not filled in...<br />";
		else {
			if ($userup != $userprofup) {
				$stmt = $db_conn->prepare ( "SELECT id FROM users WHERE user=?" );
				$stmt->execute ( array (
						$userup
				) );
				$initnum1 = $stmt->fetch ();
				if ($initnum1) {
					echo "<span style='color:red;'>**The log on user name has already been taken. Please select another**</span><br />";
				} else {
					$substmt = $db_conn->prepare ( "UPDATE users SET user=?, firstname=?, miname=?, lastname=?, maidenname=?, address=?, cityst=?, zip=?, email=?, phone=?, userlvl=?, year=?, approved=?, descpara=?, deceased=?, usercreated=? WHERE id=?" );
					$substmt->execute ( array (
							$userup,
							$firstnameup,
							$minameup,
							$lastnameup,
							$maidennameup,
							$addyup,
							$citystup,
							$zipup,
							$emailup,
							$phoneup,
							$userlvlup,
							$yearup,
							$approvedup,
							$descparaup,
							$deceasedup,
							$usercreatedup,
							$useridup
					) );
					$subsubstmt = $db_conn->prepare ( "UPDATE usersettings SET notmsg=?, notbb=?, notassoc=? WHERE userid=?" );
					$subsubstmt->execute ( array (
							$notmsg,
							$notbb,
							$notassoc,
							$useridup
					) );
					echo "The user has been updated...<br />";
				}
			} else {
				$substmt = $db_conn->prepare ( "UPDATE users SET user=?, firstname=?, miname=?, lastname=?, maidenname=?, address=?, cityst=?, zip=?, email=?, phone=?, userlvl=?, year=?, approved=?, descpara=?, deceased=?, usercreated=? WHERE id=?" );
				$substmt->execute ( array (
						$userup,
						$firstnameup,
						$minameup,
						$lastnameup,
						$maidennameup,
						$addyup,
						$citystup,
						$zipup,
						$emailup,
						$phoneup,
						$userlvlup,
						$yearup,
						$approvedup,
						$descparaup,
						$deceasedup,
						$usercreatedup,
						$useridup
				) );
				$subsubstmt = $db_conn->prepare ( "UPDATE usersettings SET notmsg=?, notbb=?, notassoc=? WHERE userid=?" );
				$subsubstmt->execute ( array (
						$notmsg,
						$notbb,
						$notassoc,
						$useridup
				) );
				echo "The user has been updated...<br />";
			}
		}
	}
}

$stmt = $db_conn->prepare ( "SELECT id, firstname, miname, lastname FROM users WHERE year=? ORDER BY lastname" );
$stmt->execute ( array (
		$adminyear
) );
echo "Please select a user to edit:<br /><form method='post' action='index.php?page=alladmin&choice=users'><select name='userselect' size='1'><option value='new'>Create new user</option>";
while ( $rowuser = $stmt->fetch () ) {
	$iduser = $rowuser ['id'];
	$firstnameuser = $rowuser ['firstname'];
	$minameuser = $rowuser ['miname'];
	$lastnameuser = $rowuser ['lastname'];
	echo "<option value='$iduser'>$lastnameuser, $firstnameuser $minameuser</option>";
}
echo "</select><input type='submit' value=' -Go- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";

if (filter_input ( INPUT_POST, 'userselect', FILTER_SANITIZE_NUMBER_INT )) {
	$idgetuser = filter_input ( INPUT_POST, 'userselect', FILTER_SANITIZE_NUMBER_INT );
	$stmt = $db_conn->prepare ( "SELECT * FROM users WHERE id=?" );
	$stmt->execute ( array (
			$idgetuser
	) );
	$rowgetuser = $stmt->fetch ();
	$usergetuser = $rowgetuser [1];
	$firstnamegetuser = $rowgetuser [3];
	$minamegetuser = $rowgetuser [4];
	$lastnamegetuser = $rowgetuser [5];
	$maidennamegetuser = $rowgetuser [6];
	$addygetuser = $rowgetuser [7];
	$citystgetuser = $rowgetuser [8];
	$zipgetuser = $rowgetuser [9];
	$emailgetuser = $rowgetuser [10];
	$phonegetuser = $rowgetuser [11];
	$userlvlgetuser = $rowgetuser [12];
	$yeargetuser = $rowgetuser [13];
	$approvedgetuser = $rowgetuser [14];
	$descparagetuser = $rowgetuser [15];
	$deceasedgetuser = $rowgetuser [16];
	$ucreatedgetuser = $rowgetuser [17];
	$bademailgetuser = $rowgetuser [19];

	$substmt = $db_conn->prepare ( "SELECT * FROM usersettings WHERE userid=?" );
	$substmt->execute ( array (
			$idgetuser
	) );
	$rowprof2 = $substmt->fetch ();
	$notmsg = $rowprof2 [2];
	$notbb = $rowprof2 [3];
	$notassoc = $rowprof2 [4];

	echo <<<_END
	<form method="post" action="index.php?page=alladmin&choice=users"><div style='font-size:1em; text-align:center;'>Has this user forrgotten his/her password?<br />Click this button to send a password reset email.<br /><input type='hidden' name='pwdreset' value='$idgetuser' /><input type='hidden' name='pwdresetemail' value='$emailgetuser' /><input type='submit' value='Password Reset' style='border-radius:8px; -moz-border-radius:8px;' /></div></form><br /><br />
	<div style='font-size:1em; text-align:center;'>Items marked with an * are required</div>
	<form method="post" action="index.php?page=alladmin&choice=users" enctype='multipart/form-data'>
	    <table cellpadding="5px" cellspacing="0px" border="2px">
	_END;
	if ($ucreatedgetuser == "1") {
		echo <<<_END
		        <tr>
		            <td>* User name:<br />Must start with a letter or number<br />Then<br />Letters numbers . - _ and spaces only</td>
		            <td><input type="text" name="useruser" value='$usergetuser' size='40' maxlength='40' rel='none' /></td>
		        </tr>
		_END;
	}
	echo <<<_END
	        <tr>
	            <td>* First name:</td>
	            <td><input type="text" name="firstnameuser" value='$firstnamegetuser' size='40' maxlength='30' rel='none' /></td>
	        </tr>
	        <tr>
	            <td>Middle Initial:</td>
	            <td><input type="text" name="minameuser" value='$minamegetuser' size='2' maxlength='2' rel='none' /></td>
	        </tr>
	        <tr>
	            <td>* Last name:</td>
	            <td><input type="text" name="lastnameuser" value='$lastnamegetuser' size='40' maxlength='30' rel='none' /></td>
	        </tr>
	_END;
	if ($ucreatedgetuser == "1") {
		echo <<<_END
		        <tr>
		            <td>Maiden name:</td>
		            <td><input type="text" name="maidennameuser" value='$maidennamegetuser' size='40' maxlength='30' rel='none' /></td>
		        </tr>
		        <tr>
		            <td>Address:</td>
		            <td><input type="text" name="addyuser" value='$addygetuser' size='40' maxlength='60' rel='none' /></td>
		        </tr>
		        <tr>
		            <td>City, ST:</td>
		            <td><input type="text" name="citystuser" value='$citystgetuser' size='40' maxlength='60' rel='none' /></td>
		        </tr>
		        <tr>
		            <td>ZIP:</td>
		            <td><input type="text" name="zipuser" value='$zipgetuser' size='40' maxlength='60' rel='none' /></td>
		        </tr>
		        <tr>
		            <td>* Email (has been verified):</td>
		            <td><input type="text" name="emailuser" value='$emailgetuser' size='40' maxlength='60' rel='none' />
		_END;
		if ($bademailgetuser == "1")
			echo "<br /><span style='color:red; font-size:.75em;'>Emails sent to this address have been returned as undeliverable. Please update your email address.</span>";
		echo <<<_END
		        </td>
		        </tr>
		        <tr>
		            <td>Phone:</td>
		            <td><input type="text" name="phoneuser" value='$phonegetuser' size='40' maxlength='60' rel='none' /></td>
		        </tr>
		        <tr>
		            <td>User Level:</td>
		            <td><select name="userlvluser" rel='none'><option value="0"
		_END;
		if ($userlvlgetuser == '0')
			echo " selected='selected'";
		echo ">0 - Visitor only</option><option value='1'";
		if ($userlvlgetuser == '1')
			echo " selected='selected'";
		echo ">1 - User</option><option value='2'";
		if ($userlvlgetuser == '2')
			echo " selected='selected'";
		echo ">2 - Moderator</option><option value='3'";
		if ($userlvlgetuser == '3')
			echo " selected='selected'";
		echo ">3 - Full Admin</option></select>";
		echo "Currently set at: <span style='font-weight:bold;'>";
		if ($userlvlgetuser == '0')
			echo "0 - Visitor only";
		if ($userlvlgetuser == '1')
			echo "1 - User";
		if ($userlvlgetuser == '2')
			echo "2 - Moderator";
		if ($userlvlgetuser == '3')
			echo "3 - Full Admin";
		echo <<<_END
		             </span></td>
		        </tr>
		_END;
	}
	echo <<<_END
	        <tr>
	            <td>User year:</td>
	            <td><select name="yearuser" size="1" rel='none'><option value="9999"
	_END;
	if ($adminyear == "9999")
		echo " selected='selected'";
	echo ">Faculty</option>";
	$getyear = date ( 'Y' );
	$firstyear = "1900";
	for($j = $getyear; $j > $firstyear; $j --) {
		echo "<option value='$j'";
		if ($j == $adminyear)
			echo " selected='selected'";
		echo ">$j</option>";
	}
	echo <<<_END
	            </select><input type='hidden' name='approveduser' value='$approvedgetuser' rel='none' /></td>
	        </tr>
	        <tr>
	            <td>Descriptive Paragraph</td>
	            <td><textarea cols='60' rows='10' name='descparauser' maxlength='1500' rel='none'>$descparagetuser</textarea></td>
	        </tr>
	        <tr>
	            <td>Is this person deceased?</td>
	            <td><input type='checkbox' name='deceaseduser' value='1' rel='none'
	_END;
	if ($deceasedgetuser == "1")
		echo "checked='checked' ";
	echo <<<_END
	/></td>
	        </tr>
	_END;
	if ($idgetuser != "new") {
		echo <<<_END
		        <tr>
		            <td style="text-align:center;">Current &#39;THEN&#39; Pic<br />
		_END;
		echo (file_exists ( "pics/thenpic/$idgetuser.jpg" )) ? "<img src='pics/thenpic/$idgetuser.jpg' alt='' /><br />" : "<img src='img/face.jpg' style='max-width:80px; max-height:80px;' alt='' /><br />";
		echo <<<_END
		            <br /><input type='checkbox' name='removeThen' value='1' id='removeThen' rel='none' /> <label for='removeThen'>Remove this picture.</label><br /><br /><input type='checkbox' name='changeThen' value='1' id='changeThen' rel="showThen" /> <label for='changeThen'>Change this picture.</label><br /><div rel="showThen"><input type="file" name="thenimage" size="40" /></div></td>
		            <td style="text-align:center;">Current &#39;NOW&#39; Pic<br />
		_END;
		echo (file_exists ( "pics/nowpic/$idgetuser.jpg" )) ? "<img src='pics/nowpic/$idgetuser.jpg' alt='' /><br />" : "<img src='img/face.jpg' style='max-width:80px; max-height:80px;' alt='' /><br />";
		echo <<<_END
		            <br /><input type='checkbox' name='removeNow' value='1' id='removeNow' rel='none' /> <label for='removeNow'>Remove this picture.</label><br /><br /><input type='checkbox' name='changeNow' value='1' id='changeNow' rel="showNow" /> <label for='changeNow'>Change this picture.</label><br /><div rel="showNow"><input type="file" name="nowimage" size="40" /></div></td>
		        </tr>
		        <tr>
		            <td colspan="2" style="text-align:center;">Pictures may take a while to upload and resize, please be patient.</td>
		        </tr>
		_END;
	}
	if ($ucreatedgetuser == "1") {
		echo <<<_END
		        <tr>
		        <td colspan="2"><div style="text-align:center; font-size:2em;">Notifications</div><div style="text-align:left; margin-right:40px;"><input type="checkbox" name="notmsg" value="1" id="notmsg" rel='none'
		_END;
		if ($notmsg == "1")
			echo " checked='checked' ";
		echo <<<_END
		/><label for="notmsg">Notify me by email whenever someone sends me a private message</label><br /><br /><input type="checkbox" name="notbb" value="1" id="notbb" rel='none'
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
		_END;
	}
	if ($idgetuser != "new")
		echo "<tr><td colspan='2' style='text-align:center;'><input type='checkbox' name='deleteProfile' value='$idgetuser' id='deleteProfile' rel='none' /> <label for='deleteProfile'>Delete this account.  Choosing this option will delete all of the information, all of the posted messages, and all of the bulletin board posts.</label><br /><span style='font-weight:bold; color:red;'>This action cannot be undone, so please be sure before checking this box.</span></td></tr>";
	echo "<tr><td colspan='2'><input type='hidden' name='usercreateduser' value='";
	echo ($idgetuser == "new") ? "0" : "$ucreatedgetuser";
	echo <<<_END
	' /><input type='hidden' name='userid' value='$idgetuser' /><input type="hidden" name="userprofup" value="$usergetuser" /><input type='submit' value=' -Save Changes- ' style='border-radius:8px; -moz-border-radius:8px;' /></td>
	        </tr>
	    </table>
	</form><br /><br />
	_END;
}
?>
