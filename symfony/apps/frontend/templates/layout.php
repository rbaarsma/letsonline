<?php

$sf_user = $sf_data->getRaw('sf_user');

$menu = new Menu(array(
  link_to(__("Transactions"), "transactions") => new Menu(array(
    link_to(__("Overview"), "transactions"),
    link_to(__("Send Payment"), "transactions_payment"),
    link_to(__("Send Reminder"), "transactions_reminder"),
    link_to(__("Find Transactions"), "transactions_list")
  )),
  link_to(__("Your Account"), "user_edit") => new Menu(array(
    link_to(__("Overview"), "user_edit"),
    link_to(__("Your Offers & Requests"), "personal_offers"),
    link_to(__("Account Data"), "user_ajax_edit", array('form_type'=>'data')),
    //link_to(__("Preferences"), "user_ajax_edit", array('form_type'=>'preferences')),
    link_to(__("Public Profile"), "user_ajax_edit", array('form_type'=>'profile')),
    link_to(__("Change E-mail"), "user_ajax_edit", array('form_type'=>'email')),
    link_to(__("Change Password"), "user_ajax_edit", array('form_type'=>'password')),
    link_to(__("Logout"), "user_logout")
  )),
  link_to(__("Trade Circle"), "offers") => new Menu(array(
    link_to(__("Offers & Requests"), "offers"),
    link_to(__("Member List"), "user_members"),
    link_to(__("Photo Album"), "user_members_album"),
    link_to(__("Send Email"), "user_email"),
  ))
));
if ($sf_user->hasCredential('view_users'))
  $menu->addItem(link_to(__("Admin"), "admin_members"), new Menu(array(
    link_to(__("Add user"), "admin_members/new"),
    link_to(__("Manage"), "admin_members"),
    link_to(__("Addresslist"), "admin_members_addresslist"),
    link_to(__("LETS Totals"), "admin_members_transactions"),
    link_to(__("Change identity"), "admin_members_find"),
    //link_to(__("Send E-mail"), "admin_members_email")
  )));

$path_array = explode("/",$sf_request->getPathInfo());
switch ($path_array[1])
{
  case "":
  case "transactions":
    $currpage = 0;
    break;
  case "user":
    $currpage = 1;
    break;
  case "admin":
    $currpage = 3;
    break;
  default:
    $currpage = 2;
}
foreach ($menu->getSubmenus() as $sub)
  $sub->highlight($sf_request->getPathInfoPrefix().$sf_request->getPathInfo());

$user = $sf_user->getObject();

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
        <div id="logo">
          <img src="/images/header_short.jpg" alt="logo here" title="Ruilkring Harderwijk" />
        </div>
        <div id="user_welcome">
          <?php if ($sf_user->isAuthenticated()): ?>
            <?php echo __("Welcome %name%", array("%name%"=>$user->getFullName())); ?><br/>
            (
            <?php echo link_to(__('account'), 'user_edit'); ?> |
            <?php echo link_to(__('logout'), 'user_logout'); ?>
            )
          <?php else: ?>
            <?php echo link_to(__("Please login first"), "user_login"); ?>
          <?php endif; ?>
        </div>
        <div id="totals">
          <table width="100%">
            <thead>
              <tr><th colspan="2"><?php echo __("Totals"); ?></th></tr>
            </thead>
            <tbody>
              <?php if ($sf_data->getRaw('sf_user')->getObject()->getOldLets()): ?>
              <tr>
                <th><?php echo __("Start Balance"); ?></th>
                <td class="<?php echo $sf_data->getRaw('sf_user')->getObject()->getOldLets() < 0 ? "neg" : "pos"?>">
                  <?php echo $sf_data->getRaw('sf_user')->getObject()->getOldLets() ?>
                </td>
              </tr>
              <?php endif; ?>
              <tr>
                <th><?php echo __("Payed"); ?></th>
                <td class="neg"><?php echo $sf_data->getRaw('sf_user')->getObject()->getTotalPayed() ?></td>
              </tr>
              <tr style="border-bottom: 3px double black;">
                <th><?php echo __("Received"); ?></th>
                <td class="pos"><?php echo $sf_data->getRaw('sf_user')->getObject()->getTotalReceived() ?></td>
              </tr>
              <tr>
                <th><?php echo __("Balance"); ?></th>
                <td class="<?php echo $sf_data->getRaw('sf_user')->getObject()->getBalance() < 0 ? "neg" : "pos"?>">
                  <?php echo $sf_data->getRaw('sf_user')->getObject()->getBalance() ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <table id="container_table">
        <tr style="background-color: transparent;">
          <td id="sideleft" class="sidebar" valign="top">
            <div class="sideitem_header"><?php echo __("Menu"); ?></div>
            <div id="sideleft_nav" class="sideitem"><?php echo $menu->render("sidenav", $currpage, true); ?></div>
          </td>
          <td id="content" valign="top">
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
          </td>
          <td id="sideright" class="sidebar" valign="top">
            <div id="sideright_content" style="display: none"></div>
            <div id="sideright_info">
              <?php if ($info = Info::get($sf_request->getPathInfo())): ?>
              <?php include_partial("global/info", array("info"=>$info)); ?>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      </table>

    </div>
    <div id="footer">
      <p>&copy; <a href="http://www.solidwebcode.com/">Solid Web Code <?php echo date("Y"); ?></a></p>
    </div>
  </body>
</html>
