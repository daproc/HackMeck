<?php
#####################################
#Belegungsplan 0.6					#
#©2017 Daniel ProBer alias HackMeck	#
#http://hackmeck.bplaced.net		#
#GERMANY							#
#									#
#Mail: daproc@gmx.net				#
#Paypal: daproc@gmx.net				#
#									#
#Zeigt einen Kalender mit 			#
#Belegung für ein Objekt an.		#
#z.B. Ferienwohnung 				#
#####################################

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
include ('css/insert_css.php');
?>
</style>
</head>
<p>Wählen sie zwischen Jahres- und Monatsansicht oder Automatisch.<br>
Bei Automatisch wird auf Smartphones der Monatskalender und bei Computern der Jahreskalender angezeigt.</p>

<?php
$settings = "SELECT cal_typ FROM settings WHERE id = 1" ;
 
$db_erg = mysqli_query( $db_link, $settings );
if ( ! $db_erg )
{
  die('Ungültige Abfrage: ' . mysqli_error());
}

if(empty($_GET['cal_typ'])) //Prüfen ob Link ausgewählt wurde
	{
	while ($zeile_s = mysqli_fetch_array( $db_erg, MYSQLI_ASSOC)){
	$cal_typ = $zeile_s['cal_typ'];
	}
}else {
	$cal_typ = $_GET['cal_typ'];
	$update  = "UPDATE `settings` SET `cal_typ` = '".$cal_typ."' WHERE `settings`.`id` = 1";
	$write = mysqli_query($db_link, $update);
}


	echo '<form action="index.php" method="get" class="color">';
	echo '<div class="formular">';
	echo '<fieldset>';
	echo '<legend>Anzeige</legend>';
	echo '<br><br>';
	if($cal_typ == 2){
		echo '<input type="radio" id="jahr" name="cal_typ" value="2" checked>';
		echo '<label for="jahr"> Jahresansicht</label>';
		echo '<br><br>';
		echo '<input type="radio" id="monat" name="cal_typ" value="1">';
		echo '<label for="monat"> Monatsansicht</label>';
		echo '<br><br>';
		echo '<input type="radio" id="resp" name="cal_typ" value="3">';
		echo '<label for="monat"> Automatisch (empfohlen)</label>';
	}elseif($cal_typ == 1){
		echo '<input type="radio" id="jahr" name="cal_typ" value="2">';
		echo '<label for="jahr"> Jahresansicht</label>';
		echo '<br><br>';
		echo '<input type="radio" id="monat" name="cal_typ" value="1" checked>';
		echo '<label for="monat"> Monatsansicht</label>';
		echo '<br><br>';
		echo '<input type="radio" id="resp" name="cal_typ" value="3">';
		echo '<label for="monat"> Automatisch (empfohlen)</label>';
	}else{
		echo '<input type="radio" id="jahr" name="cal_typ" value="2">';
		echo '<label for="jahr"> Jahresansicht</label>';
		echo '<br><br>';
		echo '<input type="radio" id="monat" name="cal_typ" value="1">';
		echo '<label for="monat"> Monatsansicht</label>';
		echo '<br><br>';
		echo '<input type="radio" id="resp" name="cal_typ" value="3" checked>';
		echo '<label for="monat"> Automatisch (empfohlen)</label>';
	}
	echo '<br><br>';
	echo '<input type="hidden" name="objekt" value="'.$objekt.'">';
	echo '<input type="hidden" name="in" value="scr">';
	echo '<input type="submit" value="speichern">';
	echo '</fieldset></div></form>';

?>

<div id = "iframe">
<h4>Vorschau</h4>
 <iframe src="../index.php?objekt=<?php echo $objekt; ?>" width="100%" height="421"  frameborder="0">
      <p>Ihr Browser kann leider keine eingebetteten Frames anzeigen:
      Sie können die eingebettete Seite über den folgenden Verweis aufrufen: 
      <a href="../cal.php">Kalender</a>
      </p>
    </iframe>
</div>

