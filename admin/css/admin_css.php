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
