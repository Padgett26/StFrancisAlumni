<?php
$stmt = $db_conn->prepare("SELECT id, firstname, miname, lastname, maidenname, email, year FROM users WHERE approved='0' AND usercreated='1' AND year=?");
$stmt->execute(array($useryear));
echo "Applications awaiting approval / disapproval:<br /><form method='post' action='index.php?page=modadmin&choice=approve'><table>";
while ($rowapprove2 = $stmt->fetch()) {
    $idapprove2 = $rowapprove2['id'];
    $firstnameapprove2 = $rowapprove2['firstname'];
    $minameapprove2 = $rowapprove2['miname'];
    $lastnameapprove2 = $rowapprove2['lastname'];
    $maidennameapprove2 = $rowapprove2['maidenname'];
    $emailapprove2 = $rowapprove2['email'];
    $yearapprove2 = $rowapprove2['year'];
    if ($yearapprove2 == "9999") {
        $textyearapprove2 = "Faculty";
    } else {
        $textyearapprove2 = $yearapprove2;
    }
    echo "<tr><td style='border:1px solid #000000; text-align:center;'>$firstnameapprove2";
    if ($minameapprove2)
        echo " $minameapprove2";
    echo " $lastnameapprove2";
    if ($maidennameapprove2)
        echo " ($maidennameapprove2)";
    echo "</td><td style='border:1px solid #000000; text-align:center;'>$textyearapprove2</td>
    <td style='border:1px solid #000000; text-align:center;'><a href='mailto:$emailapprove2'>$emailapprove2</a></td>
    <td style='border:1px solid #000000; text-align:center;'><input type='radio' name='send$idapprove2' value='a' id='app$idapprove2'><label for='app$idapprove2'>Approve this user</label></td>
    <td style='border:1px solid #000000; text-align:center;'><input type='radio' name='send$idapprove2' value='d' id='dis$idapprove2'><label for='dis$idapprove2'>Disapprove and delete this application.</label></td>
    <td style='border:1px solid #000000; text-align:center;'><input type='radio' name='send$idapprove2' value='u' id='wait$idapprove2'><label for='wait$idapprove2'>Decide later</label></td>
    <td style='border:1px solid #000000; text-align:center;'><input type='radio' name='send$idapprove2' value='e' id='erase$idapprove2'><label for='erase$idapprove2'>Delete w/o sending email</label></td></tr>";
}
echo "<tr><td colspan='5'><input type='hidden' name='iduserapprove' value='1' /><input type='submit' value=' -Go- ' style='border-radius:8px; -moz-border-radius:8px;' /></td></tr></table></form><br /><br />";
?>
