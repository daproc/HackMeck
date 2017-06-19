<?php
#####################################
#Belegungsplan 0.5.2				#
#©2017 Daniel ProBer alias HackMeck	#
#http://hackmeck.bplaced.net		#
#GERMANY							#
#									#
#Mail: daproc@gmx.net				#
#Paypal: daproc@gmx.net				#
#									#
#Zeigt einen Kalender mit 			#
#Belegung für ein Objekt an.		#
#z.B. Ferienwohnung 				#
#####################################

/*$controll = $remote;
if($controll != 24519){
	echo 'Kein Zugriff!';
	exit();
}*/
//require_once ('../includes/beleg-config.php');
$db_link = mysqli_connect (
                     HOST, 
                     USER,
                     PASSWORD, 
                     DATABASE
                    );
$colors = "SELECT cal_month, cal_beleg, form_back, cal_back, back, font FROM color WHERE id = 1" ;
 
$db_erg = mysqli_query( $db_link, $colors );
if ( ! $db_erg )
{
  die('Ungültige Abfrage: ' . mysqli_error());
}
while ($zeile_c = mysqli_fetch_array( $db_erg, MYSQLI_ASSOC)){
	$cal_beleg = $zeile_c['cal_beleg'];
	$cal_month = $zeile_c['cal_month'];
	$form_back = $zeile_c['form_back'];
	$cal_back = $zeile_c['cal_back'];
	$back = $zeile_c['back'];
	$font = $zeile_c['font'];
}
?>
body{
    background-color: <?php echo $back; ?>;
	
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
form.login {
    background-color: #f9f9f9;
    padding: 10px;
	margin: 5px;
    border: 1px solid silver;
}
table {
	color: <?php echo $font; ?>;
}
table, tr, td {
	border: 1px solid black;
	background-color: <?php echo $cal_back; ?>;
	border-radius: 3px 3px 3px 3px;
}
#monat {
	background-color: <?php echo $cal_month; ?>;
}
<?php
$sql = "SELECT datean, dateab FROM times WHERE YEAR(datean) = ".$jahr." OR YEAR(dateab) = ".$jahr." ORDER BY datean" ;
 
$db_erg = mysqli_query( $db_link, $sql );
if ( ! $db_erg )
{
  die('Ungültige Abfrage: ' . mysqli_error());
}
while ($zeile = mysqli_fetch_array( $db_erg, MYSQLI_ASSOC))
{
	$datean = new DateTime($zeile['datean']);
	$dateab = new DateTime($zeile['dateab']);
	for ($date = clone $datean; $date <= $dateab; $date->modify('+1 day')) {
		$datum_vergl = $date->format('Y-m-d');
		$datum = $date->format('n-j-Y');
		if($datum_vergl == $zeile['datean']){
			$datum = explode("-", $datum);
			$datum = $month[$datum[0]]."-".$datum[1]."-".$datum[2];
			echo "#".$datum."{background-image: linear-gradient(135deg, ".$cal_back." 50%, ".$cal_beleg." 50%)}\n";
		}elseif($datum_vergl == $zeile['dateab']){
			$datum = explode("-", $datum);
			$datum = $month[$datum[0]]."-".$datum[1]."-".$datum[2];
			echo "#".$datum."{background-image: linear-gradient(135deg, ".$cal_beleg." 50%, ".$cal_back." 50%)}\n";
		}else{
			$datum = explode("-", $datum);
			$datum = $month[$datum[0]]."-".$datum[1]."-".$datum[2];
			echo "#".$datum."{background-color: ".$cal_beleg.";}\n";
		}
		
	}
}
mysqli_free_result( $db_erg );
?>