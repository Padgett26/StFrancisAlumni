<?php
echo "<ul><li><a href='index.php?page=home' style='color:$colordark;'>Home</a></li>";
echo "<li><a href='index.php?page=history' style='color:$colordark;'>History</a></li>";
echo "<li><a href='index.php?page=schedule' style='color:$colordark;'>Alumni Weekend</a></li>";
echo "<li><span  style='color:$colordark; font-size:.9em;'>Select year:<br /><form method='post' action='index.php?page=classmates'><select name='selectyear' size='1'><option value='9999'";

$yearcount = array();
$got = array();
$stmt = $db_conn->prepare("SELECT year FROM users");
$stmt->execute();
while ($rowyearcount = $stmt->fetch()) {
	$got[] = $rowyearcount[0];
}
$yearcount = array_count_values($got);
if ($selectyear == "9999")
	echo " selected='selected'";
echo ">Faculty";
echo " -$yearcount[9999]";
echo "</option>";

$getyear = date('Y', $time);
$firstyear = 1900;
for ($j = $getyear; $j > $firstyear; -- $j) {
	echo "<option value='$j'";
	if ($j == $selectyear)
		echo " selected='selected'";
	echo ">$j";
	echo " -$yearcount[$j]";
	echo "</option>";
}
echo <<<_END
</select><input type='submit' value='Go' style='border-radius:8px; -moz-border-radius:8px;' /></form></span></li>
<li><a href='index.php?page=pictures' style='color:$colordark;'>Pictures</a></li>
<li><a href='index.php?page=classmates' style='color:$colordark;'>
_END;
if ($textyear == "Faculty")
	echo "$textyear-mates";
else
	echo " Classmates";
echo "</a></li>";
if ($selectyear != "9999")
	echo "<li><a href='index.php?page=seniorpics' style='color:$colordark;'>Senior Photos</a></li>";
if ($selectyear <= "9998") {
	echo "<li><a href='index.php?page=yearbook&ybyear=$selectyear' style='color:$colordark;'>Yearbook</a></li>";
}
echo "<li><a href='index.php?page=bboard' style='color:$colordark;'>Bulletin Board</a></li>";
echo "<li><a href='index.php?page=messages' style='color:$colordark;'>";
if ($newmessnum != "0")
	echo "<b>$newmessnum New</b> ";
else
	echo "View ";
echo "Message";
if ($newmessnum == "1")
	echo "";
else
	echo "s";
echo <<<_END
</a></li>
<li><a href='index.php?page=search' style='color:$colordark;'>Student Search</a></li>
<li><a href='index.php?page=invite' style='color:$colordark;'>Invite Classmates</a></li>
<li><a href='index.php?page=profile' style='color:$colordark;'>Edit Profile</a></li>
_END;
$stmt = $db_conn->query("SELECT COUNT(id) FROM users WHERE approved='0' AND usercreated='1'");
$numapprove = $stmt->fetchColumn();
$stmt->closeCursor();

$stmt = $db_conn->query("SELECT COUNT(id) FROM contact WHERE resolved='0'");
$numcontact = $stmt->fetchColumn();
$stmt->closeCursor();

$stmt = $db_conn->query("SELECT COUNT(id) FROM reports WHERE resolved='0'");
$numreport = $stmt->fetchColumn();
$stmt->closeCursor();

echo "<li><a href='index.php?page=alladmin' style='color:$colordark;'>";
if ($numapprove != "0" || $numcontact != "0" || $numreport != "0")
	echo "<b>Admin</b></a></li>";
else
	echo "Admin</a></li>";
echo <<<_END
<li><a href='index.php?page=jobs' style='color:$colordark;'>Job Opportunites</a></li>
<li><a href='index.php?page=links' style='color:$colordark;'>Links</a></li>
<li><a href='index.php?page=help' style='color:$colordark;'>Help Topics</a></li>
<li><a href='http://stfrancisalumni.org/index.php?logout=yep' style='color:$colordark;'>Log Out</a></li>
</ul>
_END;
?>