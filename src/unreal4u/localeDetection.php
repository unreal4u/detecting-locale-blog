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
        'nl_NL',
    );

    /**
     * The definitive good old locale we'll load
     * @var string
     */
    public $locale = 'en_US';

    /**
     * Offers a very easy method to overwrite IP, useful for debugging
     * @var string
     */
    public $overwriteIp = '';

    /**
     * Path to GeoLite2 Country mmdb file
     * @link http://dev.maxmind.com/geoip/geoip2/geolite2/
     * @see $this->getLocaleFromIP()
     * @var string
     */
    public $geoliteCountryDBLocation = '';

    /**
     * Constructor
     *
     * @param array $validLocales A list of accepted locales
     */
    public function __construct($validLocales=array()) {
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

    /**
     * Gets the real ip from the user
     *
     * @return string
     */
    protected function _getIpFromClient() {
        $ip = '127.0.0.1';
        if (PHP_SAPI != 'cli' || !$this->overwriteIp) {
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }

        if ($this->overwriteIp) {
            $ip = $this->overwriteIp;
        }

        return $ip;
    }

    /**
     * Tries all methods in order
     *
     * @return string
     */
    public function getLocaleFromClient() {
        $this->locale = $this->getLocaleFromGetRequest();
        if (empty($this->locale)) {
            $this->locale = $this->getLocaleFromHeaders();
            if (empty($this->locale)) {
                $this->locale = $this->getLocaleFromIP();
            }
        }

        return $this->locale;
    }

    /**
     * Tries to find out from a GET variable which locale we should load
     *
     * @return string
     */
    public function getLocaleFromGetRequest() {
        $this->locale = '';

        if (isset($_GET['locale']) && $this->_checkLocale($_GET['locale']) !== '') {
            $this->locale = $_GET['locale'];
        }

        return $this->locale;
    }

    /**
     * Tries to find out from the headers which is the prefered locale
     *
     * @return string
     */
    public function getLocaleFromHeaders() {
        $this->locale = '';

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $preferredLocale = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $this->locale = $this->_checkLocale($preferredLocale);
        }

        return $this->locale;
    }

    /**
     * Gets the locale based on an ip search
     *
     * This method needs a readable database downloaded from MaxMind, check link
     * to download one and work with it. The database this class needs is the
     * "GeoLite2 Country" one
     *
     * @link http://dev.maxmind.com/geoip/geoip2/geolite2/
     * @throws \Exception Can throw any kind of MaxMind's exception
     * @return string
     */
    public function getLocaleFromIP() {
        $this->locale = '';

        if (!empty($this->geoliteCountryDBLocation)) {
            $reader = new \GeoIp2\Database\Reader($this->geoliteCountryDBLocation);
            $record = $reader->country($this->_getIpFromClient());
            $primaryLanguage = \Locale::getPrimaryLanguage($record->country->isoCode);
            if (!empty($primaryLanguage)) {
                $locale = $primaryLanguage.'_'.$record->country->isoCode;
                if ($this->_checkLocale($locale)) {
                    $this->locale = $locale;
                }
            }
        }

        return $this->locale;
    }
}
