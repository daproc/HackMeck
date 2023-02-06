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
include ('includes/functions.php');
$range_month = array(
    'options' => array(
        'min_range' => 1,
        'max_range' => 12
    )
);
$range_year = array(
    'options' => array(
        'min_range' => date("Y"),
        'max_range' => date("Y", strtotime(date("Y") . " + 10 years"))
    )
);
$remote = 24519;
if (filter_input(INPUT_GET, 'jahr', FILTER_VALIDATE_INT, $range_year)) {
    $year = filter_input(INPUT_GET, 'jahr', FILTER_VALIDATE_INT, $range_year); //Jahr uebernehmen
} else {
    $year = date("Y");
}
if (filter_input(INPUT_GET, 'month', FILTER_VALIDATE_INT, $range_month)) {
    $month = filter_input(INPUT_GET, 'month', FILTER_VALIDATE_INT, $range_month);  //Monat uebernehmen
} else {
    $month = date("n");      //Ohne Auswahl aktuelles Jahr übernehmen
}
if (filter_input(INPUT_GET, 'objekt', FILTER_VALIDATE_INT)) {
    $objekt = filter_input(INPUT_GET, 'objekt', FILTER_VALIDATE_INT); //Jahr uebernehmen
} else {
    echo '<!doctype html>
            <html lang="de">
            <head>
            <meta charset="utf-8">
			<meta name="author" content="Daniel Procek-Berger">
			<meta name="copyright" content=" (c) HackMeck">
			<meta name="description" content="Kostenloser Belegungsplan für Ferienwohnungen, Ferienhäuser und anderer Ferienobjekte von HeckMeck">
			<meta name="keywords" contend="Belegungsplan, HackMeck, Ferienwohnung, Ferienhaus, Buchung, buchen, Buchungssystem, kostenlos">
			<meta name="robots" content="index,follow">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style/main.css">
			<title>Belegungsplan und Buchung</title>
            </head>
            <body>
            <div class="fehler"><p>Kein gültiges Objekt gewählt</p></div>
            </body>
            </html>';
    die();
}
$settings = $pdo->prepare("SELECT cal_typ, cal_m_zahl, book FROM settings WHERE id=?");
$settings->execute(array(1));
while ($zeile_c = $settings->fetch()) {
    $cal_typ = $zeile_c['cal_typ'];
    $anzahl_month = $zeile_c['cal_m_zahl'];
    $book = $zeile_c['book'];
}
if ($anzahl_month > 12) {
    $anzahl_month = 12;
} elseif ($anzahl_month < 1) {
    $anzahl_month = 1;
}
$monatsnamen = ["Monat", "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];
?>
<!doctype html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style/main.css">
        <title>Belegung</title> 
        <style>
<?php
include 'style/insert.css.php';
?>
        </style>
    </head>
    <body>
        <?php
        if ($book == 1) {
            echo '<div class="info"><p>Zur Buchung auf den gewünschten Anreisetag klicken.</p></div>';
        }
        if ($cal_typ == 2) {
            echo '<div class = "auswahl">';
            year($year, $objekt);
            echo '</div>';
            echo '<div class="cal">';
            echo '<div class="cal">';
            if ($book == 1) {
                cal($monatsnamen, $year, $objekt);
            } else {
                cal_no($monatsnamen, $year, $objekt);
            }
            echo '</div>';
            echo '</div>';
            exit();
        } elseif ($cal_typ == 1) {
            echo '<div class = "auswahl">';
            auswahl($monatsnamen, $objekt);
            echo '</div>';
            echo '<div class="cal">';
            echo '<div class="cal">';
            if ($book == 1) {
                cal_m($year, $month, $anzahl_month, $monatsnamen, $objekt);
            } else {
                cal_m_no($year, $month, $anzahl_month, $monatsnamen, $objekt);
            }
            ?>
            <footer>
                <div class="legend">
                    <div class="legend">Belegt:</div>
                    <div class="legend" id="belegt">
                    </div>
                </div>
                <div class="legend">
                    <div class="legend">Anreise:</div>
                    <div class="legend" id="an">
                    </div>
                </div>
                <div class="legend">
                    <div class="legend">Abreise:</div>
                    <div class="legend" id="ab">
                    </div>
                </div>
            </footer>
            <footer>
                <div class="copy"><a href="http://hackmeck.bplaced.net">HackMeck &copy; 2016-<?php echo date("Y") ?></a></div>
            </footer>
        </div>
        <?php
    } elseif ($cal_typ == 3) {
        echo '<div class="mobi">';
        echo '<div class = "auswahl">';
        auswahl($monatsnamen, $objekt);
        echo '</div>';
        echo '<div class="cal">';
        echo '<div class="cal">';
        if ($book == 1) {
            cal_m($year, $month, $anzahl_month, $monatsnamen, $objekt);
        } else {
            cal_m_no($year, $month, $anzahl_month, $monatsnamen, $objekt);
        }
        echo '</div></div>';
        ?>
        <footer>
            <div class="legend">
                <div class="legend">Belegt:</div>
                <div class="legend" id="belegt">
                </div>
            </div>
            <div class="legend">
                <div class="legend">Anreise:</div>
                <div class="legend" id="an">
                </div>
            </div>
            <div class="legend">
                <div class="legend">Abreise:</div>
                <div class="legend" id="ab">
                </div>
            </div>
        </footer>
        <footer>
            <div class="copy"><a href="http://hackmeck.bplaced.net">HackMeck &copy; 2016-<?php echo date("Y") ?></a></div>
        </footer>
    </div>
    <?php
    echo '<div class="web">';
    echo '<div class = "auswahl">';
    year($year, $objekt);
    echo '</div>';
    echo '<div class="cal">';
    echo '<div class="cal">';
    if ($book == 1) {
        cal($monatsnamen, $year, $objekt);
    } else {
        cal_no($monatsnamen, $year, $objekt);
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>

</div>
</div>
</div>
</body>
</html>




