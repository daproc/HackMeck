#########################################
#Belegungsplan xxx			#
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

/*
  Belegungsplan is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License version 2 as
  published by the Free Software Foundation.

  Belegungsplan is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License along
  with this program; if not, see <http://www.gnu.org/licenses/>.


  Other used software:

  CKEditor 
  License: GNU General Public License Version 2 or later (the “GPL”)
  Copyright (c) 2003-2017, CKSource Frederico Knabben 

  PHPMailer
  License: GNU Lesser General Public License Version 2.1 or later (the “LGPL”)
  http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html
  copyright 2012 - 2017 Marcus Bointon
  copyright 2010 - 2012 Jim Jagielski
  copyright 2004 - 2009 Andy Prevost
*/



1. Requirements

In order to be able to use the booking system you need:
web space,
PHP,
MySQL database


2. Installation
 
After you have unzipped the downloaded file, you can upload the complete folder
"beleg" to your web space. The two folders "/beleg/includes" and "/beleg/admin/includes"
need write permission for the installation, because the access data for the database
is stored there. Open your browser and go to your domain + path to the Belegungsplan + /beleg/install.

Example: www.yourdomain.de/subfolders/beleg/install

Enter your access data for the database and click on "Weiter" (Continue). The script
creates the database tables. If everything worked, then you click "Weiter" (Continue).
For the administration of your booking system, you need a secured access. The data for
this you can enter now. The email address you enter here will also be used for messages to you. 
The password must be at least 7 characters long. Consider, a secure password consists of
a combination of numbers, upper case characters, lower case characters and special characters.
Remember your login credentials! After you have clicked on "Absenden" (Submit), you can log in.

To finish the installation you delete the folder "/beleg/install" and and remove write permissions
from the two folders "/beleg/includes" and "/beleg/admin/includes".


3. create object (vacation apartment / house)

After logging in, click on "Ferienobjekte -> Verwalten" (vacation objects -> Manage)
The list of your objects is still empty, so you choose "neues Objekt anlegen" (create
new object). In the following form you enter the name of your vacation object, the other
fields are optional, and click on "speichern" (Save). The fields "Max Gäste" (max guests)
and "max Hunde" (max Dogs) should be filled in however. These are important for the booking
system. If everything was correct you can click on "weiter" (continue).

4. create booking forms

Go to "Ferienobjekte -> Formulare -> Formular für..." (vacation objects -> forms --> form for..)
Here you can select in the first column the fields that should be displayed on your website.
In the second column you can choose if the guest has to fill out the field. "Ja" (Yes) =
mandatory field "Nein" (No) = not required. With a click on "Formular speichern" (save form)
this is also created.

5 Emails

5.1 Email settings

Via "Einstellungen -> Email-Einstellungen" (Settings -> Email Settings) you can
specify an SMTP server for outgoing emails. This has the advantage that emails
are less likely to end up in the spam folder of guests.
You get the data from the provider of your email address.
If you don't change any settings, emails will be sent via your web server.

Example using GMX
Server: mail.gmx.net
Username: Your email address
Password: Your GMX password
Port: can stay on 587

5.2 Text for emails to the guest

Choose "Einstellungen --> Textbausteine" (Settings -> Text modules)
Now you can enter in the upper field the text that the guest will receive as an email
with your booking confirmation. Here I recommend to enter all important information
about arrival, departure and stay.
!!! The salutation and the booking period will be created automatically. !!!

In the lower field you can enter the text that the guest will receive when he has
filled out the booking form. Again, the salutation will be created automatically.
I recommend here to point out that this represents only a booking request, which
only becomes a binding booking with the booking confirmation. You can also select
your terms and conditions or other PDF files to be sent as attachments in the
automatically generated mail. 

6 Integration of the calendar on your website

6.1 Integrate the calendar via IFRAME
You can integrate the calendar into an existing or new page via IFRAME.
There is also a JavaScript in the booking plan which automatically adjusts the
size of the IFRAMES. In the head area of ​​your page (as between <head> and </head>)
add the following code:

<script src="beleg/js/iframe.js"></script>

You may have to adjust the path to the booking plan installation.

Now go to holiday "Ferienobjekte -> Verwalten" (objects -> manage) in the
admin area. In the following window you click on the holiday property/object,
whose calendar you want to output, on "bearbeiten" (edit).

Here you can see the code that you can output on any page. Copy the code from
"<div>" to "</div>" and paste this into the source code of your page. Place
this one there where the calendar should be seen.

Proceed in the same way with all other holiday apartments.

6.2 Link directly to the calendar
You can also call up the calendar directly by placing a link to the desired calendar.
The link could look like this:

<a href="beleg/index.php?objekt=1">Occupancy and booking</a>

With "objekt=1" you replace the 1 with the ID of the desired object.
Depending on the installation location, you still have to adjust the path.

7. Use

To manage the booking plan you have to log in to the admin area. Call in your browser 
the admin area.

Example: www.yourdomain.de/subfolders/beleg/admin/

After login you can create bookings, delete bookings and change the colors of the calendar.

To create a booking, choose "Buchungen -> Eintragen -> Eintragen in ...."
(Bookings -> Add -> Add to ...."). Enter an arrival and a departure date and
click on "weiter" (continue). 

To delete a booking, select "Buchungen --> löschen" (Bookings -> Delete).
Click on the entry you want to delete and confirm with "Ja" (Yes).

To confirm a booking select "Buchungen -> Buchungsanfragen" (Bookings -> Booking requests).
You will now see all open booking requests and can confirm them by clicking on "bearbeiten"
(edit). To do this, click on "Buchung bestätigen" (Confirm booking) on the next page and
you will see an email form at the bottom of the page. Here you can check or change the
email the guest receives and select an attachment like an invoice or your terms and conditions.
With the send button you send the mail. You can also confirm booking requests with the link
in the email you receive.

You can also cancel booking requests by selecting "Buchungen -> Buchungen" (Bookings -> Bookings).
The procedure is the same as for confirming.

You can change the colors of the calendar via "Einstellungen -> Farbe" (Settings -> Colors).
Here you can select all colors. With a click on "Speichern" (Save) to save your selected colors.
Below you can see how your selection looks like.

You can choose if the calendar should show a whole year or only single months. To do this go to
"Einstellungen --> Anzeige" (Settings -> Display). Click on "Jahresansicht" (Year View),
"Monatsansicht" (Month View) or "Automatisch" (Automatic), choose how many monthly calendars
you want to show and then click on "speichern" (save).

If you select "Automatisch" (Automatic), the appropriate calendar will be displayed depending
on the device (computer, tablet or cell phone).

If you have done everything, you can click on your username in the upper right corner to log out.

Booking requests can be started by the guest by clicking in the calendar on the day on which
he wants to arrive. Then a form opens where he can enter his contact details.

I wish you're going to enjoy the booking system and even more success!
Daniel Procek-Berger alias HackMeck

In case you need help, you can write me an e-mail. I'm also happy to receive a "thank you".
If you're pleased with my work, you can send me a small donation via PayPal, my PayPal address
is daproc@gmx.net
