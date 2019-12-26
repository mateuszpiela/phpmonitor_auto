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
define('Status', TRUE);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';


checkup();

foreach ($PORTY as $port => $nazwa_portu)
{

    if($port == 80 || $port == 443)
    {
        $connection = @fsockopen("127.0.0.1", $port);
        if (is_resource($connection)) {
            $ch = curl_init($HTTP_HOST);
            curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
            curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        
            if($httpcode == 202)
            {
                service_is_up($port,$nazwa_portu);
            }
            elseif($httpcode == 500)
            {
                service_is_down($port,$nazwa_portu);
            }

            fclose($connection);
        }
        else
        {
            service_is_down($port,$nazwa_portu);
        }


    }
    else
    {
    $connection = @fsockopen("127.0.0.1", $port);
    if (is_resource($connection))
    {

        service_is_up($port,$nazwa_portu);
        fclose($connection);
    }
    else
    {
        service_is_down($port,$nazwa_portu);
    }
    }
    
}
