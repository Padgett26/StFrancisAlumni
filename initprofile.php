<?php

if (filter_input(INPUT_GET, 'user', FILTER_SANITIZE_STRING)) {
    $userid = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);
    if ($status == md5("$salt1$userid$salt2"))
        $initstep = "1";
    else {
        echo "<a href='index.php'>Please click this sentence and log in.</a>";
    }
}

if (filter_input(INPUT_POST, 'userpwd', FILTER_SANITIZE_NUMBER_INT)) {
    $userid = filter_input(INPUT_POST, 'userpwd', FILTER_SANITIZE_NUMBER_INT);
    $initfirstname = filter_input(INPUT_POST, 'initfirstname', FILTER_SANITIZE_STRING);
    $initminame = filter_input(INPUT_POST, 'initminame', FILTER_SANITIZE_STRING);
    $initlastname = filter_input(INPUT_POST, 'initlastname', FILTER_SANITIZE_STRING);
    $initmaidenname = filter_input(INPUT_POST, 'initmaidenname', FILTER_SANITIZE_STRING);
    $initveruser = filter_input(INPUT_POST, 'inituser', FILTER_SANITIZE_STRING);
    if (preg_match("/^[a-zA-Z0-9][a-zA-Z0-9.-_ ]*/", $initveruser)) {
        $inituser = $initveruser;
    } else {
        $inituser = "";
    }
    $initpwd1 = filter_input(INPUT_POST, 'initpwd1', FILTER_SANITIZE_STRING);
    $initpwd2 = filter_input(INPUT_POST, 'initpwd2', FILTER_SANITIZE_STRING);
    $hidepwd = md5($salt1 . $initpwd1 . $salt2);
    $terms = filter_input(INPUT_POST, 'terms', FILTER_SANITIZE_NUMBER_INT);
    if ($inituser == "" || $initpwd1 == "" || $initfirstname == "" || $initlastname == "") {
        $initstep = "1";
        $errorterms = "<span style='color:red;'>**Some fields were not filled in.**</span>";
    } else {
        if ($terms == "1") {
            $stmt = $db_conn->prepare("SELECT id FROM users WHERE user=?");
            $stmt->execute(array($inituser));
            $initnum1 = $stmt->fetch();
            if ($initnum1) {
                $initstep = "1";
                $errorpwd = "<span style='color:red;'>**The log on user name has already been taken. Please select another**</span>";
            } else {
                if ($initpwd1 == $initpwd2) {
                    $substmt = $db_conn->prepare("UPDATE users SET user=?, pwd=?, firstname=?, miname=?, lastname=?, maidenname=? WHERE id=?");
                    $substmt->execute(array($inituser,$hidepwd,$initfirstname,$initminame,$initlastname,$initmaidenname,$userid));
                    $initstep = "2";
                    $subsubstmt = $db_conn->prepare("SELECT id, firstname, miname, lastname, userlvl, year FROM users WHERE user=? AND pwd=?");
                    $subsubstmt->execute(array($inituser,$hidepwd));
                    $initrow2 = $subsubstmt->fetch();
                    $userid2 = $initrow2['id'];
                    $firstname = $initrow2['firstname'];
                    $miname = $initrow2['miname'];
                    $lastname = $initrow2['lastname'];
                    $fullname = $firstname;
                    if ($miname)
                        $fullname .= " " . $miname;
                    $fullname .= " " . $lastname;
                    $userlvl = $initrow2['userlvl'];
                    $useryear = $initrow2['year'];
                    $_SESSION['userid'] = $userid2;
                    $_SESSION['user'] = $fullname;
                    $_SESSION['userlvl'] = $userlvl;
                    $_SESSION['useryear'] = $useryear;
                    $subsubsubstmt = $db_conn->prepare("INSERT INTO usersettings VALUES" . "('NULL', ?, '0', '0', '1', '0', '0', '0', '0', '0')");
                    $subsubsubstmt->execute(array($userid2));
                } else {
                    $initstep = "1";
                    $errorpwd = "<span style='color:red;'>**Your passwords did not match.**</span>";
                }
            }
        } else {
            $initstep = "1";
            $errorterms = "<span style='color:red;'>**You must agree to these terms before you will be given access to this website.**</span>";
        }
    }
}

if (filter_input(INPUT_POST, 'addys', FILTER_SANITIZE_NUMBER_INT)) {
    $initadd = filter_input(INPUT_POST, 'initadd', FILTER_SANITIZE_STRING);
    $initcityst = filter_input(INPUT_POST, 'initcityst', FILTER_SANITIZE_STRING);
    $initzip = filter_input(INPUT_POST, 'initzip', FILTER_SANITIZE_STRING);
    $initphone = filter_input(INPUT_POST, 'initphone', FILTER_SANITIZE_STRING);
    $stmt = $db_conn->prepare("UPDATE users SET address=?, cityst=?, zip=?, phone=? WHERE id=?");
    $stmt->execute(array($initadd,$initcityst,$initzip,$initphone,$userid));
    $initstep = "3";
}

if (filter_input(INPUT_POST, 'ucreated', FILTER_SANITIZE_STRING)) {
    $ucreated = filter_input(INPUT_POST, 'ucreated', FILTER_SANITIZE_STRING);
    if ($ucreated != "new") {
        $stmt = $db_conn->prepare("DELETE FROM users WHERE id=? AND usercreated='0'");
        $stmt->execute(array($ucreated));
    }
    $initstep = "4";
}

if (filter_input(INPUT_POST, 'usertext', FILTER_SANITIZE_STRING)) {
    $usertext = filter_input(INPUT_POST, 'usertext', FILTER_SANITIZE_STRING);
    $stmt = $db_conn->prepare("UPDATE users SET descpara=? WHERE id=?");
    $stmt->execute(array($usertext,$userid));
    $initstep = "5";
}

if ($initstep == "1") {
    $stmt = $db_conn->prepare("SELECT user, pwd, firstname, miname, lastname, maidenname FROM users WHERE id=?");
    $stmt->execute(array($userid));
    $initrow = $stmt->fetch();
    $inituser = $initrow['user'];
    $initpwd = $initrow['pwd'];
    $initfirstname = $initrow['firstname'];
    $initminame = $initrow['miname'];
    $initlastname = $initrow['lastname'];
    $initmaidenname = $initrow['maidenname'];
    if ($inituser != "" && $initpwd != "") {
        echo "<div style='text-align:justify; font-size:1.25em;'>It seems you have already set up a user name and password.</div>";
        echo "<a href='index.php'>Please click this sentence and log in. Your information can be changed on your profile page.</a>";
    } else {
        echo "<div style='text-align:justify; font-size:1.25em;'>Lets get your user name and password set up for this site.<br />This will be what you type in to log on to this website.<br />Fields marked with an * are required.<br /><br />";
        echo "<form method='post' action='index.php?page=initprofile'>";
        echo "*What is your first name?<br /><input type='text' name='initfirstname' value='$initfirstname' size='40' maxlength='30' /><br /><br />";
        echo "What is your middle initial?<br /><input type='text' name='initminame' value='$initminame' size='2' maxlength='2' /><br /><br />";
        echo "*What is your last name?<br /><input type='text' name='initlastname' value='$initlastname' size='40' maxlength='30' /><br /><br />";
        echo "What is your maiden name? If none, just leave blank.<br /><input type='text' name='initmaidenname' value='$initmaidenname' size='40' maxlength='30' /><br /><br />";
        echo "*What would you like your user name to be?<br /><span style='font-size:.75em;'>This should be different than your real name.<br />Must start with a letter or number<br />Then<br />Letters numbers . - _ and spaces only</span><br /><input type='text' name='inituser' size='40' maxlength='40' value='$inituser' /><br />$errorpwd<br />";
        echo "*Please type in the passsword you want to use:<br /><input type='password' name='initpwd1' size='40' maxlength='40' /><br /><br />";
        echo "*Please type your passsword again:<br /><input type='password' name='initpwd2' size='40' maxlength='40' /><br /><br />";
        echo "<p style='text-align:center;'>$errorterms</p><p style='text-align:justify;'><blockquote>Any media uploaded or added to this site, whether it be a photograph, or text, or any other means, is considered to be public, and is subject to review or even deletion by the administrators of this site.</blockquote></p>";
        echo "<div style='text-align:center; font-weight:bold;'>*Do you agree to these terms?<br /><input type='radio' name='terms' value='1' id='terms1' /><label for='terms1'>YES</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' name='terms' value='0' id='terms0' /><label for='terms0'>NO</label></div>";
        echo "<input type='hidden' name='userpwd' value='$userid' /><input type='submit' value=' -Submit- ' style='border-radius:8px; -moz-border-radius:8px;' /></form></div><br /><br />";
    }
}

if ($initstep == "2") {
    echo "<div style='text-align:justify; font-size:1.25em;'>From now on you can use your user name and password to log in on the home page of this web site.<br /><br />Lets get a little more information.  This information WILL NOT be visible to anyone else and is not required.  It will be used by the Alumni Association only, and will never given out or sold.  The only information we will show on your page will be your name, current city, and your descriptive paragraph, which you can write in a minute.<br /><br />";
    echo "<form method='post' action='index.php?page=initprofile'>What is your address?<br /><input type='text' name='initadd' size='40' maxlength='60' /><br /><br />";
    echo "What is your city and ST?<br /><input type='text' name='initcityst' size='40' maxlength='60' /><br /><br />";
    echo "What is your zip code?<br /><input type='text' name='initzip' size='5' maxlength='5' /><br /><br />";
    echo "What is your phone number?<br /><input type='text' name='initphone' size='13' maxlength='13' /><br /><br />";
    echo "<input type='hidden' name='addys' value='$userid' /><input type='submit' value=' -Submit- ' style='border-radius:8px; -moz-border-radius:8px;' /></form></div>";
}

if ($initstep == "3") {
    echo "Your information has been saved.<br /><br />";
    $stmt = $db_conn->prepare("SELECT year FROM users WHERE id=?");
    $stmt->execute(array($userid));
    $initrow = $stmt->fetch();
    $year = $initrow['year'];
    $substmt = $db_conn->prepare("SELECT id, firstname, miname, lastname FROM users WHERE year=? AND usercreated='0'");
    $substmt->execute(array($year));
    $subnuminit = $substmt->rowCount();
    if ($subnuminit >= 1) {
        echo "<div style='text-align:justify; font-size:1.25em;'>It seems that we have uploaded user profiles for this year. If you are in the list, please select your name, if not, please select new.<br /></div>";
        echo "<form method='post' action='index.php?page=initprofile'><input type='radio' name='ucreated' value='new' id='new' /><label for='new'>New</label><br />";
        while ($subrowinit = $substmt->fetch()) {
            $subinitid = $subrowinit['id'];
            $subinitfirstname = $subrowinit['firstname'];
            $subinitminame = $subrowinit['miname'];
            $subinitlastname = $subrowinit['lastname'];
            echo "<input type='radio' name='ucreated' value='$subinitid' id='$subinitid' /><label for='$subinitid'>$subinitfirstname $subinitminame $subinitlastname</label><br />";
        }
        echo "<input type='submit' value=' -Submit- ' /></form>";
    }
    else
        echo "<div style='text-align:justify; font-size:1.25em;'>I just checked to see if there is any previously uploaded data that might be related to your account, and there isn&#39;t.<br />Please press &#39;Continue&#39;</div><form method='post' action='index.php?page=initprofile'><input type='hidden' name='ucreated' value='new' /><input type='submit' value='Continue' style='border-radius:8px; -moz-border-radius:8px;' /></form>";
}

if ($initstep == "4") {
    echo "<div style='text-align:justify; font-size:1.25em;'>Lets get your descriptive paragraph written.<br /><br />";
    echo "Your descriptive paragraph should contain any information you want any registered user to see.  We would suggest not putting your phone number or email address in this paragragh.  You will be able to send private messages to other users, and if you wanted someone to have your email address or phone number you should send it to them in a private message.  This paragraph is great place to let everyone know what you have been doing since graduation.<br />We allow 1500 characters in this field.<br /></div>";
    echo "<form method='post' action='index.php?page=initprofile'><textarea name='usertext' cols='80' rows='10' maxlength='1500'></textarea><br /><input type='submit' value=' -Continue- ' /></form>";
}

if ($initstep == "5") {
    echo "<div style='text-align:justify; font-size:1.25em;'>OK, you are all set up.  Let&#39;s get started using the site.<br /><br />If you need to change any information you just entered, use the profile link<br /><br />To send another person a private message, find them on the classmates page and press the &#39;Send Private Message&#39; link.<br /><br />Visit the bulliten board to talk to everyone from your alumni year.<br /><br />If you have pictures you would like to upload, visit the picture page for your year, and you will find the link.<br /><br />If you find yourself stuck, or not sure how to use a feature on the site, please visit the &#39;Contact Us&#39; page.<br /></div>";
    echo "<form method='post' action='index.php'><input type='submit' value='Continue' style='border-radius:8px; -moz-border-radius:8px;' /></form>";
}
?>
