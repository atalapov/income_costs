<?php
/**
 * Created by PhpStorm.
 * User: Marcus
 * Date: 11.01.2019
 * Time: 18:59
 */

/**
 *  'SHSYS' Let know the system init
 */
define('SHSYS', true);
/**
 * 'SHPATH' Path app path
 */
define('SHPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
/**
 * 'SHSYSPATH' Path system files
 */
define('SHSYSPATH', SHPATH . 'system' . DIRECTORY_SEPARATOR);
/**
 * Add core file to the app
 */
require_once SHSYSPATH . 'core.php';
global $core;
/**
 * Run core app
 */
$core->run();