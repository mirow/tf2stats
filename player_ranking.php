<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php

include("inc/dbconnect.php");
include("inc/footer.inc.php");

$page = 1;
$nos = 0;
$ppp = $nrofplayers;
$playername = 0;
if (isset($_GET['page'])) {
    $page = (int)$_GET["page"];
}
if (isset($_GET['nos'])) {
    $nos = (int)$_GET["nos"];
}
if (isset($_GET['ppp'])) {
    $ppp = (int)$_GET["ppp"];
}
if (isset($_GET['playername'])) {
    $playername = mysql_real_escape_string($_GET['playername']);
}


$fromplayer = ($page * $ppp) - $ppp;
$toplayer = $page * $ppp;
$nextpage = $page + 1;
$prevpage = $page - 1;

$maxplayercount = mysql_num_rows(mysql_query("SELECT name FROM `Player`"));

$nos = ceil($maxplayercount / $ppp);
$lastpage = floor($maxplayercount / $ppp) + 1; //zero based.,.

$foundPlayers = array();

mysql_query("SET CHARACTER SET 'utf8'");

$sql = 'SELECT * FROM `Player`';
$sql .= " WHERE `STEAMID` <> 'BOT'";
if ($playername) {
    $sql .= ' AND `NAME` LIKE \'%' . $playername . '%\'';
}

$sql .= ' ORDER BY `POINTS`/`PLAYTIME` DESC LIMIT ' . $fromplayer . ',' . $ppp . ' ';

$rank = 0;
$ergebnis = mysql_query($sql);
while ($adr = mysql_fetch_array($ergebnis)) {

    //$sql = "SELECT COUNT(*) as rank FROM Player  WHERE `STEAMID` <> 'BOT' AND POINTS >= " . $adr['POINTS'];
    //$rank = mysql_fetch_row(mysql_query($sql));
	$rank++;

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

    // Get nr of sniper kills
    $sqlweapon = "SELECT WEAPON FROM weapons WHERE NAME in ('Sniper Rifle', 'The Huntsman', 'The Ambassador', 'Machina', 'Bazaar Bargain')";
    $ergebnisweapon = mysql_query($sqlweapon);
    $sniperkills = 0;
    while ($weapon = mysql_fetch_array($ergebnisweapon)) {
        $sniperkills = $sniperkills + $adr[$weapon['WEAPON']];
    }

    //$regioncity = countryCityFromIP($adr['IPAddress'], $adr['STEAMID']);

    $foundPlayers[] = array(
        'points' => $adr['POINTS'],
        'KILLS' => $adr['KILLS'],
        'Death' => $adr['Death'],
        'lastOnTime' => $adr['LASTONTIME'],
        'steamId' => $adr['STEAMID'],
        'name' => $adr['NAME'],
        'pt' => $adr['PLAYTIME'],
        'headshot' => $adr['HeadshotKill'],
        'playtime' => $playtime,
        'rank' => $rank,
        'sniperkills' => $sniperkills,
        'dominations' => $adr['Domination'],
        //'region' => $regioncity,
    );
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TF2 Player Stats - <?php echo $clanname ?></title>
    <link rel=stylesheet type=text/css href=styles/default.css>
    <link type="text/css" href="styles/menu.css" rel="stylesheet"/>
    <link rel="stylesheet" href="styles/uniform.default.css" type="text/css" media="screen" charset="utf-8"/>
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
    <script src="js/jquery.uniform.min.js" type="text/javascript"></script>

    <script type="text/javascript">

        $(document).ready(function () {
            $("#sortable_id1").tablesorter({
                // pass the headers argument and assing a object
                headers: {
                    // assign the secound column (we start counting zero)
                    11: {
                        // disable it by setting the property sorter to false
                        sorter: false
                    },
                    // assign the third column (we start counting zero)
                    12: {
                        // disable it by setting the property sorter to false
                        sorter: false
                    }
                }
            });

            $("select, input[type=checkbox], input[type=radio], input[type=file], input[type=submit], a.button, button").uniform();
        });
    </script>

</head>

<body>
<?php include("inc/headerlinks.no.inc.php"); ?>
<div align="center">
    <div align=center>
        <img src="images/headers/header_playerranking.jpg"/>
    </div>

    <br/>

    <div align=center class=box>
        <?php
        include("inc/paging.php");
        include("inc/searchform.php");
        ?>

        <form method="get" action="compare.php"><br \>
            <input type="submit" value="Compare Player Actions" class="btn" name="searchplayer"/>
            <input type="submit" value="Compare Found Items" class="btn" name="searchitems"/>
            <input type="submit" value="Compare top 10 best Weapons" class="btn" name="searchtop10bestweapons"/>
            <input type="submit" value="Compare Weapon Information" class="btn" name="searchweapons"/>
            <br/><br/>
            <table class="sortable" id="sortable_id1" cellpadding=0 cellspacing=0 class=flatrow border="0">

                <thead>
                <tr>
                    <th align="left" class="tlarge coltitle header"></th>
                    <th align="left" class="tlarge coltitle header">Rank</th>
                    <th align="left" class="tlarge coltitle header"><img src="images/icons/medal.png"
                                                                         title="Nr of Dominations"
                                                                         alt="Nr of Dominations"/></th>
                    <th align="left" class="tlarge coltitle header">Name</th>
                    <th align="left" class="tlarge coltitle header">Points</th>
                    <th align="left" class="tlarge coltitle header">Kills</th>
                    <th align="left" class="tlarge coltitle header {sorter: 'procent'}">Headshots</th>
                    <th align="left" class="tlarge coltitle header">Deaths</th>
                    <th align="left" class="tlarge coltitle header">Kills per <br/>Death</th>
                    <th align="left" class="tlarge coltitle header">Points per <br/>Minute</th>
                    <th align="left" class="tlarge coltitle header">Kills per <br/>Minute</th>
                    <th align="left" class="tlarge coltitle header date {sorter: 'date'}">Playtime</th>
                    <th align="left" class="tlarge coltitle header date {sorter: 'date'}">Last Playtime</th>
                </tr>
                </thead>
                <?php
                $rowcount = 1;

                foreach ($foundPlayers as $player) {

                    $timestamp = $player['lastOnTime'];
                    if ($timestamp == 0) {
                        $laststr = "never";
                    } else {
                        $datum = date("d.m.Y", $timestamp);
                        $uhrzeit = date("H:i", $timestamp);
                        $laststr = $datum;

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
                            $laststr = $datum . " <br /> " . $standardTime;

                        } else {
                            if ($datetimeformat == "1") {
                                $laststr = $datum . " - " . $uhrzeit;
                                $laststr = str_replace(".", "-", $laststr);
                            }
                        }
                    }

                    if ($player['steamId'] != "BOT") {
                        echo '      <tr>
                	<td   align="left" class=row><input type="checkbox" name="state[]" value="' . $player['steamId'] . '"></td>
                	<td  align="left"  class=row_alt>' . $player['rank'] . '</td>
                	<td  align="left"  class=row>' . $player['dominations'] . '</td>
									<td  align="left"  class=row_alt ALIGN="left"><a href="player.php?steamid=' . $player['steamId'] . '">' . $player['name'] . '</a></td>
                	<td  align="left"  class=row>' . $player['points'] . '</td>
                        <td class=row_alt>' . $player['KILLS'] . '</td>
			<td class=row>';

                        if ($player['sniperkills'] != 0) {
                            echo round(($player['headshot'] / $player['sniperkills']) * 100, 2);
                        } else {
                            echo '0';
                        }

                        echo '% (' . $player['headshot'] . '/' . $player['sniperkills'] . ')</td><td class=row_alt>' . $player['Death'] . '</td>
                        <td class=row>';

                        if ($player['Death'] != 0) {
                            echo round($player['KILLS'] / $player['Death'], 2);
                        } else {
                            echo "0";
                        }


                        echo '		</td><td class=row_alt>';

                        if ($player['pt'] != 0) {
                            echo round($player['points'] / $player['pt'], 2);
                        } else {
                            echo "0";
                        }

                        echo '		</td><td class=row>';

                        if ($player['pt'] != 0) {
                            echo round($player['KILLS'] / $player['pt'], 2);
                        } else {
                            echo "0";
                        }

                        echo '          </td>
                	<td class=row_alt>' . $player['playtime'] . '</td>
                	<td class=row>' . $laststr . '</td>
              	  </tr>

			';
                        $rowcount++;

                    }

                }
                ?>
            </table>
            <br/>
            <?php
            include("inc/paging.php");
            ?>
            <br/>

        </form>

    </div>
    <br \>

</div><?php echo $mainfooter ?>
</tr>
</td>
</table>
</body>
</html>
