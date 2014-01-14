<?php

include('../vendor/autoload.php');
include('../src/unreal4u/localeDetection.php');

$localeDetection = new unreal4u\localeDetection();
$localeDetection->overwriteIp = '77.251.240.195';
$localeDetection->geoliteCountryDBLocation = '../db/GeoLite2-Country.mmdb';

echo 'locale from client: ';
var_dump($localeDetection->getLocaleFromClient());
echo 'locale from get request: ';
var_dump($localeDetection->getLocaleFromGetRequest());
echo 'locale from ip: ';
var_dump($localeDetection->getLocaleFromIP());

echo 'locale from prefered method: ';
var_dump($localeDetection->getLocaleFromClient());
