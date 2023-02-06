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


session_start();
if (!isset($_SESSION['userid'])) {
    $filename = '../install/';

    if (file_exists($filename)) {
        echo "<h1>!!! Denk daran den Ordner install zu löschen !!!</h1>";
    }

    if (empty($_GET)) {
        $ende = '';
    } elseif (!empty($_GET)) {
        while ($bla = current($_GET)) {
            if (key($_GET) == 'login') {
                next($_GET);
            } else {
                $request[] = key($_GET) . '=' . $_GET[key($_GET)];
                next($_GET);
            }
        }
        $ende = implode('&', $request);
    }
    ?>

    <p>Bitte erst einloggen.</p>

    <form action="login.php?login=1&<?php echo $ende; ?>" method="post">
        E-Mail:<br>
        <input type="email" size="40" maxlength="250" name="email"><br><br>

        Dein Passwort:<br>
        <input type="password" size="40"  maxlength="250" name="passwort"><br><br>

        <input type="submit" value="anmelden"><br>
    </form> 
    <?php
    die();
}

require_once ('includes/beleg-config.php');
include ('includes/functions.php');
$remote = 24519;
$db_link = mysqli_connect(
        HOST, USER, PASSWORD, DATABASE
);

$userid = $_SESSION['userid'];
$sel_name = "SELECT name, email FROM users WHERE id = '" . $userid . "'";
$result = mysqli_query($db_link, $sel_name);
$row = mysqli_fetch_assoc($result);
$username = $row['name'];
$useremail = $row['email'];
$settings = "SELECT book FROM settings WHERE id = 1";
$db_erg = mysqli_query($db_link, $settings);
while ($zeile_s = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
    $book_on = $zeile_s['book'];
}
?>
<!DOCTYPE html>
<html lang="de">
    <meta charset="utf-8">
    <meta name="robots" content="noindex">

    <head>
        <style>
<?php
include ('css/admin_css.php');
?>
        </style>
    </head>
    <body>
        <div id = "menu">
            <nav>
                <ul>
                    <li class="submenu"><a href="#" title="Einstellungen">Einstellungen</a>
                        <ul>
                            <li><a href="index.php?in=scr&objekt=1" title="Anzeige">Anzeige</a></li>
                            <li><a href="index.php?in=des&objekt=1" title="Farben">Farben</a></li>
                            <li><a href="index.php?in=text" title="Texte">Textbausteine</a></li>
                            <li><a href="index.php?in=user_ad" title="Texte">Nutzer</a></li>
                        </ul>
                    </li>
                    <li class="submenu"><a href="#" title="Objekte">Ferienobjekte</a>
                        <ul>
                            <li><a href="index.php?in=alt" title="verwalten">Verwalten</a></li>
                            <?php
                            if ($book_on == 1) {
                                ?>
                                <li class="submenu"><a href="#" title="verwalten">Formulare</a>
                                    <ul><?php
                                        $sql = "SELECT name, id FROM objekt ORDER BY id";
                                        mysqli_set_charset($db_link, 'utf8');
                                        $db_erg = mysqli_query($db_link, $sql);
                                        if (!$db_erg) {
                                            die('Ungültige Abfrage: ' . mysqli_error());
                                        }
                                        while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
                                            $name = $zeile['name'];
                                            $id = $zeile['id'];
                                            echo '<li><a href="index.php?in=forms&objekt=' . $id . '" title="Formular">Formular für ' . $name . '</a></li>';
                                        }
                                        ?>
                                    </ul>
                                    <?php
                                }
                                ?>
                        </ul>

                    </li>
                    <li class="submenu"><a href="#" title="Buchungen">Buchungen</a>
                        <ul>
                            <?php
                            if ($book_on == 1) {
                                ?>
                                <li><a href="index.php?in=bq" title="offen">Buchungsanfragen</a></li>
                                <li><a href="index.php?in=booking" title="Buchungen">Buchungen</a></li>
                                <?php
                            }
                            ?>
                            <li class="submenu"><a href="#" title="eintragen">Eintragen</a>
                                <ul>
                                    <?php
                                    $sql = "SELECT name, id FROM objekt ORDER BY id";
                                    mysqli_set_charset($db_link, 'utf8');
                                    $db_erg = mysqli_query($db_link, $sql);
                                    if (!$db_erg) {
                                        die('Ungültige Abfrage: ' . mysqli_error());
                                    }
                                    while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
                                        $name = $zeile['name'];
                                        $id = $zeile['id'];
                                        echo '<li><a href="index.php?in=book&objekt=' . $id . '" title="Eintragen">Eintragen in ' . $name . '</a></li>';
                                    }
                                    ?>
                                </ul>
                            </li>


                            <li class="submenu"><a href="#" title="exportieren">Export Ical</a>
                                <ul>
                                    <?php
                                    $sql = "SELECT name, id FROM objekt ORDER BY id";
                                    mysqli_set_charset($db_link, 'utf8');
                                    $db_erg = mysqli_query($db_link, $sql);
                                    if (!$db_erg) {
                                        die('Ungültige Abfrage: ' . mysqli_error());
                                    }
                                    while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
                                        $name = $zeile['name'];
                                        $id = $zeile['id'];
                                        echo '<li><a href="index.php?in=exp&objekt=' . $id . '" title="Export von ' . $name . '">Export von ' . $name . '</a></li>';
                                    }
                                    ?>
                                </ul>
                            </li>

                            <li class="submenu"><a href="#" title="Importieren">Import Ical</a>
                                <ul>
                                    <?php
                                    $sql = "SELECT name, id FROM objekt ORDER BY id";
                                    mysqli_set_charset($db_link, 'utf8');
                                    $db_erg = mysqli_query($db_link, $sql);
                                    if (!$db_erg) {
                                        die('Ungültige Abfrage: ' . mysqli_error());
                                    }
                                    while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
                                        $name = $zeile['name'];
                                        $id = $zeile['id'];
                                        echo '<li><a href="index.php?in=imp&objekt=' . $id . '" title="Import in ' . $name . '">Import in ' . $name . '</a></li>';
                                    }
                                    ?>
                                </ul>
                            </li>


                            <li><a href="index.php?in=rem" title="Löschen">Löschen</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="right">
                    <li class="submenu"><a href="#"><?php echo $username; ?></a>
                        <ul>
                            <li><a href="logout.php">logout</a></li>
                            <li><a href="index.php?in=user_set">bearbeiten</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <div id = "main">
            <?php
            if (!isset($_GET['in'])AND empty($_GET['in'])) {
                $in = date("Y");
                echo '<br><br>';
                echo '<h1>Herzlich Willkommen</h1>';
            } elseif (isset($_GET['in'])AND ! empty($_GET['in'])) {
                $in = $_GET['in'];
            }
            if ($in == 'book') {
                echo '<h1>Eintragen in ';
                name($_GET['objekt']);
                echo '</h1>';
                include ('book.php');
            } elseif ($in == 'rem') {
                echo '<h1>Löschen</h1>';
                include ('remove.php');
            } elseif ($in == 'loe') {
                echo '<h1>Löschen</h1>';
                include ('loeschen.php');
            } elseif ($in == 'des') {
                echo '<h1>Design</h1>';
                include ('design.php');
            } elseif ($in == 'scr') {
                echo '<h1>Anzeige</h1>';
                include ('screen.php');
            } elseif ($in == 'alt') {
                echo '<h1>Meine Ferienwohnungen</h1>';
                include ('alter.php');
            } elseif ($in == 'obr') {
                echo '<h1>';
                name($_GET['objekt']);
                echo ' löschen?</h1>';
                include ('obj_rem.php');
            } elseif ($in == 'forms') {
                echo '<h1>Buchungsformular für ';
                name($_GET['objekt']);
                echo '</h1>';
                include ('forms.php');
            } elseif ($in == 'formin') {
                echo '<h1>Meine Buchungsformulare</h1>';
                include ('form_ins.php');
            } elseif ($in == 'vali') {
                echo '<h1>meine Buchungsanfragen</h1>';
                include ('valid.php');
            } elseif ($in == 'bq') {
                echo '<h1>meine Buchungsanfragen</h1>';
                include ('book_quest.php');
            } elseif ($in == 'booking') {
                echo '<h1>meine Buchungen</h1>';
                include ('bookings.php');
            } elseif ($in == 'vali_re') {
                echo '<h1>meine Buchungen</h1>';
                include ('valid_re.php');
            } elseif ($in == 'mail') {
                echo '<h1>Mail</h1>';
                include ('mail.php');
            } elseif ($in == 'text') {
                echo '<h1>Textbausteine</h1>';
                include ('textbau.php');
            } elseif ($in == 'reg') {
                echo '<h1>Nutzer anlegen</h1>';
                include ('register.php');
            } elseif ($in == 'user_set') {
                echo '<h1>Profil bearbeiten</h1>';
                include ('user_set.php');
            } elseif ($in == 'user_rem') {
                echo '<h1>Profil löschen?</h1>';
                include ('user_remove.php');
            } elseif ($in == 'user_ad') {
                echo '<h1>Nutzer Einstellungen</h1>';
                include ('user_admin.php');
            } elseif ($in == 'exp') {
                echo '<h1>Export aus ';
                name($_GET['objekt']);
                echo '</h1>';
                include ('export.php');
            } elseif ($in == 'imp') {
                echo '<h1>Import in ';
                name($_GET['objekt']);
                echo '</h1>';
                include ('import.php');
            }
            ?>
        </div>
        <div id = "breadcrumbs">
        </div>
        <br>
        <div id = "bottom">
            <div><p>&copy;2016-<?php echo date('Y'); ?> <a href="http://hackmeck.bplaced.net">HackMeck</a></p></div>
        </div>
        <div id = "header">
        </div>
    </body>
</html>