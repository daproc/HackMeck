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
require_once ('includes/beleg-config.php');
$db_link = mysqli_connect(
        HOST, USER, PASSWORD, DATABASE
);
?>
<!DOCTYPE html> 
<html> 
    <head>
        <meta charset="utf-8">
        <title>Nutzer-Verwaltung</title> 
        

    </head> 
    <body>
        <p><a href="index.php?in=reg" title="neuen Nutzer anlegen">neuen Nutzer anlegen</a></p>
        <?php
        if (isset($_POST['send'])) {
            $change = $_POST['send'];
            $send = 0;
            $insert = "UPDATE `users` SET send=? WHERE `users`.`send` = 1";
            $stmt = mysqli_prepare($db_link, $insert);
            mysqli_stmt_bind_param($stmt, 'i', $send);
            mysqli_stmt_execute($stmt);
            $send = 1;
            $insert = "UPDATE `users` SET send=? WHERE `users`.`id` = ?";
            $stmt = mysqli_prepare($db_link, $insert);
            mysqli_stmt_bind_param($stmt, 'ii', $send, $change);
            mysqli_stmt_execute($stmt);
        }
        ?>
        <form action="index.php?in=user_ad" method="POST" class="color">
            <div class="formular">
                <fieldset>
                    <legend>Mail</legend>
                    <p>An welche Adresse sollen Mails gesendet werden?</p>
                    <?php
                    $sql = "SELECT name, email, id, send FROM users";
                    $db_erg = mysqli_query($db_link, $sql);
                    if (!$db_erg) {
                        die('Ungültige Abfrage: ' . mysqli_error($db_erg));
                    }
                    while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
                        $uname = $zeile['name'];
                        $umail = $zeile['email'];
                        $uid = $zeile['id'];
                        $send = $zeile['send'];
                        echo '<p><input type="radio" id="'.$uname.'" name="send" value="' . $uid . '"';
                        if ($send == 1) {
                            echo ' checked';
                        }
                        echo '><label for="'.$uname.'"> ' . $umail . ' </label></p><hr>';
                    }
                    echo '<input type="submit" value="speichern">';
                    ?>
                </fieldset>
            </div>
        </form>