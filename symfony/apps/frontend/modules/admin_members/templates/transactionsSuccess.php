<table>
  <thead>
    <tr>
      <th colspan="4"><?php echo __("Transaction Totals"); ?></th>
    </tr>
    <tr>
      <td><?php echo __("User"); ?></td>
      <td>+</td>
      <td>-</td>
      <td><?php echo __("Total"); ?></td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
      <td><?php echo $user->getRaw('profileLink') ?></td>
      <td class="pos"><?php echo (int)$user->getReceived() ?></td>
      <td class="neg"><?php echo (int)$user->getPayed() ?></td>
      <td class="<?php echo $user->getReceived() - $user->getPayed() < 0 ? "neg" : "pos" ?>">
        <?php echo $user->getReceived() - $user->getPayed() ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>