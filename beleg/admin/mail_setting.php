<?php
#########################################
#Belegungsplan  			#
#©2017 Daniel ProBer alias HackMeck	#
#https://www.hackmeck.de		#
#GERMANY				#
#					#
#Mail: daproc@gmx.net			#
#Paypal: daproc@gmx.net			#
#					#
#Zeigt einen Kalender mit 		#
#Belegung für ein Objekt an.		#
#z.B. Ferienwohnung 			#
#########################################

/* 	Belegungsplan ist Freie Software: Sie können ihn unter den Bedingungen
  der GNU General Public License, wie von der Free Software Foundation,
  Version 2 der Lizenz weiterverbreiten und/oder modifizieren.

  Belegungsplan wird in der Hoffnung, dass er nützlich sein wird, aber
  OHNE JEDE GEWÄHRLEISTUNG, bereitgestellt; sogar ohne die implizite
  Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
  Siehe die GNU General Public License für weitere Details.

  Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
  Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>
 */

$controll = $remote;
if ($controll != 24519) {
    echo 'Kein Zugriff!';
    exit();
}
$msg_pass = '';
if (isset($_POST['check']) && !empty($_POST['check']) && $_POST['check'] == 101) {
    if ($_POST['pass'] == $_POST['pass2']) {
        $server = filter_input(INPUT_POST, 'server', FILTER_SANITIZE_ENCODED);
        $user = filter_input(INPUT_POST, 'user', FILTER_UNSAFE_RAW);
        $pass = filter_input(INPUT_POST, 'pass', FILTER_UNSAFE_RAW);
        $port = filter_input(INPUT_POST, 'port', FILTER_VALIDATE_INT);
        $inhalt_smtp = '<?php
define("SMTPSERVER", "' . $server . '");
define("SMTPUSER", "' . $user . '"); 
define("SMTPPASSWORD", "' . $pass . '");
define("SMTPPORT", "' . $port . '");
';

        $handle = fopen("includes/smtp-config.php", "w");
        fwrite($handle, $inhalt_smtp);
        fclose($handle);
    } else {
        $msg_pass = 'Passwörter stimmen nicht überein!';
    }
} elseif (empty($_POST['check'])) {
    $filename = 'includes/smtp-config.php';
    if (file_exists($filename)) {
        include 'includes/smtp-config.php';
    } else {
        define("SMTPSERVER", "");
        define("SMTPUSER", "");
        define("SMTPPASSWORD", "");
        define("SMTPPORT", "25");
    }
}
?>

<p>Hier können Sie Die Einstellungen für den Versand der Emails festlegen.</p>
<h3>SMTP-Server</h3>
<p>Die Daten erhalten sie von Ihrem E-Mailanbieter.</p>

<form action="#" method="post">
    <fieldset>
        <legend>SMTP-Sever</legend>
        <label for="server" class="left">Server: </label><input type="text" id="server" name="server" value="<?php echo SMTPSERVER; ?>"><br><br>
        <label for="user" class="left">Nutzername: </label><input type="text" id="user" name="user" value="<?php echo SMTPUSER; ?>"><br><br>
        <label for="pass" class="left">Passwort: </label><input type="password" id="pass" name="pass"><br><br>
        <label for="pass2" class="left">Passwort wiederholen: </label><input type="password" id="pass2" name="pass2">
        <?php
        echo '<span style="color: red;">' . $msg_pass . '</span>';
        ?>
        <br><br>
        <label for="port" class="left">Port: </label><input type="text" id="port" name="port"  value="<?php echo SMTPPORT; ?>"><br><br>
        <input type="hidden" id="check" name="check" value="101">
    </fieldset>
    <br><input type="submit" name="senden" value="speichern">
</form>