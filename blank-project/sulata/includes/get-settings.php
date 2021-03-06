<?php

/* DO NOT EDIT THIS PAGE */
//Get settings
if ($_SESSION[SESSION_PREFIX . 'getSettings'] == '') {

    $sql = "SELECT id, TRIM(BOTH '\"' FROM json_extract(data,'$.setting_title') ) AS setting_title , TRIM(BOTH '\"' FROM json_extract(data,'$.setting_key') ) AS setting_key , TRIM(BOTH '\"' FROM json_extract(data,'$.setting_value') ) AS setting_value FROM _settings WHERE live='Yes' ";
    $result = suQuery($sql);

    if ($result['connect_errno'] == 0 && $result['errno'] == 0) {
        foreach ($result['result'] as $row) {
            $_SESSION[SESSION_PREFIX . 'getSettings'][suUnstrip($row['setting_key'])] = suUnstrip($row['setting_value']);
        }
    }
}

$getSettings = array();
$getSettings = $_SESSION[SESSION_PREFIX . 'getSettings'];

//Explode the values to make array
$getSettings['allowed_file_formats'] = explode(',', $getSettings['allowed_file_formats']);
$getSettings['allowed_picture_formats'] = explode(',', $getSettings['allowed_picture_formats']);

//Define date format
define('DATE_FORMAT', $getSettings['date_format']);
if (DATE_FORMAT == 'mm-dd-yy') {
    $today = date("m-d-Y");
} else {
    $today = date("d-m-Y");
}    

