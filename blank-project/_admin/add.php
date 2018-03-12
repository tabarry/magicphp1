<?php
include('../sulata/includes/config.php');
include('../sulata/includes/language.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');

//Check admin login.
//If user is not logged in, send to login page.
checkAdminLogin();
$sessionUserId = $_SESSION[SESSION_PREFIX . 'user_id'];

//Any actions desired at this point should be coded in this file
if (file_exists('includes/specific/add-top.php')) {
    include('includes/specific/add-top.php');
}

$mode = 'add';
$table = suSegment(1);
if (!isset($table) || $table == '') {
    suExit(INVALID_RECORD);
}
//Check group permission - this include must be after $table variable is built
include('../sulata/includes/check-group-permissions.php');
//Stop unauthorised add access
if ($_SESSION[SESSION_PREFIX . 'user_group'] != ADMIN_GROUP_NAME) {
    if (!in_array($table, $addables)) {
        suExit(INVALID_ACCESS);
    }
}

$sql = "SELECT id,title,slug,label_add,label_update,display,save_for_later,structure FROM " . STRUCTURE_TABLE_NAME . " WHERE live='Yes' AND slug='" . $table . "' LIMIT 0,1";
$result = suQuery($sql);
$numRows = $result['num_rows'];
if ($numRows == 0) {
    suExit(INVALID_RECORD);
}
$row = $result['result'][0];
$id = suUnstrip($row['id']);
$title = suUnstrip($row['title']);
$table = suUnstrip($row['slug']);
$label_add = $row['label_add'];
$label_update = $row['label_update'];
$display = $row['display'];
$save_for_later = $row['save_for_later'];

$structure = $row['structure'];
$structure = json_decode($structure, 1);
//Create heading
$h1 = 'Add ' . $title;
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
                        <?php if ($_GET['overlay'] != 1) { ?>
                            <a href="<?php echo ADMIN_URL; ?>manage<?php echo PHP_EXTENSION; ?>/<?php echo $table; ?>/" class="btn btn-circle"><i class="fa fa-table"></i></a>
                        <?php } ?>

                        <?php
                        include('includes/header.php');
                        if (file_exists('inc-' . $table . ',php')) {
                            include('inc-' . $table . ',php');
                        }
                        ?>

                        <div id="error-area">
                            <ul></ul>
                        </div>    
                        <div id="message-area">
                            <p></p>
                        </div>
                        <form name="suForm" id="suForm" method="post" target="remote" action="<?php echo ADMIN_URL; ?>remote<?php echo PHP_EXTENSION; ?>/add/<?php echo $table; ?>/" enctype="multipart/form-data"  data-parsley-validate>
                            <?php
                            //Any actions desired at this point should be coded in this file
                            if (file_exists('includes/specific/add-pre.php')) {
                                include('includes/specific/add-pre.php');
                            }
                            ?>

                            <div class="row">
                                <div class="form-group">
                                    <?php
                                    $uniqueArray = array();

                                    foreach ($structure as $value) {

                                        if ($value['Type'] == 'hidden' || $value['Type'] == 'ip_address') {
                                            suBuildField($value);
                                        } else {
                                            ?>
                                            <div class="col-xs-12 col-sm-12 col-md-<?php echo $value['Width']; ?> col-lg-<?php echo $value['Width']; ?>" id="data_div_<?php echo $value['Slug']; ?>">
                                                <?php suBuildField($value, $mode, $label_add); ?>
                                                <div>&nbsp;</div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>


                                <?php
                                //Any actions desired at this point should be coded in this file
                                if (file_exists('includes/specific/add-post.php')) {
                                    include('includes/specific/add-post.php');
                                }
                                ?>
                            </div>

                            <div>&nbsp;</div>

                            <div class="clearfix"></div>
                            <div>&nbsp;</div>

                            <p class="pull-right">
                                <?php
                                //If reloadField
                                if (isset($_GET['reloadField'])) {
                                    $arg = array('type' => 'hidden', 'name' => 'reloadField', 'id' => 'reloadField', 'value' => $_GET['reloadField']);
                                    echo suInput('input', $arg);
                                    $arg = array('type' => 'hidden', 'name' => 'sourceField', 'id' => 'sourceField', 'value' => $_GET['sourceField']);
                                    echo suInput('input', $arg);
                                }

                                //Hidden to store save for later action
                                $arg = array('type' => 'hidden', 'name' => 'save_for_later_use', 'id' => 'save_for_later_use', 'value' => 'No');
                                echo suInput('input', $arg);

                                //Submit
                                $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'class' => 'btn btn-theme');
                                echo suInput('button', $arg, "<i class='fa fa-check'></i>", TRUE);

                                //If save for later
                                if ($save_for_later == 'Yes') {
                                    echo ' ';
                                    $arg = array('type' => 'submit', 'name' => 'save_for_later', 'id' => 'save_for_later', 'class' => 'btn btn-theme');
                                    echo suInput('button', $arg, "<i class='fa fa-save'></i>", TRUE);
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