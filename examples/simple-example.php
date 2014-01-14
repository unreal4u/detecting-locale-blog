<?php

include('../vendor/autoload.php');
include('../src/unreal4u/localeDetection.php');

$localeDetection = new unreal4u\localeDetection();
var_dump($localeDetection->getLocaleFromClient());
var_dump($localeDetection->getLocaleFromGetRequest());
var_dump($localeDetection->getLocaleFromIP('../db/GeoLite2-Country.mmdb'));
