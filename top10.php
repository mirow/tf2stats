<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<?php
include("inc/dbconnect.php");
include("inc/footer.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TF2 Player Stats - <?php echo $clanname ?></title>
    <link href=styles/default.css rel=stylesheet type=text/css>
    <link type="text/css" href="styles/menu.css" rel="stylesheet"/>
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>

    <script type="text/javascript">


        $(document).ready(function () {
            $("#sortable_id1").tablesorter({
                // pass the headers argument and assing a object
                headers: {
                    // assign the secound column (we start counting zero)
                    3: {
                        // disable it by setting the property sorter to false
                        sorter: false
                    },
                    // assign the third column (we start counting zero)
                    4: {
                        // disable it by setting the property sorter to false
                        sorter: false
                    }
                }
            });

            $("#sortable_id2").tablesorter({
                // pass the headers argument and assing a object
                headers: {
                    // assign the secound column (we start counting zero)
                    3: {
                        // disable it by setting the property sorter to false
                        sorter: false
                    },
                    // assign the third column (we start counting zero)
                    4: {
                        // disable it by setting the property sorter to false
                        sorter: false
                    }
                }
            });
        });

    </script>

</head>

<body>

<?php include("inc/headerlinks.inc.php"); ?>
<div align=center>
    <img src="images/headers/header_top10.jpg"/>
</div>
<div class="bump box" align=center>
    <table class="" id="sortable_id1" cellpadding=0 cellspacing=0 class=flatrow border="0" width=600">
        <thead>
        <tr>
            <th class="coltitle tlarge">Rank</th>
            <th class="coltitle tlarge">Name</th>
            <th class="coltitle tlarge">Points</th>
            <th class="coltitle tlarge">Playtime</th>
            <th class="coltitle tlarge">Last Play time</th>
        </tr>
        </thead>
        <?php
        mysql_query("SET CHARACTER SET 'utf8'");
        $fromplayer = 0;
        $sql = 'SELECT * FROM `Player` ORDER BY `POINTS` DESC LIMIT 0,10 ';
        $ergebnis = mysql_query($sql);
        $i = 1;
        $i = $i + $fromplayer;
        while ($adr = mysql_fetch_array($ergebnis)) {
            $timestamp = $adr['LASTONTIME'];
            if ($timestamp == 0) {
                $laststr = "never";
            } else {
                $datum = date("d.m.Y", $timestamp);
                $uhrzeit = date("H:i", $timestamp);
                $laststr = $datum;
            }

            if ($datetimeformat == "0") {
                // Convert military time to standard time
                //$milTime = '000';
                $newTime = $uhrzeit;

                $find = "/:/";
                $replace = "";
                $milTime = preg_replace($find, $replace, $newTime);

                $amPm = 'AM';

                if (intval($milTime > 2359) || strlen($milTime) < 4) {
                    die("Invalid time");
                }

                $milTimeHours = substr($milTime, 0, 2);
                $milTimeMinutes = substr($milTime, 2, 2);

                if (intval($milTimeHours >= 12)) {
                    $amPm = 'PM';
                    if (intval($milTimeHours > 12)) {
                        $standardTimeHours = intval($milTimeHours - 12);
                    } else {
                        $standardTimeHours = intval($milTimeHours);
                    }
                } elseif (intval($milTimeHours == 0)) {
                    $standardTimeHours = 12;
                } else {
                    $standardTimeHours = intval($milTimeHours);
                }

                if ($timestamp != 0) {
                    $datum = date("m/d/Y", $timestamp);
                }
                $standardTime = "$standardTimeHours:$milTimeMinutes $amPm";
                $laststr = $datum . " - " . $standardTime;
            } else {
                if ($datetimeformat == "1") {
                    $laststr = $datum . " - " . $uhrzeit;
                    $laststr = str_replace(".", "-", $laststr);
                }
            }

// Friendly Playtime
            $playtime = $adr['PLAYTIME'];
            if ($playtime > 60) {
                $hours = floor($playtime / 60);
                $mins = $playtime - ($hours * 60);
                $playtime = $hours . " hrs " . $mins . " min";
            } else {
                $playtime = $playtime . " min";
            }

            if ($adr['STEAMID'] != "BOT") {
                echo '
    <tr>
      <td class=row>' . $i . '</td>
      <td class=row_alt><a href="player.php?steamid=' . $adr['STEAMID'] . '">' . $adr['NAME'] . '</a></td>
      <td class=row>' . $adr['POINTS'] . '</td>
      <td class=row_alt>' . $playtime . '</td>
      <td class=row>' . $laststr . '</td>
    </tr>
	';
                $i = $i + 1;
            }
        }
        ?>
    </table>
</div>
<div align=center>
    <img src="images/headers/header_maps.jpg"/>
</div>
<div class="bump box" align=center>
    <table class="" id="sortable_id2" width=600" cellpadding=0 cellspacing=0 class=flatrow border="0">
        <thead>
        <tr>
            <th class="coltitle tlarge">Rank</th>
            <th class="coltitle tlarge">Name</th>
            <th class="coltitle tlarge">Points</th>
            <th class="coltitle tlarge">Playtime</th>
            <th class="coltitle tlarge">Last Play time</th>
        </tr>
        </thead>
        <?php
        mysql_query("SET CHARACTER SET 'utf8'");

        $sql = 'SELECT * FROM `Map` ORDER BY `POINTS`, `PLAYTIME` DESC LIMIT 0,10 ';
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

                if ($datetimeformat == "0") {
                    // Convert military time to standard time
                    //$milTime = '000';
                    $newTime = $uhrzeit;

                    $find = "/:/";
                    $replace = "";
                    $milTime = preg_replace($find, $replace, $newTime);

                    $amPm = 'AM';

                    if (intval($milTime > 2359) || strlen($milTime) < 4) {
                        die("Invalid time");
                    }

                    $milTimeHours = substr($milTime, 0, 2);
                    $milTimeMinutes = substr($milTime, 2, 2);

                    if (intval($milTimeHours >= 12)) {
                        $amPm = 'PM';
                        if (intval($milTimeHours > 12)) {
                            $standardTimeHours = intval($milTimeHours - 12);
                        } else {
                            $standardTimeHours = intval($milTimeHours);
                        }
                    } elseif (intval($milTimeHours == 0)) {
                        $standardTimeHours = 12;
                    } else {
                        $standardTimeHours = intval($milTimeHours);
                    }

                    $datum = date("m/d/Y", $timestamp);
                    $standardTime = "$standardTimeHours:$milTimeMinutes $amPm";
                    $laststr = $datum . " - " . $standardTime;
                    $laststr = str_replace(".", "/", $laststr);
                } else {
                    if ($datetimeformat == "1") {
                        $laststr = $datum . " - " . $uhrzeit;
                        $laststr = str_replace(".", "-", $laststr);
                    }
                }
            }

            // Friendly Playtime
            $playtime2 = $adr['PLAYTIME'];
            if ($playtime > 60) {
                $hours2 = floor($playtime2 / 60);
                $mins2 = $playtime2 - ($hours2 * 60);
                $playtime2 = $hours2 . " hrs " . $mins2 . " min";
            } else {
                $playtime2 = $playtime2 . " min";
            }

            echo '
    <tr>
      <td class=row>' . $i . '</td>
      <td class=row_alt><a href="map.php?mapname=' . $adr['NAME'] . '">' . $adr['NAME'] . '</a></td>
      <td class=row>' . $adr['POINTS'] . '</td>
      <td class=row_alt>' . $playtime2 . '</td>
      <td class=row>' . $laststr . '</td>
    </tr>
	';
            $i = $i + 1;
        }
        ?>
    </table>
</div><?php echo $mainindexfooter ?>
<td></tr>
    </table>
</body>
</html>
