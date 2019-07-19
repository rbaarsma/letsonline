<table>
  <thead>
    <tr>
      <th colspan="4"><?php echo __("Address List"); ?></th>
    </tr>
    <tr>
      <td><?php echo __("Name"); ?></td>
      <td><?php echo __("Street"); ?></td>
      <td><?php echo __("Zip code"); ?></td>
      <td><?php echo __("Phone"); ?></td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
      <td><?php echo $user->getFirstName()." ".$user->getLastName() ?></td>
      <td><?php echo $user->getAddressLine() ?></td>
      <td><?php echo $user->getZipCode() ?></td>
      <td><?php echo $user->getPhone() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>