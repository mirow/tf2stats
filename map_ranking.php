<?php
include("inc/dbconnect.php");
include("inc/footer.inc.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Map Ranking - <?php echo $clanname ?></title>
    <link href=styles/default.css rel=stylesheet type=text/css>
    <link type="text/css" href="styles/menu.css" rel="stylesheet"/>
    <link rel="stylesheet" href="styles/lightbox.css" type="text/css" media="screen"/>

    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>

    <script type="text/javascript" src="js/prototype.js"></script>
    <script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
    <script type="text/javascript" src="js/lightbox.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {
            $(".wiki").colorbox({iframe: true});

            $("#sortable_id1").tablesorter({
                // pass the headers argument and assing a object
                headers: {
                    // assign the secound column (we start counting zero)
                    2: {
                        // disable it by setting the property sorter to false
                        sorter: false
                    },
                    // assign the third column (we start counting zero)
                    3: {
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
    <div align=center>
        <img src="images/headers/header_mapstats.jpg"/>
    </div>
</div>
<div class="bump box">
    <table class="" id="sortable_id1" width=500" cellpadding=0 cellspacing=0 class=flatrow border="0">
        <thead>
        <tr>
            <th class=coltitle>Rank</th>
            <th class=coltitle>Name</th>
            <th class=coltitle>Playtime</th>
            <th class=coltitle>Last Played time</th>
            <th class=coltitle></th>
        </tr>
        </thead>

        <?php
        mysql_query("SET CHARACTER SET 'utf8'");

        $sql = 'SELECT * FROM `Map` ORDER BY `PLAYTIME` DESC';
        $ergebnis = mysql_query($sql);
        $i = 1;
        $laststr = "";
        $countertest = 0;
        while ($adr = mysql_fetch_array($ergebnis)) {
            $countertest++;
            $timestamp = $adr['LASTONTIME'];
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
                $standardTime = "$standardTimeHours:$milTimeMinutes $amPm";

                $datum = date("m/d/Y", $timestamp);
                $laststr = $datum . " - " . $standardTime;

            } else {
                if ($datetimeformat == "1") {
                    $laststr = $datum . " - " . $uhrzeit;
                    $laststr = str_replace(".", "-", $laststr);
                }
            }

            $days = floor($adr['PLAYTIME'] / 60 / 24);
            $hours = floor($adr['PLAYTIME'] / 60 - $days * 24);
            $mins = $adr['PLAYTIME'] - $hours * 60 - $days * 24 * 60;

            if ((int)$mins == 0) {
                $mins = '00';
            }

            if ($days == 0) {
                $playtime = $hours . 'h ' . $mins . 'm';
                if ($hours == 0) {
                    $playtime = $mins . 'm';
                }
            } else {
                $playtime = $days . 'd ' . $hours . 'h ' . $mins . 'm';
            }

            echo '
	   <tr>
	   <td class=row_alt>' . $i . '</td>
	   <td class=row><a href="map.php?mapname=' . $adr['NAME'] . '">' . $adr['NAME'] . '</a></td>
	   <td class=row_alt>' . $playtime . '</td>
	   <td class=row>' . $laststr . '</td>
	   <td class=row valign=right>';

            if ($showimageicon == 1 && file_exists("images/maps/" . $adr['NAME'] . ".jpg")) {
                echo '<a rel="lightbox[' . $countertest . ']" href="images/maps/' . $adr['NAME'] . '.jpg" /><img width="25px" src="images/icons/imageicon.png" border=0 alt="Image Present"/></a>';
            }
            if ($showblueicon == 1 && file_exists("images/heatmaps/" . $adr['NAME'] . ".jpg")) {
                echo '<a rel="lightbox[' . $countertest . ']" class="wiki" href="images/heatmaps/' . $adr['NAME'] . '.jpg" /><img width="25px" src="images/icons/blueprint icon.png"  border=0  alt="Blueprint Present"/></a>';
            }

            echo '</td>
	   </tr>
		';
            $i++;
        }
        ?>
    </table>

</div>      <?php echo $mainfooter ?>
</tr></td>
</table>
</body>
</html>
