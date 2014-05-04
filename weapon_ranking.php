<?php
include("inc/dbconnect.php");
include("inc/footer.inc.php");

$id = "";
if (isset($_GET['id'])) {
    $id = mysql_real_escape_string($_GET["id"]);
}

$wcolumn = "";
?>

<?php
$sqlweapon = "SELECT DISTINCT NAME, WEAPON, IMAGE FROM `weapons`";
$ergebnisweapon = mysql_query($sqlweapon);
if (!$ergebnisweapon) {
    echo "Query $myquery failed: " . mysql_error();
}
$weaponnr = 0;
while ($w = mysql_fetch_array($ergebnisweapon)) {

    if ($id == $w['NAME']) {
        $wcolumn = $w['WEAPON'];
    }

    $WeapKills[$weaponnr]["Name"] = $w['NAME'];
    $WeapKills[$weaponnr]["ImgUrl"] = $w['IMAGE'];
    $WeapKills[$weaponnr]["Weapon"] = $w['WEAPON'];
    $sqlweapondetail = "SELECT sum(" . $w['WEAPON'] . ") as Sum FROM `Player`";

    $ergebnisweapondetail = mysql_query($sqlweapondetail);
    if (!$ergebnisweapondetail) {
        echo "Query $sqlweapondetail failed: " . mysql_error();
    }

    while ($wd = mysql_fetch_array($ergebnisweapondetail)) {
        $WeapKills[$weaponnr]["Kills"] = $wd['Sum'];
    }

    $weaponnr++;

}

$MaxWeap = count($WeapKills);
$DisplayZeroKills = 0;

// Make an array of all the kills, then sort the array descending
For ($i = 0; $i < $MaxWeap; $i++) {
    $KillIndex[] = $WeapKills[$i]["Kills"];
}
arsort($KillIndex);


if (!empty($wcolumn)) {
    $sql = 'SELECT STEAMID, NAME, POINTS, Domination, ' . $wcolumn . ' FROM `Player`';
    $sql .= ' WHERE ' . $wcolumn . ' > 0 ';
    $sql .= ' ORDER BY ' . $wcolumn . ' DESC LIMIT 0,' . $shownrofplayers . ' ';

    $ergebnis = mysql_query($sql);
    if (!$ergebnis) {
        echo "Query $sql failed: " . mysql_error();
    }
    while ($adr = mysql_fetch_array($ergebnis)) {

        $sql = "SELECT COUNT(*) as rank FROM Player  WHERE `STEAMID` <> 'BOT' AND " . $wcolumn . " >= " . $adr[$wcolumn];
        $rank = mysql_fetch_row(mysql_query($sql));

        $foundPlayers[] = array(
            'points' => $adr['POINTS'],
            'KILLS' => $adr[$wcolumn],
            'steamId' => $adr['STEAMID'],
            'name' => $adr['NAME'],
            'rank' => $rank[0],
            'dominations' => $adr['Domination'],
        );
    }
}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Weapon Ranking - <?php echo $clanname ?></title>
    <link href=styles/default.css rel=stylesheet type=text/css>
    <link type="text/css" href="styles/menu.css" rel="stylesheet"/>
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>

</head>

<body>

<?php include("inc/headerlinks.alt.inc.php"); ?>
<div align=center>
    <img src="images/headers/header_weaponstats.jpg"/>
</div>

<table>
    <tr>
        <td width="150px">

            <table width="100%" style="" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="50%" align="center" class="coltitle tlarge header">Weapon</td>
                    <td width="50%" align="center" class="coltitle tlarge header">Kills</td>
                </tr>
                <?php
                // Display Weapon data sorted by weapon kills
                For ($i = 0; $i < $MaxWeap; $i++) {
                    if ($i == 0) {
                        $tdclass = "toprow";
                    } elseif ($tdclass == "toprow") {
                        $tdclass = "row_alt";
                    }

                    if ($tdclass == "row") {
                        $tdclass = "row_alt";
                    } elseif ($tdclass == "row_alt") {
                        $tdclass = "row";
                    }

                    $IMG = $WeapKills[key($KillIndex)]["ImgUrl"];
                    $KILLCOUNT = $WeapKills[key($KillIndex)]["Kills"];
                    $ALT = $WeapKills[key($KillIndex)]["Name"];
                    next($KillIndex);

                    echo "<tr>";
                    if ($id == $ALT) {
                        echo "<td class='" . $tdclass . "' align='center' bgcolor='#000000'><a href='weapon_ranking.php?id=" . $ALT . "'><img border=4 title='" . $ALT . "' alt='" . $ALT . "' src='" . $IMG . "'/><br><span class='weapontxt'>" . $ALT . "</span></a></td>";
                    } else {
                        echo "<td class='" . $tdclass . "' align='center' bgcolor='#000000'><a href='weapon_ranking.php?id=" . $ALT . "'><img border=0 title='" . $ALT . "' alt='" . $ALT . "' src='" . $IMG . "'/><br><span class='weapontxt'>" . $ALT . "</span></a></td>";
                    }
                    echo "<td class='" . $tdclass . "' align='right' bgcolor=''>" . $KILLCOUNT . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>

        </td>
        <td id="top players pr weapon" width="900px" valign="TOP">


            <table class="sortable" width="100%" id="sortable_id1" cellpadding=0 cellspacing=0 class=flatrow border="0">

                <thead>
                <tr>
                    <th align="left" class="tlarge coltitle header">Rank</th>
                    <th align="left" class="tlarge coltitle header"><img src="images/icons/medal.png"
                                                                         title="Nr of Dominations"
                                                                         alt="Nr of Dominations"/></th>
                    <th align="left" class="tlarge coltitle header">Name</th>
                    <th align="left" class="tlarge coltitle header">Points</th>
                    <th align="left" class="tlarge coltitle header">Kills with the <?php echo $id; ?></th>
                </tr>
                </thead>

                <?php
                $rowcount = 1;

                if (!empty($wcolumn)) {
                    foreach ($foundPlayers as $player) {
                        if ($player['steamId'] != "BOT") {


                            echo '			<tr>
			                	<td  align="left"  class=row_alt>' . $player['rank'] . '</td>
			                	<td  align="left"  class=row>' . $player['dominations'] . '</td>
						<td  align="left"  class=row_alt><a href="player.php?steamid=' . $player['steamId'] . '">' . $player['name'] . '</a></td>
						<td  align="left"  class=row>' . $player['points'] . '</td>
						<td  align="left"  class=row_alt>' . $player['KILLS'] . '</td>
						</tr>';

                        }
                    }
                } else {
                    echo '			<tr>
			                	<td colspan="5" align="center"  class=row_alt>Please select a Weapon.</td>
						</tr>';
                }

                ?>

            </table>


        </td>
    </tr>


</table>

<?php echo $mainfooter ?>
</td>
</tr>
</table>
</body>
</html>