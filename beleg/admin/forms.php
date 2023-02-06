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
	

$controll = $remote;
if($controll != 24519){
	echo 'Kein Zugriff!';
	exit();
}
$fields = array("anrede", "name", "vorname", "str", "plz", "ort", "tel", "mail", "anzahl_gesamt","anzahl_erw", "anzahl_kinder", "tiere", "text");
$field_name = array("Anrede", "Name", "Vorname", "Straße", "PLZ", "Ort", "Telefon", "Email", "Anzahl Gäste", "Anzahl Erwachsene", "Anzahl Kinder", "Hunde", "Nachricht");
$obj = $_GET['objekt'];
?>
<html>
<head>
<style>


</style>
</head>
<body>
<h3>Wählen Sie Ihre Formularfelder</h3>
<div class="form_gen">
<h3>Auswahl</h3>
<?php
echo '<form action="index.php?in=formin&objekt='.$obj.'" method="post">';
	for ($i = 0; $i < count($fields); $i++){
		if ($i % 2 != 0){
			echo '<div class="gerade">';
		}else{
			echo '<div>';
		}
		echo '<label >';
		if ($i > 8){
			echo '<input type="checkbox" name="'.$fields[$i].'" value="1" tabindex="1">';
		}else{
			echo '<input type="checkbox" name="'.$fields[$i].'" value="1" checked tabindex="1">';
		}
        echo $field_name[$i].' &nbsp;';
        echo '</label>';
		echo '</div>';
}
?>
<button type="submit" name="action">Formular speichern</button>
</div>

<div class="form_gen">
<h3>Pflichtfeld</h3>
<?php
	for ($i = 0; $i < count($fields); $i++){
		if ($i % 2 != 0){
			echo '<div class="gerade">';
		}else{
			echo '<div>';
		}
		echo '<input type="radio" id="'.$fields[$i].'_pfl" name="'.$fields[$i].'_pfl" value="1" tabindex="1" checked>';
		echo '<label for="1">'; 
		echo 	'ja';
		echo '</label>';
		echo '<input type="radio" id="'.$fields[$i].'_pfl" name="'.$fields[$i].'_pfl" value="0" tabindex="1">';
		echo '<label for="0">'; 
		echo	'nein';
		echo '</label></div>';
		
	}
?>
</div>

</form>
