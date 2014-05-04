<?php
header('Content-Type: text/html; charset=UTF-8');

include("inc/dbconnect.php");
include("inc/footer.inc.php");
include("swf/open-flash-chart.php");

mysql_query("SET CHARACTER SET 'utf8'");

$sql = 'SELECT STEAMID, NAME, POINTS FROM `Player`';
$sql .= " WHERE `STEAMID` <> 'BOT'";
$sql .= ' ORDER BY `POINTS` DESC LIMIT 0, 5 ';

$ergebnis = mysql_query($sql);

$players = array();
$points = array();
$highestpoints = 0;
$counter = 0;
while ($adr = mysql_fetch_array($ergebnis)) {
    $counter++;
    $players[$counter] = $adr['NAME'];
    $points[$counter] = $adr['POINTS'];

    if ($adr['POINTS'] > $highestpoints) {
        $highestpoints = $adr['POINTS'];
    }
}

$animation = 'pop';
$delay = 0.5;
$cascade = 1;

$title = new title("");
$title->set_style('{color: #567300; font-size: 14px}');

$data = array();
$x_labels = array();
$i2 = 0;
foreach ($players as $player) {
    $i2++;
    $barvalue = new bar_value($points[$i2]);
    $barvalue->set_tooltip($points[$i2]);
    $data[] = $barvalue;

    $x_labels[] = $player;
}

$bar = new bar_sketch('#EEA411', '#567300', 5);
$bar->set_values($data);
$bar->set_on_show(new bar_on_show($animation, $cascade, $delay));

$y = new y_axis_right();
$y->set_range(0, $highestpoints);


$xlabel = new x_axis_labels();
$xlabel->set_vertical();
$xlabel->set_labels($x_labels);

$x = new x_axis();
$x->set_labels_from_array($x_labels);
$x->set_labels($xlabel);

$chart = new open_flash_chart();
$chart->set_title($title);
$chart->add_element($bar);
$chart->set_y_axis($y);
$chart->set_x_axis($x);

//echo $chart->toPrettyString();
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Current Players - <?php echo $clanname ?></title>
    <link rel=stylesheet type=text/css href="styles/default.css">
    <link type="text/css" href="styles/menu.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="styles/jquery.jqplot.css"/>

    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>

    <script type="text/javascript" src="js/json2.js"></script>
    <script type="text/javascript" src="js/swfobject.js"></script>
    <script
        type="text/javascript">swfobject.embedSWF("swf/open-flash-chart.swf", "my_chart", "490", "300", "9.0.0");</script>

    <script type="text/javascript" language="javascript"><
        !--

            function ofc_ready() {
            }

        function open_flash_chart_data() {
            return JSON.stringify(data);
        }

        function findSWF(movieName) {
            if (navigator.appName.indexOf("Microsoft") != -1) {
                return window[movieName];
            } else {
                return document[movieName];
            }
        }

        var data = <?php echo $chart->toPrettyString(); ?>;

    </script>

</head>
<body>
<?php include("inc/headerlinks.inc.php"); ?>
<div align=center>
    <img src="images/headers/header_onlineplayers.jpg"/>
</div>
<table style="" width="500" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr valign="top">
        <td valign=top colspan="2" align="center" class="tlarge coltitle">Best Players of the Server</td>
    </tr>
    <tr>
        <td class=row style="background-color:#fff;" colspan="2">

            <div id="my_chart"></div>

        </td>
    </tr>
</table>
<table class="" style="border:none" width=500" border="0">

    <tr>
        <td class=row>
            <div>

                <?php
                $ts1 = time() - (90 + $tioffset);
                mysql_query("SET CHARACTER SET 'utf8'");

                $sql = "SELECT * FROM Player WHERE LASTONTIME >= '$ts1'";
                $query = mysql_query($sql) or die('DB Query failed');
                if (mysql_num_rows($query) == 0) {
                    echo 'No online players at the moment';
                } else {

                    $page = 1;
                    if (isset($_GET['page'])) {
                        if (mysql_real_escape_string($_GET['page'])) {
                            $page = mysql_real_escape_string($_GET['page']);
                        }
                    }

                    $per_page = "35";
                    $count = mysql_num_rows($query);
                    $limit = ($page - 1) * $per_page;

                    $pages = ceil($count / $per_page);
                    $previous = $page - 1;
                    $next = $page + 1;

                    $ol = $limit + 1;

                    echo('<ol start="' . $ol . '">');

                    if ($page <= $pages) {
                        $sql = 'SELECT * FROM `Player` WHERE LASTONTIME >= ' . $ts1 . ' ORDER BY `POINTS` DESC LIMIT ' . $limit . ',' . $per_page;
                        $ergebnis = mysql_query($sql);
                        $fromplayer = 0;
                        $i = 1;
                        $i = $i + $fromplayer;
                        while ($adr = mysql_fetch_array($ergebnis)) {
                            $timestamp = $adr['LASTONTIME'];
                            if ($timestamp == 0) {
                                $laststr = "never";
                            } else {
                                $datum = date("d.m.Y", $timestamp);
                                $uhrzeit = date("H:i", $timestamp);
                                $laststr = $datum . " - " . $uhrzeit;
                            }
                            echo '
    <li><a href="player.php?steamid=' . $adr['STEAMID'] . '">' . $adr['NAME'] . '</a><br /><br /></li>
    
	';


                        }
                        echo('</ol>');

                        switch ($page) {
                            case($page == $pages && $previous == 0):
                                echo('Page ' . $page . ' of ' . $pages);
                                break;

                            case($page != $pages && $previous != 0):
                                echo('Page ' . $page . ' of ' . $pages . ' <a href="?page=' . $previous . '">Previous 20</a> | <a href="?page=' . $next . '">Next 20</a>');
                                break;

                            case($page != $pages):
                                echo('Page ' . $page . ' of ' . $pages . ' <a href="?page=' . $next . '">Next 20</a>');
                                break;

                            case($page == $pages && $previous != 0):
                                echo('Page ' . $page . ' of ' . $pages . ' <a href="?page=' . $previous . '">Previous 20</a>');
                                break;
                        }
                    } else {
                        echo("</ol>");
                    }
                }

                ########################


                #############################


                ?>
        </td>
    </tr>
    </div>
</table>
<?php echo $mainindexfooter ?>
</td>
</tr>
</table>
</body>
</html>
