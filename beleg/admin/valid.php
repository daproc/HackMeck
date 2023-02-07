<?php
#########################################
#Belegungsplan  			#
#©2017-2023 Daniel ProBer alias		#
#HackMeck				#
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
?>
<html>
    <head>
        <script src="js/ckeditor/ckeditor.js"></script>
        <style>
<?php
$controll = $remote;
if ($controll != 24519) {
    echo 'Kein Zugriff!';
    exit();
}


include ('css/admin_css.php');
include_once ('includes/beleg-config.php');

$db_link = mysqli_connect(
        HOST, USER, PASSWORD, DATABASE
);
mysqli_set_charset($db_link, 'utf8');
?>
        </style>
    </head>
    <p>Buchungsanfrage bearbeiten</p>
    <?php
    if (isset($_GET['bookingnr']) && !empty($_GET['bookingnr'])) {
        $nr = $_GET['bookingnr'];
        list($user_id, $times_id, $booking_id) = explode("-", $nr);
    } else {
        echo 'Keine Buchungsanfrage ausgewählt';
        die();
    }
    $user = "SELECT * FROM guests WHERE id=? ";
    $stmt = mysqli_prepare($db_link, $user);
    mysqli_stmt_bind_param($stmt, 's', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    echo '<table>';
    while ($zeile_c = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr><td>Anrede:</td><td>' . $zeile_c['anrede'] . '</td></tr>';
        $anrede = $zeile_c['anrede'];
        echo '<tr><td>Name:</td><td>' . $zeile_c['vorname'] . ' ' . $zeile_c['nname'] . '</td></tr>';
        $zuname = $zeile_c['nname'];
        echo '<tr><td>Str.:</td><td>' . $zeile_c['str'] . '</td></tr>';
        echo '<tr><td>PLZ Ort:</td><td>' . $zeile_c['plz'] . ' ' . $zeile_c['ort'] . '</td></tr>';
        echo '<tr><td>Tel:</td><td>' . $zeile_c['tel'] . '</td></tr>';
        echo '<tr><td>Email:</td><td>' . $zeile_c['email'] . '</td></tr>';
        $email_adr = $zeile_c['email'];
    }
    $book = "SELECT * FROM booking WHERE id=? AND guest_id=? AND times_id=?";
    $stmt = mysqli_prepare($db_link, $book);
    mysqli_stmt_bind_param($stmt, 'sss', $booking_id, $user_id, $times_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($zeile_c = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr><td>Gesamte Personen:</td><td>' . $zeile_c['anzahl_pers'] . '</td></tr>';
        echo '<tr><td>Erwachsene:</td><td>' . $zeile_c['anzahl_erw'] . '</td></tr>';
        echo '<tr><td>Kinder:</td><td>' . $zeile_c['anzahl_kind'] . '</td></tr>';
        echo '<tr><td>Hunde:</td><td>' . $zeile_c['anzahl_tier'] . '</td></tr>';
        echo '<tr><td>Nachricht:</td><td>' . $zeile_c['text'] . '</td></tr>';
    }
    $time = "SELECT * FROM times WHERE id=? AND user=? ";
    $stmt = mysqli_prepare($db_link, $time);
    mysqli_stmt_bind_param($stmt, 'ss', $times_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($zeile_c = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $datean = date('d.m.Y', strtotime($zeile_c['datean']));
        echo '<tr><td>Anreise:</td><td>' . $datean . '</td></tr>';
        $dateab = date('d.m.Y', strtotime($zeile_c['dateab']));
        echo '<tr><td>Abreise:</td><td>' . $dateab . '</td></tr>';
        $obj_id = $zeile_c['objekt_id'];
    }
    $obj = "SELECT name FROM objekt WHERE id=?";
    $stmt = mysqli_prepare($db_link, $obj);
    mysqli_stmt_bind_param($stmt, 's', $obj_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($zeile_c = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr><td>Objekt:</td><td>' . $zeile_c['name'] . '</td></tr>';
        $obj_name = $zeile_c['name'];
    }
    echo '</table>';
    if (isset($_GET['validate']) && !empty($_GET['validate'])) {
        $conf = 1;
        $upd_times = "UPDATE `times` SET `confirmed` = ? WHERE `times`.`id` = ?";
        $stmt = mysqli_prepare($db_link, $upd_times);
        mysqli_stmt_bind_param($stmt, 'ii', $conf, $times_id);
        mysqli_stmt_execute($stmt);
        if (!$stmt) {
            die('Ungültige Abfrage: ' . mysqli_error());
        } else {

            echo 'Buchung bestätigt, diese Buchung wird jetzt im Kalender als belegt angezeigt.<br>';
            $text = "SELECT best_text FROM mail_text ORDER BY ID DESC LIMIT 1";
            $stmt = mysqli_prepare($db_link, $text);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($zeile_c = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $best_text = json_decode($zeile_c['best_text']);
            }
            $mail_bcc = "SELECT email FROM users ORDER BY ID DESC LIMIT 1";
            $stmt = mysqli_prepare($db_link, $mail_bcc);
            //mysqli_stmt_bind_param($stmt, 's', $obj_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($zeile_c = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $emailadr = $zeile_c['email'];
            }

            //Buchungsbestätigung versenden
            $betreff = 'Buchungsbestätigung ' . $obj_name;
            if ($anrede == 'Frau') {
                $anr = 'Sehr geehrte Frau ' . $zuname . ',';
            } elseif ($anrede == 'Herr') {
                $anr = 'Sehr geehrter Herr ' . $zuname . ',';
            } else {
                $anr = 'Sehr geehrte/ r Frau/ Herr ' . $zuname . ',';
            }
            $nachricht = '
<p>' . $anr . '</p>
<p>Hiermit bestätigen wir Ihre Buchung vom ' . $datean . ' bis ' . $dateab . '.<br>
' . $best_text . '</p>';

            echo '<br><div class=mailform><form action="index.php?in=mail" enctype="multipart/form-data" method="post">';
            echo '<fieldset>';
            echo '<legend>Buchungsbestätigung per Email versenden: </legend>';
            echo '<label for="empfaenger" class="create">Empfänger:</label><br><input type="email" name="empfaenger" value="' . $email_adr . '"><br>';
            echo '<label for="bcc" class="create">BCC (Kopie an mich):</label><br><input type="email" name="bcc" value="' . $emailadr . '"><br>';
            echo '<label for="betreff" class="create">Betreff:</label><br><input type="text" name="betreff" value="' . $betreff . '"><br>';
            echo '<label for="nachricht" class="create">Nachricht:</label><br><textarea name="nachricht" cols="100" rows="20">' . $nachricht . '</textarea><br><br>';
            echo '<p>Hier können Sie eine Datei, z.B. Ihre AGB´s oder eine Rechnung auswählen.</p>';
            echo '<label for="datei">Anhang: <input name="datei" type="file" size="50" accept="file/*"></label><br><br>';
            echo '<input type="submit" name="senden" value="senden">';
            echo '</fieldset></form>';
            ?>
            <script>CKEDITOR.replace('nachricht', {
                    height: 300,
                    filebrowserBrowseUrl: 'fileman/index.html',
                    filebrowserImageBrowseUrl: 'fileman/index.html?type=Images',
                });
            </script>
            <?php
        }
    } else {
        echo '<a href="index.php?in=vali&bookingnr=' . $nr . '&validate=1&time=' . $times_id . '">Buchung bestätigen</a><br>';
    }
    export($db_link, $obj_id);
    ?>
