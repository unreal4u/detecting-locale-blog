<?php

namespace unreal4u;

/**
 * Is able to detect through various methods which locale we must load
 *
 * @author unreal4u
 */
class localeDetection {
    /**
     * A list of valid locales, can be overwritten on constructor
     * @var array
     */
    public $validLocales = array(
        'en_US',
        'es_CL',
    );

    /**
     * The definitive good old locale we'll load
     * @var string
     */
    public $locale = 'en_US';

    /**
     * The database connection
     * @var unreal4u\dbmysqli
     */
    protected $_dbConnection = null;

    /**
     * Constructor
     * @param array $validLocales A list of accepted locales
     */
    public function __construct($validLocales) {
        if (is_array($validLocales) && !empty($validLocales)) {
            $this->validLocales = $validLocales;
        }
    }

    /**
     * Does some checks on our intern locale array, returns a valid locale or an empty string otherwise
     *
     * @param string $locale
     * @return string
     */
    protected function _checkLocale($locale='') {
        if (is_string($locale) && in_array($locale, $this->validLocales)) {
            return $locale;
        }

        return '';
    }

    protected function _getIpFromClient() {
        $ip = '127.0.0.1';
        if (PHP_SAPI != 'cli') {
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }

        return $ip;
    }

    /**
     * Tries to find out from a GET variable which locale we should load
     */
    public function getLocaleFromGetRequest() {
        if (isset($_GET['locale']) && $this->_checkLocale($_GET['locale']) !== '') {
            $this->locale = $_GET['locale'];
        }
    }

    /**
     * Tries to find out from the headers which is the prefered locale
     */
    public function getLocaleFromHeaders() {
        $this->locale = '';

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $preferredLocale = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $this->locale = $this->_checkLocale($preferredLocale);
        }

        return $this->locale;
    }

    public function getLocaleFromIP($dbName='', $dbHost='', $dbUser='', $dbPass='', $dbPort=3306) {
        $ip = $this->_getIpFromClient();

        $this->_dbConnection = new unreal4u\dbmysqli();
        $this->_dbConnection->registerConnection($dbName, $dbHost, $dbUser, $dbPass, $dbPort);
        $result = $this->_dbConnection->query('SELECT VERSION()');
        var_dump($result);
    }
}