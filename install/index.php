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
	
?>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="styles/main.css" />
</head>
<body>
<h3>Installation von Belegungsplan</h3>
<p>Sollten sie Hilfe benötigen wenden sie sich an daproc [at] gmx [punkt] net! Gegen eine Spende bin ich gern bereit die Installation für Sie zu übernehmen.</p>
<div class="formular">
<form action="db_test.php" method="post">
	<fieldset>
	<legend>Datenbank</legend>
	<p>
	<label for="db_name">Datenbankname</label>
		<input type="text" name="db_name" required><span> wird meist von Ihrem Provider zugewiesen</span>
	</p>
	<p>
	<label for="db_host">Datenbankhost</label>
		<input type="text" name="db_host" value="localhost" required><span> ist oft "localhost" oder in Form einer IP: 123.456.789.123 </span>
	</p>
	<p>
	<label for="db_user">Datenbanknutzer</label>
		<input type="text" name="db_user" required><span> wird meist von Ihrem Provider zugewiesen</span>
	</p>
	<p>
	<label for="db_pass">Datenbankpasswort</label>
		<input type="password" name="db_pass"><span> wird von Ihrem Provider zugewiesen oder beim Erstellen der Datenbank vergeben</span>
	</p>
	
	<p>
	<label></label>
		<input type="submit" value="weiter">
	<p>
	</fieldset>
</form>
</div>
</body>
</html>