<?php

if (filter_input(INPUT_GET, 'selectuseryear', FILTER_SANITIZE_NUMBER_INT))
    $_SESSION['adminyear'] = filter_input(INPUT_GET, 'selectuseryear', FILTER_SANITIZE_NUMBER_INT);

if (filter_input(INPUT_GET, 'selectsenioryear', FILTER_SANITIZE_NUMBER_INT))
    $_SESSION['adminyear'] = filter_input(INPUT_GET, 'selectsenioryear', FILTER_SANITIZE_NUMBER_INT);

if (filter_input(INPUT_GET, 'selectybookyear', FILTER_SANITIZE_NUMBER_INT))
    $_SESSION['adminyear'] = filter_input(INPUT_GET, 'selectybookyear', FILTER_SANITIZE_NUMBER_INT);

if (isset($_SESSION['adminyear']))
    $adminyear = $_SESSION['adminyear'];

include "alladmin_dbupdate.php";

if ($userlvl == "3") {
    $stmt = $db_conn->query("SELECT COUNT(id) FROM users WHERE approved='1' AND usercreated='1'");
    $numcount = $stmt->fetchColumn();
    $stmt->closeCursor();

    $stmt = $db_conn->query("SELECT COUNT(id) FROM users WHERE approved='0' AND usercreated='1'");
    $numapprove = $stmt->fetchColumn();
    $stmt->closeCursor();

    $stmt = $db_conn->query("SELECT COUNT(id) FROM contact WHERE resolved='0'");
    $numcontact = $stmt->fetchColumn();
    $stmt->closeCursor();

    $stmt = $db_conn->query("SELECT COUNT(id) FROM reports WHERE resolved='0'");
    $numreport = $stmt->fetchColumn();
    $stmt->closeCursor();

    echo <<<_END
    $errorprof
    <div style='margin-left:20px;'>Welcome $username...<br /><br />
    Current approved user count... $numcount<br /><br />
    <a href="index.php?page=alladmin">Refresh this page</a><br /><br /></div>
    <a href="index.php?page=alladmin&choice=approve" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
_END;
    if ($numapprove >= 1)
        echo "<span style='font-weight:bold;'>$numapprove</span>";
    else
        echo "$numapprove";
    echo <<<_END
    <br />Users needing approved
    </div></a>
    <a href="index.php?page=alladmin&choice=contact" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
_END;
    if ($numcontact >= 1)
        echo "<span style='font-weight:bold;'>$numcontact</span>";
    else
        echo "$numcontact";
    echo <<<_END
    <br />Inquiries needing resolved
    </div></a>
    <a href="index.php?page=alladmin&choice=reports" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
_END;
    if ($numreport >= 1)
        echo "<span style='font-weight:bold;'>$numreport</span>";
    else
        echo "$numreport";
    echo <<<_END
    <br />Reported items to investigate
    </div></a>
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        &nbsp;<br />
_END;
    if (isset($_SESSION['adminyear']))
        echo "<a href='index.php?page=alladmin&choice=users&selectuseryear=$adminyear' style='color:$colordark'>Edit users</a>";
    else
        echo "Edit users";
    echo <<<_END
        <br />
        <form name="form1" id="form1">
        <select name="jumpmenu" id="jumpmenu" onChange="jumpto1(document.form1.jumpmenu.options[document.form1.jumpmenu.options.selectedIndex].value)">
        <option>Select year</option><option value=index.php?page=alladmin&choice=users&selectuseryear=9999
_END;
    echo ">Faculty</option>";
    $getyear = date('Y');
    $firstyear = "1900";
    for ($j = $getyear; $j > $firstyear; $j--) {
        echo "<option value=index.php?page=alladmin&choice=users&selectuseryear=$j>$j</option>";
    }
    echo <<<_END
</select></form>
    </div>
    <a href="index.php?page=alladmin&choice=homepage" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        &nbsp;<br />Edit home page text
    </div></a>
    <a href="index.php?page=alladmin&choice=history" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        &nbsp;<br />Edit history page text
    </div></a>
    <a href="index.php?page=alladmin&choice=gmess" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        Send a global message
    </div></a>
    <a href="index.php?page=alladmin&choice=gemail" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        &nbsp;<br />Send a global email
    </div></a>
    <a href="index.php?page=alladmin&choice=mods" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        &nbsp;<br />View/email moderators
    </div></a>
    <a href="index.php?page=alladmin&choice=csv" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        Download users in CSV format
    </div></a>
    <a href="index.php?page=alladmin&choice=help" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        &nbsp;<br />Help topics
    </div></a>
    <a href="index.php?page=alladmin&choice=bademail" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        &nbsp;<br />Bad Email address
    </div></a>
    <a href="index.php?page=alladmin&choice=weekend" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        Alumni weekend schedule
    </div></a>
    <a href="index.php?page=alladmin&choice=vision" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        &nbsp;<br />Vision questions
    </div></a>
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
_END;
    if (isset($_SESSION['adminyear']))
        echo "<a href='index.php?page=alladmin&choice=ybook&selectybookyear=$adminyear' style='color:$colordark'>Upload yearbook pages</a>";
    else
        echo "Upload yearbook pages";
    echo <<<_END
        <br />
        <form name="form2">
        <select name="jumpmenu" onChange="jumpto2(document.form2.jumpmenu.options[document.form2.jumpmenu.options.selectedIndex].value)">
        <option>Select year</option>
_END;
    $getyear = date('Y');
    $firstyear = "1958";
    for ($j = $getyear; $j > $firstyear; $j--) {
        echo "<option value=index.php?page=alladmin&choice=ybook&selectybookyear=$j>$j</option>";
    }
    echo <<<_END
</select></form>
    </div>
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
_END;
    if (isset($_SESSION['adminyear']))
        echo "<a href='index.php?page=alladmin&choice=senior&selectsenioryear=$adminyear' style='color:$colordark'>Upload senior pictures</a>";
    else
        echo "Upload senior pictures";
    echo <<<_END
        <br />
        <form name="form3">
        <select name="jumpmenu" onChange="jumpto3(document.form3.jumpmenu.options[document.form3.jumpmenu.options.selectedIndex].value)">
        <option>Select year</option>
_END;
    $getyear = date('Y');
    $firstyear = "1900";
    for ($j = $getyear; $j > $firstyear; $j--) {
        echo "<option value=index.php?page=alladmin&choice=senior&selectsenioryear=$j>$j</option>";
    }
    echo "</select></form></div>";
    echo <<<_END
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark; width:175px;">
        &nbsp;<br />Site settings<br />
        <form name="form4">
        <select name="jumpmenu" onChange="jumpto4(document.form4.jumpmenu.options[document.form4.jumpmenu.options.selectedIndex].value)">
        <option>Select...</option>
        <option value=index.php?page=alladmin&choice=site&site=colors>School Colors</option>
        <option value=index.php?page=alladmin&choice=site&site=logo>Corner Logo</option>
        <option value=index.php?page=alladmin&choice=site&site=headtext>Site Title</option>
        <option value=index.php?page=alladmin&choice=site&site=homepics>Home Page Pics</option>
        <option value=index.php?page=alladmin&choice=site&site=histpics>History Page Pics</option>
        <option value=index.php?page=alladmin&choice=site&site=homeheadpics>Home Page Header Pics</option>
        <option value=index.php?page=alladmin&choice=site&site=headpics>Page Header Pics</option>
        <option value=index.php?page=alladmin&choice=site&site=profile>Default Profile Pic</option>
        </select></form>
    </div>
            <a href="index.php?page=alladmin&choice=pdf" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        &nbsp;<br />PDF Manager
    </div></a>
_END;
    if ($userid == "1") {
        echo <<<_END
    <a href="index.php?page=alladmin&choice=cleanup" style="text-decoration:none;">
    <div class="adminbox" style="border:5px solid $colordark; background-color:$colorlight; color:$colordark;">
        Cleanup the usersettings table.
    </div></a>
_END;
    }
    echo "<div class='adminbox' style='border:5px solid $colordark; background-color:$colorlight; color:$colordark;'>Year being edited:<br />$adminyear</div>";
} else
    echo "You do not have access to this page, please select another.";

if (filter_input(INPUT_GET, 'choice', FILTER_SANITIZE_STRING)) {
    $choice = filter_input(INPUT_GET, 'choice', FILTER_SANITIZE_STRING);
    echo "<div style='margin:400px 0px 0px 20px;'>";
    include "alladmin_$choice.php";
    echo "</div>";
}
?>
