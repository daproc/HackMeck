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

require_once ('includes/beleg-config.php');
$pdo = new PDO(SERVER, USER, PASSWORD, $options);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="style/book.css">
        <title>Buchung</title> 
    </head>
    <body id="kontaktseite" onload="document.booking.ab.focus();">
<?php
if (filter_input(INPUT_GET, 'objekt', FILTER_VALIDATE_INT)) {
    $objekt = filter_input(INPUT_GET, 'objekt', FILTER_VALIDATE_INT); //Objekt uebernehmen
} else {
    echo '<div class="error">Kein gültiges Objekt gewählt</div>';
    die();
}
$max_pers = 0;
$max_tier = 0;
$exists = false;
$people = $pdo->prepare("SELECT max_people, max_tier FROM objekt WHERE id=?");
$people->execute(array($objekt)); 
while($zeile_c = $people->fetch()) {
    $max_pers = $zeile_c['max_people'];
    $max_tier = $zeile_c['max_tier'];
    $exists = true;
}
if ($exists != true) {
    /** @todo In der CSS heisst die Klasse allerdings "fehler"... */
    echo '<div class="error">Objekt existiert nicht!</div>';
}
$remote = 24519;
$max_kind = $max_pers - 1;
// NULL in $timestamp nach date() im Fehlerfall sorgt auch fuer den Tag heute.
$timestamp = NULL;
if (filter_input(INPUT_GET, 'date', FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"#^[0-9]{4}[-][0-9]{1,2}[-][0-9]{1,2}$#")))) {
    $timestamp = strtotime($_GET['date']); //Anreise uebernehmen
} else {
    echo '<div class="error">Ein Fehler ist aufgetreten!</div>';
}
/*if (!empty($_GET['date'])) {
    $timestamp = strtotime($_GET['date']);
} else {
    $timestamp = strtotime("now");
}*/
$an = date("Y-m-d", $timestamp);
$ab = date("Y-m-d", strtotime('+5 days', $timestamp));
?>
        <?php
        $form = $pdo->prepare("SELECT json_code FROM forms WHERE objektid=?");
        $form->execute(array($objekt));
        $json_code = "[]";
        while ($zeile_c = $form->fetch()) {
            $json_code = $zeile_c['json_code'];
        }
        $bookingform = json_decode($json_code, true);

        echo '<form action="insert_book.php" name="booking" method="post" class="alter">';
        echo '<div class="formular">';
        echo '<fieldset>';
        echo '<legend>Buchungsanfrage: </legend>';
        echo '<p>Mit * gekennzeichnete Felder sind Pflichtfelder</p>';
        echo '<label for="an" class="create">Anreise:</label><input type="date" name="an" value="' . $an . '"><br>';
        echo '<label for="ab" class="create">Abreise:</label><input type="date" name="ab" value="' . $ab . '"><br>';
        for ($i = 0; $i < count($bookingform); $i++) {
            $newform = str_replace("max_pers", "$max_pers", $bookingform[$i]);
            $newform = str_replace("max_kind", "$max_kind", $newform);
            $newform = str_replace("max_tier", "$max_tier", $newform);
            echo $newform . '<br>';
        }
        echo '<input type="hidden" name="objekt" value="' . $objekt . '">';
        echo '<button type="submit" name="action">senden</button>';
        echo '</fieldset>';
        echo '</form>';
        ?>
    </body>
</html>
