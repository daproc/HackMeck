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
$host = $_POST['db_host'];
$user = $_POST['db_user'];
$pass = $_POST['db_pass'];
$name = $_POST['db_name'];
$db_link = mysqli_connect (
                     $host, 
                     $user,
                     $pass, 
                     $name
                    );

if (mysqli_connect_errno()) {
    $fehler = mysqli_connect_errno();
	if ($fehler == 1049){
		echo 'Datenbank nicht bekannt';
	}
	if ($fehler == 2002){
		echo 'Kann nicht mit Host verbinden';
	}
	if ($fehler == 1045){
		echo 'Nutzer oder Passwort falsch';
	}
    exit();
}else{
	mysqli_set_charset($db_link, 'utf8');
	$inhalt = '<?php

define("HOST", "'.$host.'");
define("USER", "'.$user.'"); 
define("PASSWORD", "'.$pass.'");
define("DATABASE", "'.$name.'");
';
 
	$handle = fopen("../admin/includes/beleg-config.php", "w");
	fwrite($handle, $inhalt);
	fclose($handle);
	$handle2 = fopen("../includes/beleg-config.php", "w");
	fwrite($handle2, $inhalt);
	fclose($handle2);

	$import = file_get_contents("test.sql");

	   $import = preg_replace ("%/\*(.*)\*/%Us", '', $import);
	   $import = preg_replace ("%^--(.*)\n%mU", '', $import);
	   $import = preg_replace ("%^$\n%mU", '', $import);

	   mysqli_real_escape_string($db_link, $import); 
	   $import = explode (";", $import); 

	   foreach ($import as $imp){
		if ($imp != '' && $imp != ' '){
		 mysqli_query($db_link, $imp);
		}
	   }  
	?>
	<form action="register.php" method="post">
		
	<label></label>
		<input type="submit" value="weiter">
</form>
<?php
}
?>