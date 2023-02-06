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

?>
<html>
<head>
<style>
<?php
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
<body>
<?php
if(!empty($_POST['id_text']) && isset($_POST['id_text'])){
	$id_text = $_POST['id_text'];
	$best_text = $_POST['best_text'];
	$buch_text = $_POST['buch_text'];
	$upd_text = "UPDATE `mail_text` SET `best_text` = ?, `buch_text` = ? WHERE `mail_text`.`id` = ?";
	$stmt = mysqli_prepare ($db_link, $upd_text);
	mysqli_stmt_bind_param ($stmt, 'ssi', $best_text, $buch_text, $id_text);
	mysqli_stmt_execute($stmt);
	if ( ! $stmt ){
		die('Ungültige Abfrage: ' . mysqli_error($db_link));
	}else{
		echo 'Daten geändert<br>';
	}
}
?>
<p>Hier können Sie Textbausteine eingeben, welche per Mail an Ihre Gäste versendet werden.</p>


Sie können den Text auch mit html-Tags formatieren.<br>

<?php
$text = "SELECT id, best_text, buch_text FROM mail_text ORDER BY ID DESC LIMIT 1";
$stmt = mysqli_prepare($db_link, $text);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($zeile_c = mysqli_fetch_array( $result, MYSQLI_ASSOC)){
	$best_text = $zeile_c['best_text'];
	$id_text = $zeile_c['id'];
	$buch_text = $zeile_c['buch_text'];
}	
echo '<br><div><form action=index.php?in=text method="post">';
echo '<fieldset>';
echo '<br>';
echo '<legend>Emailtexte bearbeiten: </legend>';
echo '<label for="best_text" class="create">Text für die Buchungsbestätigung:</label><br><br><p><b>Die Anrede und der Betreff werden automatisch erzeugt.</b><br>
z. Bsp.:</p> 
<p>Sehr geehrte Frau Mustermann,</p>
<p>Hiermit bestätigen wir Ihre Buchung vom 5.5.2018 bis 10.05.2018.<br>
<i>Hier kommt dann Ihr Text</i></p><textarea name="best_text" cols="100" rows="20">'.$best_text.'</textarea><br><br>';
echo '<label for="buch_text" class="create">Text für die Buchungsanfrage:</label><br><br><p><b>Die Anrede wird automatisch erzeugt.</b><br>
z. Bsp.:</p> 
<p>Sehr geehrte Frau Mustermann,</p>
<p>
<i>Hier kommt dann Ihr Text</i></p><textarea name="buch_text" cols="100" rows="20">'.$buch_text.'</textarea><br>';
echo '<input type="hidden" name="id_text" value="'.$id_text.'">';
echo '<input type="submit" name="senden" value="speichern">';

		
?>
