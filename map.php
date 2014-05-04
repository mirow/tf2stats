<?php
include("inc/dbconnect.php");
include("inc/footer.inc.php");

$steamid = "";
if (isset($_GET['mapname'])) {
    $steamid = mysql_real_escape_string($_GET["mapname"]);
}


mysql_query("SET CHARACTER SET 'utf8'");
$sql = "SELECT * FROM `Map` WHERE `NAME` LIKE '$steamid'";
$ergebnis = mysql_query($sql);
while ($adr = mysql_fetch_array($ergebnis)) {
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" contnet=1>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Map <?php echo $adr['NAME'] ?> - <?php echo $clanname ?></title>
        <link rel=stylesheet type=text/css href=styles/default.css>
        <link type="text/css" href="styles/menu.css" rel="stylesheet"/>
        <link rel="stylesheet" href="styles/lightbox.css" type="text/css" media="screen"/>

        <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
        <script type="text/javascript" src="js/menu.js"></script>

        <script type="text/javascript" src="js/prototype.js"></script>
        <script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
        <script type="text/javascript" src="js/lightbox.js"></script>
    </head>

    <body>
    <?php include("inc/headerlinks.inc.php"); ?>
    <div align=center>
        <img src="images/headers/header_maps.jpg"/>
    </div>
    <table>
        <tr>
            <td valign="top" height="" style="border:none">
                <div class=box align="center">
                    <table class="" style="border:none" width="500" border="0">
                        <tr valign="top">
                            <td colspan="3" align="left" class="coltitle tlarge">Map Profile (this is not suported at
                                this moment!!)
                            </td>
                        </tr>
                        <tr class=row valign="top">
                            <td class=row><img width="160" src="images/maps/<?php echo $adr['NAME'] ?>.jpg"
                                               onerror="this.src='images/maps/unknown.jpg';"/></td>

                            <td class=row>
                                <table class="" style="border:none" border="0">
                                    <tr>
                                        <td class=toprow width="200" align="left">Name:</td>
                                        <td class=toprow width="" align="left">
                                            <div align="right">
                                                <?php echo $adr['NAME'] ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class=row>
                                        <td class=row_alt align="left">Last Played:</td>
                                        <?php
                                        $timestamp = $adr['LASTONTIME'];
                                        if ($timestamp == 0) {
                                            $laststr = "never";
                                        } else {
                                            $datum = date("d.m.Y", $timestamp);
                                            $uhrzeit = date("H:i", $timestamp);
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
                                            //debug
                                            //echo "$standardTime";

                                            $laststr = $datum . " - " . $standardTime;

                                            $laststr = $datum . " - " . $standardTime;
                                            //Change dots to slashes
                                            $laststr = str_replace(".", "/", $laststr);

                                        }

                                        $days = floor($adr['PLAYTIME'] / 60 / 24);
                                        $hours = floor($adr['PLAYTIME'] / 60 - $days * 24);
                                        $mins = $adr['PLAYTIME'] - $hours * 60 - $days * 24 * 60;

                                        if ((int)$mins == 0) {
                                            $mins = '00';
                                        }
                                        if ($days == 0) {
                                            $playtime_format = $hours . 'h ' . $mins . 'm';
                                            if ($hours == 0) {
                                                $playtime_format = $mins . 'm';
                                            }
                                        } else {
                                            $playtime_format = $days . 'd ' . $hours . 'h ' . $mins . 'm';
                                        }

                                        ?>
                                        <td class="row_alt" align="left">
                                            <div align="right">
                                                <?php echo $laststr ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr bgcolor="">
                                        <td class=row align="left">Total Play Time:</td>
                                        <td class=row align="left">
                                            <div align="right">
                                                <?php echo $playtime_format ?>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="bump box">
                    <table width="500" border="0" cellpadding="0" cellspacing="0">

                        <tr valign="top">
                            <td valign=top colspan="2" class="tlarge coltitle">Statistics Summary

                            </td>
                        </tr>
                        <tr>
                            <td class="toprow" width="52%">Rank:</td>
                            <td class="toprow" width="48%">
                                <div align="right">
                                    <?php
                                    $sql2 = "SELECT NAME FROM `Map` WHERE `POINTS` >= '" . $adr['POINTS'] . "'";
                                    $ergebnis2 = mysql_query($sql2);
                                    $anzahl = mysql_num_rows($ergebnis2);
                                    echo $anzahl;
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="">
                            <td class=row width="52%">Points:</td>
                            <td class=row colspan="2" width="48%">
                                <div align="right">
                                    <?php echo $adr['POINTS'] ?>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="">
                            <td class=row_alt width="52%">Kills:</td>
                            <td class=row_alt colspan="2" width="48%">
                                <div align="right">
                                    <?php echo $adr['KILLS'] ?>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="">
                            <td class=row width="52%">Deaths:</td>
                            <td class=row colspan="2" width="48%">
                                <div align="right">
                                    <?php echo $adr['Death'] ?>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="">
                            <td class=row width="52%">Kills per Minute:</td>
                            <td class=row colspan="2" width="48%">
                                <div align="right">
                                    <?php
                                    if ($adr['PLAYTIME'] != 0) {
                                        echo round($adr['KILLS'] / $adr['PLAYTIME'], 2);
                                    } else {
                                        echo "0";
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                </div>
    </table>
    <div style="float:right;">

        <?php
        if ($showblueicon == 1 && file_exists("images/heatmaps/" . $adr['NAME'] . ".jpg")) {
            echo '<a rel="lightbox[' . $countertest . ']" class="wiki" href="images/heatmaps/' . $adr['NAME'] . '.jpg" /><img width="25px" src="images/icons/blueprint icon.png"  border=0  alt="Blueprint Present"/></a>';
        }
        ?>

    </div>

    </td>
    </tr>
    </td>

    </tr>
    </table>
    <p>&nbsp;</p>  <?php echo $mainfooter ?>
    </div>
    </body>
    </html>

<?php
}
?>