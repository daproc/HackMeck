<?php
#####################################
#Belegungsplan 0.6					#
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
require_once ('includes/beleg-config.php');
$db_link = mysqli_connect (
                     HOST, 
                     USER,
                     PASSWORD, 
                     DATABASE
                    );
$settings = "SELECT cal_typ FROM settings WHERE id = 1" ;
 
$db_erg = mysqli_query( $db_link, $settings );
if ( ! $db_erg )
{
  die('Ungültige Abfrage: ' . mysqli_error());
}
while ($zeile_s = mysqli_fetch_array( $db_erg, MYSQLI_ASSOC)){
	$cal_typ = $zeile_s['cal_typ'];
	
}

if($cal_typ == 1){
	include ('cal_m.php');
}elseif($cal_typ == 2){
	include ('cal.php');
}else{
	?>
	<!DOCTYPE html>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Belegungsplan</title>
<style>
<?php
$remote = 24519;
if(isset($_GET['jahr'])and !empty($_GET['jahr'])) //Prüfen ob Jahr ausgewählt wurde
	{
	$jahr = $_GET['jahr'];  //Jahr uebernehmen
}else {
	$jahr = date("Y");      //Ohne Auswahl aktuelles Jahr übernehmen
}
if(isset($_GET['month'])and !empty($_GET['month'])) //Prüfen ob Jahr ausgewählt wurde
	{
	$month_now = $_GET['month'];  //Monat uebernehmen
}else {
	$month_now = date("m");      //Ohne Auswahl aktuelles Jahr übernehmen
}
$objekt = $_GET['objekt'];
$month = array("Monat", "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
$days = array("Mo", "Di", "Mi", "Do", "Fr", "Sa", "So");
include ('includes/functions.php');
include ('css/insert_css.php');
?>
</style>
<link rel="stylesheet" href="css/main.css" />	
</head>
<body>
<p>Den Anreisetag im Kalender wählen um eine Buchungsanfrage zu senden.</p>
<main>
<div id="cal">
	<div id="mobi">
		<p class="none">
		
		<?php

//$month_now = date("m");
$m = date("n", strtotime($jahr."-".$month_now."-01"));
//$year = date("Y");
$day_first = date("N", strtotime($jahr."-".$month_now."-01"));
$day_last = date("t", strtotime($jahr."-".$month_now."-01"));
//echo $day_last;
?>
<form action="index.php" method="get" >
<?php
echo '<select name="month" size="1">';
for($k == 1; $k <=12; $k++){
	if($k == $m){
		echo '<option value="'.$k.'" selected>'.$month[$k].'</option>';
	}else{
		echo '<option value="'.$k.'">'.$month[$k].'</option>';
	}
}
echo '</select>';
echo '<select name="jahr" size="1">';
for ($year = date("Y"); $year <= (date("Y")+2); $year++){  //Auswahlfeld aktuelles Jahr und die 2 folgenden
    if ($year == $jahr){
		echo '<option selected>'.$year.'</option>';
	}else{
		echo   "<option>".$year."</option>";
	}
}
if ($jahr < date("Y") OR $jahr > $year){
	echo '<option selected>'.$jahr.'</option>';
}
echo '<input type="hidden" name="objekt" value="'.$objekt.'">';
?>
     </select>
	<input type="submit" value="wechseln">
</form>
<?php
echo '<table id="tabelle">';
if($m == 1){
	echo '<thead><tr><th><a href="index.php?jahr='.($jahr-1).'&month=12&objekt='.$objekt.'"> &lt;&nbsp; </a></th><th colspan="5">'.$month[$m].' '.$jahr.'</th><th><a href="index.php?jahr='.$jahr.'&month='.($m+1).'&objekt='.$objekt.'"> &gt;&nbsp; </a></th></tr></thead>';
}elseif($m == 12){
	echo '<thead><tr><th><a href="index.php?jahr='.$jahr.'&month='.($m-1).'&objekt='.$objekt.'"> &lt;&nbsp; </a></th><th colspan="5">'.$month[$m].' '.$jahr.'</th><th><a href="index.php?jahr='.($jahr+1).'&month=1&objekt='.$objekt.'"> &gt;&nbsp; </a></th></tr></thead>';
}else{
	echo '<thead><tr><th><a href="index.php?jahr='.$jahr.'&month='.($m-1).'&objekt='.$objekt.'"> &lt;&nbsp; </a></th><th colspan="5">'.$month[$m].' '.$jahr.'</th><th><a href="index.php?jahr='.$jahr.'&month='.($m+1).'&objekt='.$objekt.'"> &gt;&nbsp; </a></th></tr></thead>';
}
echo '<tfoot><tr>';
	echo '<td colspan="8" align="right" border="0">&copy; 2016-'.date('Y').' <a href="http://hackmeck.bplaced.net">HackMeck</a></td></tr></tfoot>';
echo '<tr>';
for($d = 0; $d <= 6; $d++){
	echo '<td id="monat" width="30px" height="20px" align="center">'.$days[$d].'</td>';
}
for($a=$day_last+$day_first-1;$a % 7 != 0;$a++){
}
for($i=1; $i <= $a; $i++){
	$day = $i - $day_first + 1;
	if($i % 7 == 1){
		echo '<tr>';
	}
	if($i < $day_first OR $day > $day_last){
		echo '<td id="leer" width="30" height="30px" align="right"> </td>';
	}
	if($i >= $day_first AND $day <= $day_last){
		echo '<td id="'.$month[$m].'-'.$day.'-'.$jahr.'" width="30" height="30px" align="right"><a href="booking.php?objekt='.$objekt.'&date='.$jahr.'-'.$m.'-'.$day.'">'.$day.'</a></td>';
	}
	if($i % 7 == 0){
		echo '</tr>';
	}
}
echo '</table>';
?>
		
		</p>
	</div>
	<div id="web">
		<p class="visi">
		
		<form action="index.php" method="get" >
	<select name="jahr" size="1">
<?php
for ($year = date("Y"); $year <= (date("Y")+2); $year++){  //Auswahlfeld aktuelles Jahr und die 2 folgenden
    if ($year == $jahr){
		echo '<option selected>'.$year.'</option>';
	}else{
		echo   "<option>".$year."</option>";
	}
}
if ($jahr < date("Y") OR $jahr > $year){
	echo '<option selected>'.$jahr.'</option>';
}
echo '<input type="hidden" name="objekt" value="'.$objekt.'">';
?>
     </select>
	<input type="submit" value="wechseln">
</form>
<?php
cal($month, $jahr, $objekt);
?>

		
		</p>
	</div>
</div>	
</main>

</body>
<?php
}
?>
