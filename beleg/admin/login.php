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
	
session_start();
require_once ('includes/beleg-config.php');
$db_link = mysqli_connect (
                     HOST, 
                     USER,
                     PASSWORD, 
                     DATABASE
                    );
 
if(isset($_GET['login'])) {
 $email = $_POST['email'];
 $passwort = $_POST['passwort'];
 
 $mail_chk = "SELECT * FROM users WHERE email = '".$email."'";
 $result = mysqli_query($db_link, $mail_chk);
 $row = mysqli_fetch_assoc($result);
 //$statement = $db_link->prepare("SELECT * FROM users WHERE email = :email");
 //$result = $statement->execute(array('email' => $email));
 //$user = $result->fetch();

$request = NULL;

while ($bla = current($_GET)) {
	if(key($_GET) == 'login'){
    next($_GET);
	}else{
	$request[] = key($_GET).'='.$_GET[key($_GET)];
    next($_GET);
	}
}
if($request != NULL){
	$ende = implode( '&', $request);
}else{
	$ende = NULL;
}
//echo $ende;
$uri = 'index.php?'.$ende;
//echo $uri; 

 //Überprüfung des Passworts
 if ($row !== NULL && password_verify($passwort, $row['passwort'])) {
 $_SESSION['userid'] = $row['id'];
 header('refresh:0;url='.$uri);
 //header('Location: index.php?'.$ende);
 die('Login erfolgreich. Weiter zu <a href="index.php?'.$ende.'">internen Bereich</a>');
 } else {
 $errorMessage = "E-Mail oder Passwort war ungültig<br>";
 }
 
}
?>
<!DOCTYPE html> 
<html> 
<head>
  <meta charset="utf-8">
  <title>Login</title> 
</head> 
<body>
 
<?php 
if(isset($errorMessage)) {
 echo $errorMessage;
}
?>
 
<form action="?login=1" method="post">
E-Mail:<br>
<input type="email" size="40" maxlength="250" name="email"><br><br>
 
Dein Passwort:<br>
<input type="password" size="40"  maxlength="250" name="passwort"><br>
 
<input type="submit" value="Abschicken">
</form> 
</body>
</html>
