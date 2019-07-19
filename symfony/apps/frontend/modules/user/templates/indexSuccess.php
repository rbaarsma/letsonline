<?php use_helper('I18N'); ?>

<?php slot('actions'); ?>
<?php echo link_to(__("Edit Offers & Requests"), "personal_offers"); ?>
<?php echo link_to(__("Edit Account Data"), "user_ajax_edit", array('form_type'=>'data')); ?>
<?php echo link_to(__("Edit Public Profile"), "user_ajax_edit", array('form_type'=>'profile')); ?>
<?php end_slot(); ?>

<style type="text/css">
  #sideright { display: none; }
  table.styless { background-color: transparent; border-collapse: collsapse; border: 0 none; magin: 0; }
  table.styless td { background-color: transparent; border: 0 none; padding: 0; }
  table.styless tr { background-color: transparent; border: 0 none; }
  table.styless td table td {background-color: white;}
  table.styless table { margin: 0; }
</style>

<table width="100%" class="styless">
  <tr>
    <td valign="top" style="padding-right: 1em;">
      <div id="data" class="form">
        <table>
          <thead>
            <th colspan="2">
              <?php echo Utils::getInstance()->icon_to("edit.png", __("Edit Account Data"), "user_ajax_edit", array('form_type'=>'data'), array("style"=>"float:right; margin-right: 5px;")); ?>
              <?php echo __("Account Data"); ?>
            </th>
          </thead>
          <tbody>
            <tr>
              <th><?php echo __("First Name"); ?></th>
              <td><?php echo $user->getFirstName(); ?></td>
            </tr>
            <tr>
              <th><?php echo __("Last Name"); ?></th>
              <td><?php echo $user->getLastName(); ?></td>
            </tr>
            <tr>
              <th><?php echo __("Address"); ?></th>
              <td><?php echo $user->getAddressLine(); ?></td>
            </tr>
            <tr>
              <th><?php echo __("Zip Code"); ?></th>
              <td><?php echo $user->getZipCode(); ?></td>
            </tr>
            <tr>
              <th><?php echo __("City"); ?></th>
              <td><?php echo $user->getCity(); ?></td>
            </tr>
            <tr>
              <th><?php echo __("Country"); ?></th>
              <td><?php echo format_country($user->getCountry()); ?></td>
            </tr>
            <?php if ($user->getPhoneHome()): ?>
            <tr>
              <th><?php echo __("Phone (Home)"); ?></th>
              <td><?php echo $user->getPhoneHome(); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($user->getPhoneMobile()): ?>
            <tr>
              <th><?php echo __("Phone (Mobile)"); ?></th>
              <td><?php echo $user->getPhoneMobile(); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($user->getPhoneWork()): ?>
            <tr>
              <th><?php echo __("Phone (Work)"); ?></th>
              <td><?php echo $user->getPhoneWork(); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($user->getBirthDate()): ?>
            <tr>
              <th><?php echo __("Birth Date"); ?></th>
              <td><?php echo $user->getFormattedBirthDate(); ?></td>
            </tr>
            <?php endif; ?>
            <tr>
              <th><?php echo __("Language"); ?></th>
              <td><?php echo format_language($sf_user->getCulture()); ?></td>
            </tr>
            <tr>
              <th><?php echo __("E-mail"); ?></th>
              <td>
                <?php echo Utils::getInstance()->icon_to("edit.png", __("Change E-mail Address"), "user_ajax_edit", array('form_type'=>'email'), array("style"=>"float:right;")); ?>
                <?php echo $user->getEmail(); ?>
              </td>
            </tr>
            <tr>
              <th><?php echo __("Password"); ?></th>
              <td>
                <?php echo Utils::getInstance()->icon_to("edit.png", __("Change Password"), "user_ajax_edit", array('form_type'=>'password'), array("style"=>"float:right;")); ?>
                ********
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </td>
    <td valign="top">
      <div id="profile" class="form">
        <table>
          <thead>
            <th colspan="2">
              <?php echo Utils::getInstance()->icon_to("edit.png", __("Edit Public Profile"), "user_ajax_edit", array('form_type'=>'profile'), array("style"=>"float:right; margin-right: 5px;")); ?>
              <?php echo __("Public Profile"); ?>
            </th>
          </thead>
          <tbody>
            <tr>
              <th><?php echo __("Profile Link"); ?></th>
              <td><a href="<?php echo $user->getProfileUrl(); ?>"><?php echo __("view public profile"); ?></a></td>
            </tr>
            <tr>
              <th><?php echo __("Display Name"); ?></th>
              <td><?php echo $user->getDisplayName(); ?></td>
            </tr>
            <tr>
              <th><?php echo __("Personal Text"); ?></th>
              <td><?php echo $user->getPersonalText() ?></td>
            </tr>
            <tr>
              <th><?php echo __("Avatar Image"); ?></th>
              <td><?php echo $user->getRaw('avatarImage') ?></td>
            </tr>
            <tr>
              <th><?php echo __("Show e-mail?"); ?></th>
              <td><?php echo $user->getShowEmail() ? __("yes") : __("no"); ?></td>
            </tr>
            <tr>
              <th><?php echo __("Show phone?"); ?></th>
              <td><?php echo $user->getShowPhone() ? __("yes") : __("no"); ?></td>
            </tr>
            <tr>
              <th><?php echo __("Show address?"); ?></th>
              <td><?php echo $user->getShowAddress() ? __("yes") : __("no"); ?></td>
            </tr>
            <tr>
              <th><?php echo __("Show your age?"); ?></th>
              <td><?php echo $user->getShowAge() ? __("yes") : __("no"); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </td>
  </tr>
</table>
<?php include_partial('offers/personal_list', array('offers' => $offers)) ?>