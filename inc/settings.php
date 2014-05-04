<?php

//Overall settings
$clanname = "TF2 Rankings"; ////Your Clan Name or Website Title

$hascustomachs = "0"; //Set to 1 if you use the TF2 custom achievements plugin
// http://forums.alliedmods.net/showthread.php?t=109397

$customachpath = "../../achievements/";
//Relative path to the achievements root folder.

$tioffset = "0";
//Time in seconds to offset 'Currently Online' queries by, larger = more players listed
//This is only needed if your gameserver and webserver clocks are not synced

$datetimeformat = "1";
//You can select your datetimeformat.
//0 = '09/27/2010 - 8:20 AM' 	(US)
//1 = '27-09-2010 - 08:20'	(Europe)


//Put in you own generted steam api key in here (grab one yourself over here: http://steamcommunity.com/dev/apikey)
$steamapikey = "1EAE3406397C5ED62F675837FB0ACBF0";


//Map settings
$showimageicon = 1; //1 or 0 for showing the image icon
$showblueicon = 1; //1 or 0 for showing the Blueprint icon

//Paging settings
$nrofplayers = 50; //Number of players per page (player_ranking page)
$maxpaging = 5; //Max nr of pagging (5 would be for example: '1 2 3 4 5 ... 211')
$minpaging = 3; //Enable paging when having more then for example 3 pages


//Player page settings
$showbuttons = "0"; //Show steam community buttons, or just use text links? 1/0.
//There are two location pictures on the player page. Here you can configure the zoom level of both pictures. (1/10)
$zoom1 = 3; //first picture
$zoom2 = 7; //second picture
$showbestclasses = 1; //Show best classes on the player page (1|0) (this can slow your site down!)
$DisplayZeroKills = 1; //Remove Zero stats from player page 1=on | 0=off
$GoogleApi = ""; //Get google api key here: http://code.google.com/intl/nl/apis/maps/signup.html

//Weapon ranking page
$shownrofplayers = 20; // number of people to show on the weapon ranking page

//Replay Movies
$showmaxnrofreplaymovies = 5; // max number of replay movies


//Database Settings       
$mysql_server = "";
$mysql_user = "";
$mysql_password = "";
$mysql_database = "tf2stats";


//Functions
function db_escape($post)
{
    if (is_string($post)) {
        if (get_magic_quotes_gpc()) {
            $post = stripslashes($post);
        }
        return mysql_real_escape_string($post);
    }

    foreach ($post as $key => $val) {
        $post[$key] = db_escape($val);
    }

    return $post;
}

?>
