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

/** @todo Der iCal-Import ist wahrscheinlich schon seit längerer Zeit defekt. */

$controll = $remote;
if ($controll != 24519) {
    echo 'Kein Zugriff!';
    exit();
}
$obj = $_GET['objekt'];
$import = false;
$zaehler = 0;
$fail_zaehler = 0;
$corrupt_date = 0;
?>
        <form name="datei" action="index.php?in=imp&amp;objekt=<?php echo $obj; ?>" method="post" enctype="multipart/form-data">
            <p>iCalendar Datei hochladen<br/>
                Nur Dateien mit der Endung .ics, .ifb, .iCal, .iFBf erlaubt</p>
            <label for="datei">Datei hochladen:</label><br/><input type="file" name="datei"/><br/><br/>
            <input type="submit" value="Import"/>
        </form>
        <p>oder</p>
        <form action="index.php?in=imp&amp;objekt=<?php echo $obj; ?>" method="post">
            <p>iCalendar von externer Seite (z.B. Google Kalender) importieren<br/>
                Nur Dateien mit der Endung .ics, .ifb, .iCal, .iFBf erlaubt</p>
            <label for="pfad">Import aus Pfad:</label><br/><input type="text" name="pfad" id="pfad"/><br/><br/>
            <input type="submit" value="Import"/>
        </form>
        <br/>        
        <?php
        if ($_FILES OR isset($_POST['pfad'])) {
            if ($_FILES) {
                $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
                $allowed_extensions = array('ics', 'ifb', 'iCal', 'iFBf');
                if (!in_array($extension, $allowed_extensions)) {
                    die("Ungültige Dateiendung, nur Dateien mit der Endung .ics, .ifb, .iCal, .iFBf erlaubt");
                }
                move_uploaded_file($_FILES['datei']['tmp_name'], "ical/upload/temp.ics");
            } elseif (isset($_POST['pfad'])) {
                $extension = strtolower(pathinfo($_POST['pfad'], PATHINFO_EXTENSION));
                $allowed_extensions = array('ics', 'ifb', 'iCal', 'iFBf');
                if (!in_array($extension, $allowed_extensions)) {
                    die("Ungültige Dateiendung, nur Dateien mit der Endung .ics, .ifb, .iCal, .iFBf erlaubt");
                }
                $ch = curl_init($_POST['pfad']);
                $zieldatei = fopen("ical/upload/temp.ics", "w");
                curl_setopt($ch, CURLOPT_FILE, $zieldatei);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
                curl_exec($ch);
                fclose($zieldatei);
            }
            $cal_import = file("ical/upload/temp.ics");
            for ($i = 0; $i < count($cal_import); $i++) {
                $datean = strstr($cal_import[$i], 'DTSTART');
                if ($datean) {
                    $zeichen = preg_split('//', $datean, -1, PREG_SPLIT_NO_EMPTY);
                    $output = array_slice($zeichen, -18, 8);
                    if (ctype_digit($output[3])) {
                        $datan = implode("", $output);
                    } else {
                        $output = array_slice($zeichen, -10, 8);
                        $datan = implode("", $output);
                    }
                    if (filter_var($datan, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "#^[0-9]{8}$#")))) {
                        $date = DateTime::createFromFormat('Ymd', $datan);
                        $datean = $date->format('Y-m-d');
                        $check = "SELECT datean FROM times WHERE objekt_id ='" . $obj . "' AND datean = '" . $datean . "'";
                        $result = mysqli_query($db_link, $check);
                        $row_cnt = mysqli_num_rows($result);
                        if ($row_cnt == 0) {
                            $insert = "INSERT INTO times (datean, objekt_id) VALUES ('$datean', '$obj')";
                            $write = mysqli_query($db_link, $insert);
                            if (!$write) {
                                die('Ungültige Abfrage: ' . mysqli_error($db_link));
                            } else {
                                $last_id = mysqli_insert_id($db_link);
                            }
                        } else {
                            $fail_zaehler++;
                        }
                    } else {
                        $corrupt_date++;
                    }
                }
                $dateab = strstr($cal_import[$i], 'DTEND');
                if ($dateab) {
                    $zeichen = preg_split('//', $dateab, -1, PREG_SPLIT_NO_EMPTY);
                    $output = array_slice($zeichen, -18, 8);
                    if (ctype_digit($output[3])) {
                        $datab = implode("", $output);
                    } else {
                        $output = array_slice($zeichen, -10, 8);
                        $datab = implode("", $output);
                    }
                    if (filter_var($datab, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "#^[0-9]{8}$#")))) {
                        $date = DateTime::createFromFormat('Ymd', $datab);
                        $dateab = $date->format('Y-m-d');
                        if ($row_cnt == 0) {
                            $insert = "UPDATE `times` SET `dateab` = " . $datab . ", `confirmed` = 1 WHERE `times`.`id` = " . $last_id;
                            $write = mysqli_query($db_link, $insert);
                            if (!$write) {
                                die('Ungültige Abfrage: ' . mysqli_error($db_link));
                            } else {
                                $zaehler++;
                                $import = true;
                            }
                        }
                    } else {
                        if ($row_cnt == 0 AND $last_id > 0) {
                            $loesch_bel = "DELETE FROM times WHERE `times`.`id` = " . $last_id;
                            $remove_bel = mysqli_query($db_link, $loesch_bel);
                        }
                        $corrupt_date++;
                    }
                }
            }

            echo 'Kalender mit ' . $zaehler . ' Datensätzen importiert.<br/>';
            echo $fail_zaehler . ' Datensätze übersprungen.<br/>';
            echo $corrupt_date . ' Datensätze fehlerhaft.';
            unlink("ical/upload/temp.ics");
        }
        ?>
