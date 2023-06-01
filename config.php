<?php
if (filter_input ( INPUT_POST, 'login', FILTER_SANITIZE_NUMBER_INT )) {
	$user = $_POST ['userid'];
	filter_input ( INPUT_POST, 'userid', FILTER_SANITIZE_STRING );
	$pass = filter_input ( INPUT_POST, 'pass', FILTER_SANITIZE_STRING );
	$hidepwd = md5 ( "$salt1$pass$salt2" );
	if ($user == "" || $pass == "") {
		echo "<div class='error'><b>Both the user name and password fields are required. Please fill in these fields when completing the form.</b></div>";
	} else {
		$stmt = $db_conn->prepare ( "SELECT id, firstname, miname, lastname, userlvl, year, approved FROM users WHERE user=? AND pwd=?" );
		$stmt->execute ( array (
				$user,
				$hidepwd
		) );
		$row = $stmt->fetch ();
		if ($row) {
			$userid = $row [0];
			$firstname = $row [1];
			$miname = $row [2];
			$lastname = $row [3];
			$fullname = $firstname;
			if ($miname)
				$fullname .= " " . $miname;
			$fullname .= " " . $lastname;
			$userlvl = $row [4];
			$useryear = $row [5];
			$approved = $row [6];
			if ($approved == '1') {
				$_SESSION ['userid'] = $userid;
				$_SESSION ['user'] = $fullname;
				$_SESSION ['userlvl'] = $userlvl;
				$_SESSION ['useryear'] = $useryear;
			} elseif ($approved == '0') {
				$_SESSION ['userlvl'] = '0';
				echo "<div class='error'><b>Your application has been received, your email has been verified, but your account not yet approved.</b></div>";
			}
		} else {
			$substmt = $db_conn->prepare ( "SELECT id FROM users WHERE user=?" );
			$substmt->execute ( array (
					$user
			) );
			$num = $substmt->fetch ();
			if ($num) {
				echo "<div class='error'><b>Your password is incorrect.</b></div>";
			} else {
				echo "<div class='error'><b>I did not find your user name in the database.</b></div>";
			}
		}
	}
}

if (isset ( $_SESSION ['user'] )) {
	$userlvl = $_SESSION ['userlvl'];
	$username = $_SESSION ['user'];
	$userid = $_SESSION ['userid'];
} else {
	$userlvl = "0";
	$username = "";
	$userid = "";
}

if ($userlvl != '0') {
	$stmt = $db_conn->prepare ( "SELECT id FROM messages WHERE userid=? AND isread='0'" );
	$stmt->execute ( array (
			$userid
	) );
	$newmessnum = $stmt->rowCount ();
	$stmt->closeCursor ();
}

if (isset ( $_SESSION ['useryear'] )) {
	$useryear = $_SESSION ['useryear'];
	if ($useryear == "9999")
		$textuseryear = "Faculty";
	else
		$textuseryear = $useryear;
} else {
	$textuseryear = $useryear = date ( "Y", $time );
}

if (filter_input ( INPUT_GET, 'selectyear', FILTER_SANITIZE_NUMBER_INT )) {
	$_SESSION ['selectyear'] = filter_input ( INPUT_GET, 'selectyear', FILTER_SANITIZE_NUMBER_INT );
}

if (filter_input ( INPUT_POST, 'selectyear', FILTER_SANITIZE_NUMBER_INT )) {
	$_SESSION ['selectyear'] = filter_input ( INPUT_POST, 'selectyear', FILTER_SANITIZE_NUMBER_INT );
}

if (isset ( $_SESSION ['selectyear'] )) {
	$selectyear = $_SESSION ['selectyear'];
} else
	$selectyear = $useryear;

if ($selectyear == "9999")
	$textyear = "Faculty";
else
	$textyear = $selectyear;

if (filter_input ( INPUT_POST, 'changehead', FILTER_SANITIZE_NUMBER_INT )) {
	$headText = filter_input ( INPUT_POST, 'headText', FILTER_SANITIZE_STRING );
	$headFontSize = filter_input ( INPUT_POST, 'headFontSize', FILTER_SANITIZE_NUMBER_FLOAT );
	$stmt = $db_conn->prepare ( "UPDATE sitesettings SET headText=?, headFontSize=? WHERE id='1'" );
	$stmt->execute ( array (
			$headText,
			$headFontSize
	) );
}

if (filter_input ( INPUT_POST, 'colorchange', FILTER_SANITIZE_NUMBER_INT )) {
	$schoolcolorlight = filter_input ( INPUT_POST, 'schoolcolorlight', FILTER_SANITIZE_STRING );
	$customcolorlight = filter_input ( INPUT_POST, 'customcolorlight', FILTER_SANITIZE_STRING );
	$schoolcolordark = filter_input ( INPUT_POST, 'schoolcolordark', FILTER_SANITIZE_STRING );
	$customcolordark = filter_input ( INPUT_POST, 'customcolordark', FILTER_SANITIZE_STRING );
	if ($schoolcolorlight == "custom") {
		$lightcolor = $customcolorlight;
	} else {
		$lightcolor = $schoolcolorlight;
	}
	if ($schoolcolordark == "custom") {
		$darkcolor = $customcolordark;
	} else {
		$darkcolor = $schoolcolordark;
	}
	$stmt = $db_conn->prepare ( "UPDATE sitesettings SET schoolColorLight=?, schoolColorDark=? WHERE id='1'" );
	$stmt->execute ( array (
			$lightcolor,
			$darkcolor
	) );
}

if (filter_input ( INPUT_POST, 'deleteUser', FILTER_SANITIZE_NUMBER_INT )) {
	$deleteUser = filter_input ( INPUT_POST, 'deleteUser', FILTER_SANITIZE_NUMBER_INT );
	$sec = filter_input ( INPUT_POST, 'sec', FILTER_SANITIZE_STRING );
	if ($sec == md5 ( "$salt1$deleteUser$salt2" )) {
		$astmt = $db_conn->prepare ( "DELETE FROM bboard WHERE id=?" );
		$astmt->execute ( array (
				$deleteUser
		) );
		$bstmt = $db_conn->prepare ( "DELETE FROM users WHERE id=?" );
		$bstmt->execute ( array (
				$deleteUser
		) );
		$cstmt = $db_conn->prepare ( "DELETE FROM messages WHERE id=?" );
		$cstmt->execute ( array (
				$deleteUser
		) );
		$dstmt = $db_conn->prepare ( "DELETE FROM usersettings WHERE id=?" );
		$dstmt->execute ( array (
				$deleteUser
		) );
		if (file_exists ( "pics/nowpic/$deleteUser.jpg" ))
			unlink ( "pics/nowpic/$deleteUser.jpg" );
		if (file_exists ( "pics/thenpic/$deleteUser.jpg" ))
			unlink ( "pics/thenpic/$deleteUser.jpg" );
	}
}
?>
