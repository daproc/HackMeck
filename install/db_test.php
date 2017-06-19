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