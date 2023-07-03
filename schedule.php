<?php
$picname = filter_input(INPUT_POST, 'wename', FILTER_SANITIZE_STRING);
$weyear = filter_input(INPUT_POST, 'weyear', FILTER_SANITIZE_NUMBER_INT);
if (isset($_FILES['weimage']['tmp_name']) && $_FILES['weimage']['size'] >= 1000) {
	$image = $_FILES['weimage']["tmp_name"];
	$folder = "pics/weekend/$weyear";
	if (! is_dir("$folder")) {
		mkdir("$folder", 0777, true);
	}
	list ($width, $height) = (getimagesize($image) != null) ? getimagesize($image) : null;
	if ($width != null && $height != null) {
		$imageType = getPicType($_FILES['weimage']['type']);
		$imageName = $picname . "." . $imageType;
		processPic("$folder", $imageName, $image, 800, 150);
	}
}

if (filter_input(INPUT_GET, 'wedelpic', FILTER_SANITIZE_STRING)) {
	if ($userlvl == "3") {
		$wedelpic = filter_input(INPUT_GET, 'wedelpic', FILTER_SANITIZE_STRING);
		echo "<div style='text-align:center;'>Are you sure you want to delete this picture?&nbsp;&nbsp;<form action='index.php?page=schedule' method='post'><input type='hidden' name='confwedelpic' value='$wedelpic' /><input type='submit' value='YES' /></form>&nbsp;&nbsp;<form action='index.php?page=schedule' method='post'><input type='submit' value='NO' /></form></div>";
	}
}

echo "<div style='text-align:center; font-size:1.5em; font-weight:bold;'>Select a year to view the Alumni weekend schedule and pictures:</div>";

$j = 1;
echo "<div style='text-align:center; font-size:1.25em; margin-top:10px;'>";
$cyear = date("Y");
for ($t = $cyear; $t >= 2012; $t --) {
	if ($j != "1")
		echo " - ";
	echo "<a href='index.php?page=schedule&weekendyear=" . $t . "' style='text-decoration:none;'>" . $t . "</a>";
	$j = $j + 1;
}
echo "</div>";

if (filter_input(INPUT_GET, 'weekendyear', FILTER_SANITIZE_NUMBER_INT)) {
	$_SESSION['weekendyear'] = filter_input(INPUT_GET, 'weekendyear', FILTER_SANITIZE_NUMBER_INT);
} else {
	if (! isset($_SESSION['weekendyear'])) {
		$stmt = $db_conn->prepare("SELECT DISTINCT YEAR(starttime) FROM weekend ORDER BY starttime DESC LIMIT 1");
		$stmt->execute();
		$row = $stmt->fetch();
		$_SESSION['weekendyear'] = $row[0];
	}
}
$weekendyear = $_SESSION['weekendyear'];

if (filter_input(INPUT_POST, 'confwedelpic', FILTER_SANITIZE_STRING)) {
	$wepic = filter_input(INPUT_POST, 'confwedelpic', FILTER_SANITIZE_STRING);
	unlink("pics/weekend/$weekendyear/$wepic");
}

$months = array(
		1 => 'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
);

echo "<div style='text-align:center; font-size:3em; margin-top:20px;'>Alumni Weekend $weekendyear</div>";

if ($userlvl != "0")
	echo "<div style='text-align:center; font-size:1.25em; margin-top:20px;' class='showtitle'>Do you have pictures from the Alumni Weekend to upload? CLICK HERE</div>";

$wename = time();
$wename .= "_" . $userid;
echo "<div style='margin-top:10px; text-align:center;' class='showtext'>Upload your Alumni Weekend picture here:<br /><form method='post' action='index.php?page=schedule' enctype='multipart/form-data'><input type='file' name='weimage' /><input type='hidden' name='wename' value='$wename' /><br />";
echo "In which year's Alumni weekend was this picture taken? <select name='weyear' size='1'>";
for ($t = $cyear; $t >= 2012; $t --) {
	echo "<option value='$t'>$t</option>\n";
}
echo "</select><input type='submit' value=' Upload ' /></form></div>";
if (! file_exists("pics/weekend/$cyear"))
	mkdir("pics/weekend/$cyear", 0755);
$tic = 1;
echo "<table>";
foreach (new DirectoryIterator("pics/weekend/" . $weekendyear) as $j) {
	if (! $j->isDot()) {
		echo ($tic % 2 == 1) ? "<tr><td>" : "<td>";
		echo "<a href='pics/weekend/$weekendyear/$j' target='_blank'><img src='pics/weekend/$weekendyear/$j' alt='' style='border:0px; margin:15px; max-width:375px' /></a><br />\n";
		if ($userlvl == "3")
			echo "<div style='text-align:center; margin-bottom:15px;'><a href='index.php?page=schedule&wedelpic=$j'>Delete this picture</a></div>";
		echo ($tic % 2 == 1) ? "</td>" : "</td></tr>";
		$tic = $tic + 1;
	}
}
echo "</table>";

echo "<div style='text-align:center; font-size:2em; margin-top:20px;'>Schedule of Events</div>";
echo "<div style='text-align:center; font-size:1em;'>If you need to Add / Edit / Delete Your event on this page,<br /><a href='index.php?page=contact'>Please use the Contact form.</a><br /><br /></div>";

echo "<div style='margin-top:0px;'>";
echo "<table cellpadding='10px' cellspacing='0px' border='0px'>";
$stmt = $db_conn->prepare("SELECT DISTINCT MONTH(starttime) FROM weekend WHERE YEAR(starttime)=? ORDER BY starttime");
$stmt->execute(array(
		$weekendyear
));
while ($row = $stmt->fetch()) {
	$weekendmonth = $row[0];
	$substmt = $db_conn->prepare("SELECT DISTINCT DAY(starttime) FROM weekend WHERE YEAR(starttime)=? AND MONTH(starttime)=? ORDER BY starttime");
	$substmt->execute(array(
			$weekendyear,
			$weekendmonth
	));
	while ($subrow = $substmt->fetch()) {
		$weekendday = $subrow[0];
		echo "<tr><td>&nbsp;</td><td colspan='2'><div style='font-size:1.5em; font-weight:bold; text-align:center; margin-top:20px;'>" . $months[$weekendmonth] . " " . $weekendday . ", " . $weekendyear . "</div></td><td>&nbsp;</td></tr>";
		$subsubstmt = $db_conn->prepare("SELECT * FROM weekend WHERE YEAR(starttime)=? AND MONTH(starttime)=? AND DAY(starttime)=? ORDER BY starttime");
		$subsubstmt->execute(array(
				$weekendyear,
				$weekendmonth,
				$weekendday
		));
		while ($subsubrow = $subsubstmt->fetch()) {
			$title = $subsubrow['title'];
			$description = nl2br($subsubrow['description']);
			$starttime = date_create($subsubrow['starttime']);
			$start = date_format($starttime, 'g:ia');
			$endtime = date_create($subsubrow['endtime']);
			$end = date_format($endtime, 'g:ia');
			$link = $subsubrow['link'];
			$linktext = $subsubrow['linktext'];
			echo "<tr>
                <td>&nbsp;</td>
                <td style='border:1px solid black; text-align:right; width:125px;'><span style='font-size:1em'>$start - $end</span></td>
                <td style='border:1px solid black; text-align:left; width:600px;'><div style='font-size:1.25em; cursor:pointer;";
			if ($description || $link)
				echo " text-decoration:underline;' class='schedtitle'";
			else
				echo "'";
			echo ">$title</div><div";
			if ($description || $link)
				echo " class='schedtext'";
			echo "><br />";
			if ($description)
				echo "$description<br /><br />";
			if ($link)
				echo "<a href='$link' target='_blank'>$linktext</a>";
			echo "</div></td>";
			echo "<td>&nbsp;</td></tr>";
		}
	}
}
echo "</table></div><br /><br />";
?>