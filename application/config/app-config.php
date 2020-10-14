<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
* --------------------------------------------------------------------------
* Base Site URL
* --------------------------------------------------------------------------
*
* URL to your CodeIgniter root. Typically this will be your base URL,
* WITH a trailing slash:
*
*   http://example.com/
*
* If this is not set then CodeIgniter will try guess the protocol, domain
* and path to your installation. However, you should always configure this
* explicitly and never rely on auto-guessing, especially in production
* environments.
*
*/
define('APP_BASE_URL', 'http://localhost');

/*
* --------------------------------------------------------------------------
* Encryption Key
* IMPORTANT: Do not change this ever!
* --------------------------------------------------------------------------
*
* If you use the Encryption class, you must set an encryption key.
* See the user guide for more info.
*
* http://codeigniter.com/user_guide/libraries/encryption.html
*
* Auto added on install
*/
define('APP_ENC_KEY', '7ae56b6cb246deb69478b5362ff84edd');

/**
 * Database Credentials
 * The hostname of your database server
 */
define('APP_DB_HOSTNAME', 'localhost');
/**
 * The username used to connect to the database
 */
define('APP_DB_USERNAME', 'root');
/**
 * The password used to connect to the database
 */
define('APP_DB_PASSWORD', '');
/**
 * The name of the database you want to connect to
 */
define('APP_DB_NAME', 'oskars');

/**
 * @since  2.3.0
 * Database charset
 */
define('APP_DB_CHARSET', 'utf8');
/**
 * @since  2.3.0
 * Database collation
 */
define('APP_DB_COLLATION', 'utf8_general_ci');

/**
 *
 * Session handler driver
 * By default the database driver will be used.
 *
 * For files session use this config:
 * define('SESS_DRIVER', 'files');
 * define('SESS_SAVE_PATH', NULL);
 * In case you are having problem with the SESS_SAVE_PATH consult with your hosting provider to set "session.save_path" value to php.ini
 *
 */
define('SESS_DRIVER', 'database');
define('SESS_SAVE_PATH', 'sessions');

/**
 * Enables CSRF Protection
 */
define('APP_CSRF_PROTECTION', true);