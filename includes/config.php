<?php

ini_set('display_errors', 1);
//VERSION INFO
define('FRAMEWORK_NAME', 'Magic PHP');
define('FRAMEWORK_VERSION', '1.0');
define('RELEASE_DATE', 'February 24, 2018');
//Debug
define('DUBUG', FALSE);

//Variables
$title = FRAMEWORK_NAME . ' ' . FRAMEWORK_VERSION;
$minMysqlVersion = '5.7';
$minMariaDbVersion = '10';
$cookieExpiry = time() + (30 * 86400);
if (DUBUG == TRUE) {
    $frame = 'frame-show';
} else {
    $frame = 'frame-hide';
}

//Logins
define('SUPER_USER', 'Superman');
define('_MAGIC_LOGIN', 'magic@sulata.com.pk');
define('_MAGIC_PASSWORD', 'magic');
define('_ADMIN_LOGIN', 'superman@sulata.com.pk');
define('_ADMIN_PASSWORD', 'krypton');

//Messages
define('FOLDER_ALREADY_EXISTS', 'A folder with the name `%s` already exists.');
define('DATABASE_ALREADY_EXISTS', 'A database with the name `%s` already exists.');
define('DB_VERSION_ERROR', 'Minimum version requirement for MySQL is `%s` or MariaDB is `%s`.');
define('SUCCESS_MESSAGE', 'Project `<span id="project-name"></span>` created successfully.<br>Go to project\'s <a id="magic-url" href="">`_magic`</a> folder or <a id="admin-url" href="">`_admin`</a> folder.<br>_magic Login:`'._MAGIC_LOGIN.'` Password: `'._MAGIC_PASSWORD.'`.<br>_admin Login: `'._ADMIN_LOGIN.'` Password: `'._ADMIN_PASSWORD.'`.');
