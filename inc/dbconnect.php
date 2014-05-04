<?php
include('settings.php');
$version = "7.6.1";
mysql_connect($mysql_server, $mysql_user, $mysql_password);
mysql_query("SET NAMES 'UTF8'");
mysql_select_db($mysql_database);
date_default_timezone_set('UTC');
?>