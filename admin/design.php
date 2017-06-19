<?php
#########################################
#Belegungsplan 0.6			#
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

/*	Belegungsplan ist Freie Software: Sie können ihn unter den Bedingungen
    der GNU General Public License, wie von der Free Software Foundation,
    Version 2 der Lizenz weiterverbreiten und/oder modifizieren.

    Belegungsplan wird in der Hoffnung, dass er nützlich sein wird, aber
    OHNE JEDE GEWÄHRLEISTUNG, bereitgestellt; sogar ohne die implizite
    Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
    Siehe die GNU General Public License für weitere Details.

    Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
    Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>
*/
	

if(isset($_GET['jahr'])and !empty($_GET['jahr'])) //Prüfen ob Jahr ausgewählt wurde
	{
	$jahr = $_GET['jahr'];  //Jahr uebernehmen
}else {
	$jahr = date("Y");      //Ohne Auswahl aktuelles Jahr übernehmen
}
$controll = $remote;
if($controll != 24519){
	echo 'Kein Zugriff!';
	exit();
}
?>
<html>
<head>
<style>
<?php
$objekt = $_GET['objekt'];
include ('css/admin_css.php');
include ('../css/insert_css.php');
?>
</style>
</head>
<p>Hier können Sie die Farben des auf der Seite angezeigten Kalender anpassen.</p>
<p>
<ul>
	<li>Seite - Der Hintergrund der Gesamten Seite</li>
	<li>Tabelle - Der Hintergrund der Tabelle und der nicht belegten Tage</li>
	<li>Belegung - Die Farbe, mit der belegte Tage markiert werden</li>
	<li>Monate - Die Hintergrundfarbe der Monatsnamen</li>
	<li>Formularfeld - Die Hintergundfarbe des ausgewählten Formulars für die Jahreszahlen</li>
</ul>
</p> 
<?php
$colors = "SELECT cal_month, cal_beleg, form_back, cal_back, back, font FROM color WHERE id = 1" ;
 
$db_erg = mysqli_query( $db_link, $colors );
if ( ! $db_erg )
{
  die('Ungültige Abfrage: ' . mysqli_error());
}

if(empty($_GET['cal_month']) AND empty($_GET['cal_beleg']) AND empty($_GET['cal_back']) AND empty($_GET['form_back']) AND empty($_GET['back']) AND empty($_GET['font'])) //Prüfen ob Link ausgewählt wurde
	{
	while ($zeile_c = mysqli_fetch_array( $db_erg, MYSQLI_ASSOC)){
	$cal_beleg = $zeile_c['cal_beleg'];
	$cal_month = $zeile_c['cal_month'];
	$form_back = $zeile_c['form_back'];
	$cal_back = $zeile_c['cal_back'];
	$back = $zeile_c['back'];
	$font = $zeile_c['font'];
}
}else {
	$cal_beleg = $_GET['cal_beleg'];
	$cal_month = $_GET['cal_month'];
	$form_back = $_GET['form_back'];
	$cal_back = $_GET['cal_back'];
	$back = $_GET['back'];
	$font = $_GET['font'];
	if (!preg_match("#^[0-9 a-f \#\]]+$#", $cal_beleg) OR !preg_match("#^[0-9 a-f \#\]]+$#", $cal_month) OR !preg_match("#^[0-9 a-f \#\]]+$#", $cal_back) OR !preg_match("#^[0-9 a-f \#\]]+$#", $form_back) OR !preg_match("#^[0-9 a-f \#\]]+$#", $back) OR !preg_match("#^[0-9 a-f \#\]]+$#", $font)) {
		echo 'Nur Hexadezimale Farbangaben erlaubt!';
		exit();
	}else{		
	$update  = "UPDATE `color` SET `cal_month` = '".$cal_month."', `cal_beleg` = '".$cal_beleg."', `form_back` = '".$form_back."', `cal_back` = '".$cal_back."', `back` = '".$back."', `font` = '".$font."' WHERE `color`.`id` = 1";
	$write = mysqli_query($db_link, $update);
	}
}

	echo '<form action="index.php" method="get" class="color">';
	echo '<div class="formular">';
	echo '<fieldset>';
	echo '<legend>Hintergründe</legend>';
	echo '<br><br>';
	echo '<label for="back" class="left">';
    echo 'Seite: </label>';
	echo '<input type="color" name="back" value="'.$back.'" required>';
	echo '<br><br>';
	echo '<label for="cal_back" class="left">';
    echo 'Tabelle: </label>';
	echo '<input type="color" name="cal_back" value="'.$cal_back.'" required>';
	echo '<br><br>';
	echo '<label for="cal_beleg" class="left">';
	echo 'Belegung: </label>';
	echo '<input type="color" name="cal_beleg" value="'.$cal_beleg.'" required>';
	echo '<br><br>';
	echo '<label for="cal_month" class="left">';
    echo 'Monate: </label>';
	echo	'<input type="color" name="cal_month" value="'.$cal_month.'" required>';
	echo '<br><br>';
	echo '<label for="form_back" class="left">';
    echo 'Formularfeld: </label>';
	echo	'<input type="color" name="form_back" value="'.$form_back.'" required>';
	echo '<br><br>';
	echo '</fieldset>';
	echo '<br>';
	echo '<fieldset>';
	echo '<legend>Schriftfarbe</legend>';
	echo '<br><br>';
	echo '<label for="font" class="left">';
    echo 'Schrift: </label>';
	echo	'<input type="color" name="font" value="'.$font.'" required>';
	echo '<br><br>';
	echo '<input type="hidden" name="objekt" value="'.$objekt.'">';
	echo '<input type="hidden" name="in" value="des">';
	echo	'<input type="submit" value="speichern">';
	echo '</fieldset></div></form>';

?>

<div id = "iframe">
<h4>Vorschau</h4>
 <iframe src="../index.php?objekt=<?php echo $objekt; ?>" width="100%" height="421" frameborder="0">
      <p>Ihr Browser kann leider keine eingebetteten Frames anzeigen:
      Sie können die eingebettete Seite über den folgenden Verweis aufrufen: 
      <a href="../index.php">Kalender</a>
      </p>
    </iframe>
</div>

