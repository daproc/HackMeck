<?php
#########################################
#Belegungsplan  			#
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
	
?>
<html>
<head>
<style>
<?php
$controll = $remote;
if($controll != 24519){
	echo 'Kein Zugriff!';
	exit();
}


include ('css/admin_css.php');
include ('includes/beleg-config.php');

$db_link = mysqli_connect(
                     HOST, 
                     USER,
                     PASSWORD, 
                     DATABASE
                    );
mysqli_set_charset($db_link, 'utf8');					

?>
</style>
</head>
<p>Buchungsanfrage bearbeiten</p>
<?php
if(isset($_GET['bookingnr']) && !empty($_GET['bookingnr'])){
	$nr = $_GET['bookingnr'];
	list($user_id, $times_id, $booking_id) = explode("-", $nr);
}else{
	echo 'Keine Buchungsanfrage ausgewählt';
	die();
}
$user = "SELECT * FROM guests WHERE id=? ";
$stmt = mysqli_prepare($db_link, $user);
mysqli_stmt_bind_param($stmt, 's', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
echo '<table>';
while ($zeile_c = mysqli_fetch_array( $result, MYSQLI_ASSOC)){
	echo '<tr><td>Anrede:</td><td>'.$zeile_c['anrede'].'</td></tr>';
	echo '<tr><td>Name:</td><td>'.$zeile_c['vorname'].' '.$zeile_c['nname'].'</td></tr>';
	echo '<tr><td>Str.:</td><td>'.$zeile_c['str'].'</td></tr>';
	echo '<tr><td>PLZ Ort:</td><td>'.$zeile_c['plz'].' '.$zeile_c['ort'].'</td></tr>';
	echo '<tr><td>Tel:</td><td>'.$zeile_c['tel'].'</td></tr>';
	echo '<tr><td>Email:</td><td>'.$zeile_c['email'].'</td></tr>';	
}
$book = "SELECT * FROM booking WHERE id=? AND guest_id=? AND times_id=?";
$stmt = mysqli_prepare($db_link, $book);
mysqli_stmt_bind_param($stmt, 'sss', $booking_id, $user_id, $times_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($zeile_c = mysqli_fetch_array( $result, MYSQLI_ASSOC)){
	echo '<tr><td>Gesamte Personen:</td><td>'.$zeile_c['anzahl_pers'].'</td></tr>';
	echo '<tr><td>Erwachsene:</td><td>'.$zeile_c['anzahl_erw'].'</td></tr>';
	echo '<tr><td>Kinder:</td><td>'.$zeile_c['anzahl_kind'].'</td></tr>';
	echo '<tr><td>Hunde:</td><td>'.$zeile_c['anzahl_tier'].'</td></tr>';
	echo '<tr><td>Nachricht:</td><td>'.$zeile_c['text'].'</td></tr>';	
}
$time = "SELECT * FROM times WHERE id=? AND user=? ";
$stmt = mysqli_prepare($db_link, $time);
mysqli_stmt_bind_param($stmt, 'ss', $times_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($zeile_c = mysqli_fetch_array( $result, MYSQLI_ASSOC)){
	echo '<tr><td>Anreise:</td><td>'.$zeile_c['datean'].'</td></tr>';
	echo '<tr><td>Abreise:</td><td>'.$zeile_c['dateab'].'</td></tr>';
	$obj_id = $zeile_c['objekt_id'];
}
$obj = "SELECT name FROM objekt WHERE id=?";
$stmt = mysqli_prepare($db_link, $obj);
mysqli_stmt_bind_param($stmt, 's', $obj_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($zeile_c = mysqli_fetch_array( $result, MYSQLI_ASSOC)){
	echo '<tr><td>Objekt:</td><td>'.$zeile_c['name'].'</td></tr>';
}	
echo '</table>';
if(isset($_GET['validate']) && !empty($_GET['validate'])){
	$conf = 0;
	$upd_times = "UPDATE `times` SET `confirmed` = ? WHERE `times`.`id` = ?";
	$stmt = mysqli_prepare ($db_link, $upd_times);
	mysqli_stmt_bind_param ($stmt, 'ii', $conf, $times_id);
	mysqli_stmt_execute($stmt);
	if ( ! $stmt ){
		die('Ungültige Abfrage: ' . mysqli_error($db_link));
	}else{
		echo 'Buchungsbestätigung entzogen, diese Buchung ist jetzt im Kalender frei gegeben.<br>';
	}
}else{
	echo '<a href="index.php?in=vali_re&bookingnr='.$nr.'&validate=re&time='.$times_id.'">Buchungsbestätigung entziehen</a>';
}

?>