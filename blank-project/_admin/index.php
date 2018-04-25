<?php
include('../sulata/includes/config.php');
include('../sulata/includes/language.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');

//Check admin login.
//If user is not logged in, send to login page.
checkAdminLogin();
$sessionUserId = $_SESSION[SESSION_PREFIX . 'user_id'];
$title = 'Welcome';
$h1 = $title;

//Check group permission - this include must be after $table variable is built
include('../sulata/includes/check-group-permissions.php');
//Stop unauthorised add access
if ($_SESSION[SESSION_PREFIX . 'user_group'] != ADMIN_GROUP_NAME) {
    //Check IP restriction
    suCheckIpAccess();
}
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
                        <div>
                            <img class="img-responsive" src="<?php echo BASE_URL; ?>files/sample-dashboard.jpg"/>

                        </div>
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