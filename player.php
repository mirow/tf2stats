<?php
header('Content-Type: text/html; charset=UTF-8');

include("inc/dbconnect.php");
include("inc/footer.inc.php");

include("swf/open-flash-chart.php");

if (extension_loaded('bcmath')) {
    $bcmathloaded = true;
} else {
    $bcmathloaded = false;
}

$steamid = "";
$countrycode = "";
$cityname = "";
$countryname = "";
$replaytitle = "";
$replayurl = "";
$deletereplay = "";
$steamid = "";

if (isset($_GET['steamid'])) {
    $steamid = mysql_real_escape_string($_GET["steamid"]);
}
if (isset($_GET['cc'])) {
    $countrycode = mysql_real_escape_string($_GET["cc"]);
}
if (isset($_GET['c'])) {
    $cityname = mysql_real_escape_string($_GET["c"]);
}
if (isset($_GET['cn'])) {
    $countryname = mysql_real_escape_string($_GET["cn"]);
}
if (isset($_GET['rt'])) {
    $replaytitle = mysql_real_escape_string($_GET["rt"]);
}
if (isset($_GET['ru'])) {
    $replayurl = mysql_real_escape_string($_GET["ru"]);
}
if (isset($_GET['deletereplay'])) {
    $deletereplay = mysql_real_escape_string($_GET["deletereplay"]);
}

if ($bcmathloaded) {
    function steam2friend($steam_id)
    {
        $steam_id = strtolower($steam_id);
        if (substr($steam_id, 0, 7) == 'steam_0') {
            $tmp = explode(':', $steam_id);
            if ((count($tmp) == 3) && is_numeric($tmp[1]) && is_numeric($tmp[2])) {
                return bcadd((($tmp[2] * 2) + $tmp[1]), '76561197960265728');
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getMostEffectiveClasses($player)
    {
        $scoutstats = 0;
    }
}

$sql = "SELECT * FROM `Player` WHERE `STEAMID` LIKE '$steamid'";
$ergebnis = mysql_query($sql);
if (!$ergebnis) {
    echo "Query $myquery failed: " . mysql_error();
}

while ($adr = mysql_fetch_array($ergebnis)) {

    $timestamp = $adr['LASTONTIME'];
    if ($timestamp == 0) {
        $laststr = "never";
    } else {
        $datum = date("d.m.Y", $timestamp);
        $uhrzeit = date("H:i", $timestamp);
        $laststr = $datum . " <br /> ";
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

    if ($datetimeformat == "0") {
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
    } else {
        if ($datetimeformat == "1") {
            $laststr = $datum . " - " . $uhrzeit;
            $laststr = str_replace(".", "-", $laststr);
        }
    }

    $checktime = time() - (4 * 7 * 24 * 60 * 60);
    $sqlhistory2 = "SELECT * FROM `history` WHERE `STEAMID` LIKE '" . $steamid . "' AND ONTIME > " . $checktime . " ORDER BY ONTIME ASC LIMIT 0 , 5";
    $ergebnishistory2 = mysql_query($sqlhistory2);

    $timearr = array();
    $pointsarr = array();
    $counteradrhistory2 = 0;

    $highestontime = 0;
    $highestpoints = 0;
    $highestpoints2 = 0;
    while ($adrhistory2 = mysql_fetch_array($ergebnishistory2)) {

        if ($highestontime < $adrhistory2['ONTIME']) {
            $highestontime = $adrhistory2['ONTIME'];
            $highestpoints = $adrhistory2['POINTS'];
        }

        $counteradrhistory2++;
        $pointsarr[$counteradrhistory2] = $adrhistory2['POINTS'];
        $timearr[$counteradrhistory2] = $adrhistory2['ONTIME'];
    }

    if ($highestpoints <> $adr['POINTS']) {
        //insert record
        $queryinserthistory = "INSERT INTO history (STEAMID, POINTS, ONTIME) VALUES ('" . $adr['STEAMID'] . "', '" . $adr['POINTS'] . "', '" . $adr['LASTONTIME'] . "')";
        mysql_query($queryinserthistory);

        $counteradrhistory2++;
        $pointsarr[$counteradrhistory2] = $adr['POINTS'];
        $timearr[$counteradrhistory2] = $adr['LASTONTIME'];

        if ($highestpoints < $adr['POINTS']) {
            $highestpoints = $adr['POINTS'];
        }
    }

    $animation = 'pop';
    $delay = 0.5;
    $cascade = 1;

    $i2 = 0;
    $data = array();
    $x_labels = array();
    foreach ($pointsarr as $pointsar) {
        $i2++;

        $barvalue = new bar_value($pointsar);
        $barvalue->set_tooltip($pointsar);
        $data[] = $barvalue;

        $x_labels[] = date("d/m", $timearr[$i2]);
    }

    $bar = new bar_sketch('#EEA411', '#567300', 5);
    $bar->set_values($data);
    $bar->set_on_show(new bar_on_show($animation, $cascade, $delay));

    $y = new y_axis_right();

//				$y->set_range( $highestpoints-($highestpoints/4), $highestpoints+($highestpoints/5) );
    $y->set_range(0, $highestpoints + ($highestpoints / 5));

    $xlabel = new x_axis_labels();
    $xlabel->set_vertical();
    $xlabel->set_labels($x_labels);

    $x = new x_axis();
    $x->set_labels_from_array($x_labels);
    $x->set_labels($xlabel);

    $chart = new open_flash_chart();
    //$chart->set_title( $title );
    $chart->add_element($bar);
    $chart->set_y_axis($y);
    $chart->set_x_axis($x);


    $steam_id = strtolower($steamid);
    if (substr($steam_id, 0, 7) == 'steam_0') {
        $tmp = explode(':', $steam_id);
    }
    if ((count($tmp) == 3) && is_numeric($tmp[1]) && is_numeric($tmp[2])) {

        $steamidCalc = ($tmp[2] * 2) + $tmp[1]; //Work out step 1
        $calckey = '1197960265728'; //Second bit of the magic number
        $pre = '7656'; //First bit of the magic number

        $steamcid = number_format(
            $steamidCalc + $calckey,
            0,
            '.',
            ''
        ); //works out the ending of the steam community ID, number_format to fix a weird bug that made it use scientific notation
        $steamlink = "http://steamcommunity.com/profiles/";
    };

    if ($adr['IPAddress'] != "") {

        $ip = $_SERVER['REMOTE_ADDR'];

        if ($countrycode != "") {
            if ($adr['IPAddress'] == $ip) {
                //update location information
                $updatekwerie = "UPDATE location SET COUNTRYCODE = '" . $countrycode . "', COUNTRYNAME = '" . $countryname . "', CITYNAME = '" . $cityname . "' WHERE STEAMID = '" . $steamid . "'";
                //echo "running upate query: ".$updatekwerie;
                mysql_query($updatekwerie);
            }
        }

        if ($replaytitle != "" && $replayurl != "") {
            //echo $replayurl." " .$replaytitle;
            if ($adr['IPAddress'] == $ip && $deletereplay != 'yes') {
                $sqlreplaycheck = "SELECT * FROM `replay` WHERE `URL` LIKE '$replayurl'";
                $ergebnissqlreplaycheck = mysql_query($sqlreplaycheck);
                if (!$ergebnissqlreplaycheck) {
                    echo "Query $myquery failed: " . mysql_error();
                }

                $breplaycheck = false;
                while ($replaycheck = mysql_fetch_array($ergebnissqlreplaycheck)) {
                    echo "This youtube link was already saved.";
                    $breplaycheck = true;
                }

                $sqlreplaycheckcount = "SELECT Count(*) as COUNT FROM `replay` WHERE `STEAMID` = '$steamid'";
                //echo $sqlreplaycheckcount;
                $ergebnissqlreplaycheckcount = mysql_query($sqlreplaycheckcount);
                if (!$ergebnissqlreplaycheckcount) {
                    echo "Query $myquery failed: " . mysql_error();
                }

                while ($replaycheck = mysql_fetch_array($ergebnissqlreplaycheckcount)) {

                    if ($replaycheck['COUNT'] >= $showmaxnrofreplaymovies) {
                        echo "You've reached the max number of replay movies. Please remove some before adding new replay's.";
                        $breplaycheck = true;
                    }
                }

                if ($breplaycheck == false) {
                    //Insert replay movie
                    $updatereplaykwerie = "INSERT INTO replay ( STEAMID, IP, TITLE, URL ) VALUES ( '" . $steamid . "', '" . $ip . "', '" . $replaytitle . "', '" . $replayurl . "' )";
                    //echo $updatereplaykwerie;
                    mysql_query($updatereplaykwerie);
                }
            } else {
                if ($deletereplay == 'yes') {
                    //Insert replay movie
                    $updatedeletereplaykwerie = "DELETE FROM replay WHERE STEAMID = '" . $steamid . "' AND URL = '" . $replayurl . "'";
                    //echo $updatedeletereplaykwerie;
                    mysql_query($updatedeletereplaykwerie);
                }
            }
        }

        //get location from steamid
        $sqlregion = "SELECT COUNTRYCODE, COUNTRYNAME, CITYNAME, IP FROM location WHERE STEAMID = '" . $steamid . "'";
        $ergebnisregion = mysql_query($sqlregion);
        $regionfound = "N";
        $sameip = "N";
        while ($region = mysql_fetch_array($ergebnisregion)) {
            $countrycode = $region['COUNTRYCODE'];
            $countryname = $region['COUNTRYNAME'];
            $cityname = $region['CITYNAME'];
            $regionfound = "Y";

            if ($adr['IPAddress'] == $ip) {
                $sameip = "Y";
            }
        }
//http://api.ipinfodb.com/


        /*						if($regionfound == "N")
                                {
                                        $ipAddr  = $adr['IPAddress'];
                                        $geokey = "2683f23959fc1eae60a5eee41d18807152c89e976d32cdebc80b05cfb3469053";


                                        //no location found, get information and update database
                                        ip2long($ipAddr)== -1 || ip2long($ipAddr) === false ? trigger_error("Invalid IP", E_USER_ERROR) : "";

                                        $geourl = "http://api.ipinfodb.com/v2/ip_query.php?key=".$geokey."&ip=".$ipAddr."&timezone=false";

                                        //echo $geourl;

                                        $xml = file_get_contents($geourl);
                                        preg_match("@<CountryCode>(.*?)</CountryCode>@si",$xml,$matchecode);
                                        $countrycode = $matchecode[1];

        //echo $countrycode;

                                        preg_match("@<CountryName>(.*?)</CountryName>@si",$xml,$matchename);
                                        $countryname = $matchename[1];

        //echo $countryname;

                                        preg_match("@<City>(.*?)</City>@si",$xml,$matchecity);
                                        $cityname = $matchecity[1];
        //echo $cityname;

                                        //insert in database
                                        $queryinsertlocation = "INSERT INTO location (STEAMID, IP, COUNTRYCODE, COUNTRYNAME, CITYNAME) VALUES ('".$adr['STEAMID']."', '".$adr['IPAddress']."', '".$countrycode."', '".$countryname."', '".$cityname."')";
                                        mysql_query($queryinsertlocation);
                                }
        */
    }

    $key = $GoogleApi;
    $address = urlencode($cityname . ", " . $countryname);

    $sturl = 'http://maps.google.com/maps/geo?q=' . $address . '&output=csv&key=' . $key;

    $ch = curl_init($sturl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $res = curl_exec($ch);
    $resinfo = curl_getinfo($ch);
    curl_close($ch);
    $res = explode(",", $res);
    $latitude = $res[2];
    $longitude = $res[3];


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
    $Action[6]["Name"] = "Healing";
    $Action[6]["Value"] = $adr['MedicHealing'];
    // Engineer Related
    $Action[7]["Name"] = "Built Object - Sentrygun";
    $Action[7]["Value"] = $adr['BuildSentrygun'];
    $Action[8]["Name"] = "Built Object - Dispenser";
    $Action[8]["Value"] = $adr['BuildDispenser'];
    $Action[9]["Name"] = "Entrances Built";
    $Action[9]["Value"] = $adr['BOTeleporterentrace'];
    $Action[10]["Name"] = "Exits Built";
    $Action[10]["Value"] = $adr['BOTeleporterExit'];
    $Action[11]["Name"] = "Sappers Removed";
    $Action[11]["Value"] = $adr['KOSapper'];
    // Spy Related
    $Action[12]["Name"] = "Backstabs";
    $Action[12]["Value"] = $adr['K_backstab'];
    $Action[13]["Name"] = "Sappers Placed";
    $Action[13]["Value"] = $adr['BOSapper'];
    $Action[14]["Name"] = "Sentries Destroyed";
    $Action[14]["Value"] = $adr['KOSentrygun'];
    $Action[15]["Name"] = "Dispensers Destroyed";
    $Action[15]["Value"] = $adr['KODispenser'];
    $Action[16]["Name"] = "Entrances Destroyed";
    $Action[16]["Value"] = $adr['KOTeleporterEntrace'];
    $Action[17]["Name"] = "Exits Destroyed";
    $Action[17]["Value"] = $adr['KOTeleporterExit'];
    $Action[18]["Name"] = "Feigned Deaths";
    $Action[18]["Value"] = $adr['player_feigndeath'];
    // Capture Related
    $Action[19]["Name"] = "Points Captured";
    $Action[19]["Value"] = $adr['CPCaptured'];
    $Action[20]["Name"] = "Captures Blocked";
    $Action[20]["Value"] = $adr['CPBlocked'];
    $Action[21]["Name"] = "Intel Captured";
    $Action[21]["Value"] = $adr['FileCaptured'];
    // Miscellenious
    $Action[22]["Name"] = "Sandviches Stolen";
    $Action[22]["Value"] = $adr['player_stealsandvich'];
    $Action[23]["Name"] = "People Extinguished";
    $Action[23]["Value"] = $adr['player_extinguished'];
    $Action[24]["Name"] = "Times Teleported";
    $Action[24]["Value"] = $adr['player_teleported'];
    $Action[25]["Name"] = "Total Players Teleported";
    $Action[25]["Value"] = $adr['TotalPlayersTeleported'];

    if ($showbestclasses == 1) {

        $firstkills = 0;
        $secondkills = 0;
        $thirdkills = 0;
        $maxkills = 0;
        $firstclass = "";
        $secondclass = "";
        $thirdclass = "";

        if ($adr['SoldierKills'] > $firstkills) {
            $thirdkills = $secondkills;
            $thirdclass = $secondclass;
            $secondkills = $firstkills;
            $secondclass = $firstclass;
            $firstkills = $adr['SoldierKills'];
            $firstclass = "Soldier";
        } else {
            if ($adr['SoldierKills'] > $secondkills) {
                $thirdkills = $secondkills;
                $thirdclass = $secondclass;
                $secondkills = $adr['SoldierKills'];
                $secondclass = "Soldier";
            } else {
                if ($adr['SoldierKills'] > $thirdkills) {
                    $thirdkills = $adr['SoldierKills'];
                    $thirdclass = "Soldier";
                }
            }
        }

        //Check heavy
        if ($adr['HeavyKills'] > $firstkills) {
            $thirdkills = $secondkills;
            $thirdclass = $secondclass;
            $secondkills = $firstkills;
            $secondclass = $firstclass;
            $firstkills = $adr['HeavyKills'];
            $firstclass = "Heavy";
        } else {
            if ($adr['HeavyKills'] > $secondkills) {
                $thirdkills = $secondkills;
                $thirdclass = $secondclass;
                $secondkills = $adr['HeavyKills'];
                $secondclass = "Heavy";
            } else {
                if ($adr['HeavyKills'] > $thirdkills) {
                    $thirdkills = $adr['HeavyKills'];
                    $thirdclass = "Heavy";
                }
            }
        }


//Check Engineer
        if ($adr['EngiKills'] > $firstkills) {
            $thirdkills = $secondkills;
            $thirdclass = $secondclass;
            $secondkills = $firstkills;
            $secondclass = $firstclass;
            $firstkills = $adr['EngiKills'];
            $firstclass = "Engineer";
        } else {
            if ($adr['EngiKills'] > $secondkills) {
                $thirdkills = $secondkills;
                $thirdclass = $secondclass;
                $secondkills = $adr['EngiKills'];
                $secondclass = "Engineer";
            } else {
                if ($adr['EngiKills'] > $thirdkills) {
                    $thirdkills = $adr['EngiKills'];
                    $thirdclass = "Engineer";
                }
            }
        }

//Check Pyro
        if ($adr['PyroKills'] > $firstkills) {
            $thirdkills = $secondkills;
            $thirdclass = $secondclass;
            $secondkills = $firstkills;
            $secondclass = $firstclass;
            $firstkills = $adr['PyroKills'];
            $firstclass = "Pyro";
        } else {
            if ($adr['PyroKills'] > $secondkills) {
                $thirdkills = $secondkills;
                $thirdclass = $secondclass;
                $secondkills = $adr['PyroKills'];
                $secondclass = "Pyro";
            } else {
                if ($adr['PyroKills'] > $thirdkills) {
                    $thirdkills = $adr['PyroKills'];
                    $thirdclass = "Pyro";
                }
            }
        }

//Check Scout
        if ($adr['ScoutKills'] > $firstkills) {
            $thirdkills = $secondkills;
            $thirdclass = $secondclass;
            $secondkills = $firstkills;
            $secondclass = $firstclass;
            $firstkills = $adr['ScoutKills'];
            $firstclass = "Scout";
        } else {
            if ($adr['ScoutKills'] > $secondkills) {
                $thirdkills = $secondkills;
                $thirdclass = $secondclass;
                $secondkills = $adr['ScoutKills'];
                $secondclass = "Scout";
            } else {
                if ($adr['ScoutKills'] > $thirdkills) {
                    $thirdkills = $adr['ScoutKills'];
                    $thirdclass = "Scout";
                }
            }
        }

//Check Medic
        if ($adr['MedicKills'] > $firstkills) {
            $thirdkills = $secondkills;
            $thirdclass = $secondclass;
            $secondkills = $firstkills;
            $secondclass = $firstclass;
            $firstkills = $adr['MedicKills'];
            $firstclass = "Medic";
        } else {
            if ($adr['MedicKills'] > $secondkills) {
                $thirdkills = $secondkills;
                $thirdclass = $secondclass;
                $secondkills = $adr['MedicKills'];
                $secondclass = "Medic";
            } else {
                if ($adr['MedicKills'] > $thirdkills) {
                    $thirdkills = $adr['MedicKills'];
                    $thirdclass = "Medic";
                }
            }
        }


//Check Sniper
        if ($adr['SniperKills'] > $firstkills) {
            $thirdkills = $secondkills;
            $thirdclass = $secondclass;
            $secondkills = $firstkills;
            $secondclass = $firstclass;
            $firstkills = $adr['SniperKills'];
            $firstclass = "Sniper";
        } else {
            if ($adr['SniperKills'] > $secondkills) {
                $thirdkills = $secondkills;
                $thirdclass = $secondclass;
                $secondkills = $adr['SniperKills'];
                $secondclass = "Sniper";
            } else {
                if ($adr['SniperKills'] > $thirdkills) {
                    $thirdkills = $adr['SniperKills'];
                    $thirdclass = "Sniper";
                }
            }
        }


//Check Sniper
        if ($adr['SniperKills'] > $firstkills) {
            $thirdkills = $secondkills;
            $thirdclass = $secondclass;
            $secondkills = $firstkills;
            $secondclass = $firstclass;
            $firstkills = $adr['SniperKills'];
            $firstclass = "Sniper";
        } else {
            if ($adr['SniperKills'] > $secondkills) {
                $thirdkills = $secondkills;
                $thirdclass = $secondclass;
                $secondkills = $adr['SniperKills'];
                $secondclass = "Sniper";
            } else {
                if ($adr['SniperKills'] > $thirdkills) {
                    $thirdkills = $adr['SniperKills'];
                    $thirdclass = "Sniper";
                }
            }
        }

//Check Spy
        if ($adr['SpyKills'] > $firstkills) {
            $thirdkills = $secondkills;
            $thirdclass = $secondclass;
            $secondkills = $firstkills;
            $secondclass = $firstclass;
            $firstkills = $adr['SpyKills'];
            $firstclass = "Spy";
        } else {
            if ($adr['SpyKills'] > $secondkills) {
                $thirdkills = $secondkills;
                $thirdclass = $secondclass;
                $secondkills = $adr['SpyKills'];
                $secondclass = "Spy";
            } else {
                if ($adr['SpyKills'] > $thirdkills) {
                    $thirdkills = $adr['SpyKills'];
                    $thirdclass = "Spy";
                }
            }
        }

//Check Demo
        if ($adr['DemoKills'] > $firstkills) {
            $thirdkills = $secondkills;
            $thirdclass = $secondclass;
            $secondkills = $firstkills;
            $secondclass = $firstclass;
            $firstkills = $adr['DemoKills'];
            $firstclass = "Demoman";
        } else {
            if ($adr['DemoKills'] > $secondkills) {
                $thirdkills = $secondkills;
                $thirdclass = $secondclass;
                $secondkills = $adr['DemoKills'];
                $secondclass = "Demoman";
            } else {
                if ($adr['DemoKills'] > $thirdkills) {
                    $thirdkills = $adr['DemoKills'];
                    $thirdclass = "Demoman";
                }
            }
        }
    }
    ?>

    <html xmlns="http://www.w3.org/1999/xhtml" contnet=1>
    <head>
        <title><?php echo $adr['NAME'] ?> - <?php echo $clanname; ?></title>
        <META http-equiv="Content-Type" content="text/html; charset=utf-8">

        <link rel=stylesheet type=text/css href=styles/default.css>
        <link type="text/css" media="screen" rel="stylesheet" href="styles/colorbox.css"/>
        <link type="text/css" href="styles/menu.css" rel="stylesheet"/>

        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/menu.js"></script>
        <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>

        <script type="text/javascript" src="js/json2.js"></script>
        <script type="text/javascript" src="js/swfobject.js"></script>
        <script
            type="text/javascript">swfobject.embedSWF("swf/open-flash-chart.swf", "my_chart", "340", "250", "9.0.0");</script>


        <script>
            $(document).ready(function () {
                $(".wiki").colorbox({width: "90%", height: "90%", iframe: true});

                $('#google').hide();
                $('#google2').hide();
                $('#changegoogle').hide();

                $('#googleshow').click(function () {
                    $('#google').toggle(400);
                    $('#google2').toggle(400);
                    return false;
                });

                $('#changegoogleshow').click(function () {
                    $('#changegoogle').toggle(400);
                    return false;
                });

                $('#addreplay').hide();

                $('#addreplayshow').click(function () {
                    $('#addreplay').toggle(400);
                    return false;
                });
            });

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
    <?php include("inc/headerlinks.alt.inc.php"); ?>

    <?php
    $sqlweapon = "SELECT DISTINCT NAME, WEAPON, IMAGE FROM `weapons`";
    $ergebnisweapon = mysql_query($sqlweapon);
    if (!$ergebnisweapon) {
        echo "Query $myquery failed: " . mysql_error();
    }
    $weaponnr = 0;
    while ($w = mysql_fetch_array($ergebnisweapon)) {
        $WeapKills[$weaponnr]["Name"] = $w['NAME'];
        $WeapKills[$weaponnr]["Kills"] = $adr[$w['WEAPON']];
        $WeapKills[$weaponnr]["ImgUrl"] = $w['IMAGE'];
        $weaponnr++;
    }

    $MaxWeap = count($WeapKills);
    $DisplayZeroKills = 0;

    // Make an array of all the kills, then sort the array descending
    For ($i = 0; $i < $MaxWeap; $i++) {
        $KillIndex[] = $WeapKills[$i]["Kills"];
    }
    arsort($KillIndex);

    ?>

    <div class="bump box">
    <table width="250" style="" border="0" cellpadding="0" cellspacing="0">
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

            $KILLCOUNT = $WeapKills[key($KillIndex)]["Kills"];
            $IMG = $WeapKills[key($KillIndex)]["ImgUrl"];
            $ALT = $WeapKills[key($KillIndex)]["Name"];
            next($KillIndex);
            if ($DisplayZeroKills == 0 && $KILLCOUNT == 0) {
                // Do Nothing
            } else {
                echo "<tr>";
                echo "<td class='" . $tdclass . "' align='center' bgcolor='#000000'><img title='" . $ALT . "' style='' alt='" . $ALT . "' src='" . $IMG . "'/><br><span class='weapontxt'>" . $ALT . "</span></td>";
                echo "<td class='" . $tdclass . "' align='right' bgcolor=''>" . $KILLCOUNT . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
    </td>
    <td VALIGN='top' style="padding:20px">

    <table style="" width="610" border="0" cellpadding="0" cellspacing="0">
        <tr bgcolor="#abccd6" valign="top">
            <td colspan="2" align="center" class="coltitle tlarge" id="googleshow">Location <img
                    src="images/arrows/icon_collapse.png"/></td>
        </tr>
        <!--
            <tr bgcolor="#abccd6" valign="top">
              <td colspan="2" id="google" align="center" class="coltitle tlarge google " style="height:290px;">
	              	<table width="100%" style="padding:0px;"><tr><td style="padding:0px;">
	              		<img id="google" class="google" style="float:left;" src="http://maps.google.com/maps/api/staticmap?key=<?php echo $key ?>&size=300x280&maptype=satellite&markers=color:green|<?php echo $latitude ?>,<?php echo $longitude ?>&zoom=<?php echo $zoom1 ?>&sensor=false" title="Google Maps Api" />
	              		</td><td style="padding:0px;">
	              		<img id="google" class="google" style="float:right;" src="http://maps.google.com/maps/api/staticmap?key=<?php echo $key ?>&size=300x280&maptype=satellite&markers=color:green|<?php echo $latitude ?>,<?php echo $longitude ?>&zoom=<?php echo $zoom2 ?>&sensor=false" title="Google Maps Api" />
	              		</td></tr>
	              	</table>
            	</td>
            </tr>
-->
        <?php
        if ($sameip == "Y") {
            echo '<tr bgcolor="#abccd6" id="google2" class="google2" valign="top"><td colspan="2" align="center" class="coltitle tlarge changegoogleshow"><a href="#" id="changegoogleshow" class="changegoogleshow">Change location information</a>';
            echo '<form name="input" action="player.php" method="get">';
            echo '<table id="changegoogle" class="changegoogle">';
            echo '<tr><td>Country code:</td><td><input type="text" name="cc" value="' . $countrycode . '" /></td></tr>';
            echo '<tr><td>Country name:</td><td><input type="text" name="cn" value="' . $countryname . '" /></td></tr>';
            echo '<tr><td>City name:</td><td><input type="text" name="c" value="' . $cityname . '" />';
            echo '<input type="hidden" name="steamid" value="' . $steamid . '"></td></tr>'; //add hidden field with steamid information
            echo '<tr><td colspan=2><input type="submit" value="Save" /></td></tr>';
            echo '</table>';
            echo '</td></tr>';
        }
        ?>
    </table>

    <table style="" width="600" border="0" cellpadding="0" cellspacing="0">
        <tr valign="top">
            <td align="left">

                <br/>

                <div class=box align="center">
                    <table cellpadding=0 cellspacing=0 class=flatrow border="0" width="345">
                        <tr bgcolor="" valign="top">
                            <td colspan="2" align="center" class="coltitle tlarge">Player Profile</td>
                        </tr>
                        <tr>
                            <td class=toprow width="112" align="left">Name:</td>

                            <td class=toprow width="" align="left">
                                <div align="right">
                                    <?php
                                    if (file_exists("images/flags/" . strtolower($countrycode) . ".png")) {
                                        ?>

                                        <img src="images/flags/<?php echo strtolower($countrycode) ?>.png"/>
                                    <?php
                                    }
                                    $plrname = $adr['NAME'];
                                    echo $plrname;
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class=row align="left">Steam ID:</td>
                            <td class=row align="left">
                                <div align="right">
                                    <?php echo $adr['STEAMID'] ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class=row_alt align="left">Last Connect:</td>

                            <td class="row_alt" align="left">
                                <div align="right">
                                    <?php echo $laststr; ?>
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
                        </tr>
                        <tr bgcolor="">
                            <td class=row align="left" colspan="2">
                                <div class="addthis_toolbox addthis_default_style">
                                    <a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c9c485965ec3832"
                                       class="addthis_button_compact">Share</a>
                                    <span class="addthis_separator">|</span>
                                    <a class="addthis_button_preferred_1"></a>
                                    <a class="addthis_button_preferred_2"></a>
                                    <a class="addthis_button_preferred_3"></a>
                                    <a class="addthis_button_preferred_4"></a>
                                </div>
                                <script type="text/javascript"
                                        src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c9c485965ec3832"></script>
                            </td>
                        </tr>

                </div>
            </td>
        </tr>
    </table>
    <br/>
    <table style="" width="345" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr valign="top">
            <td valign=top colspan="2" align="center" class="tlarge coltitle">Player History</td>
        </tr>
        <tr class="firstchart">
            <td class=row style="border: 1px solid #cccccc;" colspan="2">
                <div id="my_chart"></div>
            </td>
        </tr>
    </table>

    <br/>

    <table style="" width="345" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr bgcolor="#abccd6" valign="bottom">
            <td colspan="2" align="center" class="coltitle tlarge">Actions</td>
        </tr>
        <?php
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
            echo "<td class='" . $tdclass . "'>" . $Action[$i]["Name"] . "</td>\r\n";
            echo "<td class='" . $tdclass . "' align='right' bgcolor=''>" . $Action[$i]["Value"] . "</td>\r\n";
            echo "</tr>\r\n";

        }
        ?>

    </table>
    <br/>

    <table style="" width="345" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr bgcolor="#abccd6" valign="bottom">
            <td colspan="4" align="center" class="coltitle tlarge">Class information</td>
        </tr>
        <tr bgcolor="#abccd6" valign="bottom">
            <td align="center" class="coltitle tlarge">Class</td>
            <td align="center" class="coltitle tlarge">Kills</td>
            <td align="center" class="coltitle tlarge">Deaths</td>
            <td align="center" class="coltitle tlarge">K/D Ratio</td>
        </tr>

        <tr>
            <td class='row_alt'>Pyro</td>
            <td class='row_alt'><?php echo $adr['PyroKills'] ?></td>
            <td class='row_alt'><?php echo $adr['PyroDeaths'] ?></td>
            <td class='row_alt'>
                <?php
                if ($adr['PyroDeaths'] != 0) {
                    echo round($adr['PyroKills'] / $adr['PyroDeaths'], 2);
                } else {
                    echo "0";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class='row'>Soldier</td>
            <td class='row'><?php echo $adr['SoldierKills'] ?></td>
            <td class='row'><?php echo $adr['SoldierDeaths'] ?></td>
            <td class='row'>
                <?php
                if ($adr['SoldierDeaths'] != 0) {
                    echo round($adr['SoldierKills'] / $adr['SoldierDeaths'], 2);
                } else {
                    echo "0";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class='row_alt'>Heavy</td>
            <td class='row_alt'><?php echo $adr['HeavyKills'] ?>
            <td class='row_alt'><?php echo $adr['HeavyDeaths'] ?></td>
            <td class='row_alt'>
                <?php
                if ($adr['HeavyDeaths'] != 0) {
                    echo round($adr['HeavyKills'] / $adr['HeavyDeaths'], 2);
                } else {
                    echo "0";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class='row'>Engineer</td>
            <td class='row'><?php echo $adr['EngiKills'] ?></td>
            <td class='row'><?php echo $adr['EngiDeaths'] ?></td>
            <td class='row'>
                <?php
                if ($adr['EngiDeaths'] != 0) {
                    echo round($adr['EngiKills'] / $adr['EngiDeaths'], 2);
                } else {
                    echo "0";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class='row_alt'>Scout</td>
            <td class='row_alt'><?php echo $adr['ScoutKills'] ?></td>
            <td class='row_alt'><?php echo $adr['ScoutDeaths'] ?></td>
            <td class='row_alt'>
                <?php
                if ($adr['ScoutDeaths'] != 0) {
                    echo round($adr['ScoutKills'] / $adr['ScoutDeaths'], 2);
                } else {
                    echo "0";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class='row'>Medic</td>
            <td class='row'><?php echo $adr['MedicKills'] ?></td>
            <td class='row'><?php echo $adr['MedicDeaths'] ?></td>
            <td class='row'>
                <?php
                if ($adr['MedicDeaths'] != 0) {
                    echo round($adr['MedicKills'] / $adr['MedicDeaths'], 2);
                } else {
                    echo "0";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class='row_alt'>Sniper</td>
            <td class='row_alt'><?php echo $adr['SniperKills'] ?></td>
            <td class='row_alt'><?php echo $adr['SniperDeaths'] ?></td>
            <td class='row_alt'>
                <?php
                if ($adr['SniperDeaths'] != 0) {
                    echo round($adr['SniperKills'] / $adr['SniperDeaths'], 2);
                } else {
                    echo "0";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class='row'>Spy</td>
            <td class='row'><?php echo $adr['SpyKills'] ?></td>
            <td class='row'><?php echo $adr['SpyDeaths'] ?></td>
            <td class='row'>
                <?php
                if ($adr['SpyDeaths'] != 0) {
                    echo round($adr['SpyKills'] / $adr['SpyDeaths'], 2);
                } else {
                    echo "0";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class='row_alt'>Demoman</td>
            <td class='row_alt'><?php echo $adr['DemoKills'] ?></td>
            <td class='row_alt'><?php echo $adr['DemoDeaths'] ?></td>
            <td class='row_alt'>
                <?php
                if ($adr['DemoDeaths'] != 0) {
                    echo round($adr['DemoKills'] / $adr['DemoDeaths'], 2);
                } else {
                    echo "0";
                }
                ?>
            </td>
        </tr>

    </table>
    <br/>

    <table style="" width="345" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr bgcolor="#abccd6" valign="bottom">
            <td colspan="4" align="center" class="coltitle tlarge">Replay Movies:</td>
        </tr>

        <?php
        $sqlreplay = "SELECT URL, TITLE FROM `replay` WHERE `STEAMID` LIKE '$steamid'";
        $ergebnisreplay = mysql_query($sqlreplay);
        if (!$ergebnisreplay) {
            echo "Query $myquery failed: " . mysql_error();
        }

        while ($replaylist = mysql_fetch_array($ergebnisreplay)) {


            ?>
            <tr>
                <td class='row_alt'>

                    <?php

                    echo "<object width='320' height='195'><param name='movie' value='http://www.youtube.com/v/" . $replaylist['URL'] . "?fs=1&amp;hl=nl_NL&amp;rel=0'></param><param name='allowFullScreen' value='true'></param>
	<param name='allowscriptaccess' value='always'></param><embed src='http://www.youtube.com/v/" . $replaylist['URL'] . "?fs=1&amp;hl=nl_NL&amp;rel=0' type='application/x-shockwave-flash' width='320' height='195'
	 allowscriptaccess='always' allowfullscreen='true'></embed></object>";

                    if ($sameip == "Y") {
                        echo '<a href="player.php?steamid=' . $steamid . '&rt=' . $replaylist['TITLE'] . '&ru=' . $replaylist['URL'] . '&deletereplay=yes">Delete</a>';
                    }
                    ?>

                </td>
            </tr>

        <?php
        }
        ?>

        <tr>
            <?php
            if ($sameip == "Y") {
                echo '<tr bgcolor="#abccd6" id="replay" class="replay" valign="top"><td colspan="2" align="center" class="coltitle tlarge addreplayshow"><a href="#" id="addreplayshow" class="addreplayshow">Add Replay Movie:</a>';
                echo '<form name="input" action="player.php" method="get">';
                echo '<table id="addreplay" class="addreplay">';
                echo '<tr><td>Movie title:</td><td><input type="text" name="rt" value="" /></td></tr>';
                echo '<tr><td>Movie Youtube <b>ID</b></td><td><input type="text" name="ru" value="" /></td></tr>';
                echo '<tr><td colspan=2>Make sure you only enter the movie id, so: http://www.youtube.com/watch?v=jzrcSF_EZEo becomes <b>jzrcSF_EZEo</b></td></tr>';
                echo '<input type="hidden" name="steamid" value="' . $steamid . '"></td></tr>'; //add hidden field with steamid information
                echo '<tr><td colspan=2><input type="submit" value="Save" /></td></tr>';
                echo '</table>';
                echo '</td></tr>';
            }
            ?>
        </tr>
    </table>


    </td>
    <td align="left">
        <br/>
        <table width="250" border="0" cellpadding="0" cellspacing="0">
            <tr valign="top">
                <td valign=top colspan="2" align="center" class="tlarge coltitle">Steam Community Information</td>
            </tr>
            <tr>
                <td class="toprow" align="center" width="100%">

                    <?php if ($showbuttons == 1) {
                        ?>
                        <a class='wiki' href="<?php echo $steamlink;
                        echo $pre;
                        echo $steamcid; ?>" target="_blank"><img src="images/buttons/viewprofile.png" border="0"
                                                                 alt="View Profile"/></a><br/>
                        <a class='wiki' href="http://www.tf2items.com/profiles/<?php echo $pre;
                        echo $steamcid; ?>" target="_blank"><img src="images/buttons/viewbackpack.png" border="0"
                                                                 alt="View Backpack"/></a><br/>
                        <a class='wiki' href="http://steamcommunity.com/profiles/<?php echo $pre;
                        echo $steamcid; ?>/stats/TF2?tab=achievements" target="_blank"><img
                                src="images/buttons/viewachieves.png" border="0" alt="View Valve Achievements"/></a>
                        <br/>
                        <a class='wiki' href="steam://friends/add/<?php echo $pre;
                        echo $steamcid; ?>" target=""><img src="images/buttons/friendinvite.png" border="0"
                                                           alt="Send Friend Invite"/></a><br/>
                        <a class='wiki' href="steam://friends/message/<?php echo $pre;
                        echo $steamcid; ?>" target=""><img src="images/buttons/sendmessage.png" border="0"
                                                           alt="Send Steam Message"/></a>
                    <?php
                    } else {
                        if ($showbuttons == 0) {
                            ?>
                            <a class='wiki' href="<?php echo $steamlink;
                            echo $pre;
                            echo $steamcid; ?>" target="_blank">View Profile</a><br/>
                            <a class='wiki' href="http://www.tf2items.com/profiles/<?php echo $pre;
                            echo $steamcid; ?>" target="_blank">View Backpack</a><br/>
                            <a class='wiki' href="http://willitcraft.com/#!<?php echo $pre;
                            echo $steamcid; ?>" target="_blank">Will It Craft</a><br/>
                            <a class='wiki' href="http://steamcommunity.com/profiles/<?php echo $pre;
                            echo $steamcid; ?>/stats/TF2?tab=achievements" target="_blank">View Valve Achievements</a>
                            <br/>
                            <a class='wiki' href="http://steamcommunity.com/profiles/<?php echo $pre;
                            echo $steamcid; ?>/stats/TF2?tab=stats" target="_blank">View Valve Player Stats</a><br/>
                            <a class='wiki' href="steam://friends/add/<?php echo $pre;
                            echo $steamcid; ?>" target="">Send Friend Invite</a><br/>
                            <a class='wiki' href="steam://friends/message/<?php echo $pre;
                            echo $steamcid; ?>" target="">Send Steam Message</a>
                        <?php
                        }
                    }
                    if ($hascustomachs == 1)
                    {
                    ?>
                    <br/><a class='wiki' href="<?php echo $customachpath; ?>player.php?u=<?php echo $steamid; ?>"
                            target="_blank"><img src="images/buttons/custachieves.png" border="0"
                                                 alt="View Custom Achievements"/>
                        <?php } ?>
                </td>
            </tr>
        </table>

        <?php
        if ($showbestclasses == 1 && file_exists("images/classes/" . $firstclass . ".png")) {
            ?>
            <br/>
            <table style="" width="250" border="0" cellpadding="0" cellspacing="0">
                <tr bgcolor="#abccd6" valign="bottom">
                    <td colspan="2" align="center" class="coltitle tlarge">Best three classes:</td>
                </tr>
                <tr bgcolor="#abccd6" valign="bottom">
                    <td colspan="2" align="center" class="coltitle tlarge">Most kills as a <?php echo $firstclass ?>
                        (<?php echo $firstkills ?>)
                    </td>
                </tr>
                <tr>
                    <td class=row_alt colspan=2><img src="images/classes/<?php echo $firstclass ?>.png" width="220"/>
                    </td>
                </tr>
                <tr>
                    <td class=row><?php echo $secondclass ?></td>
                    <td class=row><?php echo $secondkills ?></td>
                </tr>
                <tr>
                    <td class=row_alt><?php echo $thirdclass ?></td>
                    <td class=row_alt><?php echo $thirdkills ?></td>
                </tr>
            </table>
        <?php
        }
        ?>

        <br/>

        <div class="bump box">
            <table width="250" border="0" cellpadding="0" cellspacing="0" align="center">
                <tr valign="bottom">
                    <td valign=top colspan="2" align="center" class="tlarge coltitle">Statistics Summary</td>
                </tr>
                <tr>
                    <td class="toprow" width="52%">Rank:</td>
                    <td class="toprow" width="48%">
                        <div align="right">
                            <?php
                            $sql2 = "SELECT STEAMID FROM `Player` WHERE `POINTS` >= '" . $adr['POINTS'] . "'";
                            $ergebnis2 = mysql_query($sql2);
                            if (!$ergebnis2) {
                                echo "Query $myquery failed: " . mysql_error();
                            }
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
                    <td class=row_alt width="52%">Kills per Death:</td>
                    <td class=row_alt colspan="2" width="48%">
                        <div align="right">
                            <?php
                            if ($adr['Death'] != 0) {
                                echo round($adr['KILLS'] / $adr['Death'], 2);
                            } else {
                                echo "0";
                            }

                            ?>
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
            </table>

            <br/>
            <table style="" width="250" border="0" cellpadding="0" cellspacing="0" align="center">
                <tr valign="bottom">
                    <td colspan="2" align="center" class="coltitle tlarge">Items Found</td>
                </tr>
                <?php
                $sql = "SELECT ItemIndex, COUNT(ItemIndex) as FOUND FROM founditems WHERE `STEAMID` LIKE '$steamid' GROUP BY ItemIndex";
                $result = mysql_query($sql);
                if (!$result) {
                    echo "Query $myquery failed: " . mysql_error();
                }

                $counter = 0;
                while ($adr = mysql_fetch_array($result)) {
                    $counter++;

                    $sqlitem = "SELECT * FROM items WHERE ItemIndex = '" . $adr["ItemIndex"] . "';";
                    $resultitem = mysql_query($sqlitem);
                    if (!$resultitem) {
                        echo "Query $myquery failed: " . mysql_error();
                    }

                    while ($adritem = mysql_fetch_array($resultitem)) {
                        $WeapFound[$counter]["Name"] = $adritem['NAME'];
                        $WeapFound[$counter]["ImgUrl"] = $adritem['IMGURL'];
                        $WeapFound[$counter]["Found"] = $adr['FOUND'];
                    }
                }

                // echo mysql_real_escape_string($Name);
                if (count($WeapFound) != 0) {
                    $count = 0;
                    echo "<tr><td class='row' align='center' >\r\n";
                    for ($i = 0; $i < 84; $i++) {
                        if (isset($WeapFound[$i]["Name"])) {
                            $itemname = $WeapFound[$i]["Name"];
                            //echo trim(substr($itemname, 0, 4));
                            if (strtoupper(substr($itemname, 0, 3)) == "THE") {
                                $itemname = trim(substr($itemname, 3));
                            }

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
                ?>
            </table>


    </td>
    </tr>
    </table>
    </tr>
    <tr>
        <td colspan="3"><?php echo $mainfooter ?></td>
        </table>
    </div>
    </body>
    </html>
<?php
}
?>
