<?php
/* $Id$ */
/*
    Dieses Skript zeigt die Emailadressen (optional mit Benutzernamen) in Form einer Textdatei an!
                        Nendilo by http://wbb3addons.ump2002.net/
                        
    - Lade das Skript auf Deinen Server in das Root-Verzeichnis Deines WBB.
    - Rufe das Skript im Browser auf.
    - Gebe Dein Passwort der Datenbank ein.
    - Wähle die Einstellungen.
    - Senden und fertig!
    
*/
    if(isset($_POST['showEmails'])) {
        include_once("config.inc.php");
        include_once(RELATIVE_WCF_DIR."config.inc.php");
	    $dbConnect = mysql_connect($dbHost, $dbUser, $dbPassword) or die ("Konnte nicht zum MySQL Server verbinden");
	    $selectDB = mysql_select_db($dbName) or die ("Konnte die MySQL Datenbank nicht auswählen");
	    $selectEmails = mysql_query("SELECT username, email FROM wcf" . WCF_N . "_user ORDER BY username");
        $accept_pass = $_POST['passwort'];
        switch ($_POST['seperator']) {
            case 1:
                $seperator = ",";
            break;
            case 2:
                $seperator = ";";
            break;
            case 3:
                $seperator = "\r\n";
            break;
            case 4:
                $seperator = " ";
            break;
            default:
                $seperator = ",";
            break;
        }
        $show_user = $_POST['showUser'];
        if($accept_pass == $dbPassword) {
	        header('content-type: text/plain; charset=utf-8');
	        if(mysql_num_rows($selectEmails) != 0) {
		        while($row = mysql_fetch_array($selectEmails)){
                    echo ($show_user) ? $row['username'] . " <" . $row['email'] . ">" . $seperator : $row['email'] . $seperator;
                }
            } else {
                echo "Keine Daten vorhanden!";
            }
        }
    }
    else { 
header("Content-type: text/html; charset=UTF-8"); { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de" style="background-color: #000;">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="de" />
		<title>Dieses Skript liest die Emailadressen aus der Datenbank aus. - Nendilo by wbb3addons.ump2002.net</title>
	</head>
	<body style="background: #000;">
        <p style="padding: 10px;margin: 0;line-height: 2em;text-align: center;font-size: 1.5em;font-weight: bold;font-family: Arial, sans-serif;color: #fff;">
            Dieses Skript liest die Emailadressen aus der Datenbank aus.
        </p>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" accept-charset="UTF-8">
            <div style="position: absolute;top: 50%;left: 50%;width: 500px;height: 280px;margin: -140px -250px;padding: 0;background-color: #fff;text-align: center;">
                <p style="height: 55px;line-height: 25px;padding: 5px 0;margin: 0;font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #000;">
                    Gebe Dein Passwort für die Datenbank ein und wähle die Einstellungen:
                </p>
                <p style="font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #000;height: 55px;line-height: 25px;vertical-align: middle;padding: 0 0 5px;margin: 0;">
                    <label for="passwort">Passwort:</label><br />
                    <input type="password" name="passwort" id="passwort" style="border: 3px dotted #000;background: #fff;font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #900;" />
                </p>
                <p style="font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #000;height: 55px;line-height: 25px;vertical-align: middle;padding: 0 0 5px;margin: 0;">
                    <label for="seperator">Trennzeichen:</label><br />
                    <select name="seperator" id="seperator">
                        <option>------ Bitte wählen ------</option>
                        <option value="1">Komma</option>
                        <option value="2">Semikon</option>
                        <option value="3">Zeilenumbruch</option>
                        <option value="4">Leerzeichen</option>
                    </select> 
                </p>
                <p style="font-size: 1.2em;font-weight: bold;font-family: Arial, sans-serif;color: #000;height: 25px;line-height: 25px;vertical-align: middle;padding: 0;margin: 0;">
                    <input type="checkbox" name="showUser" id="showUser" value="1" checked="checked" style="border: 3px solid #000;background: #fff;cursor: pointer;font-size: 1.5em;font-weight: bold;font-family: Arial, sans-serif;color: #000;" /> <label for="showUser">Benutzernamen mit anzeigen.</label>
                </p>
                <p style="height: 55px;line-height: 55px;vertical-align: middle;padding: 0;margin: 0;">
                    <input type="submit" name="showEmails" id="showEmails" value="Senden" style="border: 3px solid #000;background: #fff;cursor: pointer;font-size: 1.5em;font-weight: bold;font-family: Arial, sans-serif;color: #000;" />
                </p>
            </div>
        </form>
        <p style="position: absolute;bottom: 0;right: 0;padding: 116px 10px 10px;margin: 0;">
            <a href="http://wbb3addons.ump2002.net/" style="color: #990;text-decoration: none;font-weight: bold;font-family: Arial, sans-serif;background: transparent url(http://wbb3addons.ump2002.net/img/logoS_WBB3addons_2.png) no-repeat 50% 0;padding-top: 116px">© wbb3addons.ump2002.net</a>
        </p>
	</body>
</html>
<?php }
}
?>
