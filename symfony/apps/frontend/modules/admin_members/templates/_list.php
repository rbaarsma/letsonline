<table class="list">
  <thead>
    <tr>
      <th colspan="7">
        Members
        <a href="<?php echo url_for('admin_members/new') ?>"><?php echo __("Add member"); ?></a>
      </th>
    </tr>
    <tr>
      <td><?php echo __("User"); ?></td>
      <td><?php echo __("Email"); ?></td>
      <td><?php echo __("Last login"); ?></td>
      <td><?php echo __("Joined"); ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr onclick="showDetails('<?php echo $user->getProfileUrl(); ?>', this, '<?php echo __("Profile of %user%", array("%user%"=>$user->getDisplayName())) ?>');">
      <td><?php echo $user->getDisplayName() ?></td>
      <td><?php echo $user->getEmail() ?></td>
      <td><?php echo $user->getFormattedLastLogin() ?></td>
      <td><?php echo $user->getFormattedCreatedAt() ?></td>
      <td><a href="<?php echo url_for('admin_members/edit?id='.$user->getId()) ?>"><?php echo __("edit"); ?></a></td>
      <?php if ($user->isActive()): ?>
        <td><a href="<?php echo url_for('admin_members/deactivate?id='.$user->getId()) ?>"><?php echo __("deactivate"); ?></a></td>
      <?php else: ?>
        <td><a href="<?php echo url_for('admin_members/activate?id='.$user->getId()) ?>"><?php echo __("activate"); ?></a></td>
      <?php endif; ?>
      <td width="23"><?php echo Utils::getInstance()->showInfo(__("view details"), "profile", array("slug"=>$user->getSlug())); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php $pager->render() ?>