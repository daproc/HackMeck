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

$controll = $remote;
if ($controll != 24519) {
    echo 'Kein Zugriff!';
    exit();
}
require_once ('includes/beleg-config.php');
$pdo = new PDO(SERVER, USER, PASSWORD, $options);
//$colors = "SELECT cal_month, cal_beleg, form_back, cal_back, back, font FROM color WHERE id = 1";
$colors = $pdo->prepare("SELECT cal_month, cal_beleg, form_back, cal_back, cal_head, cal_days, cal_we, back, font FROM color WHERE id = ?");
$colors->execute(array("1"));
while($zeile_c = $colors->fetch()) {
    $cal_beleg = $zeile_c['cal_beleg'];
    $cal_month = $zeile_c['cal_month'];
    $form_back = $zeile_c['form_back'];
    $cal_back = $zeile_c['cal_back'];
    $cal_head = $zeile_c['cal_head'];
    $cal_days = $zeile_c['cal_days'];
    $cal_we = $zeile_c['cal_we'];
    $back = $zeile_c['back'];
    $font = $zeile_c['font'];
}
?>
body {
background-color: <?php echo $back; ?>;
color: <?php echo $font; ?>;
}
a {
color: <?php echo $font; ?>;
text-decoration:none;
}
form.color { 
line-height: 10px;
}
label.left {
clear: both;
width: 8em;
display: block;
float: left;
cursor: pointer;  /* Mauszeiger aendern */
}
input {

margin: 2px;
}	
input:focus, textarea:focus {
background-color: <?php echo $form_back; ?>;
}
select:focus, textarea:focus {
background-color: <?php echo $form_back; ?>;
}
form.booking {
background-color: #eeeeff;
padding: 20px;
margin: 5px;
border: 1px solid silver;
}
table, tr, td {
border: 1px solid;
background-color: <?php echo $cal_back; ?>;
}
table .monatskalender, tr, th {
border: 1px solid;
background-color: <?php echo $cal_head; ?>;
}
.days{
background-color: <?php echo $cal_days; ?>;
}
.we{
background-color: <?php echo $cal_we; ?>;
}
#monat {
background-color: <?php echo $cal_month; ?>;
}
.footer{
clear: both;
}
#an {
background-image: linear-gradient(135deg, <?php echo $cal_back; ?> 50%, <?php echo $cal_beleg; ?> 50%);
width: 1em;
height: 1em;
}
#ab{
background-image: linear-gradient(135deg, <?php echo $cal_beleg; ?> 50%, <?php echo $cal_back; ?> 50%);
width: 1em;
height: 1em;
}
#belegt{
background-color: <?php echo $cal_beleg; ?>;
width: 1em;
height: 1em;
}
<?php
$statement = $pdo->prepare("SELECT datean, dateab FROM times WHERE (objekt_id = ? AND confirmed = 1 AND YEAR(datean) = ?) OR (objekt_id = ? AND confirmed = 1 AND YEAR(dateab) = ?) ORDER BY datean");
$statement->execute(array($objekt, $year, $objekt, $year+1));
while ($zeile = $statement->fetch()) {
//while ($zeile = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $datean = new DateTime($zeile['datean']);
    $dateab = new DateTime($zeile['dateab']);
    for ($date = clone $datean; $date <= $dateab; $date->modify('+1 day')) {
        $datum_vergl = $date->format('Y-m-d');
        $datum = $date->format('n-j-Y');
        if ($datum_vergl == $zeile['datean']) {
            $datum = explode("-", $datum);
            $datum = $monatsnamen[$datum[0]] . "-" . $datum[1] . "-" . $datum[2];
            echo "#" . $datum . "{background-image: linear-gradient(135deg, " . $cal_back . " 50%, " . $cal_beleg . " 50%)}\n";
        } elseif ($datum_vergl == $zeile['dateab']) {
            $datum = explode("-", $datum);
            $datum = $monatsnamen[$datum[0]] . "-" . $datum[1] . "-" . $datum[2];
            echo "#" . $datum . "{background-image: linear-gradient(135deg, " . $cal_beleg . " 50%, " . $cal_back . " 50%)}\n";
        } else {
            $datum = explode("-", $datum);
            $datum = $monatsnamen[$datum[0]] . "-" . $datum[1] . "-" . $datum[2];
            echo "#" . $datum . "{background-color: " . $cal_beleg . ";}\n";
        }
    }
}
?>