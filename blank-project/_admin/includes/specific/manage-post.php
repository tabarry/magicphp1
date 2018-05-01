<?php

if ($table == USERS_TABLE_NAME) {
    //Stop user from self deleting
    suPrintJs("
        if($('#del_icon_" . $_SESSION[SESSION_PREFIX . 'user_id'] . "')){
            $('#del_icon_" . $_SESSION[SESSION_PREFIX . 'user_id'] . "').remove();
        }
        if($('#pretty_check_" . $_SESSION[SESSION_PREFIX . 'user_id'] . "')){
            $('#pretty_check_" . $_SESSION[SESSION_PREFIX . 'user_id'] . "').remove();
        } 
    ");

    //Others users cannot see Super User
    if ($_SESSION[SESSION_PREFIX . 'user_id'] != $getSettings['super_user_id']) {
        suPrintJs("
        if($('#row_" . $getSettings['super_user_id'] . "')){
            $('#row_" . $getSettings['super_user_id'] . "').remove();
        }
        
    ");
    }
}

//Stop user from deleting admin group by hiding the edit,update, duplicate and delete icons
if ($table == 'groups') {
    $sql = "SELECT id FROM groups WHERE " . suJsonExtract('data', 'group_title', FALSE) . "='" . ADMIN_GROUP_NAME . "' AND live='Yes' LIMIT 0,1";
    $result = suQuery($sql);
    $adminGroupId = $result['result'][0][id];
    suPrintJs("
        if($('#edit_icon_" . $adminGroupId . "')){
            $('#edit_icon_" . $adminGroupId . "').remove();
        }
        if($('#preview_icon_" . $adminGroupId . "')){
            $('#preview_icon_" . $adminGroupId . "').remove();
        }
        if($('#duplicate_icon_" . $adminGroupId . "')){
            $('#duplicate_icon_" . $adminGroupId . "').remove();
        }
        if($('#del_icon_" . $adminGroupId . "')){
            $('#del_icon_" . $adminGroupId . "').remove();
        }
        if($('#pretty_check_" . $adminGroupId . "')){
            $('#pretty_check_" . $adminGroupId . "').remove();
        }         
    ");
}


