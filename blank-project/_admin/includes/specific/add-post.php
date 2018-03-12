<?php

//Build the group permissions
if ($table == 'groups') {
    if (file_exists('includes/specific/permissions-matrix.php')) {
        include('includes/specific/permissions-matrix.php');
    }
}
//Generate auto password on add user
if ($table == 'users') {
    if (file_exists('includes/specific/generate-user-password.php')) {
        include('includes/specific/generate-user-password.php');
    }
}