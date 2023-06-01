<?php
if (filter_input(INPUT_POST, 'bademail', FILTER_SANITIZE_EMAIL)) {
    $bademail = filter_input(INPUT_POST, 'bademail', FILTER_SANITIZE_EMAIL);
    $stmt = $db_conn->prepare("SELECT id,firstname,lastname FROM users WHERE email=?");
    $stmt->execute(array($bademail));
    while ($row = $stmt->fetch()) {
        $id = $row['id'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $substmt = $db_conn->prepare("UPDATE users SET bademail='1' WHERE id=?");
        $substmt->execute(array($id));
        $subsubstmt = $db_conn->prepare("UPDATE usersettings SET notmsg='0',notbb='0',notassoc='0',notpic='0' WHERE userid=?");
        $subsubstmt->execute(array($id));
        echo "$firstname $lastname's profile affected.<br /><br />";
    }
    if ($num == "0")
        echo "There were no users found with that email address.<br /><br />";
}

echo "This will log a bad email address, so we aren't trying to send them emails.<br />";
echo "It will also put an error on that person's profile page, so they know we cannot reaach them using the address they have recorded.<br /><br />";
echo "Please type in the bad email address:<br />";
echo "<form method='post' action='index.php?page=alladmin&choice=bademail'>";
echo "<input type='text' name='bademail' value='' /><br />";
echo "<input type='submit' value='Mark address as bad' /></form>";
?>
