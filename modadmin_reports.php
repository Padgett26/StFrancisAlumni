<?php



// number of rows to show per page
$rowsperpage = 1;
// find out total pages
$totalpages = ceil($numreport / $rowsperpage);

// get the current page or set a default
if (filter_input(INPUT_GET, 'currentpage', FILTER_SANITIZE_NUMBER_INT)) {
   $currentpage = filter_input(INPUT_GET, 'currentpage', FILTER_SANITIZE_NUMBER_INT);
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if
// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if
$submitpage = $currentpage + 1;
// the offset of the list, based on current page
$offset = ($currentpage - 1) * $rowsperpage;
$sql = "SELECT t1.*, t2.firstname, t2.miname, t2.lastname, t2.email FROM reports AS t1 INNER JOIN users AS t2 ON t1.userid = t2.id WHERE t1.resolved='0' AND t1.year='$useryear' LIMIT $offset, $rowsperpage";
$stmt = $db_conn->query($sql);

// while there are rows to be fetched...
while ($list = $stmt->fetch()) {
   // echo data
    $messid = $list['messid'];
    $bbid = $list['bbid'];
    $picid = $list['picid'];
    if ($messid != "0"){
        $table = "pictures";
        $id = $picid;
    }
    elseif ($bbid != "0"){
        $table = "bboard";
        $id = $bbid;
    }
    elseif ($picid != "0"){
        $table = "pictures";
        $id = $picid;
        $substmt = $db_conn->prepare("SELECT created FROM pictures WHERE id=?");
        $substmt->execute(array($picid));
        $rowpic = $substmt->fetch();
        $createdpic = $rowpic['created'];
    }
   echo "Inquiries needing resolved:<br />";
    echo "<table cellpadding='20px' cellspacing='0' border='1'><tr><td style='text-align:center;'>" . $list['firstname'];
    if ($list['miname'])
        echo " " . $list['miname'];
    echo " " . $list['lastname'] . "<br /><a href='mailto:" . $list['email'] . "'>" . $list['email'] . "</td></tr><tr><td style='text-align:justify;'><img src='pics/" . $list['userid'] . "/$createdpic.jpg' alt='' /><blockquote>" . $list['text'] . "</blockquote></td></tr><tr><td><form method='post' action='index.php?page=modadmin&choice=reports&currentpage=$submitpage'>Delete this picture / message / board post? <input type='checkbox' name='delete' value='$id' /><br /><br />Mark this report as resolved? <input type='checkbox' name='resolved' value='1' /><br /><input type='hidden' name='reportid' value='" . $list['id'] . "' /><input type='hidden' name='table' value='$table' /><input type='submit' value=' -Save Decision- ' style='border-radius:8px; -moz-border-radius:8px;' /></form></td></tr></table><br /><br />";
} // end while

/******  build the pagination links ******/
// range of num links to show
$range = 3;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='index.php?page=modadmin&choice=reports&currentpage=1'><<</a> ";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo " <a href='index.php?page=modadmin&choice=reports&currentpage=$prevpage'><</a> ";
} // end if

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='index.php?page=modadmin&choice=reports&currentpage=$x'>$x</a> ";
      } // end else
   } // end if
} // end for

// if not on last page, show forward and last page links
if ($currentpage != $totalpages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page
   echo " <a href='index.php?page=modadmin&choice=reports&currentpage=$nextpage'>></a> ";
   // echo forward link for lastpage
   echo " <a href='index.php?page=modadmin&choice=reports&currentpage=$totalpages'>>></a> ";
} // end if
echo "<br /><br />";
?>
