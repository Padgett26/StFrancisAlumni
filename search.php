<?php
echo "<div style='font-size:1.25em'>Search for Students:</div><form method='post' action='index.php?page=search'><input type='text' name='searchtext' size='40' maxlength='40' /><input type='submit' value=' -GO- ' style='border-radius:8px; -moz-border-radius:8px;' /></form>";

if (filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_STRING)) {
    $searchtext = filter_input(INPUT_POST, 'searchtext', FILTER_SANITIZE_STRING);
    $stmt = $db_conn->prepare("SELECT firstname,miname,lastname,maidenname,year FROM users WHERE MATCH (firstname,lastname,maidenname) AGAINST ('$searchtext*' IN BOOLEAN MODE)");
    $stmt->execute();
    echo "<div style='font-size:1.25em'>Your search for $searchtext yielded these results:</div/><br /><div style='font-size:1.5em; text-shadow: 3px 3px 2px green;'>Active Users:</div><br />";
    while ($rowsearch = $stmt->fetch()) {
        $firstsearch = $rowsearch[0];
        $misearch = $rowsearch[1];
        $lastsearch = $rowsearch[2];
        $maidensearch = $rowsearch[3];
        $yearsearch = $rowsearch[4];
        $namesearch = $firstsearch;
        if ($misearch)
            $namesearch .= " " . $misearch;
        $namesearch .= " " . $lastsearch;
        if ($maidensearch)
            $namesearch .= " " . "($maidensearch)";
        echo "<div style='font-size:1.5em'><a href='index.php?page=classmates&selectyear=$yearsearch'>$namesearch - $yearsearch</a></div><br />";
    }
    if ($userlvl != "0") {
        $substmt = $db_conn->prepare("SELECT caption, year FROM seniorpics WHERE MATCH (caption) AGAINST ('$searchtext*' IN BOOLEAN MODE)");
        $substmt->execute();
        echo "<br /><div style='font-size:1.5em; text-shadow: 3px 3px 2px green;'>Senior Pictures:</div><br />";
        while ($rowsub = $substmt->fetch()) {
            $captionsub = $rowsub[0];
            $yearsub = $rowsub[1];
            echo "<div style='font-size:1.5em'><a href='index.php?page=seniorpics&selectyear=$yearsub'>$captionsub - $yearsub</a></div><br />";
        }
    }
}

?>
