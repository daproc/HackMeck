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
/* $db_link = mysqli_connect(
  HOST, USER, PASSWORD, DATABASE
  ); */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

?>
<!DOCTYPE html>
<html>
    <head  lang="de">
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="style/book.css"/>
        <title>Belegung</title>
    </head>
    <body>
        <?php
        $status = 0;
        $referer = getenv("HTTP_REFERER");
        if (empty($_POST['an']) && empty($_POST['ab']) && !isset($_POST['an']) && !isset($_POST['ab'])) {
            echo '<div class="error">Buchung nur über Belegungsplan möglich!</div>';

            $objekt = "";

            if (isset($_POST['objekt']) == true) {
                $objekt = htmlspecialchars($_POST['objekt']);
            }

            echo '<a href="index.php?objekt=' . $objekt . '"> Zurück </a>';
        } else {

            $fields = array("an", "ab", "anrede", "name", "vorname", "str", "plz", "ort", "tel", "mail", "anzahl_gesamt", "anzahl_erw", "anzahl_kind", "tiere", "text", "objekt");
            for ($i = 0; $i < count($fields); $i++) {
                if (array_key_exists($fields[$i], $_POST)) {

                    ${$fields[$i]} = htmlspecialchars($_POST[$fields[$i]]);
                } else {
                    ${$fields[$i]} = 0;
                }
            }

//Validierug und Umwandlung der An- und Abreisedaten
            if (filter_input(INPUT_POST, 'an', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "#^[0-9]{4}[-][0-9]{1,2}[-][0-9]{1,2}$#"))) OR filter_input(INPUT_POST, 'an', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "#^[0-9]{1,2}[-][0-9]{1,2}[-][0-9]{4}$#")))) {
                $dan = $_POST['an'];
            } else {
                echo '<div class="error">Bitte korrektes Anreisedatum wählen!</div>';
                die();
            }
            if (filter_input(INPUT_POST, 'ab', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "#^[0-9]{4}[-][0-9]{1,2}[-][0-9]{1,2}$#"))) OR filter_input(INPUT_POST, 'an', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "#^[0-9]{1,2}[-][0-9]{1,2}[-][0-9]{4}$#")))) {
                $dab = $_POST['ab'];
            } else {
                echo '<div class="error">Bitte korrektes Abreisedatum wählen!</div>';
                die();
            }
            if (preg_match("/[\d]{2}\.[\d]{2}\.[\d]{4}/", $dan)) {   //Prüft ob Format dd.mm.yyyy
                $array = explode(".", $dan);
                $date_an = $array[2] . "-" . $array[1] . "-" . $array[0];              //Erstellt Format yyyy-mm-dd
            } elseif (preg_match("/[\d]{4}\-[\d]{2}\-[\d]{2}/", $dan)) {  //Prüft ob Format yyyy-mm-dd
                $date_an = $dan;
            } else {
                echo '<div class="fehler">Bitte richtiges Format bei der Anreise angeben z.Bsp. 01.01.2017</div>';
                echo '<a href="index.php?objekt=' . $objekt . '"> Zurück </a>';
                exit();
            }

            function validateDatean($date_an, $format = 'Y-m-d') {
                $d = DateTime::createFromFormat($format, $date_an);
                return $d && $d->format($format) == $date_an;
            }

            if (validateDatean($date_an)) {
                $an = $date_an;
            } else {
                echo '<div class="fehler">Bitte gültiges Anreisedatum eingeben z.Bsp. 01.01.2017</div>';
                echo '<a href="index.php?objekt=' . $objekt . '"> Zurück </a>';
                exit();
            }
            if (preg_match("/[\d]{2}\.[\d]{2}\.[\d]{4}/", $dab)) {   //Prüft ob Format dd.mm.yyyy
                $array = explode(".", $dab);
                $date_ab = $array[2] . "-" . $array[1] . "-" . $array[0];              //Erstellt Format yyyy-mm-dd
            } elseif (preg_match("/[\d]{4}\-[\d]{2}\-[\d]{2}/", $dan)) {  //Prüft ob Format yyyy-mm-dd
                $date_ab = $dab;
            } else {
                echo '<div class="fehler">Bitte richtiges Format bei der Abreise angeben z.Bsp. 01.01.2017</div>';
                echo '<a href="index.php?objekt=' . $objekt . '"> Zurück </a>';
                exit();
            }

            function validateDateab($date_ab, $format = 'Y-m-d') {
                $d = DateTime::createFromFormat($format, $date_ab);
                return $d && $d->format($format) == $date_ab;
            }

            if (validateDateab($date_ab)) {
                $ab = $date_ab;
            } else {
                echo '<div class="fehler">Bitte gültiges Abreisedatum eingeben z.Bsp. 01.01.2017</div>';
                echo '<a href="index.php?objekt=' . $objekt . '"> Zurück </a>';
                exit();
            }

            $new_an = new DateTime($an);
            $new_ab = new DateTime($ab);
            if ($new_an >= $new_ab) {
                echo '<div class="fehler">Anreise muss vor der Abreise liegen!</div>';
                echo '<a href="index.php?objekt=' . $objekt . '"> Zurück </a>';
                exit();
            }
            $today = date('Y-m-d');
            $dateTimestamp1 = strtotime($date_an);
            $dateTimestamp2 = strtotime($today);
            if ($dateTimestamp1 < $dateTimestamp2) {
                echo '<div class="fehler">Anreise kann nicht in der Vergangenheit liegen!</div>';
                echo '<a href="index.php?objekt=' . $objekt . '"> Zurück </a>';
                exit();
            }

            $new_an->modify('+1 day');
            $new_ab->modify('-1 day');
            $booking = array();
            for ($date = $new_an; $date <= $new_ab; $date->modify('+1 day')) {
                $booking[] = $date->format('Y-m-d');
            }

            $statement = $pdo->prepare("SELECT datean, dateab FROM times WHERE objekt_id = ? AND confirmed = 1 ORDER BY datean");
            $statement->execute(array($objekt));
            while ($zeile = $statement->fetch()) {
                $datean = new DateTime($zeile['datean']);
                $dateab = new DateTime($zeile['dateab']);
                for ($date_db = clone $datean; $date_db <= $dateab; $date_db->modify('+1 day')) {
                    $datum_vergl = $date_db->format('Y-m-d');
                    if (in_array($datum_vergl, $booking)) {
                        echo '<div class="fehler">In diesem Zeitraum gibt es schon eine Belegung</div>';
                        echo '<a href="index.php?objekt=' . $objekt . '"> Zurück </a>';
                        exit();
                    }
                }
            }
//überprüfung der Personenzahl
            $chk_guests = $pdo->prepare("SELECT max_people FROM objekt WHERE id = ?");
            $chk_guests->execute(array($objekt));
            $max_guest = 0;
            while ($zeile_c = $chk_guests->fetch()) {
                $max_guest = $zeile_c['max_people'];
            }
            if ((int)$anzahl_erw + (int)$anzahl_kind > (int)$max_guest) {
                echo '<div class="fehler">Soviele Gäste bekommen wir nicht unter!</div>';
                echo '<a href="index.php?objekt=' . $objekt . '"> Zurück </a>';
                die();
            }


//persönliche Daten des Gastes und beziehen der Gast_ID	
            $ins_guest = $pdo->prepare("INSERT INTO guests (anrede, nname, vorname, str, plz, ort, tel, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $ins_guest->execute(array($anrede, $name, $vorname, $str, $plz, $ort, $tel, $mail));
            $guest_id = $pdo->lastInsertId();

//Eintragen von An- und Abreise	
            $ins_times = $pdo->prepare("INSERT INTO times (datean, dateab, user, confirmed, objekt_id) VALUES (?, ?, ?, ?, ?)");
            $ins_times->execute(array($an, $ab, $guest_id, $status, $objekt));
            $times_id = $pdo->lastInsertId();

//Eintragen der sonstigen Daten
            $ins_booking = $pdo->prepare("INSERT INTO booking (anzahl_pers, anzahl_erw, anzahl_kind, anzahl_tier, text, guest_id, times_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $ins_booking->execute(array($anzahl_gesamt, $anzahl_erw, $anzahl_kind, $tiere, $text, $guest_id, $times_id));
            $booking_id = $pdo->lastInsertId();
            $booking_nr = $guest_id . '-' . $times_id . '-' . $booking_id;
            echo 'Ihre Buchung wurde gespeichert<br>';
            echo 'Für weitere Anfragen verwenden sie bitte folgende Buchungsnummer: ' . $booking_nr;
            echo '<br><a href="index.php?objekt=' . $objekt . '">zurück</a>';

//Mailadresse holen	
            $mailadr = $pdo->prepare("SELECT email FROM users WHERE send = 1 ORDER BY ID DESC LIMIT 1");
            $mailadr->execute(array());
            while ($zeile_c = $mailadr->fetch()) {
                $emailadr = $zeile_c['email'];
            }
//Textbaustein holen
            $text = $pdo->prepare("SELECT buch_text, anhang_buch FROM mail_text ORDER BY ID DESC LIMIT 1");
            $text->execute(array());
            $buch_text = "";
            while ($zeile_c = $text->fetch()) {
                $buch_text = $zeile_c['buch_text'];
                $anhang_buch = $zeile_c['anhang_buch'];
            }
            $datei = 'admin/'.$anhang_buch;
            
//Mail an Gast versenden
            // Betreff
            $betreff = 'Ihre Buchung';
            $anr = "";
            /** @todo Sind eigentlich alle ab hier folgenden Variablenwerte, die nun
              * in die HTML-Mail einfließen, eigentlich gegen Injection schon in der
              * Datenbank gesichert? Denn eigentlich sollten sie es erst hier bei der
              * Verwendung werden, etwa mit htmlspecialchars()... */
            if ($anrede == 'Frau') {
                $anr = 'Sehr geehrte Frau ' . $name . ',';
            } elseif ($anrede == 'Herr') {
                $anr = 'Sehr geehrter Herr ' . $name . ',';
            } else {
                $anr = 'Sehr geehrte/ r Frau/ Herr ' . $name . ',';
            }
            $nachricht = '<html>'.
                           '<head>'.
                             '<meta charset="utf-8"/>'.
                           '</head>'.
                           '<body>'.
                             '<p>' . $anr . '</p>'.
                             '<p>' . $buch_text . '</p>'.
                           '</body>'.
                         '</html>';

            $filename = 'admin/includes/smtp-config.php';
            if (file_exists($filename)) {
                include 'admin/includes/smtp-config.php';
                require 'PHPMailer/src/Exception.php';
                require 'PHPMailer/src/PHPMailer.php';
                require 'PHPMailer/src/SMTP.php';
                $hmMailer = new PHPMailer;
                $hmMailer->CharSet = 'UTF-8';
                $hmMailer->isSMTP();
                $hmMailer->Host = SMTPSERVER;
                $hmMailer->SMTPAuth = true;
                $hmMailer->Username = SMTPUSER;
                $hmMailer->Password = SMTPPASSWORD;
                $hmMailer->SMTPSecure = 'tls';
                $hmMailer->Port = SMTPPORT;
                $hmMailer->SMTPDebug = 0;
                $hmMailer->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $hmMailer->From = $emailadr;
                $hmMailer->FromName = $_SERVER['SERVER_NAME'];
                $hmMailer->addAddress($mail, $mail);
                $hmMailer->addBCC($emailadr, $emailadr);
                $hmMailer->addAttachment($datei);
                $hmMailer->Subject = $betreff;
                $hmMailer->Body = $nachricht;
                $hmMailer->isHTML(true);
                $hmMailer->AltBody = strip_tags($hmMailer->Body);
                if (!$hmMailer->send()) {
                    echo 'Mailer Error: ' . $hmMailer->ErrorInfo;
                } else {
                    echo "<br>Eine Email wurde an " . $mail . " versendet. Sehen sie bitte auch in Ihrem Spamordner nach.";
                }
            } else {
                require 'PHPMailer/src/Exception.php';
                require 'PHPMailer/src/PHPMailer.php';
                $hmMailer = new PHPMailer;
                $hmMailer->CharSet = 'UTF-8';
                $hmMailer->From = $emailadr;
                $hmMailer->FromName = $_SERVER['SERVER_NAME'];
                $hmMailer->addAddress($mail, $mail);
                $hmMailer->addBCC($emailadr, $emailadr);
                $hmMailer->addAttachment($datei);
                $hmMailer->Subject = $betreff;
                $hmMailer->Body = $nachricht;
                $hmMailer->isHTML(true);
                $hmMailer->AltBody = strip_tags($hmMailer->Body);
                if (!$hmMailer->send()) {
                    echo 'Mailer Error: ' . $hmMailer->ErrorInfo;
                } else {
                    echo "<br>Eine Email wurde an " . $mail . " versendet. Sehen sie bitte auch in Ihrem Spamordner nach.";
                }
                echo "<br>Eine Email wurde an " . $mail . " versendet. Sehen sie bitte auch in Ihrem Spamordner nach.";
            }

//url für bearbeitung
            $path = $_SERVER['REQUEST_URI'];
            $path = explode('/', $path);
            $a = count($path);
            $ordner = $path[$a - 2];
            $link = $_SERVER['REQUEST_URI'];
            $teile = explode('/', $link);
            $key = array_search($ordner, $teile);
            //echo $_SERVER['SERVER_NAME'].'/';
            for ($i = 1; $i <= $key; $i++) {
                $pfad[] = $teile[$i] . '/';
            }
            $uri = $_SERVER['SERVER_NAME'] . '/';
            //echo $uri.'<br>';
            $uri .= implode('/', $pfad) . '/admin/index.php?in=vali&bookingnr=' . $booking_nr . '&objekt=' . $objekt;
            $uri = str_replace("//", "/", $uri);
//Mail an Gastgeber mit Link für bestätigung
            $betreff = 'neue Buchungsanfrage';

            $nachricht = '<html>
					<head>
					<meta charset="utf-8"/>
					</head>
					<body>
					<p>Sie haben eine neue Buchungsanfrage, klicken sie auf folgenden Link um die Buchung zu bearbeiten</p>
					<p><a href="http://' . $uri . '">' . $uri . '</a></p>
					</body></html>';

	    /** @todo Nirgends in Verwendung? */
            $header = 'MIME-Version: 1.0' . "\r\n";
            $header .= 'Content-type: text/html; charset=utf-8' . "\r\n";


            if (file_exists($filename)) {
                $aMailer = new PHPMailer;
                $aMailer->CharSet = 'UTF-8';
                $aMailer->isSMTP();
                $aMailer->Host = SMTPSERVER;
                $aMailer->SMTPAuth = true;
                $aMailer->Username = SMTPUSER;
                $aMailer->Password = SMTPPASSWORD;
                $aMailer->SMTPSecure = 'tls';
                $aMailer->Port = SMTPPORT;
                $aMailer->SMTPDebug = 0;
                $aMailer->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $aMailer->From = $emailadr;
                $aMailer->FromName = $_SERVER['SERVER_NAME'];
                $aMailer->addAddress($emailadr, $emailadr);
                $aMailer->Subject = $betreff;
                $aMailer->Body = $nachricht;
                $aMailer->isHTML(true);
                $aMailer->AltBody = strip_tags($aMailer->Body);
                if (!$aMailer->send()) {
                    //echo 'Mailer Error: ' . $aMailer->ErrorInfo;
                }
            } else {
                $aMailer = new PHPMailer;
                $aMailer->CharSet = 'UTF-8';
                $aMailer->From = $emailadr;
                $aMailer->FromName = $_SERVER['SERVER_NAME'];
                $aMailer->addAddress($emailadr, $emailadr);
                $aMailer->Subject = $betreff;
                $aMailer->Body = $nachricht;
                $aMailer->isHTML(true);
                $aMailer->AltBody = strip_tags($aMailer->Body);
                if (!$aMailer->send()) {
                    //echo 'Mailer Error: ' . $aMailer->ErrorInfo;
                }
            }
        }
        ?>
