<?php
#########################################
#Belegungsplan 0.6			#
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

/*	Belegungsplan ist Freie Software: Sie können ihn unter den Bedingungen
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
if($controll != 24519){
	echo 'Kein Zugriff!';
	exit();
}
function year($year_chk){
	
	echo $year_chk; //Ausgabe vom angezeigten Jahr
	echo '<form action="index.php" method="get" >';
	echo '<select name="jahr" size="1">';
	for ($year_chk = date("Y"); $year_chk <= (date("Y")+2); $year_chk++){  //Auswahlfeld aktuelles Jahr und die 2 folgenden
		echo   "<option>".$year_chk."</option>";
	}
    echo '</select>';
	echo '<input type="hidden" name="in" value="book">';
	echo '<input type="submit" value="absenden">';
	echo '</form>';
}
function cal($month_arr, $jahr_akt, $obj){
	echo "<table id=\"tabelle\">";
	echo '<tfoot><tr>';
	echo '<td border="0">Legende:</td>';
	echo '<td id="an"></td>';
	echo '<td colspan="3">Anreise</td>';
	echo '<td id="ab"></td>';
	echo '<td colspan="3">Abreise</td>';
	echo '<td id="belegt"></td>';
	echo '<td colspan="3">belegt</td>';
	echo '<td colspan="27" align="right" border="0">&copy; 2016-'.date('Y').' <a href="http://hackmeck.bplaced.net">HackMeck</a></td></tr></tfoot>';
	for ($m = 1; $m <= 12; $m++){
		echo "<tr>";
		if ($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12){
			for ($i = 0; $i <= 31; $i++) {
				if ($i == 0){
					echo "<td id=\"monat\">".$month_arr[$m]."</td>";
				}else{
				echo "<td id=\"".$month_arr[$m]."-".$i."-".$jahr_akt."\" width=\"15\" align=\"right\"><a href=\"booking.php?date=".$jahr_akt."-".$m."-".$i."&objekt=".$obj."\">".$i."</a></td>";
				}
			}
		}elseif ($m == 2){
			if (($jahr_akt % 400) == 0 || (($jahr_akt % 4) == 0 &&
				($jahr_akt % 100) != 0)) {
				for ($i = 0; $i <= 29; $i++) {
				if ($i == 0){
					echo "<td id=\"monat\">".$month_arr[$m]."</td>";
				}else{
				echo "<td id=\"".$month_arr[$m]."-".$i."-".$jahr_akt."\" width=\"15\" align=\"right\"><a href=\"booking.php?date=".$jahr_akt."-".$m."-".$i."&objekt=".$obj."\">".$i."</a></td>";
				}
			}
			} else {
				for ($i = 0; $i <= 28; $i++) {
				if ($i == 0){
					echo "<td id=\"monat\">".$month_arr[$m]."</td>";
				}else{
				echo "<td id=\"".$month_arr[$m]."-".$i."-".$jahr_akt."\" width=\"15\" align=\"right\"><a href=\"booking.php?date=".$jahr_akt."-".$m."-".$i."&objekt=".$obj."\">".$i."</a></td>";
				}
				}
			}
		
		}else{
			for ($i = 0; $i <= 30; $i++) {
				if ($i == 0){
					echo "<td id=\"monat\">".$month_arr[$m]."</td>";
				}else{
					echo "<td id=\"".$month_arr[$m]."-".$i."-".$jahr_akt."\" width=\"15\" align=\"right\"><a href=\"booking.php?date=".$jahr_akt."-".$m."-".$i."&objekt=".$obj."\">".$i."</a></td>";
				}
			}
		}		
		echo "</tr>";
	}
	echo "</table>";
	
} 

function formular(){
	echo '<form action="index.php" method="get" class="booking">';
	echo '<div class="formular">';
	echo '<fieldset>';
	echo '<legend>Buchung</legend>';
	echo '<label for="an">';
	echo	'Anreise: </label>';
	echo	'<input type="date" name="an" placeholder="Bsp. 01.01.2017" required>';
	echo	'<br>';
	echo '<label for="ab">';
    echo 'Abreise: </label>';
	echo	'<input type="date" name="ab" placeholder="Bsp. 01.01.2017" required>';
	echo '<br>';
	echo '<label></label>';
	echo '<input type="hidden" name="in" value="book">';
	echo	'<input type="submit" value="weiter">';
	echo '<br>';
	echo '</fieldset></div></form>';
}
?>