<?php

//Stop user from self deleting
if ($table == USERS_TABLE_NAME) {
    suPrintJs("
        if($('#del_icon_" . $row['id'] . "')){
            $('#del_icon_" . $row['id'] . "').remove();
        }
        if($('#pretty_check_" . $row['id'] . "')){
            $('#pretty_check_" . $row['id'] . "').remove();
        } 
    ");
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


