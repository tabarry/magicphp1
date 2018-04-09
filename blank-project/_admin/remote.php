<?php

include('../sulata/includes/config.php');
include('../sulata/includes/language.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
//The remote page cannot be opened directly in browser
if ($_GET['do'] != 'autocomplete') {
    suFrameBuster();
}


//Check admin login.
//If user is not logged in, send to login page.
checkAdminLogin();
$sessionUserId = $_SESSION[SESSION_PREFIX . 'user_id'];

$do = suSegment(1);
$table = suSegment(2);
//Any actions desired at this point should be coded in this file
if (file_exists('includes/specific/remote-top.php')) {
    include('includes/specific/remote-top.php');
}

if ($_GET['do'] != 'autocomplete') {
    if (!isset($do) || !isset($table)) {
        suExit(INVALID_RECORD);
    }
}

//Add record
if ($do == 'add') {

    //Check referrer
    suCheckRef();
    //Handle `Save for Later` button
    $saveForLater = FALSE;
    if (isset($_POST['save_for_later_use']) && $_POST['save_for_later_use'] == 'Yes') {
        $saveForLater = TRUE;
        $_POST['save_for_later_use'] = 'Yes';
    } else {
        $_POST['save_for_later_use'] = 'No';
    }

    //Any actions desired at this point should be coded in this file
    if (file_exists('includes/specific/remote-pre-add.php')) {
        include('includes/specific/remote-pre-add.php');
    }

//Check group permission - this include must be after $table variable is built
    include('../sulata/includes/check-group-permissions.php');
//Stop unauthorised add access
    if ($_SESSION[SESSION_PREFIX . 'user_group'] != ADMIN_GROUP_NAME) {
        //Check IP restriction
        suCheckIpAccess();
        //Stop unauthorised access
        if (!in_array($table, $addables)) {
            suExit(INVALID_ACCESS);
        }
    }


//Check unique
    if ($saveForLater == FALSE) {
        suCheckUnique($table);
    }
//Check composite unique
    if ($saveForLater == FALSE) {
        suCheckCompositeUnique($table);
    }
//Declare validate array
    if (!isset($vError)) {
        $vError = array();
    }
//Get form fields built
    $sql = "SELECT title, redirect_after_add, structure, extrasql_on_add FROM " . STRUCTURE_TABLE_NAME . " WHERE live='Yes' AND slug='" . $table . "' LIMIT 0,1";
    $result = suQuery($sql);
    $numRows = $result['num_rows'];
    if ($numRows == 0) {
        suExit(INVALID_RECORD);
    }
    $row = $result['result'][0];
    $title = suUnstrip($row['title']);
    $redirectAfterAdd = suUnstrip($row['redirect_after_add']);
    //Prepare extra sql
    $extrasqlOnAdd = html_entity_decode(suUnstrip($row['extrasql_on_add']));
    //Eval the string if $ in string
    if (stristr($extrasqlOnAdd, '$')) {
        eval("\$extrasqlOnAdd = \"$extrasqlOnAdd\";");
    }
    $extraData = json_decode("{" . $extrasqlOnAdd . "}", 1);
    //==

    $structure = $row['structure'];
    $structure = json_decode($structure, 1);

    $dates = array(); //Array to hold date fields
    $passwords = array(); //Array to hold password fields
    $attachments = array(); //Array to hold attachment fields
    $pictures = array(); //Array to hold picture fields
    //print_array($structure);

    for ($i = 0; $i <= sizeof($structure) - 1; $i++) {
        //If submitted then build validation
        if (isset($_POST[$structure[$i]['Slug']])) {

            //Check data type as date
            if ($structure[$i]['Type'] == 'date') {
                array_push($dates, $structure[$i]['Slug']);
            }
            //Check data type as password
            if ($structure[$i]['Type'] == 'password') {
                array_push($passwords, $structure[$i]['Slug']);
            }
            //Check data type as attachment field
            if ($structure[$i]['Type'] == 'attachment_field') {
                array_push($attachments, $structure[$i]['Slug']);
            }
            //Check data type as picture field
            if ($structure[$i]['Type'] == 'picture_field') {
                array_push($pictures, $structure[$i]['Slug']);
                $dimensions[$i]['imageWidth'] = $structure[$i]['ImageWidth'];
                $dimensions[$i]['imageHeight'] = $structure[$i]['ImageHeight'];
            }
            //Validate if data type is attachment or picture
            if ($structure[$i]['Type'] == 'attachment_field' || $structure[$i]['Type'] == 'picture_field') {
                if ($structure[$i]['HideOnAdd'] != 'yes') {//If not hide on add
                    if ($saveForLater == TRUE) {
                        if ($structure[$i]['RequiredSaveForLater'] == 'yes') {
                            suValidateFieldType($_FILES[$structure[$i]['Slug']]['name'], $structure[$i]['Type'], $structure[$i]['Required'], $structure[$i]['Name']);
                        }
                    } else {
                        suValidateFieldType($_FILES[$structure[$i]['Slug']]['name'], $structure[$i]['Type'], $structure[$i]['Required'], $structure[$i]['Name']);
                    }
                }
            } else {
                //Validate if data type is not attachment or picture or hide on add field
                if ($structure[$i]['HideOnAdd'] != 'yes') {
                    if ($saveForLater == TRUE) {
                        if ($structure[$i]['RequiredSaveForLater'] == 'yes') {
                            suValidateFieldType($_POST[$structure[$i]['Slug']], $structure[$i]['Type'], $structure[$i]['Required'], $structure[$i]['Name']);
                        }
                    } else {
                        suValidateFieldType($_POST[$structure[$i]['Slug']], $structure[$i]['Type'], $structure[$i]['Required'], $structure[$i]['Name']);
                    }
                }
            }
        }
    }
    //Validate submitted fields error
    //if ($saveForLater == FALSE) {
    suValdationErrors($vError);
    //}
    //Prepare data
    $data = array();
    $uid = uniqid(); //For uploads
    //$_POST values
    foreach ($_POST as $key => $value) {
        $f = '';
        $uploadPath = '';
        if (in_array($key, $dates)) {//If date, change to DB date
            $data[$key] = suDate2Db($value);
        } elseif (in_array($key, $passwords)) {//If password, encrypt it
            $data[$key] = suCrypt(suStrip($value));
        } else {
            if (is_array($value)) {
                $data[$key] = $value;
            } else {
                if (($key != 'id') && substr($key, (-1 * (strlen(CONFIRM_PASSWORD_POSTFIX)))) != CONFIRM_PASSWORD_POSTFIX) {//If it is not confirm password field or id
                    $data[$key] = suStrip($value);
                }
            }
        }
    }
    //$_FILES values
    foreach ($_FILES as $key => $value) {
        if ($_FILES[$key]['name'] != '') {
            $fileName = $value['name'];
            $fileName = suSlugify($fileName, $uid);
            $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
            $uploadPath = $uploadPath . $fileName;
            $data[$key] = $uploadPath;
        }
    }
    //Merge data and extra data
    if ($extraData != '') {
        $data = array_merge($data, $extraData);
    }
    $data = json_encode($data);

    //Prepare insert statement
    $sql = "INSERT INTO " . $table . " SET data='" . $data . "', live='Yes'";

    $result = suQuery($sql);
    if ($result['errno'] > 0) {//If error exisits
        if ($result['errno'] == 1062) {//If duplication error
            $error = sprintf(DUPLICATION_ERROR, 'Title or Slug');
        } else {
            $error = MYSQL_ERROR;
        }
        suPrintJs('
            parent.suToggleButton(0);
            parent.$("#save_for_later_use").val("No");
            parent.$("#message-area").hide();
            parent.$("#error-area").show();
            parent.$("#error-area").html("<ul><li>' . $error . '</li></ul>");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
        ');
    } else {
        $maxId = $result['insert_id'];
        //Insert usage log
        suMakeUsageLog('add', $table, $maxId);

        //Upload attachments
        if (sizeof($attachments) > 0) {
            foreach ($attachments as $value) {
                $fileName = $_FILES[$value]['name'];
                $fileName = suSlugify($fileName, $uid);
                $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
                $uploadPath = $uploadPath . $fileName;
                copy($_FILES[$value]['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath);
            }
        }
        //Upload pictures
        if (sizeof($pictures) > 0) {
            foreach ($pictures as $value) {
                $fileName = $_FILES[$value]['name'];
                $fileName = suSlugify($fileName, $uid);
                $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
                $uploadPath = $uploadPath . $fileName;
                //Get dimension
                foreach ($structure as $value2) {
                    if ($value2['Slug'] == $value) {
                        $imageWidth = $value2['ImageWidth'];
                        $imageHeight = $value2['ImageHeight'];
                    }
                }
                //Upload picture
                suResize($imageWidth, $imageHeight, $_FILES[$value]['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath);
            }
        }
        //If redirect is set
        if ($_POST['redirect'] != '') {
            $doJs = 'parent.window.location.href="' . $_POST['redirect'] . '";';
        } else { //If redirect is not set, reset form
            if ($redirectAfterAdd == 'No') {
                $doJs = 'parent.suForm.reset();';
            } else {
                $doJs = "parent.window.location.href='" . ADMIN_URL . "manage" . PHP_EXTENSION . "/" . $table . "/';";
            }
            if ($_POST['reloadField'] != '') {
                $doJs = 'parent.suForm.reset();';
                $f = $_POST['sourceField'];
                $doJs .= 'parent.parent.' . $_POST['reloadField'] . '.options.add(new Option("' . $_POST[$f] . '", "' . $_POST[$f] . '"), parent.parent.' . $_POST['reloadField'] . '.options[2]);';
                $doJs .= 'parent.parent.sortSelect("' . $_POST['reloadField'] . '", "' . $_POST[$f] . '", "+")';
            }
        }

        //Any actions desired at this point should be coded in this file
        if (file_exists('includes/specific/remote-post-add.php')) {
            include('includes/specific/remote-post-add.php');
        }

        suPrintJs('
            parent.suToggleButton(0);
            parent.$("#save_for_later_use").val("No");
            parent.$("#error-area").hide();
            parent.$("#message-area").show();
            parent.$("#message-area").html("' . SUCCESS_MESSAGE . '");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
            ' . $doJs . '
        ');
    }
    exit;
}
//Update record
if ($do == 'update') {


    //Check referrer
    suCheckRef();

    //Handle `Save for Later` button
    $saveForLater = FALSE;
    if (isset($_POST['save_for_later_use']) && $_POST['save_for_later_use'] == 'Yes') {
        $saveForLater = TRUE;
        $_POST['save_for_later_use'] = 'Yes';
    } else {
        $_POST['save_for_later_use'] = 'No';
    }


    //Check group permission - this include must be after $table variable is built
    include('../sulata/includes/check-group-permissions.php');

    //Stop unauthorised update access
    if ($_SESSION[SESSION_PREFIX . 'user_group'] != ADMIN_GROUP_NAME && $_POST['_____profile'] != 'profile') {
        //Check IP restriction
        suCheckIpAccess();
        //Stop unauthorised access
        if (!in_array($table, $updateables)) {
            suExit(INVALID_ACCESS);
        }
    }

    //Any actions desired at this point should be coded in this file
    if (file_exists('includes/specific/remote-pre-update.php')) {
        include('includes/specific/remote-pre-update.php');
    }

    //Check unique
    suCheckUnique($table, suDecrypt($_POST['id']));
    //Check composite unique
    suCheckCompositeUnique($table, suDecrypt($_POST['id']));

    //Declare validate array
    if (!isset($vError)) {
        $vError = array();
    }
    //Get form fields built
    $sql = "SELECT title, structure,extrasql_on_update FROM " . STRUCTURE_TABLE_NAME . " WHERE live='Yes' AND slug='" . $table . "' LIMIT 0,1";
    $result = suQuery($sql);
    $numRows = $result['num_rows'];
    if ($numRows == 0) {
        suExit(INVALID_RECORD);
    }
    $row = $result['result'][0];
    $title = suUnstrip($row['title']);

    //Prepare extra sql
    $extrasqlOnUpdate = html_entity_decode(suUnstrip($row['extrasql_on_update']));
    //Eval the string if $ in string
    if (stristr($extrasqlOnUpdate, '$')) {
        eval("\$extrasqlOnUpdate = \"$extrasqlOnUpdate\";");
    }
    //==
    $structure = $row['structure'];
    $structure = json_decode($structure, 1);

    $dates = array(); //Array to hold date fields
    $passwords = array(); //Array to hold password fields
    $attachments = array(); //Array to hold attachment fields
    $pictures = array(); //Array to hold picture fields


    for ($i = 0; $i <= sizeof($structure) - 1; $i++) {
        //If submitted then build validation
        if (isset($_POST[$structure[$i]['Slug']])) {
            //Check data type as date
            if ($structure[$i]['Type'] == 'date') {
                array_push($dates, $structure[$i]['Slug']);
            }
            //Check data type as password
            if ($structure[$i]['Type'] == 'password') {
                array_push($passwords, $structure[$i]['Slug']);
            }
            //Check data type as attachment field
            if ($structure[$i]['Type'] == 'attachment_field') {
                array_push($attachments, $structure[$i]['Slug']);
            }
            //Check data type as picture field field
            if ($structure[$i]['Type'] == 'picture_field') {
                array_push($pictures, $structure[$i]['Slug']);
                $dimensions[$i]['imageWidth'] = $structure[$i]['ImageWidth'];
                $dimensions[$i]['imageHeight'] = $structure[$i]['ImageHeight'];
            }
            //Validate if data type is attachment or picture
            if ($structure[$i]['Type'] == 'attachment_field' || $structure[$i]['Type'] == 'picture_field') {
                //Make it optional on update
                $structure[$i]['Required'] = 'no';
                if ($structure[$i]['HideOnUpdate'] != 'yes') {//If not fide on update
                    if ($saveForLater == TRUE) {
                        if ($structure[$i]['RequiredSaveForLater'] == 'yes') {
                            suValidateFieldType($_FILES[$structure[$i]['Slug']]['name'], $structure[$i]['Type'], $structure[$i]['Required'], $structure[$i]['Name']);
                        }
                    } else {
                        suValidateFieldType($_FILES[$structure[$i]['Slug']]['name'], $structure[$i]['Type'], $structure[$i]['Required'], $structure[$i]['Name']);
                    }
                }
            } else {
                //Validate if data type is not attachment or picture or hide on update
                if ($structure[$i]['HideOnUpdate'] != 'yes') {
                    if ($saveForLater == TRUE) {
                        if ($structure[$i]['RequiredSaveForLater'] == 'yes') {
                            suValidateFieldType($_POST[$structure[$i]['Slug']], $structure[$i]['Type'], $structure[$i]['Required'], $structure[$i]['Name']);
                        }
                    } else {
                        suValidateFieldType($_POST[$structure[$i]['Slug']], $structure[$i]['Type'], $structure[$i]['Required'], $structure[$i]['Name']);
                    }
                }
            }
        }
    }

    //Error
    if ($saveForLater == FALSE) {
        suValdationErrors($vError);
    }
    //Prepare data
    $data = array();
    $uid = uniqid(); //For uploads
    //$_POST values
    foreach ($_POST as $key => $value) {
        $f = '';
        $uploadPath = '';
        if (in_array($key, $dates)) {//If date, change to DB date
            $data[$key] = suDate2Db($value);
        } elseif (in_array($key, $passwords)) {//If password, encrypt it
            $data[$key] = suCrypt(suStrip($value));
        } else {
            if (substr($key, 0, strlen(RESERVED_PREVIOUS_PREFEX)) == RESERVED_PREVIOUS_PREFEX) {
                $key = substr($key, strlen(RESERVED_PREVIOUS_PREFEX), strlen($key));
                $data[$key] = suStrip($value);
            } else {
                if (is_array($value)) {
                    $data[$key] = $value;
                } else {
                    if (($key != 'id') && substr($key, (-1 * (strlen(CONFIRM_PASSWORD_POSTFIX)))) != CONFIRM_PASSWORD_POSTFIX) {//If it is not confirm password field or id
                        $data[$key] = suStrip($value);
                    }
                }
            }
        }
    }

    //$_FILES values
    foreach ($_FILES as $key => $value) {

        $fileName = $value['name'];
        $fileName = suSlugify($fileName, $uid);
        $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
        $uploadPath = $uploadPath . $fileName;
        //$data[$key] = $uploadPath;

        if ($value['name'] != '') {
            $data[$key] = $uploadPath;
        }
    }

    $data = json_encode($data);

    //Prepare update statment
    $sql = "UPDATE " . $table . " SET data='" . $data . "' WHERE id='" . suDecrypt($_POST['id']) . "' {$extrasqlOnUpdate}";
    $result = suQuery($sql);

    if ($result['errno'] > 0) {
        if ($result['errno'] == 1062) {
            $error = sprintf(DUPLICATION_ERROR, 'Title or Slug');
        } else {
            $error = MYSQL_ERROR;
        }
        suPrintJs('
            parent.suToggleButton(0);
            parent.$("#save_for_later_use").val("No");
            parent.$("#message-area").hide();
            parent.$("#error-area").show();
            parent.$("#error-area").html("<ul><li>' . $error . '</li></ul>");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
        ');
    } else {
        $maxId = suDecrypt($_POST['id']);
        //Insert usage log
        suMakeUsageLog('update', $table, $maxId);
        //Upload attachments
        if (sizeof($attachments) > 0) {
            foreach ($attachments as $value) {

                $fileName = $_FILES[$value]['name'];
                $fileName = suSlugify($fileName, $uid);
                $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
                $uploadPath = $uploadPath . $fileName;
                //Upload attachment if different from previous
                if ($_FILES[$value]['name'] != '') {
                    @unlink(ADMIN_UPLOAD_PATH . $_POST[RESERVED_PREVIOUS_PREFEX . $value]);
                    copy($_FILES[$value]['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath);
                }
            }
        }
        //Upload pictures
        if (sizeof($pictures) > 0) {
            foreach ($pictures as $value) {
                $fileName = $_FILES[$value]['name'];
                $fileName = suSlugify($fileName, $uid);
                $uploadPath = suMakeUploadPath(ADMIN_UPLOAD_PATH);
                $uploadPath = $uploadPath . $fileName;
                //$data[$key] = $uploadPath;
                //Get dimension
                foreach ($structure as $value2) {
                    if ($value2['Slug'] == $value) {
                        $imageWidth = $value2['ImageWidth'];
                        $imageHeight = $value2['ImageHeight'];
                    }
                }
                //Upload picture if different from previous
                if ($_FILES[$value]['name'] != '') {
                    @unlink(ADMIN_UPLOAD_PATH . $_POST[RESERVED_PREVIOUS_PREFEX . $value]);
                    suResize($imageWidth, $imageHeight, $_FILES[$value]['tmp_name'], ADMIN_UPLOAD_PATH . $uploadPath);
                }
            }
        }

        //Any actions desired at this point should be coded in this file
        if (file_exists('includes/specific/remote-post-update.php')) {
            include('includes/specific/remote-post-update.php');
        }

        $doJs = 'parent.window.location.href="' . $_POST['redirect'] . '";';

        suPrintJs('
            parent.suToggleButton(0);
            parent.$("#save_for_later_use").val("No");
            parent.$("#error-area").hide();
            parent.$("#message-area").show();
            parent.$("#message-area").html("' . SUCCESS_MESSAGE . '");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
            ' . $doJs . '
        ');
    }

    exit;
}
//Update single record
if ($do == 'update-single') {

    //Check referrer
    //suCheckRef();
    //Any actions desired at this point should be coded in this file
    if (file_exists('includes/specific/remote-pre-update-single.php')) {
        include('includes/specific/remote-pre-update-single.php');
    }
    //print_array($_REQUEST);
    $table = suSegment(2);
    $id = suSegment(3);
    $field = suSegment(4);
    //Check unique
    //suCheckUnique($table, suDecrypt($id));
    //Check composite unique
    //suCheckCompositeUnique($table, suDecrypt($id));
//Check group permission - this include must be after $table variable is built
    include('../sulata/includes/check-group-permissions.php');
//Stop unauthorised update access
    if ($_SESSION[SESSION_PREFIX . 'user_group'] != ADMIN_GROUP_NAME) {
        //Check IP restriction
        suCheckIpAccess();
        //Stop unauthorised access
        if (!in_array($table, $updateables)) {
            suExit(INVALID_ACCESS);
        }
    }

    //Get form fields built
    $sql = "SELECT extrasql_on_single_update FROM " . STRUCTURE_TABLE_NAME . " WHERE live='Yes' AND slug='" . $table . "' LIMIT 0,1";
    $result = suQuery($sql);
    $numRows = $result['num_rows'];
    if ($numRows == 0) {
        suExit(INVALID_RECORD);
    }
    $row = $result['result'][0];
    //Prepare extra SQL
    $extrasqlOnSingleUpdate = html_entity_decode(suUnstrip($row['extrasql_on_single_update']));
    //Eval the string if $ in string
    if (stristr($extrasqlOnSingleUpdate, '$')) {
        eval("\$extrasqlOnSingleUpdate = \"$extrasqlOnSingleUpdate\";");
    }
    //==

    $value = $_GET['v'];

    if ($value == '') {
        suPrintJs("parent.$('#_____span_" . $field . "_" . $id . "').html(parent._____v);parent.$('#_____hidden_" . $field . "_" . $id . "').val(parent._____v);");
        exit();
    }
    $_REQUEST[$field] = $_GET['v']; //Required to be used in unique check functions
    //Check unique
    $uError = suCheckUnique($table, $id, TRUE);
    if ($uError != '') {//If error, revert the changes
        suPrintJs("alert('" . $uError . "');parent.$('#_____span_" . $field . "_" . $id . "').html(parent._____v);parent.$('#_____hidden_" . $field . "_" . $id . "').val(parent._____v);window.location.href='" . PING_URL . "'");
        exit();
    }
    //Check composite unique
    $uError = suCheckCompositeUnique($table, $id, TRUE);
    if ($uError != '') {//If error, revert the changes
        suPrintJs("alert('" . $uError . "');parent.$('#_____span_" . $field . "_" . $id . "').html(parent._____v);parent.$('#_____hidden_" . $field . "_" . $id . "').val(parent._____v);window.location.href='" . PING_URL . "'");
        exit();
    }



    $sql = "UPDATE $table SET data= JSON_REPLACE(data,'$." . $field . "','" . urlencode($value) . "') WHERE id='$id' {$extrasqlOnSingleUpdate}";

    suQuery($sql);
    //Insert usage log
    suMakeUsageLog('update-single', $table, $id);
    //Any actions desired at this point should be coded in this file
    if (file_exists('includes/specific/remote-post-update-single.php')) {
        include('includes/specific/remote-post-update-single.php');
    }

    exit;
}
//Delete record
if ($do == "delete") {
    //Check referrer
    suCheckRef();

    $id = suSegment(2);
    $table = suSegment(3);

    //Check group permission - this include must be after $table variable is built
    include('../sulata/includes/check-group-permissions.php');
//    //Stop unauthorised delete access
    if ($_SESSION[SESSION_PREFIX . 'user_group'] != ADMIN_GROUP_NAME) {
        //Check IP restriction
        suCheckIpAccess();
        //Stop unauthorised access
        if (!in_array($table, $deleteables)) {
            suExit(INVALID_ACCESS);
        }
    }
    //Any actions desired at this point should be coded in this file
    if (file_exists('includes/specific/remote-pre-delete.php')) {
        include('includes/specific/remote-pre-delete.php');
    }

    //Get form fields built
    $sql = "SELECT extrasql_on_delete FROM " . STRUCTURE_TABLE_NAME . " WHERE live='Yes' AND slug='" . $table . "' LIMIT 0,1";
    $result = suQuery($sql);
    $numRows = $result['num_rows'];
    if ($numRows == 0) {
        suExit(INVALID_RECORD);
    }
    $row = $result['result'][0];
    //Prepare extra SQL
    $extrasqlOnDelete = html_entity_decode(suUnstrip($row['extrasql_on_delete']));
    //Eval the string if $ in string
    if (stristr($extrasqlOnDelete, '$')) {
        eval("\$extrasqlOnDelete = \"$extrasqlOnDelete\";");
    }
    //==
    //
//Update the table
    $sql = "UPDATE " . $table . " SET live='No' WHERE id = '" . $id . "' {$extrasqlOnDelete} ";
    $result = suQuery($sql);
    //Insert usage log
    suMakeUsageLog('delete', $table, $id);
    //Any actions desired at this point should be coded in this file
    if (file_exists('includes/specific/remote-post-delete.php')) {
        include('includes/specific/remote-post-delete.php');
    }
}

//Restore record
if ($do == "restore") {
//Check referrer
    suCheckRef();


    $id = suSegment(2);
    $table = suSegment(3);

    //Any actions desired at this point should be coded in this file
    if (file_exists('includes/specific/remote-pre-restore.php')) {
        include('includes/specific/remote-pre-restore.php');
    }

    //Get form fields built
    $sql = "SELECT extrasql_on_restore FROM " . STRUCTURE_TABLE_NAME . " WHERE live='Yes' AND slug='" . $table . "' LIMIT 0,1";
    $result = suQuery($sql);
    $numRows = $result['num_rows'];
    if ($numRows == 0) {
        suExit(INVALID_RECORD);
    }
    $row = $result['result'][0];
    //Prepare extra SQL
    $extrasqlOnRestore = html_entity_decode(suUnstrip($row['extrasql_on_restore']));
    //Eval the string if $ in string
    if (stristr($extrasqlOnRestore, '$')) {
        eval("\$extrasqlOnRestore = \"$extrasqlOnRestore\";");
    }
    //==
    //Fetch record
    $sql = "SELECT data FROM " . $table . " WHERE id='" . $id . "' AND live='No' LIMIT 0,1";
    $result = suQuery($sql);
    $numRows = $result['num_rows'];
    if ($numRows == 0) {
        suExit(INVALID_RECORD);
    } else {
        $row = $result['result'][0]['data'];
    }
    $row = json_decode($row, 1);
    foreach ($row as $key => $value) {
        $_REQUEST[$key] = $value;
    }

    //Check unique
    $response = suCheckUnique($table, 0, TRUE, TRUE);
    if ($response != '') {
        suPrintJs('
            parent.suToggleButton(0);
            parent.$("#save_for_later_use").val("No");
            parent.$("#message-area").hide();
            parent.$("#error-area").show();
            parent.$("#error-area").html("<ul><li>' . $response . '</li></ul>");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
        ');
        exit;
    }
//Check composite unique
    $response = suCheckUnique($table, 0, TRUE, TRUE);
    if ($response != '') {
        suPrintJs('
            parent.suToggleButton(0);
            parent.$("#save_for_later_use").val("No");
            parent.$("#message-area").hide();
            parent.$("#error-area").show();
            parent.$("#error-area").html("<ul><li>' . $response . '</li></ul>");
            parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
        ');
        exit;
    }


    //
    //
//Update the table
    $sql = "UPDATE " . $table . " SET live='Yes' WHERE id = '" . $id . "' {$extrasqlOnRestore}";
    $result = suQuery($sql);
    //Insert usage log
    suMakeUsageLog('restore', $table, $id);
    //Any actions desired at this point should be coded in this file
    if (file_exists('includes/specific/remote-post-restore.php')) {
        include('includes/specific/remote-post-restore.php');
    }

    suPrintJs('
                parent.restoreById("' . $id . '");
                parent.$("#error-area").hide();
                //parent.$("#message-area").show();
                //parent.$("#message-area").html("' . RECORD_RESTORED . '");
                //parent.$("html, body").animate({ scrollTop: parent.$("html").offset().top }, "slow");
            ');
}
//if autocomplete
if ($_GET['do'] == 'autocomplete') {

    //If not ajax request, exit
    if (!$_SERVER['HTTP_X_REQUESTED_WITH']) {
        if (DEBUG == FALSE) {
            suExit(INVALID_ACCESS);
        }
    }

    $arr['Source'] = $_GET['source'];

//Get data from table
    $tableField = explode('.', $arr['Source']);
    $table = $tableField[0];
    $field = $tableField[1];
    $field = suSlugifyStr($field, '_');
    //$extraSql = html_entity_decode(suUnstrip($arr['ExtraSQL']));
    $extraSQL = suDecrypt($_GET['extra']);

    $sql = "SELECT " . suJsonExtract('data', $field, FALSE) . " AS f1, " . suJsonExtract('data', $field, FALSE) . " AS f2 FROM  " . $table . " WHERE lcase(" . suJsonExtract('data', $field, FALSE) . ") LIKE lcase('%" . suUnstrip($_REQUEST['term']) . "%') AND live='Yes'  " . $extraSQL . " GROUP BY " . suJsonExtract('data', $field, FALSE) . "ORDER BY " . suJsonExtract('data', $field, FALSE);

    //To overwrite above query, use the file below
    if (file_exists('includes/specific/overwrite-autocomplete-query.php')) {
        include('includes/specific/overwrite-autocomplete-query.php');
    }

    $result = suQuery($sql);

    $data = array();
    if ($result && $result['num_rows']) {
        foreach ($result['result'] as $row) {
            $data[] = array(
                'label' => urldecode($row['f1']),
                'value' => urldecode($row['f2'])
            );
        }
    }

    echo json_encode($data);
    flush();
}
//Preview
if ($do == "preview-print") {
    echo "<html>"
    . "<head>"
    . "<title>"
    . $getSettings['site_name']
    . "</title>"
    . "<style>"
    . "body{font-family:arial;font-size:13px;}"
    . "td{font-family:arial;font-size:13px;padding:4px;}"
    . ".imgThumb{width:70px;height: 70px;display: block;background-repeat: no-repeat;background-position: center !important;background-size: cover !important;border: 5px solid #CCC;border-radius: 5px;margin-bottom: 10px;}"
    . "</style>"
    . "</head>"
    . "<body>"
    . "<div id='printable-div'></div>"
    . "</body>"
    . "</html>";
    $str = "document.getElementById('printable-div').innerHTML=parent.document.getElementById('printable-div').innerHTML;document.getElementById('printable-table').style.width='600px';window.print();window.location.href='" . PING_URL . "'";
    suPrintJS($str);
}

