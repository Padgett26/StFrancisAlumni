<?php

if ($useryear == "facu")
    $modyear = "Faculty";
else
    $modyear = $useryear;

if (filter_input(INPUT_POST, 'inputmodapp', FILTER_SANITIZE_NUMBER_INT)) {
    $namemodapp = filter_input(INPUT_POST, 'namemodapp', FILTER_SANITIZE_STRING);
    $emailmodapp = filter_input(INPUT_POST, 'emailmodapp', FILTER_SANITIZE_EMAIL);
    $yearmodapp = filter_input(INPUT_POST, 'yearmodapp', FILTER_SANITIZE_NUMBER_INT);
    $messagemodapp = filter_input(INPUT_POST, 'messagemodapp', FILTER_SANITIZE_STRING);
    $createdmodapp = time() + (2 * 60 * 60);
    $stmt = $db_conn->prepare("INSERT INTO contact VALUES" . "(NULL, ?, ?, ?, ?, '0', ?)");
    $stmt->execute(array($namemodapp,$emailmodapp,$yearmodapp,$messagemodapp,$createdmodapp));
    echo "Thank you for your input.  As soon as it is reviewed, someone will get in contact with you.";
} else {
    if ($userid) {
        $stmt = $db_conn->prepare("SELECT email FROM users WHERE id=?");
        $stmt->execute(array($userid));
        $rowmodapp = $stmt->fetch();
        $useremail = $rowmodapp['email'];
    }
    echo <<<_END
<div style='text-align:left; font-size:1.25em;'>If you would like to be a moderator for $useryear, this is the form you need to fill out.<br /><br />Being a moderator means:<br /><blockquote>You will be able to help your graduating class with their profiles.<br />You will be able to read and answer questions posted on the contact us form.<br />You will be able to investigate reported pictures, messages, and bullitin board postings.<br />You will be given a &#39;Delete&#39; button, for removing offensive postings.<br />You will need to verify and approve new users to the web site.  You will need to know your class well enough to know who was actually in your class and should be approved, and who wasn&#39;t.<br />As a side note: students that went to our school, but didn't graduate here, should still be approved.<br />Being a moderator means you are willing to check your administration page for any inquiries that need to be answered, any reports that need to be investigated, any users that need to be approved, or any other issue that needs to be addressed.<br />You will not be alone, the administrators of this site are here to help you too, and together we can get any question answered.</blockquote><br />Please add your information to this form, and let us know if you would be willing to help us maintain this site by helping out your fellow alumni</div>
<form method="post" action="index.php?page=modapp">
<table cellpadding="10px" cellspacing="0px" style="border:2px solid #000000; margin-top:20px;">
    <tr>
        <td>Name:</td>
        <td><input type="hidden" name="namemodapp" value="$username" />$username</td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><input type="hidden" name="emailmodapp" value="$useremail" />$useremail</td>
    </tr>
    <tr>
        <td>Graduation Year or Faculty:</td>
        <td><input type='hidden' name="yearmodapp" value="$useryear" />$modyear</td>
     </tr>
     <tr>
        <td colspan="2">Message:<br /><textarea name="messagemodapp" cols="80" rows="10" maxlength="2000"></textarea></td>
     </tr>
     <tr>
        <td><input type="hidden" name="inputmodapp" value="1" /><input type="submit" value=" -Submit- " style='border-radius:8px; -moz-border-radius:8px;' /></td>
        <td></td>
     </tr>
</table>
</form><br /><br />
_END;
}
?>