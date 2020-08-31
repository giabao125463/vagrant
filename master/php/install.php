<?php
    session_start();
    define ('ECCUBE_INSTALL', 'ON');
    define ('SITE_URL_ORIG', 'http://giftland.trunk/');
    define ('SSL_URL_ORIG', 'https://giftland.trunk/');
    //define ('SITE_URL_ORIG', 'http://192.168.20.127/');
    //define ('SSL_URL_ORIG', 'http://192.168.20.127/');
    define ('HTML_PATH', '/var/www/sol_traveler_ec/html/');
    //define ('HTML_PATH', '/var/www/sol_traveler_ec-site/html/');
    //define ('SITE_URL_ORIG', 'http://giftland.trunk/');
    //define ('SSL_URL_ORIG', 'https://giftland.trunk/');
    define ('URL_DIR_ORIG', '/');
    define ('DOMAIN_NAME', '');
    define ('DB_TYPE', 'pgsql');
    define ('DB_USER', 'traveler_eccube');
    define ('DB_PASSWORD', 'SxpDWlB8');
//    define ('DB_SERVER', '192.168.0.174');
        
//    define ('DB_PASSWORD', 'j2webeBL');
//    define ('DB_SERVER', '192.168.0.144');

    //define ('DB_PASSWORD', 'SxpDWlB8');
    define ('DB_SERVER', '192.168.33.10');
    //define ('DB_NAME', 'traveler_eccube_db_local');
    define ('DB_NAME', 'traveler_eccube_db');
    //define ('DB_NAME', 'test_traveler_eccube_db');
    
    define ('DB_PORT', '5432');
    //define ('DATA_PATH', '/var/www/sol_traveler_ec-site/data/');
    define ('DATA_PATH', '/var/www/sol_traveler_ec/data/');
    define ('MOBILE_HTML_PATH', HTML_PATH . 'mobile/');
//    define ('MOBILE_SITE_URL', SITE_URL_ORIG . 'mobile/');
//    define ('MOBILE_SSL_URL', SSL_URL_ORIG . 'mobile/');
//    define ('MOBILE_URL_DIR', URL_DIR_ORIG . 'mobile/');
?>