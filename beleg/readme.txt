#########################################
#Belegungsplan 0.7			#
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
Readme

1. Vorrausetzungen

Um den Belegungsplan verwenden zu können benötigst Du: 
Webspace,
PHP,
MySQL-Datenbank


2. Installation

Nach dem Du die Downloaddatei entpackt hast, kannst Du den kompletten Ordner "belegbeta06" auf deinen 
Webserver laden. Die beiden Ordner "belegbeta06/includes" und "belegbeta06/admin/includes" benötigen für die 
Installation Schreibrechte, da dort die Zugangsdaten für die Datenbank abgelegt werden. 
Öffne Deinen Browser und gehe auf Deine Internetadresse + Pfad zum Belegungsplan + belegbeta06/install.

Beispiel: www.deinedomain.de/unterordner/belegbeta06/install/  

Nun gibst Du Deine Zugangsdaten für die Datenbank ein und klickst auf "weiter". Das Script erstellt
jetzt die Datenbanktabellen. Wenn alles geklappt hat, dann klickst Du auf die Schaltfläche weiter.
Für die Verwaltung Deines Belegungsplans, benötigst Du einen gesicherten Zugang. Die Daten dafür 
kannst Du jetzt eingeben. Die hier angegebene Emailadresse wird für Nachrichten an Dich genutzt. 
Das Passwort muss aus mindestens 7 Zeichen bestehen. Bedenke, dass ein 
sicheres Passwort aus einer Kombination von Ziffern, Großbuchstaben, Kleinbuchstaben und 
Sonderzeichen besteht. Merke Dir Deine Zugangsdaten! Nachdem Du auf Abschicken geklickt hast, kannst
Du dich auch schon Einloggen.

Zum Abschluß der Installation musst Du nur noch den Ordner "install/" löschen, und den beiden Ordnern 
"belegbeta06/includes" und "belegbeta06/admin/includes" die Schreibrechte wieder entziehen. 

3. Objekt (Ferienwohnung / -haus) anlegen

Nachdem Login klickst Du auf Ferienobjekte -> verwalten.
Die Liste Deiner Objekte ist noch leer, deshalb wählst Du "neues Objekt anlegen".
Im folgenden Formular gibst Du den Namen Deines Ferienobjekt ein, die anderen Felder sind optional, 
und klickst auf "speichern"! Die Felder Max Gäste und Max Hunde sollten dennoch ausgefüllt werden.
Diese sind für das Buchungssystem von Bedeutung. Wenn alles richtig war kannst Du auf weiter klicken.

4. Buchungsformulare anlegen

Gehe auf Buchungen -> Formulare -> Formular für ...
Hier kannst Du in der ersten Spalte die Felder auswählen, die auf Deiner Webseite angezeigt werden sollen.
In der zweiten Spalte kannst Du wählen ob der Gast das Feld ausfühlen muss. Ja = Pflichfeld Nein = nicht Notwendig.
Mit einem Klick auf "Formular speichern" ist dieses auch angelegt.

5. Text für Emails an den Gast

Wähle Einstellungen -> Textbausteine
Nun kannst Du im oberen Feld den Text eingeben, den der Gast bei Deiner Buchungsbestätigung als Email erhält.
Hier empfehle ich alle wichtigen Informationen zur Anreise, Abreise und zum Aufenthalt anzugeben.
!!! Die Anrede und der Buchungszeitraum werden automatisch erstellt. !!!

Im unteren Feld kannst Du den Text eingeben, den der Gast erhält, wenn er das Buchungsformular ausgefüllt hat.
Auch hier wird die Anrede wieder automatisch erstellt. Ich empfehle hier darauf hinzuweisen, dass dies nur eine 
Buchungsanfrage darstellt, welche erst mit der Buchungsbestätigung zur verbindlichen Buchung wird.

6. Einbindung des Kalenders auf Deiner Webseite

6.1 Den Kalender per IFRAME einbinden
Du kannst den Kalender in eine bestehende oder neue Seite per IFRAME einbinden. 
Dazu gibt es im Belegungsplan ein Javascript welches die Größe des IFRAMES automatisch anpasst.
Füge im Headbereich Deiner Seite (als zwischen <head> und </head>) folgenden Code ein:

<script src="beleg/js/iframe.js"></script>

Eventuell musst Du noch den Pfad zum Belegungsplan anpassen.

Jetzt gehst Du im Adminbereich auf Ferienobjekte -> verwalten. Im folgenden Fenster klickst Du bei dem 
Ferienobjekt dessen Kalender Du ausgeben möchtest auf "bearbeiten".

Hier siehst Du nun den Code, den Du auf einer beliebigen Seite ausgeben kannst. Kopiere den Code von 
"<div>" bis "</div>" und füge diesen in den Quellcode Deiner Seite ein. Platziere diesen dort, 
wo der Kalender zu sehen Sein soll.

Verfahre so auch mit allen anderen Ferienwohnungen.

6.2 Direkt auf den Kalender verlinken
Den Kalender kannst Du auch direkt aufrufen in dem Du einen Link auf den gewünschten Kalender setzt.
Der Link könnte so aussehen:

<a href="beleg/index.php?objekt=1">Belegung und Buchung</a>

Bei "objekt=1" Ersetzt Du die 1 mit der ID des gewünschten Objekts.
Je nach Installationsort musst Du noch den Pfad anpassen.
	
7. Verwendung

Um den Belegungsplan zu Verwalten musst Du dich im Adminbereich einloggen. Rufe dazu in deinem Browser 
den Adminbereich auf. 

Beispiel: www.deinedomain.de/unterordner/beleg/admin/

Nach dem Login kannst Du Belegungen erstellen, Belegungen löschen und die Farben des Kalenders anpassen.

Um eine Belegung zu erstellen, wähle "Buchungen -> Eintragen -> Eintragen in ....
Gib ein Anreise- und ein Abreisedatum an und klicke auf weiter. 

Um eine Belegung zu löschen, wähle "Buchungen -> Löschen.
Klicke auf den Eintrag, den Du löschen möchtest und und bestätige mit "Ja".

Um eine Buchung zu bestätigen wähle "Buchungen -> Buchungsanfragen"
Du siehst nun alle offenen Buchungsanfragen und kannst mit einem Klick auf bearbeiten diese dann auch bestätigen.
Dazu klickst Du auf der folgenden Seite auf "Buchung bestätigen" nun siehst du im unteren Bereich der Seite 
ein Emailformular. (lass Dich nicht von den <p> und <br> verwirren, bekommt der Gast nicht zu sehen)
Hier kannst Du die Email, die der Gast erhält noch kontrollieren oder verändern. Mit dem senden-Button 
sendest Du die Mail ab.
Buchungsanfragen kannst Du auch mit dem Link in der Email die Du bekommst bestätigen.

Du kannst auch Buchungsanfragen widerrufen, indem Du "Buchungen -> Buchungen" wählst.
Der Ablauf ist der gleiche wie beim Bestätigen.

Die Farben des Kalender änderst Du über Design -> Farben. Hier kannst Du alle Farben auswählen. Mit einem
Klick auf Speichern werden Deine gewählten Farben übernommen. Unten siehst Du wie Deine Auswahl aussieht.

Du kannst wählen ob der Kalender ein ganzes Jahr oder nur einzelne Monate anzeigen soll. Gehe dazu auf 
Design -> Anzeige. Klicke Jahres-, Monatsansicht oder Automatisch an, wähle wieviele Monatskalender angezeigt werden 
sollen und danach auf speichern.

Bei Automatisch wird je nach Gerät (Computer, Tablet oder Handy) der passende Kalender angezeigt.

Wenn Du alles erledigt hast kannst Du oben rechts auf Deinen Benutzernamen klicken um Dich auszuloggen.

Buchungsanfragen kann der Gast starten indem er im Kalender auf den Tag klickt, an dem er anreisen möchte.
Dann öffnet sich ein Formular wo er seine Kontaktdaten eingeben kann.

Ich wünsche Dir viel Freude mit dem Belegungsplan und vor allem viel Erfolg!
Daniel Procek-Berger alias HackMeck

	
Solltest Du Hilfe benötigen, kannst Du mir eine Email schreiben. Über ein "Dankeschön" freue ich mich 
natürlich auch.
Wenn Dich meine Arbeit überzeugt kannst Du mir über Paypal auch gern eine kleine Spende zukommen lassen,
meine Paypaladresse lautet daproc@gmx.net.
 


 