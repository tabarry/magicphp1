<?php
include('../sulata/includes/config.php');
include('../sulata/includes/language.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');

//Check admin login.
//If user is not logged in, send to login page.
checkAdminLogin();
$sessionUserId = $_SESSION[SESSION_PREFIX . 'user_id'];

$slug = suSegment(1);
$rid = suSegment(2);

if (!isset($slug) || $slug == '') {
    suExit(INVALID_RECORD);
}


//Get title
$sql = "SELECT id,title,slug,label_add,label_update,display,structure FROM " . STRUCTURE_TABLE_NAME . " WHERE live='Yes' AND slug='" . $slug . "' LIMIT 0,1";

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

//Check group permission - this include must be after $table variable is built
include('../sulata/includes/check-group-permissions.php');

//Any actions desired at this point should be coded in this file
if (file_exists('includes/specific/preview-top.php')) {
    include('includes/specific/preview-top.php');
}


$structure = $row['structure'];
$structure = json_decode($structure, 1);

$h1 = 'Preview';


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
                        <div class="table-responsive" id="printable-div">
                            <table class="table table-striped table-hover tablex" id="printable-table">
                                <?php
                                //Any actions desired at this point should be coded in this file
                                if (file_exists('includes/specific/preview-pre.php')) {
                                    include('includes/specific/preview-pre.php');
                                }
                                foreach ($structure as $value) {
                                    $value = array_merge($value, array('_____value' => suUnstrip($data[$value['Slug']])));
                                    suPreviewField($value);
                                }
                                ?>
                            </table>
                        </div>
                        <p class="pull-right">
                            <button id="btn-back" class="btn btn-theme" type="button" onclick="javascript:history.back(1);"><i class="fa fa-arrow-left"></i></button>
                            <?php if ($editAccess == TRUE) { ?>                            
                                <button class="btn btn-theme" type="button" onclick="window.location.href = '<?php echo ADMIN_URL; ?>update<?php echo PHP_EXTENSION; ?>/<?php echo $table; ?>/<?php echo $rid; ?>/<?php echo $redirect; ?>'"><i class="fa fa-edit"></i></button>
                            <?php } ?>
                            <button class="btn btn-theme" type="button" onclick="remote.location.href = '<?php echo ADMIN_URL; ?>remote<?php echo PHP_EXTENSION; ?>/preview-print/'"><i class="fa fa-print"></i></button>

                        </p>
                        <?php
                        if (file_exists('includes/specific/preview-post.php')) {
                            include('includes/specific/preview-post.php');
                        }
                        ?>
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