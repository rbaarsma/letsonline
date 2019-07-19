<table class="list" width="100%">
  <thead>
    <tr>
      <th colspan="5"><?php echo __("Members List"); ?></th>
    </tr>
    <tr>
      <td><?php echo __("First Name"); ?></td>
      <td><?php echo __("Last Name"); ?></td>
      <td><?php echo __("Zip Code"); ?></td>
      <td><?php echo __("Phone"); ?></td>
      <td>&nbsp;</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr onclick="showDetails('<?php echo $user->getRaw('profileUrl') ?>', this, '<?php echo __("Profile of %user%", array("%user%"=>$user->getDisplayName())) ?>');">
      <td><?php echo $user->getFirstName() ?></td>
      <td><?php echo $user->getLastName() ?></td>
      <td><?php echo $user->getZipCode() ?></td>
      <td><?php echo $user->getPhone() ?></td>
      <td width="23"><?php echo Utils::getInstance()->showInfo(__("view details"), "profile", array("slug"=>$user->getSlug())); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>