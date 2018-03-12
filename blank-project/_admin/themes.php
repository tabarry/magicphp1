<?php
include('../sulata/includes/config.php');
include('../sulata/includes/language.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');

//Check admin login.
//If user is not logged in, send to login page.
checkAdminLogin();
$sessionUserId = $_SESSION[SESSION_PREFIX . 'user_id'];

//Check group permission - this include must be after $table variable is built
include('../sulata/includes/check-group-permissions.php');


if ($_GET['theme'] != '') {
    $newTheme = $_GET['theme'];
    $sql = "UPDATE " . USERS_TABLE_NAME . " SET data= JSON_REPLACE(data,'$.theme','" . urlencode($newTheme) . "') WHERE id='" . $_SESSION[SESSION_PREFIX . 'user_id'] . "'";

    suQuery($sql);
    //Set theme in session
    $_SESSION[SESSION_PREFIX . 'user_theme'] = $newTheme;
    //Set theme in cookie
    setcookie('ck_theme', $_SESSION[SESSION_PREFIX . 'user_theme'], time() + (COOKIE_EXPIRY_DAYS * 86400), '/');

    suPrintJs('
            parent.document.getElementById("themeCss").setAttribute("href", "' . BASE_URL . 'sulata/css/admin/themes/' . $newTheme . '/style.css");
        ');
    exit();
}
$title = 'Change Theme';
$h1 = $title;
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
        <link href="<?php echo BASE_URL; ?>/sulata/css/admin/themes.css" rel="stylesheet" type="text/css"/>


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
                        <div class="row">
                            <?php
                            $dir = '../sulata/css/admin/themes/';
                            $files = scandir($dir);
                            for ($i = 0; $i <= sizeof($files) - 1; $i++) {

                                if ($files[$i][0] != '.' && $files[$i] != MAGIC_THEME) {
                                    ?>
                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                        <table width="100%" class="theme-main-table" id="theme-table" onclick="doChangeTheme('<?php echo $files[$i]; ?>');">

                                            <tr>
                                                <td width="70%">
                                                    <table align="center" width="80%">
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="theme-inner-table">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="theme-inner-table">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                        </tr>

                                                    </table>
                                                </td>
                                                <td width="20%" class="<?php echo $files[$i]; ?>">
                                                    &nbsp;
                                                </td>
                                                <td width="10%" class="<?php echo $files[$i]; ?>-sidebar">
                                                    &nbsp;
                                                </td>
                                            </tr>


                                        </table>
                                    </div>

                                    <?php
                                }
                            }
                            ?>
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