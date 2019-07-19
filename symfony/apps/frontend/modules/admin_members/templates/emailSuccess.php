<table>
  <thead>
    <tr>
      <th colspan="2"><?php echo __("E-mail users"); ?></th>
    </tr>
    <tr>
      <td><?php echo __("User"); ?></td>
      <td><?php echo __("Email"); ?></td>
      <td><?php echo __("Last login"); ?></td>
      <td><?php echo __("Joined"); ?></td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
      <td><?php echo $user->getDisplayName() ?></td>
      <td><?php echo $user->getEmail() ?></td>
      <td><?php echo $user->getLastLogin() ?></td>
      <td><?php echo $user->getJoined() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>