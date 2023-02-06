<?php
#########################################
#Belegungsplan 0.7			#
#©2017 Daniel ProBer alias HackMeck	#
#http://hackmeck.bplaced.net		#
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
?>
<html>
    <head>
        <script src="js/iframe.js"></script>
        <style>
<?php
$objekt = $_GET['objekt'];
include ('css/admin_css.php');
//include ('css/insert_css.php');
?>
        </style>
    </head>
    <p>Wählen sie zwischen Jahres- und Monatsansicht oder Automatisch.<br>
        Bei Automatisch wird auf Smartphones der Monatskalender und bei Computern der Jahreskalender angezeigt.</p>

    <?php
    $settings = "SELECT cal_typ, cal_m_zahl FROM settings WHERE id = 1";

    $db_erg = mysqli_query($db_link, $settings);
    if (!$db_erg) {
        die('Ungültige Abfrage: ' . mysqli_error());
    }

    if (empty($_GET['cal_typ'])) { //Prüfen ob Link ausgewählt wurde
        while ($zeile_s = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
            $cal_typ = $zeile_s['cal_typ'];
            $cal_m_zahl = $zeile_s['cal_m_zahl'];
        }
    } else {
        $cal_typ = $_GET['cal_typ'];
        $cal_m_zahl = $_GET['anzahl'];
        $update = "UPDATE `settings` SET `cal_typ` = '" . $cal_typ . "', `cal_m_zahl` = '" . $cal_m_zahl . "' WHERE `settings`.`id` = 1";
        $write = mysqli_query($db_link, $update);
    }


    echo '<form action="index.php" method="get" class="color">';
    echo '<div class="formular">';
    echo '<fieldset>';
    echo '<legend>Anzeige</legend>';
    echo '<br><br>';
    if ($cal_typ == 2) {
        echo '<input type="radio" id="jahr" name="cal_typ" value="2" checked>';
        echo '<label for="jahr"> Jahresansicht (auf kleinen Bildschirmen problematisch)</label>';
        echo '<br><br>';
        echo '<input type="radio" id="monat" name="cal_typ" value="1">';
        echo '<label for="monat"> Monatsansicht</label>';
        echo '<br><br>';
        echo '<input type="radio" id="resp" name="cal_typ" value="3">';
        echo '<label for="resp"> Automatisch</label>';
    } elseif ($cal_typ == 1) {
        echo '<input type="radio" id="jahr" name="cal_typ" value="2">';
        echo '<label for="jahr"> Jahresansicht (auf kleinen Bildschirmen problematisch)</label>';
        echo '<br><br>';
        echo '<input type="radio" id="monat" name="cal_typ" value="1" checked>';
        echo '<label for="monat"> Monatsansicht</label>';
        echo '<br><br>';
        echo '<input type="radio" id="resp" name="cal_typ" value="3">';
        echo '<label for="resp"> Automatisch</label>';
    } else {
        echo '<input type="radio" id="jahr" name="cal_typ" value="2">';
        echo '<label for="jahr"> Jahresansicht (auf kleinen Bildschirmen problematisch)</label>';
        echo '<br><br>';
        echo '<input type="radio" id="monat" name="cal_typ" value="1">';
        echo '<label for="monat"> Monatsansicht</label>';
        echo '<br><br>';
        echo '<input type="radio" id="resp" name="cal_typ" value="3" checked>';
        echo '<label for="resp"> Automatisch</label>';
    }
    echo '<br><br><br><br>';
    echo '<label for="anzahl">Anzahl angezeigter Monate:<br><br><input id="anzahl" name="anzahl" type="number" min="1" max="12" step="1" value="' . $cal_m_zahl . '"></label>';
    echo '<br><br>';
    echo '<input type="hidden" name="objekt" value="' . $objekt . '">';
    echo '<input type="hidden" name="in" value="scr">';
    echo '<input type="submit" value="speichern">';
    echo '</fieldset></div></form>';
    ?>

    <div id = "iframe">
        <h4>Vorschau</h4>
        <div>
            <iframe src="../index.php?objekt=<?php echo $objekt; ?>" 
                    id="idIframe" width="100%" onload="iframeLoaded()"
                    allowfullscreen
                    style="border: none;"></iframe>
        </div>

    </div>