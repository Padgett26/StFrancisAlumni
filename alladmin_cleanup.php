<?php

$stmt = $db_conn->prepare("SELECT userid FROM usersettings");
$stmt->execute();
while ($row = $stmt->fetch()) {
    $userid = $rowclean['userid'];
    $substmt = $db_conn->prepare("SELECT COUNT(id) FROM users WHERE id=?");
    $substmt->execute(array($userid));
    $subrow = $substmt->fetch();
    $existsclean = $subrow[0];
    if ($existsclean == 0) {
        $subsubstmt = $db_conn->prepare("DELETE FROM usersettings WHERE userid=?");
        $subsubstmt->execute(array($useridclean));
    }
}
echo "The usersettings table has been cleaned up.";

?>
