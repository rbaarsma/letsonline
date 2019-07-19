<?php
$menu = new Menu(array(
  link_to(__("Login"), "user_login"),
  link_to(__("Contact Us"), "contact_us")
));

$path_array = explode("/",$sf_request->getPathInfo());
switch ($path_array[1].@$path_array[2])
{
  case "userlogin":
    $currpage = 0;
    break;
  case "contact":
    $currpage = 1;
    break;
  default:
    $currpage = 0;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <div id="container">
      <div id="header">
        <div id="user_welcome">

        </div>
        <div id="logo">
          <img src="/images/header_short.jpg" alt="logo here" title="Ruilkring Harderwijk" />
        </div>
      </div>

      <table id="container_table">
        <tr style="background-color: transparent;">
          <td id="sideleft" class="sidebar" valign="top">
            <div class="sideitem_header"><?php echo __("Menu"); ?></div>
            <div id="sideleft_nav" class="sideitem"><?php echo $menu->render("home_sidenav", $currpage, false); ?></div>
          </td>
          <td id="content" valign="top">
            <div style="width: 30em;">
              <?php if ($sf_user->hasFlash('notice')): ?>
                <div class="flash flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
              <?php endif ?>
              <?php if ($sf_user->hasFlash('error')): ?>
                <div class="flash flash_error"><?php echo $sf_user->getFlash('error') ?></div>
              <?php endif ?>
              <?php if ($sf_user->hasFlash('confirm')): ?>
                <div class="flash flash_confirm"><?php echo $sf_user->getFlash('confirm') ?></div>
              <?php endif ?>
              <?php echo $sf_content ?>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div id="footer">
      <p>&copy; <a href="http://www.solidwebcode.com/">Solid Web Code <?php echo date("Y"); ?></a>.</p>
    </div>
  </body>
</html>
