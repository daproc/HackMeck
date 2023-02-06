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
?>
<html>
    <head>
        <style>
<?php
$controll = $remote;
if ($controll != 24519) {
    echo 'Kein Zugriff!';
    exit();
}
if (isset($_GET['objekt'])and ! empty($_GET['objekt'])) {
    $id = $_GET['objekt'];
}

include ('css/admin_css.php');
include ('includes/beleg-config.php');

$db_link = mysqli_connect(
        HOST, USER, PASSWORD, DATABASE
);
mysqli_set_charset($db_link, 'utf8');
?>
        </style>
    </head>
    <p>Verwalten sie Ihre Ferienobjekte hier</p>
    <?php
// Neues Objekt anlegen
    if (isset($_GET['name']) AND ! empty($_GET['name']) AND ! isset($_GET['objekt'])) {
        $name = $_GET['name'];
        $max_per = $_GET['max_per'];
        $max_tier = $_GET['max_tier'];
        $preis = $_GET['preis'];
        $zufall1 = mt_rand(11111111, 99999999);
        $zufall2 = mt_rand(11111111, 99999999);
        $zufall3 = mt_rand(11111111, 99999999);
        $exp_uri = $name . $zufall1 . $zufall2 . $zufall3 . '.ical';
        $ins_obj = "INSERT INTO objekt (name, max_people, max_tier, price, export_uri) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db_link, $ins_obj);
        mysqli_stmt_bind_param($stmt, 'siiis', $name, $max_per, $max_tier, $preis, $exp_uri);
        mysqli_stmt_execute($stmt);
        if (!$stmt) {
            die('Ungültige Abfrage: ' . mysqli_error());
        } else {
            $new_id = mysqli_insert_id($db_link);
            echo 'Objekt angelegt<br>';
            echo '<a href="index.php?in=alt">weiter</a> | ';
            echo '<a href="index.php?in=forms&objekt=' . $new_id . '">Formular anlegen</a>';
            mysqli_close($db_link);
            die();
        }
    }
// Objekt bearbeiten
    if (isset($_GET['name']) AND ! empty($_GET['name']) AND isset($_GET['objekt'])) {
        $name = $_GET['name'];
        $max_per = $_GET['max_per'];
        $max_tier = $_GET['max_tier'];
        $preis = $_GET['preis'];
        $id = $_GET['objekt'];
        $upd_obj = "UPDATE `objekt` SET `name` = ?, `max_people` = ?, `max_tier` = ?, `price` = ? WHERE `objekt`.`id` = ?";
        $stmt = mysqli_prepare($db_link, $upd_obj);
        mysqli_stmt_bind_param($stmt, 'siiii', $name, $max_per, $max_tier, $preis, $id);
        mysqli_stmt_execute($stmt);
        if (!$stmt) {
            die('Ungültige Abfrage: ' . mysqli_error());
        } else {
            echo 'Daten geändert<br>';
        }
    }
//Datenbankabfrage
    $obj = "SELECT * FROM objekt";
    $db_erg = mysqli_query($db_link, $obj);
//Formular für neues Objekt
    if (isset($_GET['id']) AND $_GET['id'] == 'new') {
        echo '<a href="index.php?in=alt&id=new">Neues Objekt anlegen</a><hr>';
        while ($zeile_c = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
            $name = $zeile_c['name'];
            echo '<p>' . $zeile_c['id'] . ' | ';
            echo $zeile_c['name'] . ' | ';
            echo '<a href="index.php?in=alt&objekt=' . $zeile_c['id'] . '">bearbeiten</a> | ';
            echo '<a href="index.php?in=obr&objekt=' . $zeile_c['id'] . '">löschen</a></p><hr>';
        }
        echo '<form action="index.php" method="get" class="alter">';

        echo '<div class="formular">';
        echo '<fieldset>';
        echo '<legend>Ferienobjekt: </legend>';
        echo '<p>Mit * gekennzeichnete Felder sind Pflichtfelder</p>';
        echo '<label for="name" class="left">';
        echo 'Name:* </label>';
        echo '<input type="text" name="name" required>';
        echo '<br><br>';
        echo '<label for="max_per" class="left">';
        echo 'Max Gäste</label>';
        echo '<input type="text" name="max_per">';
        echo '<br><br>';
        echo '<label for="max_tier" class="left">';
        echo 'Max Hunde</label>';
        echo '<input type="text" name="max_tier">';
        echo '<br><br>';
        echo '<label for="preis" class="left">';
        echo 'Preis: </label>';
        echo '<input type="text" name="preis">€';
        echo '<br><br>';
        echo '<input type="hidden" name="in" value="alt">';
        echo '<input type="submit" value="speichern">';
        echo '</fieldset></div></form>';
    }
// Anzeige aller Objekte und Abbruch wenn keine Ferienwohnung gewählt wurde 
    if (!isset($_GET['objekt'])and empty($_GET['objekt'])) {
        echo '<a href="index.php?in=alt&id=new">Neues Objekt anlegen</a><hr>';
        while ($zeile_c = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
            $name = $zeile_c['name'];
            echo '<p>' . $zeile_c['id'] . '. ';
            echo $zeile_c['name'] . ' | ';
            echo '<a href="index.php?in=alt&objekt=' . $zeile_c['id'] . '">bearbeiten</a> | ';
            echo '<a href="index.php?in=obr&objekt=' . $zeile_c['id'] . '">löschen</a></p><hr>';
        }
        die();
// Anzeige aller Objekte mit Link zum Bearbeiten und löschen
    } else {
        echo '<a href="index.php?in=alt&id=new">Neues Objekt anlegen</a><hr>';
        while ($zeile_c = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
            $name = $zeile_c['name'];
            $obj_id = $zeile_c['id'];
            echo '<p>' . $zeile_c['id'] . '. ';
            echo $zeile_c['name'] . ' | ';
            echo '<a href="index.php?in=alt&objekt=' . $zeile_c['id'] . '">bearbeiten</a> | ';
            echo '<a href="index.php?in=obr&objekt=' . $zeile_c['id'] . '">löschen</a></p><hr>';
        }
    }

    $obj = "SELECT * FROM objekt WHERE id=?";
    $stmt = mysqli_prepare($db_link, $obj);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
//$db_erg = mysqli_query( $db_link, $obj );
    while ($zeile_c = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $name = $zeile_c['name'];
        $max_people = $zeile_c['max_people'];
        $max_tier = $zeile_c['max_tier'];
        $price = $zeile_c['price'];
        $description = $zeile_c['description'];
    }
    ?>

    <br>
    <p>Folgenden Code fügen Sie einfach an der Stelle ein, an der der Belegungsplan für die Wohnung "<?php echo $name; ?>" angezeigt werden soll.</p><p>
        <?php
//Anzeige von <iframe> verweis für gewähltes Objekt
        $path = $_SERVER['REQUEST_URI'];
        $path = explode('/', $path);
        $a = count($path);
        $ordner = $path[$a - 3];
        $link = $_SERVER['REQUEST_URI'];
        $teile = explode('/', $link);
        $key = array_search($ordner, $teile);
        echo ' &lt;div&gt; &lt;iframe src=&quot;http://';
        echo $_SERVER['SERVER_NAME'] . '/';
        for ($i = 1; $i <= $key; $i++) {
            echo $teile[$i] . '/';
        }
        echo 'index.php?objekt=' . $id;
        echo '&quot; id=&quot;idIframe&quot; width=&quot;100%&quot; onload=&quot;iframeLoaded()&quot;
                allowfullscreen style= &quot;border: none;&quot;&gt;

    &lt;/iframe&gt;&lt;/div&gt;</p>';


//Anzeige Formular mit Daten des gewählten Objekts
        echo '<form action="index.php" method="get" class="alter">';

        echo '<div class="formular">';
        echo '<fieldset>';
        echo '<legend>Ferienobjekt: ' . $name . '</legend>';
        echo '<p>Mit * gekennzeichnete Felder sind Pflichtfelder</p>';
        echo '<label for="name" class="left">';
        echo 'Name:* </label>';
        echo '<input type="text" name="name" value="' . $name . '" required>';
        echo '<br><br>';
        echo '<label for="max_per" class="left">';
        echo 'Max Gäste</label>';
        echo '<input type="text" name="max_per" value="' . $max_people . '">';
        echo '<br><br>';
        echo '<label for="max_tier" class="left">';
        echo 'Max Hunde</label>';
        echo '<input type="text" name="max_tier" value="' . $max_tier . '">';
        echo '<br><br>';
        echo '<label for="preis" class="left">';
        echo 'Preis: </label>';
        echo '<input type="text" name="preis" value="' . $price . '">€';
        echo '<br><br>';
        $obj = "SELECT * FROM import WHERE objekt_id=".$id;
        $db_erg = mysqli_query($db_link, $obj);
        while ($zeile_c = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
            $uri = $zeile_c['uri'];
            echo '<label for="uri" class="left">';
            echo 'externe Kalender: </label>';
            echo '<input type="text" name="uri" value="' . $uri . '">';
            echo '<br><br>';
        }

        echo '<input type="hidden" name="objekt" value="' . $id . '">';
        echo '<input type="hidden" name="in" value="alt">';
        echo '<input type="submit" value="speichern">';
        echo '</fieldset></div></form>';
        mysqli_close($db_link);
        ?>