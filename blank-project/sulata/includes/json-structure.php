<?php

$dbs_sulata_employees_flex = array(
    'employee__Email_req' => '*',
    'employee__Email_title' => 'Email',
    'employee__Email_max' => '64',
    'employee__Email_validateas' => 'email',
    'employee__Email_html5_req' => 'required',
    'employee__Email_html5_type' => 'email',
    'employee__Password_req' => '*',
    'employee__Password_title' => 'Password',
    'employee__Password_max' => '64',
    'employee__Password_validateas' => 'password',
    'employee__Password_html5_req' => 'required',
    'employee__Password_html5_type' => 'password',
);

$dbs_sulata_employees = array_merge($dbs_sulata_employees_flex, $dbs_sulata_employees);
