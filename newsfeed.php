<?php

if (filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT)) {
    $limit = filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT);
}
else
    $limit = "10";

if (filter_input(INPUT_POST, 'newspost', FILTER_SANITIZE_STRING)) {
    $newspost = filter_input(INPUT_POST, 'newspost', FILTER_SANITIZE_STRING);
    $newstitle = filter_input(INPUT_POST, 'newstitle', FILTER_SANITIZE_STRING);
    $created = time() + (2 * 60 * 60); //adjust to centeral time
    $stmt = $db_conn->prepare("INSERT INTO newsfeed VALUES" . "(NULL, ?, ?, ?,'0','0')");
    $stmt->execute(array($newstitle,$newspost,$created));
    echo "News post uploaded...";
}

if (filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT)) {
    $delete = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT);
    if ($userlvl == "3") {
        echo "<div style='color:red; font-size:1.5em; float:left;'>Are you sure you want to delete this post?</div><div style='float:left; margin-left:10px;'><form method='post' action='index.php?page=newsfeed'><input type='submit' value=' -No- ' /></form></div><div style='float:left; margin-left:10px;'><form method='post' action='index.php?page=newsfeed'><input type='hidden' name='delete2' value='$delete' /><input type='submit' value=' -Delete- ' /></form></div><br /><br />";
    }
    else
        echo "Nothing changed";
}

if (filter_input(INPUT_POST, 'delete2', FILTER_SANITIZE_NUMBER_INT)) {
    $delete = filter_input(INPUT_POST, 'delete2', FILTER_SANITIZE_NUMBER_INT);
    if ($userlvl == "3") {
        $stmt = $db_conn->prepare("DELETE FROM newsfeed WHERE id=?");
        $stmt->execute(array($delete));
    }
    else
        echo "Nothing changed";
}
if ($userlvl == "3") {
    echo "Post news:<br />
    <form method='post' action='index.php?page=newsfeed'>
    Title: <input type='text' name='newstitle' size='80' maxlength='200' /><br />
    <textarea name='newspost' cols='80' rows='6' maxlength='1975'></textarea><br />
    <input type='submit' value=' -Submit- ' style='border-radius:8px; -moz-border-radius:8px;' /></form><br /><br />";
}
echo "<div style='margin-right:10px;'>Display: ";
if ($limit == '10')
    echo "10 | ";
else
    echo "<a href='index.php?page=newsfeed&limit=10'>10</a> | ";
if ($limit == '25')
    echo "25 | ";
else
    echo "<a href='index.php?page=newsfeed&limit=25'>25</a> | ";
if ($limit == '50')
    echo "50 | ";
else
    echo "<a href='index.php?page=newsfeed&limit=50'>50</a> | ";
if ($limit == '100')
    echo "100";
else
    echo "<a href='index.php?page=newsfeed&limit=100'>100</a>";
echo "</div>";

$stmt = $db_conn->prepare("SELECT * FROM newsfeed ORDER BY created DESC LIMIT $limit");
$stmt->execute();
while ($row = $stmt->fetch()) {
    $idbboard = $row['id'];
    $title = $row['title'];
    $content = nl2br($row['content']);
    $created = $row['created'];
    $date = date("M j, Y, g:i a", $created);
    echo <<<_END
   <table cellpadding="10px" cellspacing="0px" width="100%" style="margin-top:10px; border:2px solid #000000; border-radius:25px; -moz-border-radius:25px;">
       <tr>
       <td style=" border-right:2px solid #000000; vertcal-align:top; width:25%;">
_END;
           
    echo <<<_END
           <div style="margin-top:0px;">$date<br />
_END;
    if ($userlvl == "3") {
        echo "<a href='index.php?page=newsfeed&delete=$idbboard'>Delete post</a><br />";
    }
    echo "</td><td style='vertical-align:top; width:75%;'>";
    echo "<div style='text-align:center; font-size:1.25em; font-weight:bold;'>$title</div><br /><div style='text-align:justify;'>$content</div>";
    echo "</td></tr></table>";
}
echo "<br /><br />";
?>
