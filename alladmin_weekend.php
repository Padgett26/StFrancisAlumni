<?php
if ($userlvl == "3") {
    if (filter_input(INPUT_POST, 'confdelitem', FILTER_SANITIZE_NUMBER_INT)) {
        $id = filter_input(INPUT_POST, 'confdelitem', FILTER_SANITIZE_NUMBER_INT);
        $stmt = $db_conn->prepare("DELETE FROM weekend WHERE id=?");
        $stmt->execute(array($id));
        echo "Item deleted...<br /><br />\n";
    }
}

if (filter_input(INPUT_POST, 'weekenditem', FILTER_SANITIZE_STRING)) {
    $id = filter_input(INPUT_POST, 'weekenditem', FILTER_SANITIZE_STRING);
    $starthour = filter_input(INPUT_POST, 'starthour', FILTER_SANITIZE_NUMBER_INT);
    $startmin = filter_input(INPUT_POST, 'startmin', FILTER_SANITIZE_NUMBER_INT);
    $startday = filter_input(INPUT_POST, 'startday', FILTER_SANITIZE_NUMBER_INT);
    $startmonth = filter_input(INPUT_POST, 'startmonth', FILTER_SANITIZE_NUMBER_INT);
    $endhour = filter_input(INPUT_POST, 'endhour', FILTER_SANITIZE_NUMBER_INT);
    $endmin = filter_input(INPUT_POST, 'endmin', FILTER_SANITIZE_NUMBER_INT);
    $endday = filter_input(INPUT_POST, 'endday', FILTER_SANITIZE_NUMBER_INT);
    $endmonth = filter_input(INPUT_POST, 'endmonth', FILTER_SANITIZE_NUMBER_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $l = filter_input(INPUT_POST, 'link', FILTER_SANITIZE_URL);
    $link = ($l == "http://") ? "" : $l;
    $linktext = filter_input(INPUT_POST, 'linktext', FILTER_SANITIZE_STRING);
    $weekendyear = filter_input(INPUT_POST, 'weekendyear', FILTER_SANITIZE_NUMBER_INT);
    $delitem = filter_input(INPUT_POST, 'delitem', FILTER_SANITIZE_NUMBER_INT);
    $starttime = $weekendyear.",".$startmonth.",".$startday.",".$starthour.",".$startmin.",0";
    $endtime = $weekendyear.",".$endmonth.",".$endday.",".$endhour.",".$endmin.",0";
    if ($delitem == "1") {
        echo "Are you sure you want to delete this item?&nbsp;&nbsp;<form action='index.php?page=alladmin&choice=weekend' method='post'><input type='hidden' name='confdelitem' value='$id' /><input type='submit' value='YES' /></form>&nbsp;&nbsp;<form action='index.php?page=alladmin&choice=weekend' method='post'><input type='submit' value='NO' /></form>";
    } else {
        if ($id == "new") {
            $stmt = $db_conn->prepare("INSERT INTO weekend VALUES"."(NULL,?,?,?,?,?,?,'0','0','0')");
            $stmt->execute(array($title,$description,$starttime,$endtime,$link,$linktext));
            echo "Item uploaded...<br /><br />\n";
        } else {
            if ($title) {
                $stmt = $db_conn->prepare("UPDATE weekend SET title=? WHERE id=?");
                $stmt->execute(array($title,$id));
            }
            if ($description) {
                $stmt = $db_conn->prepare("UPDATE weekend SET description=? WHERE id=?");
                $stmt->execute(array($description,$id));
            }
            if ($starttime) {
                $stmt = $db_conn->prepare("UPDATE weekend SET starttime=? WHERE id=?");
                $stmt->execute(array($starttime,$id));
            }
            if ($endtime) {
                $stmt = $db_conn->prepare("UPDATE weekend SET endtime=? WHERE id=?");
                $stmt->execute(array($endtime,$id));
            }
            if ($link) {
                $stmt = $db_conn->prepare("UPDATE weekend SET link=? WHERE id=?");
                $stmt->execute(array($link,$id));
            }
            if ($linktext) {
                $stmt = $db_conn->prepare("UPDATE weekend SET linktext=? WHERE id=?");
                $stmt->execute(array($linktext,$id));
            }
            echo "Item updated...<br /><br />\n";
        }
    }
}

echo "<br /><br />Select a year to edit:<br /><br />";
$stmt = $db_conn->prepare("SELECT DISTINCT YEAR(starttime) FROM weekend");
$stmt->execute();
$t = 1;
while ($row = $stmt->fetch()) {
    $weekendyear = $row[0];
    if ($t != 1)
        echo "&nbsp;&nbsp;-&nbsp;&nbsp;";
    echo "<a href='index.php?page=alladmin&choice=weekend&weekendyear=$weekendyear' style='text-decoration:none; font-size:1.25em;'>$weekendyear</a>";
    $t = $t+1;
}
echo "<br /><br />";

$months = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
echo "<table cellpadding='5px' cellspacing='1px' border='1px'>";
echo "<tr><td><form action='index.php?page=alladmin&choice=weekend' method='post'>";
echo "Start:<br /><select name='starthour' size='1'>\n";
for ($j = 0; $j < 24; $j++) {
    echo "<option value='$j'>$j</option>\n";
}
echo "</select> <select name='startmin' size='1'>";
for ($k = 00; $k <= 45; $k = $k + 15) {
    echo "<option value='$k'>$k</option>\n";
}
echo "</select> <select name='startday' size='1'>\n";
for ($s = 1; $s <= 31; $s++) {
    echo "<option value='$s'>$s</option>\n";
}
echo "</select> <select name='startmonth'>";
foreach ($months as $key => $val) {
    echo "<option value='$key'>$val</option>\n";
}
echo "</select> <select name='weekendyear'>";
$pastyear = date('Y');
$futyear = $pastyear+2;
for ($o = $pastyear; $o <= $futyear; $o++) {
    echo "<option value='$o'>$o</option>\n";
}
echo "</select><br /><br />\n";
echo "End:<br /><select name='endhour' size='1'>\n";
for ($l = 0; $l < 24; $l++) {
    echo "<option value='$l'>$l</option>\n";
}
echo "</select> <select name='endmin' size='1'>";
for ($m = 00; $m <= 45; $m = $m + 15) {
    echo "<option value='$m'>$m</option>\n";
}
echo "</select> <select name='endday' size='1'>\n";
for ($n = 1; $n <= 31; $n++) {
    echo "<option value='$n'>$n</option>\n";
}
echo "</select> <select name='endmonth'>";
foreach ($months as $key => $val) {
    echo "<option value='$key'>$val</option>\n";
}
echo "</select><br />\n";
echo "</td><td>";
echo "Title:<br /><input type='text' name='title' size='70' maxlength='250' /><br />\n";
echo "Description:<br /><textarea name='description' rows='10' cols='55'></textarea><br />\n";
echo "Website Link:<br /><input type='text' name='link' size='70' maxlength='250' value='http://' /><br />\n";
echo "Link text:<br /><input type='text' name='linktext' size='70' maxlength='250' /><br />\n";
echo "<input type='hidden' name='weekenditem' value='new' /><input type='submit' value=' Upload ' /></form>";
echo "</td></tr>";
if (filter_input(INPUT_GET, 'weekendyear', FILTER_SANITIZE_NUMBER_INT)) {
    $weekend = filter_input(INPUT_GET, 'weekendyear', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $db_conn->prepare("SELECT * FROM weekend WHERE YEAR(starttime)=? ORDER BY starttime");
    $stmt->execute(array($weekend));
    while ($row = $stmt->fetch()) {
        $id  = $row['id'];
        $title  = $row['title'];
        $description  = $row['description'];
        $starttime  = date_create($row['starttime']);
        $starthour = date_format($starttime, 'G');
        $startmin = date_format($starttime, 'i');
        $startday = date_format($starttime, 'j');
        $startmon = date_format($starttime, 'n');
        $startyear = date_format($starttime, 'Y');
        $endtime  = date_create($row['endtime']);
        $endhour = date_format($endtime, 'G');
        $endmin = date_format($endtime, 'i');
        $endday = date_format($endtime, 'j');
        $endmon = date_format($endtime, 'n');
        $link  = $row['link'];
        $linktext  = $row['linktext'];
        echo "<tr><td><form action='index.php?page=alladmin&choice=weekend' method='post'>";
        echo "Start:<br /><select name='starthour' size='1'>\n";
        for ($j = 0; $j < 24; $j++) {
            echo "<option value='$j'";
            if ($j == $starthour)
                echo " selected='selected'";
            echo ">$j</option>\n";
        }
        echo "</select> <select name='startmin' size='1'>";
        for ($k = 00; $k <= 45; $k = $k + 15) {
            echo "<option value='$k'";
            if ($k == $startmin)
                echo " selected='selected'";
            echo ">$k</option>\n";
        }
        echo "</select> <select name='startday' size='1'>\n";
        for ($s = 1; $s <= 31; $s++) {
            echo "<option value='$s'";
            if ($s == $startday)
                echo " selected='selected'";
            echo ">$s</option>\n";
        }
        echo "</select> <select name='startmonth'>";
        foreach ($months as $key => $val) {
            echo "<option value='$key'";
            if ($key == $startmon)
                echo " selected='selected'";
            echo ">$val</option>\n";
        }
        echo "</select> <select name='weekendyear'>";
        $pastyear = date('Y');
        $futyear = $pastyear+2;
        for ($o = $pastyear; $o <= $futyear; $o++) {
            echo "<option value='$o'";
            if ($o == $startyear)
                echo " selected='selected'";
            echo ">$o</option>\n";
        }
        echo "</select><br /><br />\n";
        echo "End:<br /><select name='endhour' size='1'>\n";
        for ($j = 0; $j < 24; $j++) {
            echo "<option value='$j'";
            if ($j == $endhour)
                echo " selected='selected'";
            echo ">$j</option>\n";
        }
        echo "</select> <select name='endmin' size='1'>";
        for ($k = 00; $k <= 45; $k = $k + 15) {
            echo "<option value='$k'";
            if ($k == $endmin)
                echo " selected='selected'";
            echo ">$k</option>\n";
        }
        echo "</select> <select name='endday' size='1'>\n";
        for ($s = 1; $s <= 31; $s++) {
            echo "<option value='$s'";
            if ($s == $endday)
                echo " selected='selected'";
            echo ">$s</option>\n";
        }
        echo "</select> <select name='endmonth'>";
        foreach ($months as $key => $val) {
            echo "<option value='$key'";
            if ($key == $endmon)
                echo " selected='selected'";
            echo ">$val</option>\n";
        }
        echo "</select><br />\n";
        echo "</td><td>";
        echo "Title:<br /><input type='text' name='title' size='70' maxlength='250' value='$title' /><br />\n";
        echo "Description:<br /><textarea name='description' rows='10' cols='55'>$description</textarea><br />\n";
        echo "Website Link:<br /><input type='text' name='link' size='70' maxlength='250' value='$link' /><br />\n";
        echo "Link text:<br /><input type='text' name='linktext' size='70' maxlength='250' value='$linktext' /><br />\n";
        echo "Delete this item: <input type='checkbox' name='delitem' value='1' />";
        echo "<input type='hidden' name='weekenditem' value='$id' /><input type='submit' value=' Upload ' /></form>";
        echo "</td></tr>";
    }
}
echo "</table><br /><br /><br />";
?>
