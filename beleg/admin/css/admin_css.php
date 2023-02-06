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
?>
#main {
position: absolut;
padding: 5px;
padding-top: 30px;
font-family: 'Roboto', sans-serif;
box-sizing: border-box;
}

#menu {
margin: 0;
padding: 0;
font-family: 'Roboto', sans-serif;
box-sizing: border-box;
}

nav {
float: left;
width: 100%;
background: #3a3a3a;
font-size: 16px;
}

nav ul {
margin: 0;
padding: 0;
}
nav ul.right {
margin: 0;
padding: 0;
float: right;
}


nav a {
display: block;
color: #fff;
text-decoration: none;
}

nav ul li {
position: relative;
float: left;
list-style: none;
color: #fff;
transition: 0.5s;
}

nav ul li a {
padding: 20px;
}

nav ul > li.submenu > a:after {
position: relative;
float: right;
content: '';
margin-left: 10px;
margin-top: 5px;
border-left: 5px solid transparent;
border-right: 5px solid transparent;
border-top: 5px solid #fff;
border-bottom: 5px solid transparent;
}

nav ul ul li.submenu > a:after {
margin-left: auto;
margin-right: -10px;
border-left: 5px solid #fff;
border-right: 5px solid transparent;
border-top: 5px solid transparent;
border-bottom: 5px solid transparent;
}

nav ul li:hover {
background: #4096ee;
}

nav ul ul {
position: absolute;
top: -9999px;
left: -9999px;
background: #333;
box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
z-index: 1;
}

nav ul ul li {
float: none;
width: 200px;
border-bottom: 1px solid #555;
}

nav ul ul li a {
padding: 10px 20px;
}

nav ul ul li:last-child {
border-bottom: none;
}

nav ul li:hover > ul {
top: 100%;
left: 0;
}

nav ul ul li:hover > ul {
top: 0;
left: 200px;
}
nav p {
color: #ffffff;
float: right;
padding-right: 5px;
padding-top: 5px;
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
input.right {
float: left;
margin: 2px;
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
#footer {

}
#footer div {

margin: 0px;
background: #3a3a3a;
position: absolute;
bottom: 5px;
right: 5px;
left: 5px;
height: 40px;
font-family: 'Roboto', sans-serif;
box-sizing: border-box;
}
#footer p {
color: orange;
float: right;
padding-right: 5px;
}
#bottom {
float: right;
}
.form_gen {
float: left;
line-height: 2.2em;
width: 29%;
}
#form_bsp {
float: left;
line-height: 2.2em;
width: 40%;
}

.gerade {
background-color: #efefef;
}
input[type=range] {
width: 10%;
}
label.create {
width: 10em;
display: block;
float: left;
}
form {
  background-color: #eee;
  padding: 1em;
  border: 1px solid #8c8c8c;
  font-size: 1em;
  border-radius: 5px;
}
form.free {
   background-color: transparent;
   padding: 0;
   border: 0;
   border-radius: 0;
}
form p{
line-height: 2em;
}
form span {
	padding-left: 10px;
	font-size: 0.7em;
}
fieldset {
	padding: 10px;
	border-radius: 5px;    
}
