<?php

//Hide fields not required to be updated in profile
if ($table == USERS_TABLE_NAME && suSegment(3) == 'profile') {
    suPrintJs("
        if($('#status')){
            $('#status').parent().remove();
        }
        if($('#user_group')){
            $('#user_group').parent().remove();
        }
              
    ");
}

//Build the group permissions
if ($table == 'groups') {
    if (file_exists('includes/specific/permissions-matrix.php')) {
        include('includes/specific/permissions-matrix.php');
    }
}