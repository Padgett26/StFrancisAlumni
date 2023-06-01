<?php
if (filter_input(INPUT_POST, 'reportid', FILTER_SANITIZE_NUMBER_INT)) {
    $reportid = filter_input(INPUT_POST, 'reportid', FILTER_SANITIZE_NUMBER_INT);
    $reporttable = filter_input(INPUT_POST, 'table', FILTER_SANITIZE_STRING);
    $reportdelete = filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_NUMBER_INT);
    $resolved = filter_input(INPUT_POST, 'resolved', FILTER_SANITIZE_NUMBER_INT);
    $contactorigtext = filter_input(INPUT_POST, 'contactorigtext', FILTER_SANITIZE_STRING);
    if ($resolved == "1") {
        $stmt = $db_conn->prepare("UPDATE reports SET resolved='1' WHERE id=?");
        $stmt->execute(array($reportid));
        echo "Marked as resolved <br />";
    }
    if ($reportdelete) {
        $stmt = $db_conn->prepare("DELETE FROM ? WHERE id=?");
        $stmt->execute(array($reporttable,$reportdelete));
        echo "Item deleted<br />";
    }
}

if (filter_input(INPUT_POST, 'contactid', FILTER_SANITIZE_NUMBER_INT)) {
    $contactid = filter_input(INPUT_POST, 'contactid', FILTER_SANITIZE_NUMBER_INT);
    $contacttext = nl2br(filter_input(INPUT_POST, 'contacttext', FILTER_SANITIZE_STRING));
    $contactemail = filter_input(INPUT_POST, 'contactemail', FILTER_SANITIZE_EMAIL);
    $resolved = filter_input(INPUT_POST, 'resolved', FILTER_SANITIZE_NUMBER_INT);
    $contactorigtext = nl2br(filter_input(INPUT_POST, 'contactorigtext', FILTER_SANITIZE_STRING));
    if ($resolved == "1") {
        $stmt = $db_conn->prepare("UPDATE contact SET resolved='1' WHERE id=?");
        $stmt->execute(array($contactid));
    }
    $message = "
    <html><head></head><body>
    $contacttext<br /><br /><br />Original text...<br /><br />$contactorigtext
    </body></html>";
    // In case any of our lines are larger than 70 characters, we should use wordwrap()
    $message = wordwrap($message, 70);
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
    // Send
    mail($contactemail, 'Response to your inquiry at St Francis Alumni', $message, $headers);
}

if (filter_input(INPUT_POST, 'iduserapprove', FILTER_SANITIZE_NUMBER_INT) == "1") {
    $toApprove = array();
    $toDelete = array();
    $toErase = array();
    foreach ($_POST as $key => $val) {
        $key = filter_var($key, FILTER_SANITIZE_STRING);
        $val = filter_var($val, FILTER_SANITIZE_STRING);
        if ($val == "a" && preg_match("/^send([1-9][0-9]*)$/", $key, $amatch)) {
            $toApprove[] = $amatch[1];
        }
        if ($val == "d" && preg_match("/^send([1-9][0-9]*)$/", $key, $dmatch)) {
            $toDelete[] = $dmatch[1];
        }
        if ($val == "e" && preg_match("/^send([1-9][0-9]*)$/", $key, $ematch)) {
            $toErase[] = $ematch[1];
        }
    }
    if (count($toApprove)) {
        foreach ($toApprove as $appid) {
            $stmt = $db_conn->prepare("UPDATE users SET approved='1' WHERE id=?");
            $stmt->execute(array($appid));
            $status = md5("$salt1$appid$salt2");
            $substmt = $db_conn->prepare("SELECT firstname, miname, lastname, email FROM users WHERE id=?");
            $substmt->execute(array($appid));
            $approw = $substmt->fetch();
            $appfirst = $approw['firstname'];
            $appmi = $approw['miname'];
            $applast = $approw['lastname'];
            $name = $appfirst;
            if ($appmi)
                $name .= " " . $appmi;
            $name .= " " . $applast;
            $appemail = $approw['email'];
            $message = "
            <html><head></head><body>
            $name<br />
            Welcome to the Saint Francis Alumni website!<br /><br />
            Your membership has been approved, and now it is time to get started using this site.<br /><br />
            At the end of this email is a link that will take you to your profile page. There you will be able to enter your contact information, upload pictures of yourself and classmates, view and submit to your year specific newsfeed, see information on your classmates, and send them private messages.<br /><br />
            We hope this website becomes a powerful connection tool for you and all of your classmates.<br /><br />
            And remember, help is only and email away, go to the contact us page and let us know if there is any way we can help you.<br /><br />
            Here is the link that will get you started:<br /><br />
            <a href='index.php?page=initprofile&user=$appid&status=$status'>index.php?page=initprofile&user=$appid&status=$status</a><br /><br />
            If the above link does not work by clicking it, you can copy and paste it into your browser address bar.
            </body></html>";
            // In case any of our lines are larger than 70 characters, we should use wordwrap()
            $message = wordwrap($message, 70);
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
            // Send
            mail($appemail, "$name Welcome to stfrancisalumni.org", $message, $headers);
        }
        echo "Approved applications.<br />";
    }
    if (count($toDelete)) {
        foreach ($toDelete as $disid) {
            $stmt = $db_conn->prepare("SELECT email FROM users WHERE id=?");
            $stmt->execute(array($disid));
            $disrow = $stmt->fetch();
            $disemail = $disrow['email'];
            $message = "
            <html><head></head><body>
            We are sorry to inform you that your application for the stfrancisalumni.org website has been disapproved.<br /><br />
            This is an automatically sent letter, so there is no reason for the disapproval in this letter.<br /><br />
            If you feel this decision is in error, please click on the following link which will send you to a contact us page where you can ask specific questions in an email format.<br /><br />
            <a href='index.php?page=contact'>index.php?page=contact</a>
            </body></html>";
            // In case any of our lines are larger than 70 characters, we should use wordwrap()
            $message = wordwrap($message, 70);
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
            // Send
            mail($disemail, 'Response from stfrancisalumni.org', $message, $headers);
            
            $substmt = $db_conn->prepare("DELETE FROM users WHERE id=?");
            $substmt->execute(array($disid));
        }
        echo "Deleted applications.<br />";
    }
    if (count($toErase)) {
        foreach ($toErase as $erid) {
            $substmt = $db_conn->prepare("DELETE FROM users WHERE id=?");
            $substmt->execute(array($erid));
            echo "Deleted applications.<br />";
        }
    }
    unset($toApprove);
    unset($toDelete);
    unset($toErase);
}
?>
