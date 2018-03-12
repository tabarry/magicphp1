<?php

/* MISC DEFINITIONS */
define('MAGIC_TITLE', 'MAGIC PHP');
define('MAGIC_VERSION', '1.0');
define('MAGIC_DEVELOPER', 'Sulata iSoft');
define('MAGIC_DEVELOPER_URL', 'http://www.sulata.com.pk/');
define('MAGIC_THEME', 'crimson');

/* RESERVED TABLE SETTINGS */
$reservedTables = array('_settings', '_structure', '_logs', 'users', 'groups');
define('STRUCTURE_TABLE_NAME', '_structure');
define('SETTINGS_TABLE_NAME', '_settings');
define('LOG_TABLE_NAME', '_logs');
define('USERS_TABLE_NAME', 'users');
/* ADMIN USER NAME */
define('ADMIN_GROUP_NAME', 'Admin');


define('RESERVED_TABLE_PREFEX', 'del_'); //4 characters only
define('RESERVED_TABLE_MESSAGE', 'The Title `%s` is reserved for internal use only.');
define('RESERVED_PREVIOUS_PREFEX', '_____previous_'); //For previous files
define('CONFIRM_PASSWORD_POSTFIX', '_____confirm_'); //For password confirm
define('INLINE_EDIT_HIDDEN_FIELD_PREFIX', '_____hidden_'); //Inline edit hidden field
define('INLINE_EDIT_HIDDEN_SPAN_PREFIX', '_____span_'); //Inline edit hidden span
define('DOUBLECLICK_TO_EDIT', 'Double click to edit.'); //Inline edit hint


define('DEFAULT_LOGIN_EMAIL', 'builder@sulata.com.pk');
define('DEFAULT_LOGIN_PASSWORD', 'builder@000');

/* * */
define('DEFAULT_CSS_CLASS', 'form-control');
/* ARRAYS */
$labelAddArray = array('Yes' => 'Use label as field title on add page.', 'No' => 'Use placeholder as field title on add page.');
$labelUpdateArray = array('Yes' => 'Use label as field title on update page.', 'No' => 'Use placeholder as field title on update page.');
$displayFormArray = array('Yes' => 'Display form on admin side', 'No' => 'Hide form on admin side');
$saveForLaterArray = array('Yes' => 'Enable `Save for Later` Option', 'No' => 'Disable `Save for Later` Option');



