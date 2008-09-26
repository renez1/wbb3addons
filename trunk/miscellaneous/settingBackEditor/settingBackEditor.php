<?php
/* $Id$ */
/*
    Dieses Skript setzt die Editierungshinweise eines bestimmten Users zurück!
                        Nendilo by http://wbb3addons.ump2002.net/
                        
    - Lade das Skript auf Deinen Server in das Root-Verzeichnis Deines WBB.
    - Rufe das Skript im Browser auf.
    - Gebe den Benutzernamen des Users ein deren Editierungshinweise entfernt werden soll.
    - Gebe Dein Passwort der Datenbank ein.
    - Senden und fertig!
    
*/

/* Includes */
include_once("config.inc.php");
include_once(RELATIVE_WCF_DIR."config.inc.php");

/* Einstellungen */
$db_server  = $dbHost;           # Datenbankserver
$db_name    = $dbName;           # Datenbankname
$db_user    = $dbUser;           # Benutzername
$db_pass    = $dbPassword;       # Passwort
$wbb_number = WBB_N;             # WBB-Nummer
$wcf_number = WCF_N;             # WCF-Nummer

/* Los geht's */

if(isset($_POST['settingBackEditor'])) {

    $accept_pass = $_POST['passwort'];
    $edit_user  = trim($_POST['user']);
    $edit_all = $_POST['all'];
    
    if($accept_pass == $db_pass) {

        /* Verbindung */
        $verbindung = mysql_connect($db_server, $db_user, $db_pass) or die ("Keine Verbindung moeglich");
        $datenbank = mysql_select_db($db_name) or die ("Datenbank nicht gefunden");

        /* Alte Einträge löschen? */
        if($edit_all == 1) {
            $searchid = mysql_query("SELECT userID FROM wcf".$wcf_number."_user WHERE username = '".$edit_user."'");
            $userid = mysql_fetch_row($searchid);
            $querycount     = "SELECT COUNT(*) AS count FROM wbb".$wbb_number."_post WHERE
                                userID = '".$userid[0]."' AND editor != ''";
            $queryupdate    = "UPDATE wbb".$wbb_number."_post SET
                                editor = '',
                                editorID = 0,
                                lastEditTime = 0,
                                editCount = 0
                                WHERE userID = '".$userid[0]."'";
        }
        else {
            $querycount = "SELECT COUNT(*) AS count FROM wbb".$wbb_number."_post WHERE
                                username = '".$edit_user."' AND editor != ''";
            $queryupdate    = "UPDATE wbb".$wbb_number."_post SET
                                editor = '',
                                editorID = 0,
                                lastEditTime = 0,
                                editCount = 0
                                WHERE username = '".$edit_user."'";
        }
        /* Einträge davor zählen */
        $wieviel1 = mysql_query($querycount);
        $row1 = mysql_fetch_assoc($wieviel1);
        $davor = $row1["count"];

        /* Update */
        $update = mysql_query($queryupdate);

        /* Einträge danach zählen */
        $wieviel2 = mysql_query($querycount);
        $row2 = mysql_fetch_assoc($wieviel2);
        $danach = $row2["count"];
        $diff = $davor-$danach;

        /* Verbindung beenden */
        mysql_close($verbindung);
        
        $message = "Es wurden ".$davor." ".(($davor == 1) ? "Eintrag" : "Einträge")." gefunden und ".$diff." ".(($diff == 1) ? "Eintrag" : "Einträge")." gelöscht!<br /><a href=\"".$_SERVER['PHP_SELF']."\" style=\"color: #009;text-decoration: none;\">&lt;&lt;&lt; zurück</a>";

    }
    else {
        $passcheck = (empty($accept_pass)) ? "Passwort eingeben!!!" : "Falsches Passwort!!!";
        $message = ((empty($edit_user)) ? "Usernamen eingeben!!!" : $passcheck) . "<br /><a href=\"".$_SERVER['PHP_SELF']."\" style=\"color: #009;text-decoration: none;\">&lt;&lt;&lt; zurück</a>";
    }

}

/* Ausgabe */
header("Content-type: text/html; charset=UTF-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de" style="background-color: #000;">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="de" />
		<title>Dieses Skript setzt den Editierungshinweis eines Users zurück - Nendilo by wbb3addons.ump2002.net</title>
	</head>
	<body style="background: #000;">
        <p style="padding: 10px;margin: 0;line-height: 2em;text-align: center;font-size: 1.5em;font-weight: bold;font-family: Arial, sans-serif;color: #fff;">
            Dieses Skript setzt die Editierungshinweise eines Users zurück
        </p>
	    <?php if(!isset($_POST['settingBackEditor'])) { ?>
	        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" accept-charset="UTF-8">
	            <div style="position: absolute;top: 50%;left: 50%;width: 500px;height: 280px;margin: -140px -250px;padding: 0;background-color: #fff;text-align: center;">
	                <p style="height: 55px;line-height: 25px;padding: 5px 0;margin: 0;font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #000;">
	                    Gebe den zu editierenden User und Dein Passwort für die Datenbank ein:
	                </p>
	                <p style="font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #000;height: 55px;line-height: 25px;vertical-align: middle;padding: 0 0 5px;margin: 0;">
	                    <label for="user">User:</label><br />
	                    <input type="text" name="user" id="user" style="border: 3px dotted #000;background: #fff;font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #900;" />
	                </p>
	                <p style="font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #000;height: 55px;line-height: 25px;vertical-align: middle;padding: 0 0 5px;margin: 0;">
	                    <label for="passwort">Passwort:</label><br />
	                    <input type="password" name="passwort" id="passwort" style="border: 3px dotted #000;background: #fff;font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #900;" />
	                </p>
	                <p style="font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #000;height: 25px;line-height: 25px;vertical-align: middle;padding: 0 0 5px;margin: 0;">
	                    <input type="checkbox" name="all" id="all" value="1" style="border: 3px dotted #000;background: #fff;font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #900;" /> <label for="all">Auch Einträge unter alten Usernamen löschen.</label>
	                </p>
	                <p style="height: 55px;line-height: 55px;vertical-align: middle;padding: 0;margin: 0;">
	                    <input type="submit" name="settingBackEditor" value="Senden" style="border: 3px solid #000;background: #fff;cursor: pointer;font-size: 1.5em;font-weight: bold;font-family: Arial, sans-serif;color: #000;" />
	                </p>
	            </div>
	        </form>
	    <?php }
	    else { ?>
	            <div style="position: absolute;top: 50%;left: 50%;width: 500px;height: 250px;margin: -125px -250px;padding: 0;background-color: #fff;text-align: center;">
	                <p style="padding: 0;margin: 0;line-height: 2em;font-size: 1.5em;font-weight: bold;font-family: Arial, sans-serif;color: #000;">
	                    <?php echo $message . "\n"; ?>
	                </p>
	            </div>
	    <?php } ?>
        <p style="position: absolute;bottom: 0;right: 0;padding: 116px 10px 10px;margin: 0;">
            <a href="http://wbb3addons.ump2002.net/" style="color: #990;text-decoration: none;font-weight: bold;font-family: Arial, sans-serif;background: transparent url(http://wbb3addons.ump2002.net/img/logoS_WBB3addons_2.png) no-repeat 50% 0;padding-top: 116px">© wbb3addons.ump2002.net</a>
        </p>
	</body>

