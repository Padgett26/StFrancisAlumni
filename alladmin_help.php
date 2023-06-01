<?php
if(filter_input(INPUT_POST, 'helpedit', FILTER_SANITIZE_STRING)) {
    $id = filter_input(INPUT_POST, 'helpedit', FILTER_SANITIZE_STRING);
    $stmt = $db_conn->prepare("SELECT * FROM help WHERE id=?");
    $stmt->execute(array($id));
    $row = $stmt->fetch();
    $title = $row[1];
    $helptext = $row[2];
    $lvlcansee = $row[3];
    echo "<form method='post' action='index.php?page=alladmin&choice=help'>The minimum level required to see help topic: <select name='lvlcansee' size='1'><option value='0'";
    echo ($lvlcansee == "0") ? " selected='selected'" : "";
    echo ">0 - Everyone</option><option value='1'";
    echo ($lvlcansee == "1") ? " selected='selected'" : "";
    echo ">1 - User</option><option value='2'";
    echo ($lvlcansee == "2") ? " selected='selected'" : "";
    echo ">2 - Moderator</option><option value='3'";
    echo ($lvlcansee == "3") ? " selected='selected'" : "";
    echo ">3 - Administrator</option></select><br /><br />
        Title:<input type='text' name='helptitle' size='80' maxlength='100' value='$title' /><br /><br />
        Text:<br /><textarea name='helptext' cols='60' rows='10' maxlength='4950'>$helptext</textarea><br /><br />
        <input type='checkbox' name='helpdelete' value='1' /> Delete this topic.  This cannot be undone.<br /><br />
        <input type='hidden' name='helptopic' value='$id' /><input type='submit' value=' -GO- ' /></form>";
}

if(filter_input(INPUT_POST, 'helptopic', FILTER_SANITIZE_STRING)) {
    $id = filter_input(INPUT_POST, 'helptopic', FILTER_SANITIZE_STRING);
    $title = filter_input(INPUT_POST, 'helptitle', FILTER_SANITIZE_STRING);
    $helptext = filter_input(INPUT_POST, 'helptext', FILTER_SANITIZE_STRING);
    $lvlcansee = filter_input(INPUT_POST, 'lvlcansee', FILTER_SANITIZE_NUMBER_INT);
    $helpdelete = filter_input(INPUT_POST, 'helpdelete', FILTER_SANITIZE_NUMBER_INT);
    if ($id == "new") {
        $stmt = $db_conn->prepare("INSERT INTO help VALUES" . "(NULL,?,?,?)");
        $stmt->execute(array($title,$helptext,$lvlcansee));
        echo "<br /><br />The help topic has been added...";
    } else {
        if ($helpdelete == "1") {
            $stmt = $db_conn->prepare("DELETE FROM help WHERE id=?");
            $stmt->execute(array($id));
            echo "<br /><br />The help topic has been deleted...";
        } else {
            $stmt = $db_conn->prepare("UPDATE help SET title=?,helptext=?,lvlcansee=? WHERE id=?");
            $stmt->execute(array($title,$helptext,$lvlcansee,$id));
            echo "<br /><br />The help topic has been updated...";
        }
    }
}

echo "Select the help topic you would like to edit.<br />
    <form method='post' action='index.php?page=alladmin&choice=help'>
    <input type='radio' name='helpedit' value='new' id='helpedit' /> <label for='helpedit'>Create a new help topic</label><br />";
$stmt = $db_conn->prepare("SELECT id,title FROM help");
$stmt->execute();
while ($row = $stmt->fetch()) {
    $id = $row['id'];
    $title = $row['title'];
    echo "<input type='radio' name='helpedit' value='$id' id='$id' /> <label for='$id'>$title</label><br />";
}
echo "<input type='submit' value=' -GO- ' /></form>";
?>
