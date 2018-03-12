<?php

//Check if at least one group is entered
if ($table == 'groups') {   
    if (file_exists('includes/specific/group-required-check.php')) {
        include('includes/specific/group-required-check.php');
    }
}
