<?php
if (filter_input(INPUT_POST, 'userappup', FILTER_SANITIZE_NUMBER_INT)) {
    $firstnameapp = filter_input(INPUT_POST, 'firstnameapp', FILTER_SANITIZE_STRING);
    $minameapp = filter_input(INPUT_POST, 'minameapp', FILTER_SANITIZE_STRING);
    $lastnameapp = filter_input(INPUT_POST, 'lastnameapp', FILTER_SANITIZE_STRING);
    $maidennameapp = filter_input(INPUT_POST, 'maidennameapp', FILTER_SANITIZE_STRING);
    $emailapp = filter_input(INPUT_POST, 'emailapp', FILTER_SANITIZE_EMAIL);
    $yearapp = filter_input(INPUT_POST, 'yearapp', FILTER_SANITIZE_NUMBER_INT);
    $now = time();
    if ($lastnameapp == "" || $yearapp == "none" || $emailapp == "") {
        $apperror = "Not all fields were filled in, please fill in all fields before submitting the form";
        $displayform = "1";
    } else {
        echo "<div style=''>Thank you for your application.<br />An email has been sent to the email address you submitted.  In it you will find a link you will need to click before we can approve you.<br />We do this to verify that you have a usable email address, and you are not someone who is just trying to gain access to this site for malicious purposes.<br />As soon as it is approved, you will have access to many of the exciting features of this site.<br /><br />Including:<br />Sending private messages to other alumni<br />Viewing and adding to your year's newsfeed<br />Adding pictures<br />Editing your yearbook information and adding a description of what you have been doing since graduating<br />And more...<br /><br />If in a few minutes you have not received the email from the Alumni web site, the email may have been mistakenly labeled as spam, so please check your spam folder.  If you are still having trouble, you can re-fill out the membership request form, <a href='index.php?page=contact'>or send us a note using the Contact us page by clicking this sentence.</a></div>";
        $displayform = "0";
        $message = "
                <html><head></head><body>
                Thank you for your interest in the Saint Francis Alumni website.<br />
                We have you verify your email as a means of weeding out spammers and computer programs which try and get into as many sites as they can. Spammers and computers will not have an email address that works, or they would be unwilling to give us a real email.<br />
                Your email will be verified with the site once you click on the link below.  If your email does not support html based emails, you may need to copy the address and pasteit into a browser address bar.<br /><br />
                <a href='index.php?page=emailverify&firstname=$firstnameapp&miname=$minameapp&lastname=$lastnameapp&maidenname=$maidennameapp&email=$emailapp&year=$yearapp&n=$now'>index.php?page=emailverify&firstname=$firstnameapp&lastname=$lastnameapp&maidenname=$maidennameapp&email=$emailapp&year=$yearapp&n=$now</a><br /><br />
                I know it is a long and ugly address, but if you cut and paste it into a browser address bar, please get the whole thing.<br /><br />
                Next we will have a moderator approve your application and you can enjoy some of the fun features of the Saint Francis Alumni website.  Thank you for your patience with this process.
                </body></html>";
        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Saint Francis Alumni <support@stfrancisalumni.org>' . "\r\n";
        // Send
        mail($emailapp, 'Verification link from stfrancisalumni.com', $message, $headers);
    }
} else {
    $displayform = "1";
}

if ($displayform == "1") {
    ?>
    <div style="text-align:justify; font-size:1.25em;">If you are a Saint Francis Alumni and / or Faculty, and you want to interact with the people and features on this site, this form is the place to start.<br /><br />
        After filling out this form, you will receive an email to the address you provide.  In it there will be a link which brings you back to this site.  We do this to verify that you are a person with a real email address and not a program trying to gain access to the site.<br /><br />
        If you have submitted this form and feel that it is taking too long to receive your email, <br /><a href="index.php?page=contact">contact us on the contact us page, by clicking this sentence.</a><br /><br />
        If you do not receive your email fairly soon, check your junk or spam folder.<br /><br />
        All fields marked with an * are required.</div>
    <div style="margin-left:50px; margin-top:30px;">
        <form method="post" action="index.php?page=userapp">
            <table cellpadding="5px" cellspacing="0px" border="2px">
                <tr>
                    <td colspan="2" style="text-align:center;">
    <?php
    $apperror
    ?>
                    </td>
                </tr>
                <tr>
                    <td>First name *</td>
                    <td><input type="text" name="firstnameapp" size='40' maxlength='30' /></td>
                </tr>
                <tr>
                    <td>M.I.</td>
                    <td><input type="text" name="minameapp" size='2' maxlength='2' /></td>
                </tr>
                <tr>
                    <td>Last name *</td>
                    <td><input type="text" name="lastnameapp" size='40' maxlength='30' /></td>
                </tr>
                <tr>
                    <td>Maiden name</td>
                    <td><input type="text" name="maidennameapp" size='40' maxlength='30' /></td>
                </tr>
                <tr>
                    <td>Email *</td>
                    <td><input type="text" name="emailapp" size='40' maxlength='60' /></td>
                </tr>
                <tr>
                    <td>Graduation year *</td>
                    <td><select name="yearapp" size="1"><option value="none">Select</option><option value="9999">Faculty</option>
    <?php
    $getyear = date('Y');
    $firstyear = "1900";
    for ($j = $getyear; $j > $firstyear; --$j) {
        echo "<option value='$j'>$j</option>\n";
    }
    ?>
                        </select></td>
                </tr>
                <tr>
                    <td colspan='2' style="text-align:center;"><input type="hidden" name="userappup" value="1" /><input type="submit" value=" -Submit- " style='border-radius:8px; -moz-border-radius:8px;' /></td>
            </table>
        </form>
    </div>
    <?php
}
?>