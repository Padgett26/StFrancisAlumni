<?php


// number of rows to show per page
$rowsperpage = 1;
// find out total pages
$totalpages = ceil($numcontact / $rowsperpage);

// get the current page or set a default
if (filter_input(INPUT_POST, 'currentpage', FILTER_SANITIZE_NUMBER_INT)) {
   $currentpage = filter_input(INPUT_POST, 'currentpage', FILTER_SANITIZE_NUMBER_INT);
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

// get the info from the db
$stmt = $db_conn->prepare("SELECT * FROM contact WHERE resolved='0' LIMIT $offset, $rowsperpage");
$stmt->execute();

// while there are rows to be fetched...
while ($list = $stmt->fetch()) {
   // echo data
    $datecontact2 = date("F j, Y, g:i a", $list['created']);
    if ($list['year'] == "9999")
        $showyear = "Faculty";
    else
        $showyear = $list['year'];
   echo "Inquiries needing resolved:<br />";
    echo "<table cellpadding='20px' cellspacing='0' border='1'>
        <tr><td style='text-align:center;'>" . $list['name'] . "<br />" . $list['email'] . "<br />" . $showyear . "<br />$datecontact2</td></tr>
        <tr><td style='text-align:justify;'><blockquote>" . $list['message'] . "</blockquote></td></tr>
        <tr><td>Your reply to " . $list['name'] . "<br />
            <form method='post' action='index.php?page=alladmin&choice=contact&currentpage=$submitpage'>
            <textarea name='contacttext' cols='80' rows='10'></textarea><br />
            Mark this inquiry as resolved? <input type='checkbox' name='resolved' value='1' /><br />
            <input type='hidden' name='contactid' value='" . $list['id'] . "' />
            <input type='hidden' name='contactemail' value='" . $list['email'] . "' />
            <input type='hidden' name='contactorigtext' value='" . $list['message'] . "' />
            <input type='submit' value=' -Send Response- ' /></form></td></tr></table><br /><br />";
} // end while

/******  build the pagination links ******/
// range of num links to show
$range = 3;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='index.php?page=alladmin&choice=contact&currentpage=1'><<</a> ";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo " <a href='index.php?page=alladmin&choice=contact&currentpage=$prevpage'><</a> ";
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
         echo " <a href='index.php?page=alladmin&choice=contact&currentpage=$x'>$x</a> ";
      } // end else
   } // end if
} // end for

// if not on last page, show forward and last page links
if ($currentpage != $totalpages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page
   echo " <a href='index.php?page=alladmin&choice=contact&currentpage=$nextpage'>></a> ";
   // echo forward link for lastpage
   echo " <a href='index.php?page=alladmin&choice=contact&currentpage=$totalpages'>>></a> ";
} // end if
echo "<br /><br />";
?>
