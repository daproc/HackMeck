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
	
require_once ('includes/beleg-config.php');
$db_link = mysqli_connect (
                     HOST, 
                     USER,
                     PASSWORD, 
                     DATABASE
                    );
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
<link rel="stylesheet" href="css/book.css">
<style>
<?php
$objekt = $_GET['objekt'];
$people = "SELECT max_people, max_tier FROM objekt WHERE id=?";
$stmt = mysqli_prepare ($db_link, $people);
mysqli_stmt_bind_param ($stmt, 's', $objekt);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($zeile_c = mysqli_fetch_array( $result, MYSQLI_ASSOC)){
	$max_pers = $zeile_c['max_people'];
	$max_tier = $zeile_c['max_tier'];
}	
$remote = 24519;
//$max_pers = 5;
$max_kind = $max_pers - 1;
//$max_tier = 2;	
if (!empty($_GET[date])){
$timestamp = strtotime($_GET[date]);
}else{
$timestamp = strtotime("now");
}
$an = date("Y-m-d", $timestamp);
$ab = date("Y-m-d", strtotime('+5 days', $timestamp));

?>
</style>
</head>
<body id="kontaktseite" onload="document.booking.ab.focus();">
<?php

$form = "SELECT json_code FROM forms WHERE objektid=?";
$stmt = mysqli_prepare ($db_link, $form);
mysqli_stmt_bind_param ($stmt, 's', $objekt);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
//$db_erg = mysqli_query( $db_link, $obj );
while ($zeile_c = mysqli_fetch_array( $result, MYSQLI_ASSOC)){
	$json_code = $zeile_c['json_code'];
}	
$bookingform = json_decode($json_code, true);

echo '<form action="insert_book.php" name="booking" method="post" class="alter">';
echo '<div class="formular">';
echo '<fieldset>';
echo '<legend>Buchungsanfrage: </legend>';
echo '<p>Mit * gekennzeichnete Felder sind Pflichtfelder</p>';
echo '<label for="an" class="create">Anreise:</label><input type="date" name="an" value="'.$an.'"><br>';
echo '<label for="ab" class="create">Abreise:</label><input type="date" name="ab" value="'.$ab.'"><br>';
for ($i = 0; $i < count($bookingform); $i++){
	$newform = str_replace("max_pers", "$max_pers", $bookingform[$i]);
	$newform = str_replace("max_kind", "$max_kind", $newform);
	$newform = str_replace("max_tier", "$max_tier", $newform);
	echo $newform.'<br>';
}
echo '<input type="hidden" name="objekt" value="'.$objekt.'">';
echo '<button type="submit" name="action">senden</button>';
echo '</fieldset>';
echo '</form>';
?>