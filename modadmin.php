<?php
include "modadmin_dbupdate.php";

if ($userlvl == "2") {
    $stmt = $db_conn->query("SELECT COUNT(id) FROM users WHERE approved='0' AND usercreated='1' AND year='$useryear'");
    $numapprove = $stmt->fetchColumn();
    $stmt->closeCursor();

    $stmt = $db_conn->query("SELECT COUNT(id) FROM contact WHERE resolved='0' AND year='$useryear'");
    $numcontact = $stmt->fetchColumn();
    $stmt->closeCursor();

    $stmt = $db_conn->query("SELECT COUNT(id) FROM reports WHERE resolved='0' AND year='$useryear'");
    $numreport = $stmt->fetchColumn();
    $stmt->closeCursor();

    echo <<<_END
    $errorprof
    Welcome $username...<br /><br />
    <a href="index.php?page=modadmin">Refresh this page</a><br /><br />
    <a href="index.php?page=modadmin&choice=approve" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        $numapprove<br />Users needing approved
    </div></a>
    <a href="index.php?page=modadmin&choice=contact" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        $numcontact<br />Inquiries needing resolved
    </div></a>
    <a href="index.php?page=modadmin&choice=reports" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        $numreport<br />Reported items to investigate
    </div></a>
    <a href="index.php?page=modadmin&choice=users" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        &nbsp;<br />Edit users
    </div></a>
    <a href="index.php?page=modadmin&choice=gmess" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        Send a message to all $useryear
    </div></a>
    <a href="index.php?page=modadmin&choice=gemail" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        Send an email to all $useryear
    </div></a>
    <a href="index.php?page=modadmin&choice=ybook" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        $useryear Yearbook
    </div></a>
_END;
} else {
    echo "You do not have access to this page, please select another.";
}

if (filter_input(INPUT_GET, 'choice', FILTER_SANITIZE_STRING)) {
    $choice = filter_input(INPUT_GET, 'choice', FILTER_SANITIZE_STRING);
    echo "<div style='margin:270px 0px 0px 20px;'>";
    include "modadmin_$choice.php";
    echo "</div>";
}

?>
