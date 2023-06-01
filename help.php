<?php
echo "<br /><br />";
$stmt = $db_conn->prepare("SELECT id,title,helptext FROM help WHERE lvlcansee<=? ORDER BY title");
$stmt->execute(array($userlvl));
while ($row = $stmt->fetch()) {
    $j = $row['id'];
    $title = $row['title'];
    $helptext = nl2br($row['helptext']);
echo <<<_END
<a href="#" onclick="toggleview('s$j')" style="text-decoration:none; font-weight:bold; font-size:1.25em;">$title</a><br />
<div id='s$j' style="display:none; text-align:justify;"><blockquote>$helptext</blockquote></div><br />
_END;
}
?>
