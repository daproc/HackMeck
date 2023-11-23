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

function cal_m($j, $monat, $anzahl, $month, $obj) {
    for ($mo = $monat; $mo < $monat + $anzahl; $mo++) {
        if ($mo > 12) {
            $m = $mo - 12;
            $jahr = $j + 1;
        } else {
            $m = $mo;
            $jahr = $j;
        }
        $erster = date('N', mktime(0, 0, 0, $m, 1, $jahr));
        $letzter = date('t', mktime(0, 0, 0, $m, 1, $jahr));
        $gesamt = $erster - 1 + $letzter;
        echo '<div class="mkalender">';
        echo '<table class="monatskalender">';
        echo '<thead><tr><th colspan="7">' . $month[$m] . ' ' . $jahr . '</th></tr></thead>';
        echo '<tr><td class="days">Mo</td><td class="days">Di</td><td class="days">Mi</td><td class="days">Do</td><td class="days">Fr</td><td class="days">Sa</td><td class="days">So</td></tr>';
        for ($b = 1; $b <= ceil($gesamt / 7); $b++) {
            echo '<tr>';
            for ($a = 1; $a <= 7; $a++) {
                if ($b == 1) {
                    if ($a < $erster) {
                        if ($a >= 6) {
                            echo '<td class="we"></td>';
                        } else {
                            echo '<td></td>';
                        }
                    } else {
                        if ($a >= 6) {
                            $c = $a + $b - $erster;
                            echo '<td  class="we" id="' . $month[$m] . '-' . $c . '-' . $jahr . '">';
                            echo '<a href="booking.php?objekt=' . $obj . '&date=' . $jahr . '-' . $m . '-' . $c . '" rel="nofollow">' . $c . '</a>';
                            echo '</td>';
                        } else {
                            $c = $a + $b - $erster;
                            echo '<td id="' . $month[$m] . '-' . $c . '-' . $jahr . '">';
                            echo '<a href="booking.php?objekt=' . $obj . '&date=' . $jahr . '-' . $m . '-' . $c . '" rel="nofollow">' . $c . '</a>';
                            echo '</td>';
                        }
                    }
                } else {
                    $d = $c++;
                    if ($d + 1 <= $letzter) {
                        $e = $d + 1;
                        if ($a >= 6) {
                            echo '<td class="we" id="' . $month[$m] . '-' . $e . '-' . $jahr . '">';
                            echo '<a href="booking.php?objekt=' . $obj . '&date=' . $jahr . '-' . $m . '-' . $e . '" rel="nofollow">' . $e . '</a>';
                            echo '</td>';
                        } else {
                            echo '<td id="' . $month[$m] . '-' . $e . '-' . $jahr . '">';
                            echo '<a href="booking.php?objekt=' . $obj . '&date=' . $jahr . '-' . $m . '-' . $e . '" rel="nofollow">' . $e . '</a>';
                            echo '</td>';
                        }
                    } else {
                        if ($a >= 6) {
                            echo'<td class="we"></td>';
                        } else {
                            echo'<td></td>';
                        }
                    }
                }
            }
            echo '</tr>';
        }
        echo '</table></div>';
    }
}

function cal_m_no($j, $monat, $anzahl, $month, $obj) {
    for ($mo = $monat; $mo < $monat + $anzahl; $mo++) {
        if ($mo > 12) {
            $m = $mo - 12;
            $jahr = $j + 1;
        } else {
            $m = $mo;
            $jahr = $j;
        }
        $erster = date('N', mktime(0, 0, 0, $m, 1, $jahr));
        $letzter = date('t', mktime(0, 0, 0, $m, 1, $jahr));
        $gesamt = $erster - 1 + $letzter;
        echo '<div class="mkalender">';
        echo '<table class="monatskalender">';
        echo '<thead><tr><th colspan="7">' . $month[$m] . ' ' . $jahr . '</th></tr></thead>';
        echo '<tr><td class="days">Mo</td><td class="days">Di</td><td class="days">Mi</td><td class="days">Do</td><td class="days">Fr</td><td class="days">Sa</td><td class="days">So</td></tr>';
        for ($b = 1; $b <= ceil($gesamt / 7); $b++) {
            echo '<tr>';
            for ($a = 1; $a <= 7; $a++) {
                if ($b == 1) {
                    if ($a < $erster) {
                        if ($a >= 6) {
                            echo '<td class="we"></td>';
                        } else {
                            echo '<td></td>';
                        }
                    } else {
                        if ($a >= 6) {
                            $c = $a + $b - $erster;
                            echo '<td  class="we" id="' . $month[$m] . '-' . $c . '-' . $jahr . '">';
                            echo  $c;
                            echo '</td>';
                        } else {
                            $c = $a + $b - $erster;
                            echo '<td id="' . $month[$m] . '-' . $c . '-' . $jahr . '">';
                            echo $c;
                            echo '</td>';
                        }
                    }
                } else {
                    $d = $c++;
                    if ($d + 1 <= $letzter) {
                        $e = $d + 1;
                        if ($a >= 6) {
                            echo '<td class="we" id="' . $month[$m] . '-' . $e . '-' . $jahr . '">';
                            echo $e;
                            echo '</td>';
                        } else {
                            echo '<td id="' . $month[$m] . '-' . $e . '-' . $jahr . '">';
                            echo $e;
                            echo '</td>';
                        }
                    } else {
                        if ($a >= 6) {
                            echo'<td class="we"></td>';
                        } else {
                            echo'<td></td>';
                        }
                    }
                }
            }
            echo '</tr>';
        }
        echo '</table></div>';
    }
}

function auswahl($month, $obj) {
    $jahr = date("Y");
    echo '<form action = "index.php" method = "get" >';
    echo '<select name="month" size="1">';
    for ($k = 1; $k <= 12; $k++) {
        if ($k == date("n")) {
            echo '<option value="' . $k . '" selected>' . $month[$k] . '</option>';
        } else {
            echo '<option value="' . $k . '">' . $month[$k] . '</option>';
        }
    }
    echo '</select>';
    echo '<select name="jahr" size="1">';
    for ($i = 1; $i <= 3; $i++) {
        echo "<option>" . $jahr . "</option>";
        $jahr = $jahr + 1;
    }
    echo '</select>';
    echo '<input type="hidden" name="objekt" value="' . $obj . '">';
    echo '<input type="submit" value="wechseln">';
    echo '</form>';
}

function cal($month_arr, $jahr_akt, $obj) {
    echo "<table class=\"jahreskalender\" id=\"tabelle\">";
    echo '<tfoot><tr>';
    echo '<td border="0">Legende:</td>';
    echo '<td id="an"></td>';
    echo '<td colspan="3">Anreise</td>';
    echo '<td id="ab"></td>';
    echo '<td colspan="3">Abreise</td>';
    echo '<td id="belegt"></td>';
    echo '<td colspan="3">belegt</td>';
    echo '<td colspan="27" align="right" border="0">&copy; 2016-' . date('Y') . ' <a href="https://www.hackmeck.de">HackMeck</a></td></tr></tfoot>';
    for ($m = 1; $m <= 12; $m++) {
        echo "<tr>";
        if ($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12) {
            for ($i = 0; $i <= 31; $i++) {
                if ($i == 0) {
                    echo "<td id=\"monat\">" . $month_arr[$m] . "</td>";
                } else {
                    echo "<td id=\"" . $month_arr[$m] . "-" . $i . "-" . $jahr_akt . "\" width=\"15\" align=\"right\"><a href=\"booking.php?date=" . $jahr_akt . "-" . $m . "-" . $i . "&objekt=" . $obj . "\" rel=\"nofollow\">" . $i . "</a></td>";
                }
            }
        } elseif ($m == 2) {
            if (($jahr_akt % 400) == 0 || (($jahr_akt % 4) == 0 &&
                    ($jahr_akt % 100) != 0)) {
                for ($i = 0; $i <= 29; $i++) {
                    if ($i == 0) {
                        echo "<td id=\"monat\">" . $month_arr[$m] . "</td>";
                    } else {
                        echo "<td id=\"" . $month_arr[$m] . "-" . $i . "-" . $jahr_akt . "\" width=\"15\" align=\"right\"><a href=\"booking.php?date=" . $jahr_akt . "-" . $m . "-" . $i . "&objekt=" . $obj . "\" rel=\"nofollow\">" . $i . "</a></td>";
                    }
                }
            } else {
                for ($i = 0; $i <= 28; $i++) {
                    if ($i == 0) {
                        echo "<td id=\"monat\">" . $month_arr[$m] . "</td>";
                    } else {
                        echo "<td id=\"" . $month_arr[$m] . "-" . $i . "-" . $jahr_akt . "\" width=\"15\" align=\"right\"><a href=\"booking.php?date=" . $jahr_akt . "-" . $m . "-" . $i . "&objekt=" . $obj . "\" rel=\"nofollow\">" . $i . "</a></td>";
                    }
                }
            }
        } else {
            for ($i = 0; $i <= 30; $i++) {
                if ($i == 0) {
                    echo "<td id=\"monat\">" . $month_arr[$m] . "</td>";
                } else {
                    echo "<td id=\"" . $month_arr[$m] . "-" . $i . "-" . $jahr_akt . "\" width=\"15\" align=\"right\"><a href=\"booking.php?date=" . $jahr_akt . "-" . $m . "-" . $i . "&objekt=" . $obj . "\" rel=\"nofollow\">" . $i . "</a></td>";
                }
            }
        }
        echo "</tr>";
    }
    echo "</table>";
}

function cal_no($month_arr, $jahr_akt, $obj) {
    echo "<table class=\"jahreskalender\" id=\"tabelle\">";
    echo '<tfoot><tr>';
    echo '<td border="0">Legende:</td>';
    echo '<td id="an"></td>';
    echo '<td colspan="3">Anreise</td>';
    echo '<td id="ab"></td>';
    echo '<td colspan="3">Abreise</td>';
    echo '<td id="belegt"></td>';
    echo '<td colspan="3">belegt</td>';
    echo '<td colspan="27" align="right" border="0">&copy; 2016-' . date('Y') . ' <a href="https://www.hackmeck.de">HackMeck</a></td></tr></tfoot>';
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

function year($year_chk, $obj) {
    $year = $year_chk;
    echo '<form action="index.php" method="get" >';
    echo '<select name="jahr" size="1">';
    for ($year_chk = date("Y"); $year_chk <= (date("Y") + 2); $year_chk++) {  //Auswahlfeld aktuelles Jahr und die 2 folgenden
        echo "<option>" . $year_chk . "</option>";
    }
    echo '</select>';
    echo '<input type="hidden" name="objekt" value="' . $obj . '">';
    echo '<input type="submit" value="absenden">';
    echo '</form>';
    echo '<div class="jahr">' . $year . '</div>'; //Ausgabe vom angezeigten Jahr
}

?>