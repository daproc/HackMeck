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
require_once ('includes/beleg-config.php');
$db_link = mysqli_connect(
        HOST, USER, PASSWORD, DATABASE
);
?>
<!DOCTYPE html> 
<html> 
    <head>
        <meta charset="utf-8">
        <title>Registrierung</title> 
        <link rel="stylesheet" href="css/main.css" />
    </head> 
    <body>

        <?php
        $showFormular = true; //Variable ob das Registrierungsformular angezeigt werden soll


        if (isset($_GET['register'])) {
            $error = false;
            $name = $_POST['name'];
            $email = $_POST['email'];
            $passwort = $_POST['passwort'];
            $passwort2 = $_POST['passwort2'];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
                $error = true;
            }
            if (strlen($name) == 0) {
                echo 'Bitte einen Benutzernamen angeben<br>';
                $error = true;
            }
            if (strlen($passwort) <= 6) {
                echo 'Bitte ein Passwort mit mindestens 7 Zeichen angeben<br>';
                $error = true;
            }
            if ($passwort != $passwort2) {
                echo 'Die Passwörter müssen übereinstimmen<br>';
                $error = true;
            }

            //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde

            if (!$error) {
                $mail_chk = "SELECT * FROM users WHERE email = '" . $email . "'";
                $result = mysqli_query($db_link, $mail_chk);
                $row_cnt = mysqli_num_rows($result);

                if ($row_cnt >= 1) {
                    echo 'Diese E-Mail-Adresse ist schon registriert';
                    $error = true;
                }
            }

            //Keine Fehler, wir können den Nutzer registrieren
            if (!$error) {
                $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

                $insert = "INSERT INTO users (email, passwort, name) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($db_link, $insert);
                mysqli_stmt_bind_param($stmt, 'sss', $email, $passwort_hash, $name);
                mysqli_stmt_execute($stmt);
                if (!$stmt) {
                    echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
                    die('Ungültige Abfrage: ' . mysqli_error());
                } else {
                    echo 'Neuer Nutzer, ' . $name . ' angelegt';
                    $showFormular = false;
                }
            }
        }

        if ($showFormular) {
            ?>

            <form action="?register=1&in=reg" method="post">
                <fieldset>
                    <legend>Benutzer</legend>
                    <p>
                        <label for="username">Benutzername</label>
                        <input type="text" size="40"  maxlength="250" name="name"><br>
                    </p>
                    <p>
                        <label for="email">Email</label>
                        <input type="email" size="40" maxlength="250" name="email"><br><br>
                    </p>
                    <p>
                        <label for="passwort">Passwort</label>
                        <input type="password" size="40"  maxlength="250" name="passwort"><br>
                    </p>
                    <label for="passwort2">Passwort wiederholen</label>
                    <input type="password" size="40" maxlength="250" name="passwort2"><br><br>
                    </p>
                    <p>
                        <label></label>
                        <input type="submit" value="Abschicken">
                </fieldset>	
            </form>

            <?php
        } //Ende von if($showFormular)
        ?>

    </body>
</html>
