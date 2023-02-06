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

include_once 'beleg-config.php';

function year($year_chk, $objekt) {

    echo $year_chk; //Ausgabe vom angezeigten Jahr
    echo '<form class="free" action="index.php" method="get" >';
    echo '<select name="jahr" size="1">';
    for ($year_chk = date("Y"); $year_chk <= (date("Y") + 2); $year_chk++) {  //Auswahlfeld aktuelles Jahr und die 2 folgenden
        echo "<option>" . $year_chk . "</option>";
    }
    echo '</select>';
    echo '<input type="hidden" name="in" value="book">';
    echo '<input type="hidden" name="objekt" value="' . $objekt . '">';
    echo '<input type="submit" value="absenden">';
    echo '</form>';
}

function cal($month_arr, $jahr_akt) {
    echo "<table id=\"tabelle\">";
    echo '<tfoot><tr><td colspan="32" align="right" border="0">&copy; 2016-' . date('Y') . ' <a href="http://hackmeck.bplaced.net">HackMeck</a></td></tr></tfoot>';
    for ($m = 1; $m <= 12; $m++) {
        echo "<tr>";
        if ($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12) {
            for ($i = 0; $i <= 31; $i++) {
                if ($i == 0) {
                    echo "<td id=\"monat\">" . $month_arr[$m] . "</td>";
                } else {
                    echo "<td id=\"" . $month_arr[$m] . "-" . $i . "-" . $jahr_akt . "\" width=\"15\" align=\"right\">" . $i . "</td>";
                }
            }
        } elseif ($m == 2) {
            if (($jahr_akt % 400) == 0 || (($jahr_akt % 4) == 0 &&
                    ($jahr_akt % 100) != 0)) {
                for ($i = 0; $i <= 29; $i++) {
                    if ($i == 0) {
                        echo "<td id=\"monat\">" . $month_arr[$m] . "</td>";
                    } else {
                        echo "<td id=\"" . $month_arr[$m] . "-" . $i . "-" . $jahr_akt . "\" width=\"15\" align=\"right\">" . $i . "</td>";
                    }
                }
            } else {
                for ($i = 0; $i <= 28; $i++) {
                    if ($i == 0) {
                        echo "<td id=\"monat\">" . $month_arr[$m] . "</td>";
                    } else {
                        echo "<td id=\"" . $month_arr[$m] . "-" . $i . "-" . $jahr_akt . "\" width=\"15\" align=\"right\">" . $i . "</td>";
                    }
                }
            }
        } else {
            for ($i = 0; $i <= 30; $i++) {
                if ($i == 0) {
                    echo "<td id=\"monat\">" . $month_arr[$m] . "</td>";
                } else {
                    echo "<td id=\"" . $month_arr[$m] . "-" . $i . "-" . $jahr_akt . "\" width=\"15\" align=\"right\">" . $i . "</td>";
                }
            }
        }
        echo "</tr>";
    }
    echo "</table>";
}

function formular($objekt_id) {
    echo '<form action="index.php" method="get" class="booking">';
    echo '<div class="formular">';
    echo '<fieldset>';
    echo '<legend>Buchung</legend>';
    echo '<label for="an">';
    echo 'Anreise: </label>';
    echo '<input type="date" name="an" placeholder="Bsp. 01.01.2017" required>';
    echo '<br>';
    echo '<label for="ab">';
    echo 'Abreise: </label>';
    echo '<input type="date" name="ab" placeholder="Bsp. 01.01.2017" required>';
    echo '<br>';
    echo '<label></label>';
    echo '<input type="hidden" name="in" value="book">';
    echo '<input type="hidden" name="objekt" value="' . $objekt_id . '">';
    echo '<input type="submit" value="weiter">';
    echo '<br>';
    echo '</fieldset></div></form>';
}

function name($id) {
    $db_link = mysqli_connect(
        HOST, USER, PASSWORD, DATABASE
);
    $sql = "SELECT name FROM objekt WHERE id = '" . $id . "'";
    $db_erg = mysqli_query($db_link, $sql);
    if (!$db_erg) {
        die('Ungültige Abfrage: ' . mysqli_error());
    }
    while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC)) {
        $name = $zeile['name'];
        echo  $name;
    }
}