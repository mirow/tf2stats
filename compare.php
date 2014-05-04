<?php
header('Content-Type: text/html; charset=UTF-8');
include("inc/dbconnect.php");
include("inc/footer.inc.php");

?>

<html>
<head>
    <title>TF2 Player Stats - <?php echo $clanname ?></title>
    <link rel=stylesheet type=text/css href=styles/default.css>
    <link type="text/css" media="screen" rel="stylesheet" href="styles/colorbox.css"/>
    <link type="text/css" href="styles/menu.css" rel="stylesheet"/>
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
    <script>
        $(document).ready(function () {
            $(".wiki").colorbox({width: "90%", height: "90%", iframe: true});
        });
    </script>

</head>

<body>
<table>

<?php include("inc/headerlinks.no.inc.php"); ?>


<div align=center>
    <img src="images/headers/header_playercompare.jpg"/>
</div>

<?php
$state = "";
if (isset($_GET['state'])) {
    $state = $_GET['state'];
}

echo "<table border='0' cellpadding='0' cellspacing='0'>";
echo "<tr bgcolor='#3F4140' valign='top'>";
$rowcount = 0;
$playercount = 0;
foreach ($state as $statename) {
    $playercount++;
    $steamid = mysql_real_escape_string($statename);
    $sql = "SELECT * FROM `Player` WHERE `STEAMID` = '$steamid'";
    $ergebnis = mysql_query($sql);

    while ($adr = mysql_fetch_array($ergebnis)) {
        echo "<td>";


        if (isset($_REQUEST['searchplayer'])) {

            // Populate Action Array from database
            // Kill related
            $Action[0]["Name"] = "Domination";
            $Action[0]["Value"] = $adr['Domination'];
            $Action[1]["Name"] = "Revenge";
            $Action[1]["Value"] = $adr['Revenge'];
            $Action[2]["Name"] = "Headshot Kill";
            $Action[2]["Value"] = $adr['HeadshotKill'];
            $Action[3]["Name"] = "Kill Assist";
            $Action[3]["Value"] = $adr['KillAssist'];

            // Medic Related
            $Action[4]["Name"] = "Kill Assist - Medic";
            $Action[4]["Value"] = $adr['KillAssistMedic'];
            $Action[5]["Name"] = "Ubercharge";
            $Action[5]["Value"] = $adr['Overcharge'];

            // Engineer Related
            $Action[6]["Name"] = "Built Object - Sentrygun";
            $Action[6]["Value"] = $adr['BuildSentrygun'];
            $Action[7]["Name"] = "Built Object - Dispenser";
            $Action[7]["Value"] = $adr['BuildDispenser'];
            $Action[8]["Name"] = "Entrances Built";
            $Action[8]["Value"] = $adr['BOTeleporterentrace'];
            $Action[9]["Name"] = "Exits Built";
            $Action[9]["Value"] = $adr['BOTeleporterExit'];
            $Action[10]["Name"] = "Sappers Removed";
            $Action[10]["Value"] = $adr['KOSapper'];

            // Spy Related
            $Action[11]["Name"] = "Backstabs";
            $Action[11]["Value"] = $adr['K_backstab'];
            $Action[12]["Name"] = "Sappers Placed";
            $Action[12]["Value"] = $adr['BOSapper'];
            $Action[13]["Name"] = "Killed Object - Sentrygun";
            $Action[13]["Value"] = $adr['KOSentrygun'];
            $Action[14]["Name"] = "Dispensers Destroyed";
            $Action[14]["Value"] = $adr['KODispenser'];
            $Action[15]["Name"] = "Entrances Destroyed";
            $Action[15]["Value"] = $adr['KOTeleporterEntrace'];
            $Action[16]["Name"] = "Exits Destroyed";
            $Action[16]["Value"] = $adr['KOTeleporterExit'];
            $Action[17]["Name"] = "Feigned Deaths";
            $Action[17]["Value"] = $adr['player_feigndeath'];

            // Capture Related
            $Action[18]["Name"] = "Point Captured";
            $Action[18]["Value"] = $adr['CPCaptured'];
            $Action[19]["Name"] = "Capture Blocked";
            $Action[19]["Value"] = $adr['CPBlocked'];
            $Action[20]["Name"] = "Intel Captured";
            $Action[20]["Value"] = $adr['FileCaptured'];

            // Miscellenious
            $Action[21]["Name"] = "Sandviches Stolen";
            $Action[21]["Value"] = $adr['player_stealsandvich'];
            $Action[22]["Name"] = "People Extinguished";
            $Action[22]["Value"] = $adr['player_extinguished'];
            $Action[23]["Name"] = "Times Teleported";
            $Action[23]["Value"] = $adr['player_teleported'];


            $sql2 = "SELECT ItemIndex, COUNT(ItemIndex) as FOUND FROM founditems WHERE `STEAMID` LIKE '$steamid' GROUP BY ItemIndex";
            $result2 = mysql_query($sql2);
            $counter = 0;
            unset($WeapFound);
            while ($adr2 = mysql_fetch_array($result2)) {
                $counter++;
                $sqlitem = "SELECT * FROM items WHERE `ItemIndex` = '" . $adr2["ItemIndex"] . "';";
                $resultitem = mysql_query($sqlitem);
                if (!$resultitem) {
                    echo "Query $myquery failed: " . mysql_error();
                }

                while ($adritem = mysql_fetch_array($resultitem)) {
                    $WeapFound[$counter]["Name"] = $adritem['NAME'];
                    $WeapFound[$counter]["ImgUrl"] = $adritem['IMGURL'];
                    $WeapFound[$counter]["Found"] = $adr2['FOUND'];
                }
            }

            //Write Player Actions
            echo "<table width='250' border='0' cellpadding='0' cellspacing='0'>";
            echo "<tr bgcolor='#abccd6' valign='top'>";
            echo "<td colspan='2' align='center' class='coltitle tlarge'><a href=player.php?steamid=" . $steamid . ">" . $adr['NAME'] . "</td>";
            echo "</tr>";

            for ($i = 0; $i < count($Action); $i++) {
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

                echo "<tr>\r\n";

                $first = 1;
                if ($first == 1) {
                    echo "<td class='" . $tdclass . "'>" . $Action[$i]["Name"] . "</td>\r\n";
                }

                echo "<td class='" . $tdclass . "' align='right' bgcolor=''>" . $Action[$i]["Value"] . "</td>\r\n";
                echo "</tr>\r\n";

            }

            echo "</table>";
            $first = 2;

            //Found Items
            echo "<table style='' width='250' border='0' cellpadding='0' cellspacing='0'>";
            echo "<tr bgcolor='#abccd6' valign='top'>";
            echo "<td colspan='2' align='center' class='coltitle tlarge'>Items Found</td>";
            echo "</tr>";

            echo mysql_real_escape_string($Name);
            if (count($WeapFound) != 0) {
                $count = 0;
                echo "<tr><td class='row' align='center'  style='background-color:#585858;'>\r\n";
                for ($i = 0; $i < 84; $i++) {
                    if (isset($WeapFound[$i]["Name"])) {

                        $itemname = $WeapFound[$i]["Name"];
                        //echo trim(substr($itemname, 0, 4));
                        if (strtoupper(substr($itemname, 0, 3)) == "THE") {
                            $itemname = trim(substr($itemname, 3));
                        }

                        //echo "<span class='foundnr'><strong>".$WeapFound[$i]["Found"]."</strong></span><img title='".$WeapFound[$i]["Name"]."' alt='".$WeapFound[$i]["Name"]."' src='".$WeapFound[$i]["ImgUrl"]."'>";
                        echo "<div id='item' class='item' ><span class='foundnr'><strong>" . $WeapFound[$i]["Found"] . "</strong></span><a class='wiki' href='http://wiki.teamfortress.com/wiki/" . $itemname . "' target=_blanc><img width='62' height='49' title='" . $WeapFound[$i]["Name"] . "' alt='" . $WeapFound[$i]["Name"] . "' src='" . $WeapFound[$i]["ImgUrl"] . "' border=0 /></a></div>";
                        $count++;
                        if ($count > 2) {
                            $count = 0;
                            echo "<br>";
                        }
                    }
                }
                echo "</td></tr>\r\n";
            } else {
                echo "<tr><td>no items</td></tr>\r\n";
            }

            echo "</table>";
            echo "</td>";

        } //end if player information


        if (isset($_REQUEST['searchweapons'])) {
            unset($WeapKills);

            $sqlweapon = "SELECT DISTINCT NAME, WEAPON, IMAGE FROM `weapons`";
            $ergebnisweapon = mysql_query($sqlweapon);
            $weaponnr = 0;
            while ($w = mysql_fetch_array($ergebnisweapon)) {
                $WeapKills[$weaponnr]["Name"] = $w['NAME'];
                $WeapKills[$weaponnr]["Kills"] = $adr[$w['WEAPON']];
                $WeapKills[$weaponnr]["ImgUrl"] = $w['IMAGE'];
                $weaponnr++;
            }


            $MaxWeap = count($WeapKills);

            // MAKE THIS AN OPTION
            // Display Zero stats
            $DisplayZeroKills = 1;

            unset($KillIndex);
            // Make an array of all the kills, then sort the array descending
            For ($i = 0; $i < $MaxWeap; $i++) {
                $KillIndex[] = $WeapKills[$i]["Kills"];
            }
            //arsort($KillIndex);
            $rowcount++;

            echo "<table id='sortable_id" . $rowcount . "' width='250' style='' border='0' cellpadding='0' cellspacing='0'>";
            echo "<tr valign='top'>";
            echo "<td colspan=2 align='center' class='coltitle tlarge header'><a href=player.php?steamid=" . $steamid . ">" . $adr['NAME'] . "</a></td>";
            echo "</tr>";

            echo "<thead>";
            echo "<th width='50%' align='center' class='coltitle tlarge header'>Weapon</th>";
            echo "<th width='50%' align='center' class='coltitle tlarge header'>Kills</th>";
            echo "</thead>";

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

                $KILLCOUNT = $WeapKills[key($KillIndex)]["Kills"];
                $IMG = $WeapKills[key($KillIndex)]["ImgUrl"];
                $ALT = $WeapKills[key($KillIndex)]["Name"];
                next($KillIndex);
                if ($DisplayZeroKills == 0 && $KILLCOUNT == 0) {
                    // Do Nothing
                } else {
                    echo "<tr>";
                    echo "<td class='" . $tdclass . "' align='center' bgcolor='#000000'><img title='" . $ALT . "' alt='" . $ALT . "' src='" . $IMG . "'/><br><span class='weapontxt'>" . $ALT . "</span></td>";
                    echo "<td class='" . $tdclass . "' align='right' bgcolor=''>" . $KILLCOUNT . "</td>";
                    echo "</tr>";
                }

            }

            echo "</table>";


            echo "</td>";
        }


        if (isset($_REQUEST['searchtop10bestweapons'])) {
            unset($WeapKills);

            $sqlweapon = "SELECT DISTINCT NAME, WEAPON, IMAGE FROM `weapons`";
            $ergebnisweapon = mysql_query($sqlweapon);
            $weaponnr = 0;
            while ($w = mysql_fetch_array($ergebnisweapon)) {
                $WeapKills[$weaponnr]["Name"] = $w['NAME'];
                $WeapKills[$weaponnr]["Kills"] = $adr[$w['WEAPON']];
                $WeapKills[$weaponnr]["ImgUrl"] = $w['IMAGE'];
                $weaponnr++;
            }


            $MaxWeap = count($WeapKills);

            // MAKE THIS AN OPTION
            // Display Zero stats
            $DisplayZeroKills = 1;

            unset($KillIndex);
            // Make an array of all the kills, then sort the array descending
            For ($i = 0; $i < $MaxWeap; $i++) {
                $KillIndex[] = $WeapKills[$i]["Kills"];
            }
            arsort($KillIndex);
            $rowcount++;

            echo "<table id='sortable_id" . $rowcount . "' width='250' style='' border='0' cellpadding='0' cellspacing='0'>";
            echo "<tr valign='top'>";
            echo "<td colspan=2 align='center' class='coltitle tlarge header'><a href=player.php?steamid=" . $steamid . ">" . $adr['NAME'] . "</a></td>";
            echo "</tr>";

            echo "<thead>";
            echo "<th width='50%' align='center' class='coltitle tlarge header'>Weapon</th>";
            echo "<th width='50%' align='center' class='coltitle tlarge header'>Kills</th>";
            echo "</thead>";

            // Display Weapon data sorted by weapon kills
            For ($i = 0; $i < 10; $i++) {
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

                $KILLCOUNT = $WeapKills[key($KillIndex)]["Kills"];
                $IMG = $WeapKills[key($KillIndex)]["ImgUrl"];
                $ALT = $WeapKills[key($KillIndex)]["Name"];
                next($KillIndex);
                if ($DisplayZeroKills == 0 && $KILLCOUNT == 0) {
                    // Do Nothing
                } else {
                    echo "<tr>";
                    echo "<td class='" . $tdclass . "' align='center' bgcolor='#000000'><img title='" . $ALT . "' alt='" . $ALT . "' src='" . $IMG . "'/><br><span class='weapontxt'>" . $ALT . "</span></td>";
                    echo "<td class='" . $tdclass . "' align='right' bgcolor=''>" . $KILLCOUNT . "</td>";
                    echo "</tr>";
                }

            }
            echo "</table>";
            echo "</td>";
        }


        if (isset($_REQUEST['searchitems'])) {
            //echo 'searchitems';
            echo "<table width='250' border='0' cellpadding='0' cellspacing='0'>";
            echo "<tr bgcolor='#abccd6' valign='top'>";
            echo "<td colspan='2' align='center' class='coltitle tlarge'><a href=player.php?steamid=" . $steamid . ">" . $adr['NAME'] . "</td>";
            echo "</tr>";

            echo "<thead>";
            echo "<th width='50%' align='center' class='coltitle tlarge header'>Items</th>";
            echo "<th width='50%' align='center' class='coltitle tlarge header'>Found</th>";
            echo "</thead>";

            $sqlfounditems = "SELECT ITEM, NAME, IMGURL FROM items";
            $ergebnisitems = mysql_query($sqlfounditems);
            $tdclass = "row_alt";
            while ($adritems = mysql_fetch_array($ergebnisitems)) {

                if ($tdclass == "row") {
                    $tdclass = "row_alt";
                } elseif ($tdclass == "row_alt") {
                    $tdclass = "row";
                }

                $itemname = $adritems['NAME'];
                //echo trim(substr($itemname, 0, 4));
                if (strtoupper(substr($itemname, 0, 3)) == "THE") {
                    $itemname = trim(substr($itemname, 3));
                }


                $sqlitemsfound = "SELECT count(*) as FOUND FROM `founditems` WHERE ItemIndex = '" . $adritems['ItemIndex'] . "' and STEAMID = '" . $steamid . "';";
                $ergebnisitemsfound = mysql_query($sqlitemsfound);
                while ($adritemsfound = mysql_fetch_array($ergebnisitemsfound)) {
                    echo "<tr>";
                    echo "<td class='" . $tdclass . "'><CENTER><FONT SIZE=1.5>" . $adritems['NAME'] . "</FONT><br /><a class='wiki' href='http://wiki.teamfortress.com/wiki/" . $itemname . "' target=_blanc><img src='" . $adritems['IMGURL'] . "' border=0 /></a></CENTER></td>";
                    echo "<td class='" . $tdclass . "'>" . $adritemsfound['FOUND'] . "</td>";
                    echo "</tr>";
                }
            }

            echo "</table>";
            echo "</td>";
        }
    }
}


if ($playercount <= 1) {
    echo "<strong><a href='javascript:history.go(-1)'>Please select more players!!</a></STRONG>";
}

?>

</tr></table>

<?php echo $mainfooter ?>

</body>
</html>
