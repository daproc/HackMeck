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


if (isset($_GET['jahr'])and ! empty($_GET['jahr'])) { //Prüfen ob Jahr ausgewählt wurde
    $jahr = $_GET['jahr'];  //Jahr uebernehmen
} else {
    $jahr = date("Y");      //Ohne Auswahl aktuelles Jahr übernehmen
}
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
//include ('../style/insert.css.php');
?>
        </style>
    </head>
    <p>Hier können Sie die Farben des auf der Seite angezeigten Kalender anpassen.</p>
    <p>
    <ul>
        <li>Seite - Der Hintergrund der Gesamten Seite</li>
        <li>Tabelle - Der Hintergrund der Tabelle und der nicht belegten Tage</li>
        <li>Belegung - Die Farbe, mit der belegte Tage markiert werden</li>
        <li>Formularfeld - Die Hintergundfarbe des ausgewählten Formulars für die Jahreszahlen</li>
		<li>Schriftfarbe - Farbe der Schrift innerhalb der Tabelle</li>
    </ul>
	<ul>
		<li>Monate - Die Hintergrundfarbe der Monatsnamen</li>
	</ul>
	<ul>
		<li>Monate/ Jahr - Die Hintergrundfarbe der Tabellenüberschrift</li>
		<li>Tage - Die Hintergrundfarbe der einzelnen Tage</li>
		<li>Wochenenden - Die Hintergrundfarbe der Wochenenden (Sa & So)</li>
	</ul>
</p> 
<?php
$colors = "SELECT cal_month, cal_beleg, form_back, cal_back, cal_head, cal_days, cal_we, back, font FROM color WHERE id = 1";

$db_erg = mysqli_query($db_link, $colors);
if (!$db_erg) {
    die('Ungültige Abfrage: ' . mysqli_error());
}

if (empty($_GET['cal_month']) AND empty($_GET['cal_beleg']) AND empty($_GET['cal_back']) AND empty($_GET['form_back']) AND empty($_GET['back']) AND empty($_GET['font'])) { //Prüfen ob Link ausgewählt wurde
    while ($zeile_c = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
        $cal_beleg = $zeile_c['cal_beleg'];
        $cal_month = $zeile_c['cal_month'];
        $form_back = $zeile_c['form_back'];
        $cal_back = $zeile_c['cal_back'];
        $cal_head = $zeile_c['cal_head'];
        $cal_days = $zeile_c['cal_days'];
        $cal_we = $zeile_c['cal_we'];
        $back = $zeile_c['back'];
        $font = $zeile_c['font'];
    }
} else {
    $cal_beleg = $_GET['cal_beleg'];
    $cal_month = $_GET['cal_month'];
    $form_back = $_GET['form_back'];
    $cal_back = $_GET['cal_back'];
    $cal_head = $_GET['cal_head'];
    $cal_days = $_GET['cal_days'];
    $cal_we = $_GET['cal_we'];
    $back = $_GET['back'];
    $font = $_GET['font'];
    if (!preg_match("#^[0-9 a-f \#\]]+$#", $cal_beleg) OR ! preg_match("#^[0-9 a-f \#\]]+$#", $cal_month) OR ! preg_match("#^[0-9 a-f \#\]]+$#", $cal_back) OR ! preg_match("#^[0-9 a-f \#\]]+$#", $form_back) OR ! preg_match("#^[0-9 a-f \#\]]+$#", $back) OR ! preg_match("#^[0-9 a-f \#\]]+$#", $font)) {
        echo 'Nur Hexadezimale Farbangaben erlaubt!';
        exit();
    } else {
        $update = "UPDATE `color` SET `cal_month` = '" . $cal_month . "', `cal_beleg` = '" . $cal_beleg . "', `form_back` = '" . $form_back . "', `cal_back` = '" . $cal_back . "',  `cal_head` = '" . $cal_head . "', `cal_days` = '" . $cal_days . "', `cal_we` = '" . $cal_we . "', `back` = '" . $back . "', `font` = '" . $font . "' WHERE `color`.`id` = 1";
        $write = mysqli_query($db_link, $update);
    }
}

echo '<form action="index.php" method="get" class="color">';
echo '<div class="formular">';
echo '<fieldset>';
echo '<legend>Farben allgemein</legend>';
echo '<br><br>';
echo '<label for="back" class="left">';
echo 'Seite: </label>';
echo '<input type="color" name="back" value="' . $back . '" required>';
echo '<br><br>';
echo '<label for="cal_back" class="left">';
echo 'Tabelle: </label>';
echo '<input type="color" name="cal_back" value="' . $cal_back . '" required>';
echo '<br><br>';
echo '<label for="cal_beleg" class="left">';
echo 'Belegung: </label>';
echo '<input type="color" name="cal_beleg" value="' . $cal_beleg . '" required>';
echo '<br><br>';
echo '<label for="form_back" class="left">';
echo 'Formularfeld: </label>';
echo '<input type="color" name="form_back" value="' . $form_back . '" required>';
echo '<br><br>';
echo '<label for="font" class="left">';
echo 'Schrift: </label>';
echo '<input type="color" name="font" value="' . $font . '" required>';
echo '<br><br>';
echo '</fieldset>';
echo '<br><br>';
echo '<fieldset>';
echo '<legend>Farben Jahreskalender</legend>';
echo '<br><br>';
echo '<label for="cal_month" class="left">';
echo 'Monate: </label>';
echo '<input type="color" name="cal_month" value="' . $cal_month . '" required>';
echo '<br><br>';
echo '</fieldset>';
echo '<br>';
echo '<fieldset>';
echo '<legend>Farben Monatskalender</legend>';
echo '<br><br>';
echo '<label for="cal_head" class="left">';
echo 'Monat/ Jahr: </label>';
echo '<input type="color" name="cal_head" value="' . $cal_head . '" required>';
echo '<br><br>';
echo '<label for="cal_days" class="left">';
echo 'Tage: </label>';
echo '<input type="color" name="cal_days" value="' . $cal_days . '" required>';
echo '<br><br>';
echo '<label for="cal_we" class="left">';
echo 'Wochenenden: </label>';
echo '<input type="color" name="cal_we" value="' . $cal_we . '" required>';
echo '<br><br>';
echo '<input type="hidden" name="objekt" value="' . $objekt . '">';
echo '<input type="hidden" name="in" value="des">';
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

