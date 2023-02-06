<?php

#########################################
#Belegungsplan   			#
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


$controll = $remote;
if ($controll != 24519) {
    echo 'Kein Zugriff!';
    exit();
}

if (strlen($_FILES['datei']['name'])>0) {
       $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
        $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = array('pdf', 'txt', 'doc', 'docx', 'odt');
        if (!in_array($extension, $allowed_extensions)) {
            die("Ungültige Dateiendung, nur Dateien mit der Endung 'pdf','txt','doc','docx','odt' erlaubt");
        }
        
        $anhang = 'files/attachment/' . $filename . '.' . $extension;
        move_uploaded_file($_FILES['datei']['tmp_name'], $anhang);
        $anhang_pfad = 'files/attachment/' . $filename . '.' . $extension;
    } else {
        $anhang_pfad = '';
    }

$mail = $_POST['empfaenger'];
$betreff = $_POST['betreff'];
$nachricht = $_POST['nachricht'];
$emailadr = $_POST['bcc'];
$header = 'MIME-Version: 1.0' . "\n";
$header .= 'Content-type: text/html; charset=utf-8' . "\n";
$header .= 'Reply: ' . $emailadr . "\n";
$header .= 'Bcc: ' . $emailadr . "\n";
$filename = 'includes/smtp-config.php';
if (file_exists($filename)) {
    include 'includes/smtp-config.php';
    require 'PHPMailerAutoload.php';
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
    $hmMailer->addAttachment($anhang_pfad);
    $hmMailer->isHTML(true);
    $hmMailer->Subject = $betreff;
    $hmMailer->Body = $nachricht;
    $hmMailer->AltBody = strip_tags($hmMailer->Body);
    if (!$hmMailer->send()) {
        echo 'Mailer Error: ' . $hmMailer->ErrorInfo;
    } else {
        echo "<br>Eine Email wurde an " . $mail . " versendet. Sehen sie bitte auch in Ihrem Spamordner nach.";
    }
} else {
    require 'PHPMailerAutoload.php';
    $hmMailer = new PHPMailer;
    $hmMailer->CharSet = 'UTF-8';
    $hmMailer->From = $emailadr;
    $hmMailer->FromName = $_SERVER['SERVER_NAME'];
    $hmMailer->addAddress($mail, $mail);
    $hmMailer->addBCC($emailadr, $emailadr);
    $hmMailer->addAttachment($anhang_pfad);
    $hmMailer->isHTML(true);
    $hmMailer->Subject = $betreff;
    $hmMailer->Body = $nachricht;
    $hmMailer->AltBody = strip_tags($hmMailer->Body);
    if (!$hmMailer->send()) {
        echo 'Mailer Error: ' . $hmMailer->ErrorInfo;
    } else {
        echo "<br>Eine Email wurde an " . $mail . " versendet. Sehen sie bitte auch in Ihrem Spamordner nach.";
    }
}
echo '<p>Die Nachricht wurde erfolgreich versendet</p>';
echo '<p><b>An: </b><br>' . $mail . '</p>';
echo '<p><b>Betreff: </b><br>' . $betreff . '</p>';
echo '<p><b>Nachricht: </b><br>' . $nachricht . '</p>';
?>
