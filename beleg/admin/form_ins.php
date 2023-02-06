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
	
$controll = $remote;
if($controll != 24519){
	echo 'Kein Zugriff!';
	exit();
}


$db_link = mysqli_connect(
                     HOST, 
                     USER,
                     PASSWORD, 
                     DATABASE
                    );
mysqli_set_charset($db_link, 'utf8');
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



$max_kind = $max_pers - 1;

$fields = array("anrede", "name", "vorname", "str", "plz", "ort", "tel", "mail", "anzahl_gesamt","anzahl_erw", "anzahl_kinder", "tiere", "text");
for ($i = 0; $i < count($fields); $i++){
	if (array_key_exists($fields[$i], $_POST)) {
		if ($_POST[$fields[$i]] == 1 && $_POST[$fields[$i].'_pfl'] == 1){
			${$fields[$i]} = 2;
		}else{
			${$fields[$i]} = 1;
		}
	}else{
		${$fields[$i]} = 0;
	}
}

if ($anrede == 2){
	$form = array('<label for="anrede" class="create">Anrede*: </label>
		<select name="anrede" size="1" required>
			  <option>Frau</option>
			  <option>Herr</option>
			  <option>Firma</option>
		</select>');
}elseif ($anrede == 1){
	$form = array('<label for="anrede" class="create">Anrede: </label>
		<select name="anrede" size="1">
			  <option>Frau</option>
			  <option>Herr</option>
			  <option>Firma</option>
		</select>');  
}
if ($name == 2){
	$form[] = '<label for="name" class="create">Name*:</label>
        <input type="text" id="name" name="name" required>';
}elseif ($name == 1){
	$form[] = '<label for="name" class="create">Name:</label>
        <input type="text" id="name" name="name">';
}
if ($vorname == 2){
	$form[] = '<label for="vorname" class="create">Vorname*:</label>
        <input type="text" id="vorname" name="vorname" required>';
}elseif ($vorname == 1){
	$form[] = '<label for="vorname" class="create">Vorname:</label>
        <input type="text" id="vorname" name="vorname">';
}
if ($str == 2){
	$form[] = '<label for="str" class="create">Straße*:</label>
        <input type="text" id="str" name="str" required>';
}elseif ($str == 1){
	$form[] = '<label for="str" class="create">Straße:</label>
        <input type="text" id="str" name="str">';
}
if ($plz == 2){
	$form[] = '<label for="plz" class="create">Postleitzahl*:</label>
        <input type="number" id="plz" name="plz" size="5" required>';
}elseif ($plz == 1){
	$form[] = '<label for="plz" class="create">Postleitzahl:</label>
        <input type="number" id="plz" name="plz" size="5">';
}
if ($ort == 2){
	$form[] = '<label for="ort" class="create">Ort*:</label>
        <input type="text" id="ort" name="ort" required>';
}elseif ($ort == 1){
	$form[] = '<label for="ort" class="create">Ort:</label>
        <input type="text" id="ort" name="ort">';
}
if ($tel == 2){
	$form[] = '<label for="tel" class="create">Telefon*:</label>
        <input type="number" id="tel" name="tel" size="15" required>';
}elseif ($tel == 1){
	$form[] = '<label for="tel" class="create">Telefon:</label>
        <input type="number" id="tel" name="tel" size="15">';
}
if ($mail == 2){
	$form[] = '<label for="mail" class="create">Email*:</label><input type="email" name="mail" required>';
}elseif ($mail == 1){
	$form[] = '<label for="mail" class="create">Email:</label><input type="email" name="mail">';
}
if ($anzahl_gesamt == 2){
	$form[] = '<label for="anzahl_gesamt" class="create">Anzahl Personen*:</label><input type="number" name="anzahl_gesamt" min="1" max="max_pers" size="1" required>';
}elseif ($anzahl_gesamt == 1){
	$form[] = '<label for="anzahl_gesamt" class="create">Anzahl Personen:</label><input type="number" name="anzahl_gesamt" min="1" max="max_pers" size="1">';
}
if ($anzahl_erw == 2){
	$form[] = '<label for="anzahl_erw" class="create">Anzahl Erwachsene*:</label><input type="number" name="anzahl_erw" min="1" max="max_pers" size="1" required>';
}elseif ($anzahl_erw == 1){
	$form[] = '<label for="anzahl_erw" class="create">Anzahl Erwachsene: </label><input type="number" name="anzahl_erw" min="1" max="max_pers" size="1">';
}
if ($anzahl_kinder == 2){
	$form[] = '<label for="anzahl_kinder" class="create">Anzahl Kinder*: </label><input type="number" name="anzahl_kind" min="1" max="max_kind" size="1" required>';
}elseif ($anzahl_kinder == 1){
	$form[] = '<label for="anzahl_kinder" class="create">Anzahl Kinder: </label><input type="number" name="anzahl_kind" min="1" max="max_kind" size="1">';
}
if ($tiere == 2){
	$form[] = '<label for="tiere" class="create">Anzahl Tiere*: </label><input type="number" name="tiere" min="1" max="max_tier" size="1" required>';
}elseif ($tiere == 1){
	$form[] = '<label for="tiere" class="create">Anzahl Tiere: </label><input type="number" name="tiere" min="1" max="max_tier" size="1">';
}
if ($text == 2){
	$form[] = '<label for="text" class="create">Nachricht*: </label><textarea name="text" required></textarea>';
}elseif ($text == 1){
	$form[] = '<label for="text" class="create">Nachricht: </label><textarea name="text"></textarea>';
}


echo '<form>';
for ($i = 0; $i < count($form); $i++){
	$newform = str_replace("max_pers", "$max_pers", $form[$i]);
	$newform = str_replace("max_kind", "$max_kind", $newform);
	$newform = str_replace("max_tier", "$max_tier", $newform);
	echo $newform.'<br>';
}
echo '</form>';
$json_code = json_encode($form);
$objekt_id = $_GET['objekt'];
$ins_obj = "INSERT INTO forms (json_code, objektid) VALUES (?, ?) ON DUPLICATE KEY UPDATE json_code = ?";
	$stmt = mysqli_prepare ($db_link, $ins_obj);
	mysqli_stmt_bind_param ($stmt, 'sis', $json_code, $objekt_id, $json_code);
	mysqli_stmt_execute($stmt);
	if ( ! $stmt ){
		die('Ungültige Abfrage: ' . mysqli_error());
	}else{
		echo '<br>Formular angelegt<br>';
		mysqli_close($db_link);
		die();
	}
?>