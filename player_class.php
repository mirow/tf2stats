<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php

include("inc/dbconnect.php");
include("inc/footer.inc.php");

$class = "";
$page = 1;
$nos = 0;
$ppp = $nrofplayers;
$playername = "";
if (isset($_GET['class'])) {
    $class = mysql_real_escape_string($_GET["class"]);
}
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


$sql = 'SELECT *, SoldierKills/SoldierDeaths as SoldierRatio, DemoKills/DemoDeaths as DemoRatio,	HeavyKills/HeavyDeaths as HeavyRatio,	EngiKills/EngiDeaths as EngiRatio,	PyroKills/PyroDeaths as PyroRatio,	ScoutKills/ScoutDeaths as ScoutRatio,	MedicKills/MedicDeaths as MedicRatio,	SpyKills/SpyDeaths as SpyRatio FROM `Player`';
$sql .= " WHERE `STEAMID` <> 'BOT'";

if ($playername) {
    $sql .= ' AND `NAME` LIKE \'%' . $playername . '%\'';
}

if (empty($class)) {
    $sql .= ' ORDER BY `KILLS` DESC LIMIT ' . $fromplayer . ',' . $ppp . ' ';
} else {
    $classfound = false;
    switch ($class) {
        case "Soldier":
            $class = "SoldierKills";
            $classfound = true;
            break;
        case "Heavy":
            $class = "HeavyKills";
            $classfound = true;
            break;
        case "Engineer":
            $class = "EngiKills";
            $classfound = true;
            break;
        case "Pyro":
            $class = "PyroKills";
            $classfound = true;
            break;
        case "Scout":
            $class = "ScoutKills";
            $classfound = true;
            break;
        case "Medic":
            $class = "MedicKills";
            $classfound = true;
            break;
        case "Sniper":
            $class = "SniperKills";
            $classfound = true;
            break;
        case "Spy":
            $class = "SpyKills";
            $classfound = true;
            break;
        case "Demoman":
            $class = "DemoKills";
            $classfound = true;
            break;
        case "SoldierD":
            $class = "SoldierDeaths";
            $classfound = true;
            break;
        case "HeavyD":
            $class = "HeavyDeaths";
            $classfound = true;
            break;
        case "EngineerD":
            $class = "EngiDeaths";
            $classfound = true;
            break;
        case "PyroD":
            $class = "PyroDeaths";
            $classfound = true;
            break;
        case "ScoutD":
            $class = "ScoutDeaths";
            $classfound = true;
            break;
        case "MedicD":
            $class = "MedicDeaths";
            $classfound = true;
            break;
        case "SniperD":
            $class = "SniperDeaths";
            $classfound = true;
            break;
        case "SpyD":
            $class = "SpyDeaths";
            $classfound = true;
            break;
        case "DemomanD":
            $class = "DemoDeaths";
            $classfound = true;
            break;
        case "SoldierR":
            $class = "SoldierRatio";
            $classfound = true;
            break;
        case "HeavyR":
            $class = "HeavyRatio";
            $classfound = true;
            break;
        case "EngineerR":
            $class = "EngiRatio";
            $classfound = true;
            break;
        case "PyroR":
            $class = "PyroRatio";
            $classfound = true;
            break;
        case "ScoutR":
            $class = "ScoutRatio";
            $classfound = true;
            break;
        case "MedicR":
            $class = "MedicRatio";
            $classfound = true;
            break;
        case "SniperD":
            $class = "SniperRatio";
            $classfound = true;
            break;
        case "SpyR":
            $class = "SpyRatio";
            $classfound = true;
            break;
        case "DemomanR":
            $class = "DemoRatio";
            $classfound = true;
            break;
        case "MedicH":
            $class = "MedicHealing";
            $classfound = true;
            break;
    }

    if ($classfound != true) {
        $class = "KILLS";
    }

    $sql .= ' ORDER BY ' . $class . ' DESC LIMIT ' . $fromplayer . ',' . $ppp . ' ';
}

$ergebnis = mysql_query($sql);
while ($adr = mysql_fetch_array($ergebnis)) {

    $sql = "SELECT COUNT(*) as rank FROM Player  WHERE `STEAMID` <> 'BOT' AND POINTS >= " . $adr['POINTS'];
    $rank = mysql_fetch_row(mysql_query($sql));

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

    $foundPlayers[] = array(
        'points' => $adr['POINTS'],
        'KILLS' => $adr['KILLS'],
        'name' => $adr['NAME'],
        'pt' => $adr['PLAYTIME'],
        'playtime' => $playtime,
        'rank' => $rank[0],
        'lastOnTime' => $adr['LASTONTIME'],
        'steamId' => $adr['STEAMID'],
        'Soldier' => $adr['SoldierKills'],
        'Heavy' => $adr['HeavyKills'],
        'Engineer' => $adr['EngiKills'],
        'Pyro' => $adr['PyroKills'],
        'Scout' => $adr['ScoutKills'],
        'Medic' => $adr['MedicKills'],
        'Sniper' => $adr['SniperKills'],
        'Spy' => $adr['SpyKills'],
        'Demoman' => $adr['DemoKills'],
        'Medich' => $adr['MedicHealing'],
        'Soldierd' => $adr['SoldierDeaths'],
        'Heavyd' => $adr['HeavyDeaths'],
        'Engineerd' => $adr['EngiDeaths'],
        'Pyrod' => $adr['PyroDeaths'],
        'Scoutd' => $adr['ScoutDeaths'],
        'Medicd' => $adr['MedicDeaths'],
        'Sniperd' => $adr['SniperDeaths'],
        'Spyd' => $adr['SpyDeaths'],
        'Demomand' => $adr['DemoDeaths'],
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
    <script type="text/javascript" src="js/menu.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
    <script src="js/jquery.uniform.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {

                $("select, input[type=checkbox], input[type=radio], input[type=file], input[type=submit], a.button, button").uniform();
            }
        );

    </script>
</head>

<body>
<?php include("inc/headerlinks.no.inc.php"); ?>
<div align="center">
<div align=center>
    <img src="images/headers/header_classranking.jpg"/>
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
<table cellpadding=0 cellspacing=0 class=flatrow border="0">
<thead>
<tr>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header"></th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header">Rank</th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header">Name</th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header"><a
            href="player_class.php">Total</a></th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header" colspan="2"><a
            href="player_class.php?class=Soldier">Soldier</a></th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header" colspan="2"><a
            href="player_class.php?class=Heavy">Heavy</a></th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header" colspan="2"><a
            href="player_class.php?class=Engineer">Engineer</a></th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header" colspan="2"><a
            href="player_class.php?class=Pyro">Pyro</a></th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header" colspan="2"><a
            href="player_class.php?class=Scout">Scout</a></th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header" colspan="3"><a
            href="player_class.php?class=Medic">Medic</a></th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header" colspan="2"><a
            href="player_class.php?class=Sniper">Sniper</a></th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header" colspan="2"><a
            href="player_class.php?class=Spy">Spy</a></th>
    <th height="55px" style="margin-left:5px;margin-right:5px;" class="tlarge coltitle header" colspan="2"><a
            href="player_class.php?class=Demoman">Demoman</a></th>
</tr>
</thead>
<tr>
    <td class="tlarge coltitle header"></td>
    <td class="tlarge coltitle header"></td>
    <td class="tlarge coltitle header"></td>
    <td class="tlarge coltitle header"></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=Soldier">Kills</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=SoldierR">KDR</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=Heavy">Kills</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=HeavyR">KDR</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=Engineer">Kills</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=EngineerR">KDR</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=Pyro">Kills</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=PyroR">KDR</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=Scout">Kills</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=ScoutR">KDR</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=Medic">Kills</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=MedicR">KDR</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=MedicH">Healings</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=Sniper">Kills</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=SniperR">KDR</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=Spy">Kills</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=SpyR">KDR</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=Demoman">Kills</a></td>
    <td class="tlarge coltitle header" style="font-size:10px;"><a href="player_class.php?class=DemomanR">KDR</a></td>
</tr>
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
    }

    if ($player['steamId'] != "BOT") {
        echo '      <tr><td class=row_alt>' . $player['rank'] . '</td>
									<td class=row><input type="checkbox" name="state[]" value="' . $player['steamId'] . '"></td>
									<td class=row_alt><a href="player.php?steamid=' . $player['steamId'] . '">' . $player['name'] . '</a></td>
                	<td class=row>' . $player['KILLS'] . '</td>
									<td class=row_alt>' . $player['Soldier'] . '</td><td class=row_alt>';

        if ($player['Soldierd'] != 0) {
            echo round($player['Soldier'] / $player['Soldierd'], 2);
        } else {
            echo "0";
        }

        echo '</td> <td class=row>' . $player['Heavy'] . '</td><td class=row>';

        if ($player['Heavyd'] != 0) {
            echo round($player['Heavy'] / $player['Heavyd'], 2);
        } else {
            echo "0";
        }

        echo '</td>
									<td class=row_alt>' . $player['Engineer'] . '</td><td class=row_alt>';

        if ($player['Engineerd'] != 0) {
            echo round($player['Engineer'] / $player['Engineerd'], 2);
        } else {
            echo "0";
        }

        echo '</td>
									<td class=row>' . $player['Pyro'] . '</td><td class=row>';

        if ($player['Pyrod'] != 0) {
            echo round($player['Pyro'] / $player['Pyrod'], 2);
        } else {
            echo "0";
        }


        echo '</td>
									<td class=row_alt>' . $player['Scout'] . '</td><td class=row_alt>';

        if ($player['Scoutd'] != 0) {
            echo round($player['Scout'] / $player['Scoutd'], 2);
        } else {
            echo "0";
        }

        echo '</td>
									<td class=row>' . $player['Medic'] . '</td><td class=row>';

        if ($player['Medicd'] != 0) {
            echo round($player['Medic'] / $player['Medicd'], 2);
        } else {
            echo "0";
        }

        echo '</td><td class=row>' . $player['Medich'] . '</td>
									<td class=row_alt>' . $player['Sniper'] . '</td><td class=row_alt>';

        if ($player['Sniperd'] != 0) {
            echo round($player['Sniper'] / $player['Sniperd'], 2);
        } else {
            echo "0";
        }

        echo '</td>
									<td class=row>' . $player['Spy'] . '</td><td class=row>';
        if ($player['Spyd'] != 0) {
            echo round($player['Spy'] / $player['Spyd'], 2);
        } else {
            echo "0";
        }

        echo '</td>
									<td class=row_alt>' . $player['Demoman'] . '</td><td class=row_alt>';

        if ($player['Demomand'] != 0) {
            echo round($player['Demoman'] / $player['Demomand'], 2);
        } else {
            echo "0";
        }

        echo '</td>';


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
