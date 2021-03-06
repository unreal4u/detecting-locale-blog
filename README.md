localeDetection
======

This is an example class for a [blog post available here](http://blog.unreal4u.com/).

Disclaimer
--------

This is not an official class or something that you can put right on a
production system! This is intended to be an example class for a blog post only!

The blog post is available at my blog and is in spanish. You should read that
blog post first prior to using this code!

Disclaimer
--------

Esto no es una class oficial o algo que puedas poner en tu sistema de
producción! Sólo es un ejemplo de una class que fue escrita rápidamente para un
post en mi blog!

El post del blog está disponible en mi blog y está en español. Deberías leer eso
antes de ocupar este código!

Installation
--------

* Execute composer with <code>composer.phar install</code> or <code>composer.phar update</code>
    * This should install all dependencies
    * If you don't know how to use composer or what it is, visit their [official homepage](http://getcomposer.org/).
* Download Maxmind's GeoLite2 Country database from [their website](http://dev.maxmind.com/geoip/geoip2/geolite2/).
* Use the code:
<pre>include('src/unreal4u/localeDetection.php');
$localeDetection = new unreal4u\localeDetection();
$localeDetection->geoliteCountryDBLocation = '../db/GeoLite2-Country.mmdb';
var_dump($localeDetection->getLocaleFromClient());
</pre>

Instalación
--------

* Ejecuta composer con <code>composer.phar install</code> or <code>composer.phar update</code>
    * Esto debería instalar todas las dependencias.
    * Si no sabes cómo usar composer o qué es, visita su [página oficial](http://getcomposer.org/).
* Baja la base de datos "GeoLite2 Country" desde la [página de Maxmind](http://dev.maxmind.com/geoip/geoip2/geolite2/).
* Usa el código:
<pre>include('src/unreal4u/localeDetection.php');
$localeDetection = new unreal4u\localeDetection();
$localeDetection->geoliteCountryDBLocation = '../db/GeoLite2-Country.mmdb';
var_dump($localeDetection->getLocaleFromClient());
</pre>
