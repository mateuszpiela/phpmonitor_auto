<?php
/*
 * Użyto biblioteki https://github.com/fabiang/xmpp na licencji Simplified BSD License Autor Biblioteki: Fabian Grutschus @fabiang
 *
 *
 * Zrobione na licencji MIT
 * Autor : Mateusz Pieła
 * Narzędzie do automatycznego sprawdzenia czy usługi działają
 * Wersja 0.2
 */


if(!defined("Status"))
{
    die("Uruchrom: status.php");
}
$XMPP_USER = "";
//  musi być tcp://domena:port Domyślny port to 5222
$XMPP_SRV = "";
$XMPP_PASS = "";
//Do
$XMPP_TO = "";
//Porty Format: Numer => Nazwa
$PORTY = array(
    3306 => "MySQL",
    22 => "Shell",
    80 => "Apache2/HTTP"
);
//Wpisz adres domeny
$HTTP_HOST = "";
