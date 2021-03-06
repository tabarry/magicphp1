<?php
include('../sulata/includes/config.php');
include('../sulata/includes/language.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');

//Check admin login.
//If user is not logged in, send to login page.
checkAdminLogin();
$sessionUserId = $_SESSION[SESSION_PREFIX . 'user_id'];

$mode = 'update';
$slug = suSegment(1);
$rid = suSegment(2);
$s3 = suSegment(3);

if ($s3 == 'duplicate') {
    $duplicate = TRUE;
} else {
    $duplicate = FALSE;
}

//For profile update handling
if ($s3 == 'profile') {
    $profile = TRUE;
} else {
    $profile = FALSE;
}

if (!isset($slug) || $slug == '') {
    suExit(INVALID_RECORD);
}


//Get title
$sql = "SELECT id,title,slug,label_add,label_update,display,save_for_later,structure FROM " . STRUCTURE_TABLE_NAME . " WHERE live='Yes' AND slug='" . $slug . "' LIMIT 0,1";

$result = suQuery($sql);

$numRows = $result['num_rows'];
if ($numRows == 0) {
    suExit(INVALID_RECORD);
}
$row = $result['result'][0];
$id = suUnstrip($row['id']);
$title = suUnstrip($row['title']);
$table = suUnstrip($row['slug']);
$label_add = suUnstrip($row['label_add']);
$label_update = suUnstrip($row['label_update']);
$display = suUnstrip($row['display']);
$saveForLater = $row['save_for_later'];

//Check group permission - this include must be after $table variable is built
include('../sulata/includes/check-group-permissions.php');
//Stop unauthorised update access
if ($_SESSION[SESSION_PREFIX . 'user_group'] != ADMIN_GROUP_NAME && suSegment(3) != 'profile') {
    //Check IP restriction
    suCheckIpAccess();
    //Stop unauthorised access
    if (!in_array($table, $updateables)) {
        suExit(INVALID_ACCESS);
    }
}
//Any actions desired at this point should be coded in this file
if (file_exists('includes/specific/update-top.php')) {
    include('includes/specific/update-top.php');
}


$structure = $row['structure'];
$structure = json_decode($structure, 1);

if ($duplicate == TRUE) {
    $h1 = 'Add ' . $title;
    $action = 'add';
} else {
    $h1 = 'Update ' . $title;
    $action = 'update';
}



//Get data
$sqlData = "SELECT id,data FROM $table WHERE live='Yes' AND id='$rid' LIMIT 0,1";
$resultData = suQuery($sqlData);
$numRowsData = $resultData['num_rows'];
if ($numRowsData == 0) {
    suExit(INVALID_RECORD);
}
$rowData = $resultData['result'][0];
$data_id = $rowData['id'];
$data_data = $rowData['data'];
$data = $data_data;
$data = json_decode($data, 1);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $getSettings['site_name'] . ' - ' . $h1; ?></title>
        <?php include('includes/head.php'); ?>
        <script type="text/javascript">

            $(document).ready(function () {
                //Keep session alive
                $(function () {
                    window.setInterval("suStayAlive('<?php echo PING_URL; ?>')", 300000);
                });
                //Disable submit button
                suToggleButton(1);

            });
        </script> 

    </head>
    <body>

        <p id="loading-area"></p>
        <div class="container-fluid" id="container-area">
            <div class="row">
                <main>
                    <div class="col-sm-10 content-area">
                        <!-- Add new -->
                        <a href="<?php echo ADMIN_URL; ?>manage<?php echo PHP_EXTENSION; ?>/<?php echo $table; ?>/" class="btn btn-circle"><i class="fa fa-table"></i></a>
                        <?php
                        include('includes/header.php');
                        ?>

                        <div id="error-area">
                            <ul></ul>
                        </div>    
                        <div id="message-area">
                            <p></p>
                        </div>
                        <?php
                        //Any actions desired at this point should be coded in this file
                        if (file_exists('includes/specific/update-pre.php')) {
                            include('includes/specific/update-pre.php');
                        }
                        ?>
                        <form name="suForm" id="suForm" method="post" target="remote" action="<?php echo ADMIN_URL; ?>remote<?php echo PHP_EXTENSION; ?>/<?php echo $action; ?>/<?php echo $table; ?>/" enctype="multipart/form-data" data-parsley-validate>
                            <?php
                            //Previous
                            if ($duplicate == TRUE) {
                                $arg = array('type' => 'hidden', 'name' => '_____duplicate', 'id' => '_____duplicate', 'value' => 'duplicate');
                                echo suInput('input', $arg);
                            }
                            //Profile
                            if ($profile == TRUE) {
                                $arg = array('type' => 'hidden', 'name' => '_____profile', 'id' => '_____profile', 'value' => 'profile');
                                echo suInput('input', $arg);
                            }
                            ?>

                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    $uniqueArray = array();

                                    foreach ($structure as $value) {

                                        if (is_array($data[$value['Slug']])) {
                                            $value = array_merge($value, array('_____value' => $data[$value['Slug']]));
                                        } else {
                                            $value = array_merge($value, array('_____value' => suUnstrip($data[$value['Slug']])));
                                        }

                                        //$value = $rowData[];
                                        if ($value['Type'] == 'hidden' || $value['Type'] == 'ip_address') {
                                            suBuildField($value, $mode);
                                        } else {
                                            if ($value['HideOnUpdate'] == 'yes') {
                                                //If hide on update
                                                suBuildField($value, $mode);
                                            } else {
                                                ?>
                                                <div class="col-xs-12 col-sm-12 col-md-<?php echo $value['Width']; ?> col-lg-<?php echo $value['Width']; ?>" id="data_div_<?php echo $value['Slug']; ?>">    

                                                    <?php suBuildField($value, $mode, $label_update); ?>
                                                    <div>&nbsp;</div>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>

                                <?php
//Any actions desired at this point should be coded in this file
                                if (file_exists('includes/specific/update-post.php')) {
                                    include('includes/specific/update-post.php');
                                }
                                ?>
                            </div>

                            <div>&nbsp;</div>

                            <div class="clearfix"></div>
                            <div>&nbsp;</div>

                            <p class="pull-right">
                                <?php
                                //ID in hidden
                                $arg = array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => suCrypt($rid));
                                echo suInput('input', $arg);

                                //Redirect
                                if ($duplicate == TRUE) {
                                    $redirect = ADMIN_URL . "manage" . PHP_EXTENSION . "/$table/";
                                    $arg = array('type' => 'hidden', 'name' => 'redirect', 'id' => 'redirect', 'value' => $redirect);
                                } else {
                                    if (suSegment(3) == 'profile') {
                                        $redirect = ADMIN_URL . "message" . PHP_EXTENSION . "?msg=" . PROFILE_UPDATE;
                                        $arg = array('type' => 'hidden', 'name' => 'redirect', 'id' => 'redirect', 'value' => $redirect);
                                    } else {
                                        $redirect = ADMIN_URL . "manage" . PHP_EXTENSION . "/$table/?" . $_SERVER['QUERY_STRING'];
                                        $arg = array('type' => 'hidden', 'name' => 'redirect', 'id' => 'redirect', 'value' => $redirect);
                                    }
                                }

                                echo suInput('input', $arg);

                                //Hidden to store save for later action
                                $arg = array('type' => 'hidden', 'name' => 'save_for_later_use', 'id' => 'save_for_later_use', 'value' => 'No');
                                echo suInput('input', $arg);

                                //Submit
                                $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'class' => 'btn btn-theme');
                                echo suInput('button', $arg, "<i class='fa fa-check'></i>", TRUE);

                                //If save for later
                                if ($saveForLater == 'Yes') {
                                    if ($data['save_for_later_use'] != 'No' || $duplicate != FALSE) {
                                        echo ' ';
                                        $arg = array('type' => 'submit', 'name' => 'save_for_later', 'id' => 'save_for_later', 'class' => 'btn btn-theme');
                                        echo suInput('button', $arg, "<i class='fa fa-save'></i>", TRUE);
                                    }
                                }
                                ?>   

                            </p>
                            <p>&nbsp;</p>
                        </form>

                        <script>
                            $("#suForm").parsley({"validationThreshold": 0});
                            //On modal window close, reset modal iframe url
                            $("#modal-close-btn").click(function () {
                                window.top.overlayFrame.location.href = "<?php echo PING_URL; ?>";
                            });
                            $("#modal-close-btn2").click(function () {
                                window.top.overlayFrame.location.href = "<?php echo PING_URL; ?>";
                            });
                        </script>
                    </div>
                </main>
                <?php include('includes/sidebar.php'); ?>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
        <?php include('includes/footer-js.php'); ?>
    </body>
</html>
<?php suIframe(); ?>