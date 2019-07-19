<?php use_helper('I18N'); ?>

<?php slot('actions'); ?>
  <?php if ($user->getId() == $sf_user->getId()): ?>
    <?php echo link_to(__("Edit Public Profile"), "user_ajax_edit", array("form_type"=>"profile")); ?>
  <?php endif; ?>
  <?php echo link_to(__("E-mail Member"), "user_email_slug", array("slug"=>$user->getSlug())); ?>
<?php end_slot(); ?>

<div id="profile">
  <div style="position: relative;">
    <div style="position: absolute; right: 0; top: 25px;">
      <?php echo $user->getRaw('AvatarImage'); ?>
    </div>
  </div>

  <table width="100%">
    <thead>
      <th colspan="2">
        <?php echo __("Profile of %user%", array('%user%'=>$user->__toString())) ?>
      </th>
    </thead>
    <tbody>
      <tr>
        <th width="50"><?php echo __("First Name"); ?></th>
        <td><?php echo $user->getFirstName() ?></td>
      </tr>
      <tr>
        <th><?php echo __("Last Name"); ?></th>
        <td><?php echo $user->getLastName() ?></td>
      </tr>
      <?php if ($user->getShowAddress()): ?>
      <tr>
        <th><?php echo __("Address"); ?></th>
        <td><?php echo $user->getAddressLine(); ?></td>
      </tr>
      <?php endif; ?>
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
      <?php if ($user->getShowEmail()): ?>
      <tr>
        <th><?php echo __("E-mail"); ?></th>
        <td>
          <?php 
          $email = $user->getEmail();
          if (strlen($email) > 25)
            $email = str_replace("@","@<br/>",$email);
          ?>
          <?php echo $email; ?>
          <?php echo Utils::getInstance()->icon_to("email.png", __("Send E-mail"), "user_email_slug", array("slug"=>$user->getSlug()), array('style'=>'float: right;')) ?>
        </td>
      </tr>
      <?php endif; ?>
      <?php if ($user->getShowPhone()): ?>
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
      <?php endif; ?>
      <?php if ($user->getShowAge()): ?>
      <tr>
        <th><?php echo __("Age"); ?></th>
        <td><?php echo $user->getAge(); ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <th><?php echo __("Personal Text"); ?></th>
        <td>
          <?php if ($user->getPersonalText()): ?>
            <p><?php echo $user->getPersonalText(); ?></p>
          <?php endif; ?>
        </td>
      </tr>
      <tr>
        <th><?php echo __("Actions"); ?></th>
        <td>
          <?php if ($user->getId() != $sf_user->getId()): ?>
            &bull; <a href="<?php echo url_for("user_email_slug", array("slug"=>$user->getSlug())) ?>"><?php echo __("Email %user%", array("%user%"=>$user)) ?></a><br/>
            &bull; <a href="<?php echo url_for("user_offers", array("user"=>$user->getSlug())) ?>"><?php echo __("View %user%'s offers & requests", array("%user%"=>$user)) ?></a>
          <?php else: ?>
            &bull; <a href="<?php echo url_for("personal_offers") ?>"><?php echo __("View %user%'s offers & requests", array("%user%"=>$user)) ?></a>
          <?php endif; ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>