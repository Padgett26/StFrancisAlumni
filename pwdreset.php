<?php
if (filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT)) {
    $userreset = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);
    $statusreset = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);
    $stmt = $db_conn->prepare("SELECT resetpwd FROM users WHERE id=?");
    $stmt->execute(array($userreset));
    $rowreset = $stmt->fetch();
    $timereset = $rowreset[0];
    $timemd5 = md5("$timereset");

    if ($statusreset == $timemd5) {
        $verified = "1";
        $substmt = $db_conn->prepare("UPDATE users SET resetpwd=resetpwd+1 WHERE id=?");
        $substmt->execute(array($userreset));
    } else {
        $verified = "0";
    }
}

if (filter_input(INPUT_POST, 'pwdid', FILTER_SANITIZE_NUMBER_INT)) {
    $pwdid = filter_input(INPUT_POST, 'pwdid', FILTER_SANITIZE_NUMBER_INT);
    $pwd1 = filter_input(INPUT_POST, 'pwd1', FILTER_SANITIZE_STRING);
    $pwd2 = filter_input(INPUT_POST, 'pwd2', FILTER_SANITIZE_STRING);
    if ($pwd1 == $pwd2) {
        $pwd = md5("$salt1$pwd1$salt2");
        $stmt = $db_conn->prepare("UPDATE users SET pwd=? WHERE id=?");
        $stmt->execute(array($pwd,$pwdid));
        $verified = "2";
    } else {
        $verified = "1";
        $pwderror = "Your passwords did not match.  Please try again.";
    }
}

if ($verified == "1") {
    $stmt = $db_conn->prepare("SELECT firstname, miname, lastname FROM users WHERE id=?");
    $stmt->execute(array($userreset));
    $approw = $stmt->fetch();
    $appfirst = $approw[0];
    $appmi = $approw[1];
    $applast = $approw[2];
    $name = $appfirst;
    if ($appmi)
        $name .= " " . $appmi;
    $name .= " " . $applast;

    echo "$pwderror<br />$name, lets get your password changed.<br /><br /><form method='post' action='index.php?page=pwdreset'>";
    echo "<table><tr><td>New Password:</td><td><input type='password' name='pwd1' size='40' maxlength='30' /></td></tr>";
    echo "<tr><td>Type password again:</td><td><input type='password' name='pwd2' size='40' maxlength='30' /></td></tr>";
    echo "<tr><td colspan='2'>Please write down your new password.</td></tr>";
    echo "<tr><td colspan='2'><input type='hidden' name='pwdid' value='$userreset' /><input type='submit' value=' -Set new password- ' style='border-radius:8px; -moz-border-radius:8px;' /></td></tr></table></form>";
}

if ($verified == "0") {
    echo "This link is no longer working.  If you still need to reset your password, please contact an administartor using the contact form in the menu.";
}

if ($verified == "2") {
    echo "You have changed your password, please login.";
}
?>
