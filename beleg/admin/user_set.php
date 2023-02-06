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
<!DOCTYPE html>
<html lang="de">
    <meta charset="utf-8">
    <head>
        <style>
<?php
$controll = $remote;
if ($controll != 24519) {
    echo 'Kein Zugriff!';
    exit();
}
mysqli_set_charset($db_link, 'utf8');
?>
        </style>
        <link rel="stylesheet" href="css/main.css" />
    </head>
    <body>
        <?php
        if (empty($_POST['action'])) {
            ?>
            <form action="?in=user_set" method="post">
                <fieldset>
                    <legend>Benutzer</legend>
                    <p>
                        <label for="username">Benutzername</label>
                        <input type="text" size="40"  maxlength="250" name="name" value="<?php echo $username; ?>"><br>
                    </p>
                    <p>
                        <label for="email">Email</label>
                        <input type="email" size="40" maxlength="250" name="email" value="<?php echo $useremail; ?>"><br><br>
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
                        <button type="submit" name="action" value="aendern">ändern</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" name="action" value="loeschen">Profil löschen?</button>
                </fieldset>
            </form>
            <?php
        } elseif (!empty($_POST['action'])) {
            if ($_POST['action'] == 'aendern') {
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
                    echo 'Bitte ein Passwort mit mindestens 6 Zeichen angeben<br>';
                    $error = true;
                }
                if ($passwort != $passwort2) {
                    echo 'Die Passwörter müssen übereinstimmen<br>';
                    $error = true;
                }
                if (!$error) {
                    $mail_chk = "SELECT * FROM users WHERE email = '" . $email . "' AND id != '" . $userid . "'";
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

                    $insert = "UPDATE `users` SET email=?, passwort=?, name=? WHERE `users`.`id` = ?";
                    $stmt = mysqli_prepare($db_link, $insert);
                    mysqli_stmt_bind_param($stmt, 'sssi', $email, $passwort_hash, $name, $userid);
                    mysqli_stmt_execute($stmt);
                    if (!$stmt) {
                        echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
                        die('Ungültige Abfrage: ' . mysqli_error());
                    } else {
                        echo 'Daten geändert <a href="../admin/index.php">Zum Login</a>';
                        $showFormular = false;
                    }
                }


                echo 'Daten ändern';
            } elseif ($_POST['action'] == 'loeschen') {
                echo 'Möchten Sie Ihr Profil wirklich löschen?<br>';
                echo '<a href="index.php?in=user_rem">JA&nbsp;&nbsp;&nbsp;</a> | ';
                echo '<a href="index.php?in=user_set">&nbsp;&nbsp;&nbsp;NEIN</a>';
            }
        }
        ?>