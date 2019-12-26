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



use Fabiang\Xmpp\Client;
use Fabiang\Xmpp\Options;
use Fabiang\Xmpp\Protocol\Roster;
use Fabiang\Xmpp\Protocol\Presence;
use Fabiang\Xmpp\Protocol\Message;

function checkup()
{
	if (!file_exists(__DIR__ . '/port_off')){
	    mkdir(__DIR__ . '/port_off', 0700, true);
	}
}

function service_is_up($port, $nazwa_portu)
{
    require __DIR__ . "/config.php";

    if (file_exists(__DIR__ . "/port_off/". $port . ".off"))
    {
        unlink(__DIR__ . "/port_off/". $port . ".off");
        $xmpp_opt = new Options($XMPP_SRV);
        $xmpp_opt->setUsername($XMPP_USER)
              ->setPassword($XMPP_PASS);
        $xmpp_cl = new Client($xmpp_opt);
        $xmpp_cl->connect();
        $xmpp_cl->send(new Roster);
        $xmpp_cl->send(new Presence);
        $wiad = new Message;
        $wiad->setMessage("Już jest wszystko w porządku usługa " . $nazwa_portu . " \r\n Już działa")
           ->setTo($XMPP_TO);
        $xmpp_cl->send($wiad);
        $xmpp_cl->disconnect();
    }
}

function service_is_down($port, $nazwa_portu)
{ 

    require __DIR__ . "/config.php";

    $xmpp_opt = new Options($XMPP_SRV);
    $xmpp_opt->setUsername($XMPP_USER)
          ->setPassword($XMPP_PASS);
    $xmpp_cl = new Client($xmpp_opt);
    $xmpp_cl->connect();
    $ile_razy_nie_dziala_serwer = 0;
    if (!file_exists(__DIR__ . "/port_off/" . $port . ".off")) {
        $error_file = fopen(__DIR__ . "/port_off/" . $port . ".off", "x");
        fclose($error_file);
    }
    $error_file = fopen(__DIR__ . "/port_off/" . $port . ".off", "r+");
    $fsize = filesize(__DIR__ . "/port_off/" . $port . ".off");
    if ($fsize >= 1) {
        $x = (int)fread($error_file, filesize("./port_off/" . $port . ".off"));
        $x++;
        $ile_razy_nie_dziala_serwer = $x;
        $error_file = fopen(__DIR__ . "/port_off/" . $port . ".off", "w");
        fwrite($error_file, $x);
    } else {
        $x = 1;
        $ile_razy_nie_dziala_serwer = $x;
        fwrite($error_file, $x);
    }
    fclose($error_file);

    
    $xmpp_cl->send(new Roster);
    $xmpp_cl->send(new Presence);
    $wiad = new Message;
    $wiad->setMessage("Uwaga wysiadła na serwerze usługa " . $nazwa_portu . " \r\n Błąd zgłaszam po raz " . $ile_razy_nie_dziala_serwer)
        ->setTo($XMPP_TO);
    $xmpp_cl->send($wiad);
    $xmpp_cl->disconnect();
}
