<?php
include("inc/dbconnect.php");
include("inc/footer.inc.php");

/* if (!extension_loaded('bcmath')) {
$bcmath = "not found";
}else{
$bcmath = "found"; 
} */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TF2 Stats Website Version</title>
    <link href=styles/default.css rel=stylesheet type=text/css>
    <link type="text/css" href="styles/menu.css" rel="stylesheet"/>

    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
</head>

<body>
<?php include("inc/headerlinks.inc.php"); ?>

<table>
    <h1>TF2 Stats Website Heinisblog Version</h1>

    <table style="border:none" align=center>
        <tr>
            <td>
                <div align=center style="border:0; margin-top:20px">
                    <p><b>Your Version:</b> <?php echo $version; ?></p>

                    <p><b>BC Math:</b> <? echo $bcmath; ?></p>
                </div>
        </tr>
        </td></table>

    <table style="border:none" align=center>
        <tr>
            <td>

                <div align=center style="border:0; margin-top:20px">

                    <h1>Credits:</h1>

                    <h2>Version 7:</h2>

                    <h3>Updates and current support by <a href="http://forums.alliedmods.net/showthread.php?t=109006"
                                                          target="_blank">DarthNinja & Galadril</a></h3>
                    <br/><br/>

                    <h2>Version 6:</h2>

                    <h3>Updates and current support by <a href="http://forums.alliedmods.net/showthread.php?t=109006"
                                                          target="_blank">DarthNinja</a></h3>
                    <h4>All credit for previous versions<br/> and reused code goes to those listed below:</h4>

                    <p>&nbsp;</p>
                </div>
                <div align=center style="border:0; margin-top:20px">
                    <h2>Version 5:</h2>

                    <h3>Code by <a href="http://compactaim.de" target="_blank">R-Hehl</a></h3>
                    <h4>Extended by: <a href="http://beatx.info" target="_blank">beatx</a></h4>
                    <h5>Special thanks to:</h5>
                    <h5>Goerge for the Design</h5>
                    <h5>K-Play for some Coding work</h5>
                    <h5>FPS-Banana for Alpha Testing</h5>
                    <h5>Tom for Creating the Weapon Icons</h5>
                    <h5>Swamp56 for paginating the main page</h5>

                    <p>&nbsp;</p>
                </div>
        </tr>
        </td></table>
    <p>&nbsp;</p>  <?php echo $mainfooter ?>
    <td></tr>
</table>
</body>
</html>