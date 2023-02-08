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

$controll = $remote;
if ($controll != 24519) {
    echo 'Kein Zugriff!';
    exit();
}
?>
<html>
    <head>
        <style>
<?php
include ('css/admin_css.php');
include_once ('includes/beleg-config.php');

$db_link = mysqli_connect(
        HOST, USER, PASSWORD, DATABASE
);
mysqli_set_charset($db_link, 'utf8');
?>
        </style>
        <script src="js/ckeditor/ckeditor.js"></script>
    </head>
    <body>
<?php
if (!empty($_POST['id_text']) && isset($_POST['id_text'])) {
    $id_text = $_POST['id_text'];
    $best_text = $_POST['best_text'];
    $buch_text = $_POST['buch_text'];
    $old_file = $_POST['old_file'];
    if(isset($_POST['del_file'])){
        $del_file = $_POST['del_file'];
    }else{
        $del_file = 0;
    }
    if ($_FILES['datei']['error'] == 4) {
        $anhang_pfad = $old_file;
    } else {
        $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
        $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = array('pdf', 'txt', 'doc', 'docx', 'odt');
        if (!in_array($extension, $allowed_extensions)) {
            die("Ungültige Dateiendung, nur Dateien mit der Endung 'pdf','txt','doc','docx','odt' erlaubt");
        }
        
        $anhang = 'files/upload/' . $filename . '.' . $extension;
        move_uploaded_file($_FILES['datei']['tmp_name'], $anhang);
        $anhang_pfad = 'files/upload/' . $filename . '.' . $extension;
    }
    if ($del_file == 1){
        $anhang_pfad = '';
    }
    $upd_text = "UPDATE `mail_text` SET `best_text` = ?, `buch_text` = ?, `anhang_buch` = ? WHERE `mail_text`.`id` = ?";
    $stmt = mysqli_prepare($db_link, $upd_text);
    mysqli_stmt_bind_param($stmt, 'sssi', $best_text, $buch_text, $anhang_pfad, $id_text);
    mysqli_stmt_execute($stmt);
    if (!$stmt) {
        die('Ungültige Abfrage: ' . mysqli_error($db_link));
    } else {
        echo 'Daten geändert<br>';
    }
}
?>
        <p>Hier können Sie Textbausteine eingeben, welche per Mail an Ihre Gäste versendet werden.</p>


<?php
$text = "SELECT id, best_text, buch_text, anhang_buch FROM mail_text ORDER BY ID DESC LIMIT 1";
$stmt = mysqli_prepare($db_link, $text);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($zeile_c = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $best_text = $zeile_c['best_text'];
    $id_text = $zeile_c['id'];
    $buch_text = $zeile_c['buch_text'];
    if(empty($zeile_c['anhang_buch'])){
    $anhang_buch = 'Keine Datei';
    }else{
    $anhang_buch = $zeile_c['anhang_buch'];
    }
}

echo '<br><div><form action=index.php?in=text method="post" enctype="multipart/form-data">';
echo '<fieldset>';
echo '<legend>Buchungsbestätigung</legend><br>';
echo '<label for="best_text" class="create">Text für die Buchungsbestätigung:</label><br><br><p><b>Die Anrede und der Betreff werden automatisch erzeugt.</b><br>
z. Bsp.:</p> 
<p>Sehr geehrte Frau Mustermann,</p>
<p>Hiermit bestätigen wir Ihre Buchung vom 5.5.2018 bis 10.05.2018.<br>
<i>Hier kommt dann Ihr Text</i></p><textarea name="best_text" cols="100" rows="20">' . htmlspecialchars($best_text) . '</textarea><br><br>';
echo '<p>Anhänge wie Rechnung oder AGB´s können während der Buchungsbestätigung hinzugefügt werden.</p>';
echo '</fieldset><br><br>';
echo '<fieldset>';
echo '<legend>Buchungsanfrage</legend><br>';
echo '<label for="buch_text" class="create">Text für die Buchungsanfrage:</label><br><br><p><b>Die Anrede wird automatisch erzeugt.</b><br>
z. Bsp.:</p> 
<p>Sehr geehrte Frau Mustermann,</p>
<p>
<i>Hier kommt dann Ihr Text</i></p><textarea name="buch_text" cols="100" rows="20">' . htmlspecialchars($buch_text) . '</textarea><br>';
echo '<p>Hier können Sie eine Datei, z.B. Ihre AGB´s auswählen.</p>';
echo '<label for="datei">Anhang: <input name="datei" type="file" size="50" accept="file/*"></label><br>';
echo 'Ausgewählte Datei: '.$anhang_buch.'<br>';
echo '<input type="checkbox" id="del_file" name="del_file" value="1">';
echo '<label for="del_file"> keine Datei wählen</label><br><br>';
echo '<input type="hidden" name="old_file" value="' . $anhang_buch . '">';
echo '<input type="hidden" name="id_text" value="' . $id_text . '">';
echo '</fieldset><br>';
echo '<input type="submit" name="senden" value="speichern">';
?>
    </form>    
    <script>CKEDITOR.replace('best_text', {
            height: 300,
            filebrowserBrowseUrl: 'fileman/index.html',
            filebrowserImageBrowseUrl: 'fileman/index.html?type=Images',
        });
    </script>
    <script>CKEDITOR.replace('buch_text', {
            height: 300,
            filebrowserBrowseUrl: 'fileman/index.html',
            filebrowserImageBrowseUrl: 'fileman/index.html?type=Images',
        });
    </script>
