<?php
$page = "home";
if (filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING)) {
    $ipage = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
    $p = preg_replace('/[\s\W]+/', '', $ipage);
    $page = (file_exists($p . ".php")) ? $p : 'home';
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title><?php

        echo $headtext?></title>
        <link rel="shortcut icon" href="img/j.gif" />
        <meta http-equiv='Content-Type'     content='text/html; charset=UTF-8' />
        <meta name="keywords" content="saint francis ks, saint francis alumni, cheyenne co ks, northwest ks, usd297, alumni">
        <meta name="description" content="Connecting the alumni from St Francis, KS. ">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <script src="scripts/jquery-1.7.2.min.js"></script>
        <?php
        if ($page == "alladmin" || $page == "profile" || $page == "modadmin") {
            echo "<script type='text/javascript' src='scripts/usableforms.js'></script>";
            echo "<script type='text/javascript' src='scripts/sitejava.js'></script>";
        }
        echo "<link rel='stylesheet' type='text/css' href='css/cssindex.css' />";
        if ($page == "home") {
            echo <<<_END
                    <script src="scripts/jquery.cross-slide.min.js"></script>
                    <script>
                        $(function() {
                            $('#slideshow').crossSlide({
                                speed: 45,
                                fade: 1
                            }, [
                                { src: 'img/s1.jpg', dir: 'up'   },
                                { src: 'img/s2.jpg', dir: 'down' },
                                { src: 'img/s3.jpg', dir: 'up'   },
                                { src: 'img/s4.jpg', dir: 'down' },
                                { src: 'img/s5.jpg', dir: 'up'   },
                                { src: 'img/s6.jpg', dir: 'down' }
                            ])
                        });
                    </script>
            _END;
            $headpic = "<div id='slideshow'></div>";
            echo <<<_END
            <script type="text/javascript">
            function showFeed(str)
            {
            if (str=="")
              {
              document.getElementById("txtHint").innerHTML="";
              return;
              }
            if (window.XMLHttpRequest)
              {// code for IE7+, Firefox, Chrome, Opera, Safari
              xmlhttp=new XMLHttpRequest();
              }
            else
              {// code for IE6, IE5
              xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
              }
            xmlhttp.onreadystatechange=function()
              {
              if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
                }
              }
            xmlhttp.open("GET","home_ajax.php?q="+str,true);
            xmlhttp.send();
            }
            </script>
            _END;
        } elseif ($page == "alladmin" || $page == "modadmin") {
            $headpic = "<div id='slideshow'><img src='img/admin.jpg' alt='' /></div>";
        } elseif ($page == "bboard") {
            $headpic = "<div id='slideshow'><img src='img/bboard.jpg' alt='' /></div>";
        } elseif ($page == "history") {
            $headpic = "<div id='slideshow'><img src='img/history.jpg' alt='' /></div>";
        } elseif ($page == "links") {
            $headpic = "<div id='slideshow'><img src='img/links.jpg' alt='' /></div>";
        } elseif ($page == "contact") {
            $headpic = "<div id='slideshow'><img src='img/contact.jpg' alt='' /></div>";
        } elseif ($page == "messages") {
            $headpic = "<div id='slideshow'><img src='img/messages.jpg' alt='' /></div>";
        } elseif ($page == "profile") {
            $headpic = "<div id='slideshow'><img src='img/profile.jpg' alt='' /></div>";
        } elseif ($page == "report") {
            $headpic = "<div id='slideshow'><img src='img/report.jpg' alt='' /></div>";
        } elseif ($page == "pictures") {
            $headpic = "<div id='slideshow'><img src='img/pictures.jpg' alt='' /></div>";
        } elseif ($page == "classmates") {
            $headpic = "<div id='slideshow'><img src='img/classmates.jpg' alt='' /></div>";
        } else {
            $headpic = "<div id='slideshow'><img src='img/default.jpg' alt='' /></div>";
        }

        if ($page == "yearbook") {
            if (filter_input(INPUT_GET, 'ybyear', FILTER_SANITIZE_NUMBER_INT))
                $ybyear = filter_input(INPUT_GET, 'ybyear',
                        FILTER_SANITIZE_NUMBER_INT);
            else
                $ybyear = $useryear;
            if (! is_dir("pics/yearbooks/$ybyear")) {
                mkdir("pics/yearbooks/$ybyear", 0777, true);
                $old1 = 'img/noyear1.jpg';
                $old2 = 'img/noyear2.jpg';
                $new1 = "pics/yearbooks/$ybyear/1.jpg";
                $new2 = "pics/yearbooks/$ybyear/2.jpg";
                copy($old1, $new1);
                copy($old2, $new2);
            }
            $slide = array();
            foreach (new DirectoryIterator("pics/yearbooks/" . $ybyear) as $j) {
                if (! $j->isDot()) {
                    $slide[] = "$j";
                }
            }
            sort($slide, SORT_NUMERIC);
            $list = implode("','" . $ybyear . "/", $slide);
            echo <<<_END
                <script type="text/javascript" src="scripts/thumbslide.js">
                </script>
                <link rel="stylesheet" type="text/css" href="css/thumbslide.css" />
                <script>
                //Initialization code:
                $(document).ready(function(){ // on document load
                                $("#thumbsliderdiv").imageSlider({ //initialize slider
                                        'thumbs': ['$ybyear/$list'], // file names of images within slider. Default path should be changed inside thumbslide.js (near bottom)
                                        'auto_scroll':true,
                                        'auto_scroll_speed':20000,
                                        'stop_after': 1, //stop after x cycles? Set to 0 to disable.
                                        'canvas_width':640,
                                        'canvas_height':900 // <-- No comma after last option
                                        })
                        });
                </script>
            _END;
        }
        ?>
        <script type="text/javascript">
            function toggleview(itm)
            {
                var itmx = document.getElementById(itm);
                if (itmx.style.display === "none")
                {
                    itmx.style.display = "block";
                }
                else
                {
                    itmx.style.display = "none";
                }
            }
        </script>

        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-25650882-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' === document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>
        <?php
        if ($page == "schedule") {
            ?>
        <script>
            $(document).ready(function() {
                $('.schedtext').hide();
                $('.schedtitle').toggle(
                    function() {
                        $(this).next('.schedtext').slideDown();
                    },
                    function() {
                        $(this).next('.schedtext').slideUp();
                    }
                );
            });
        </script>
        <?php
        }
        ?>
    </head>
