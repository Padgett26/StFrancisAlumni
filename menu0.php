<?php
echo "<ul><li><a href='index.php?page=home' style='color:$colordark;'>Home</a></li>";
echo "<li><a href='index.php?page=history' style='color:$colordark;'>History</a></li>";
echo "<li><a href='index.php?page=schedule' style='color:$colordark;'>Alumni Weekend</a></li>";
echo "<li><span  style='color:$colordark; font-size:.9em;'>Select year:<br /><form method='post' action='index.php?page=classmates'><select name='selectyear' size='1'><option value='9999'";

$got = array ();
$stmt = $db_conn->prepare ( "SELECT year FROM users" );
$stmt->execute ();
while ( $rowyearcount = $stmt->fetch () ) {
	$got [] = $rowyearcount [0];
}
$yearcount = array_count_values ( $got );
if ($selectyear == "9999") {
	echo " selected='selected'";
}
echo ">Faculty";
echo " -$yearcount[9999]";
echo "</option>";

$getyear = date ( 'Y' );
$firstyear = "1900";
for($j = $getyear; $j > $firstyear; -- $j) {
	echo "<option value='$j'";
	if ($j == $selectyear) {
		echo " selected='selected'";
	}
	echo ">$j";
	echo (array_key_exists ( $j, $yearcount )) ? " -$yearcount[$j]" : "";
	echo "</option>";
}

echo "</select><input type='submit' value='Go' style='border-radius:8px; -moz-border-radius:8px;' /></form></span></li>";
if ($selectyear != "") {
	echo "<li><a href='index.php?page=pictures' style='color:$colordark;'>Pictures</a></li><li><a href='index.php?page=classmates' style='color:$colordark;'>";
	if ($textyear == "Faculty") {
		echo "$textyear-mates";
	} else {
		echo " Classmates";
	}
	echo "</a></li>";
}
echo <<<_END
<li><a href='index.php?page=userapp' style='color:$colordark;'>Membership</a></li>
<li><a href='index.php?page=contact' style='color:$colordark;'>Contact an Admin</a></li>
<li><a href='index.php?page=jobs' style='color:$colordark;'>Job Opportunites</a></li>
<li><a href='index.php?page=links' style='color:$colordark;'>Links</a></li>
<li><a href='index.php?page=help' style='color:$colordark;'>Help Topics</a></li>
<li><span  style='color:$colordark;'>Login:<br />User name:<br /><form method='post' action='https://stfrancisalumni.org/index.php'><input type='text' name='userid' size='15' maxlength='30' /><br />Password:<br /><input type='password' name='pass' size='15' maxlength='30' /><br /><input type='hidden' name='login' value='1' /><input type='submit' value=' -Login- ' /></form></span></li>
</ul>
_END;
?>