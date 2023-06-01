<?php


$stmt = $db_conn->prepare("SELECT id, firstname, miname, lastname, maidenname, email, year FROM users WHERE approved='0' AND usercreated='1'");
$stmt->execute();
echo "Applications awaiting approval / disapproval:<br /><form method='post' action='index.php?page=alladmin&choice=approve'><table>";
while ($rowapprove2 = $stmt->fetch()) {
    $idapprove2 = $rowapprove2[0];
    $firstnameapprove2 = $rowapprove2[1];
    $minameapprove2 = $rowapprove2[2];
    $lastnameapprove2 = $rowapprove2[3];
    $maidennameapprove2 = $rowapprove2[4];
    $emailapprove2 = $rowapprove2[5];
    $yearapprove2 = $rowapprove2[6];
    if ($yearapprove2 == "9999") {
        $textyearapprove2 = "Faculty";
    } else {
        $textyearapprove2 = $yearapprove2;
    }
    $substmt = $db_conn->query("SELECT COUNT(id) FROM users WHERE year='$yearapprove2' AND userlvl='2'");
    $subrow = $substmt->fetchColumn();
    $count = $subrow[0];
    if ($count >= 1)
        $hasmod = "Has Mod";
    else
        $hasmod = "No Mod";
    echo "<tr><td style='border:1px solid #000000; text-align:center;'>$firstnameapprove2";
    if ($minameapprove2)
        echo " $minameapprove2";
    echo " $lastnameapprove2";
    if ($maidennameapprove2)
        echo " ($maidennameapprove2)";
    echo "</td><td style='border:1px solid #000000; text-align:center;'>$textyearapprove2<br />$hasmod</td>
    <td style='border:1px solid #000000; text-align:center;'><a href='mailto:$emailapprove2'>$emailapprove2</a></td>
    <td style='border:1px solid #000000; text-align:center;'><input type='radio' name='send$idapprove2' value='a' id='app$idapprove2'><label for='app$idapprove2'>Approve this user</label></td>
    <td style='border:1px solid #000000; text-align:center;'><input type='radio' name='send$idapprove2' value='d' id='dis$idapprove2'><label for='dis$idapprove2'>Disapprove and delete this application.</label></td>
    <td style='border:1px solid #000000; text-align:center;'><input type='radio' name='send$idapprove2' value='u' id='wait$idapprove2'><label for='wait$idapprove2'>Decide later</label></td>
    <td style='border:1px solid #000000; text-align:center;'><input type='radio' name='send$idapprove2' value='e' id='erase$idapprove2'><label for='erase$idapprove2'>Delete w/o sending email</label></td></tr>";
}
echo "<tr><td colspan='5'><input type='hidden' name='iduserapprove' value='1' /><input type='submit' value=' -Go- ' style='border-radius:8px; -moz-border-radius:8px;' /></td></tr></table></form><br /><br />";
?>
