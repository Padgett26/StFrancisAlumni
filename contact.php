<?php

if (filter_input(INPUT_POST, 'inputcontact', FILTER_SANITIZE_NUMBER_INT)) {
    $namecontact = filter_input(INPUT_POST, 'namecontact', FILTER_SANITIZE_STRING);
    $emailcontact = filter_input(INPUT_POST, 'emailcontact', FILTER_SANITIZE_EMAIL);
    $yearcontact = filter_input(INPUT_POST, 'yearcontact', FILTER_SANITIZE_NUMBER_INT);
    $messagecontact = filter_input(INPUT_POST, 'messagecontact', FILTER_SANITIZE_STRING);
    $createdcontact = time() + (2 * 60 * 60);
    $stmt = $db_conn->prepare("INSERT INTO contact VALUES" . "(NULL,?,?,?,?,'0',?)");
    $stmt->execute(array($namecontact,$emailcontact,$yearcontact,$messagecontact,$createdcontact));
    echo "Thank you for your input.  As soon as it is reviewed, someone will get in contact with you.";
} else {
    if ($userid) {
        $stmt = $db_conn->prepare("SELECT email FROM users WHERE id=?");
        $stmt->execute(array($userid));
        $rowcontact = $stmt->fetch();
        $useremail = $rowcontact['email'];
    }
    echo <<<_END
<div style='text-align:center; font-size:1.65em;'>Please add as much detail as possible when filling out this form.</div>
<form method="post" action="index.php?page=contact">
<table cellpadding="10px" cellspacing="0px" style="border:2px solid #000000; margin-top:20px;">
    <tr>
        <td>Name:</td>
        <td>
_END;
    if ($userlvl == "0")
        echo "<input type='text' name='namecontact' value='' size='40' maxlength='40' />";
    else
        echo "$username<input type='hidden' name='namecontact' value='$username' />";
echo <<<_END
    </td>
    </tr>
    <tr>
        <td>Email:</td>
        <td>
_END;
    if ($userlvl == "0")
        echo "<input type='text' name='emailcontact' value='' size='40' maxlength='60' />";
    else
        echo "$useremail<input type='hidden' name='emailcontact' value='$useremail' />";
echo <<<_END
    </td>
    </tr>
    <tr>
        <td>Graduation Year or Faculty:</td>
        <td>
_END;
    if ($userlvl == "0") {
        echo "<select name='yearcontact' size='1'><option value='9999'>Faculty</option>";
        $getyear = date('Y');
        $firstyear = "1900";
        for ($j = $getyear; $j > $firstyear; --$j) {
            echo "<option value='$j'>$j</option>";
        }
        echo "</select>";
    }
    else
        echo "$useryear<input type='hidden' name='yearcontact' value='$useryear' />";
echo <<<_END
    </td>
     </tr>
     <tr>
        <td colspan="2">Message:<br /><textarea name="messagecontact" cols="80" rows="10" maxlength="2000"></textarea></td>
     </tr>
     <tr>
        <td><input type="hidden" name="inputcontact" value="$userid" /><input type="submit" value=" -Submit- " style='border-radius:8px; -moz-border-radius:8px;' /></td>
        <td></td>
     </tr>
</table>
</form>
_END;
}
?>
