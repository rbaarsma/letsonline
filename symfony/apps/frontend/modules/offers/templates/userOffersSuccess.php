<table class="list" width="100%">
  <thead>
    <tr>
      <th colspan="7"><?php echo __("%user%'s Offers & Requests", array("%user%"=>$user)); ?></th>
    </tr>
    <tr>
      <td><?php echo __("User") ?></td>
      <td><?php echo __("Category") ?></td>
      <td><?php echo __("Type") ?></td>
      <td><?php echo __("Description") ?></td>
      <td>&nbsp;</td>
    </tr>
  </thead>
  <tbody>
    <?php if ($offers->count() == 0): ?>
      <td colspan="7"><?php echo __("%user% has no offers or requests", array("%user%"=>$user)); ?></td>
    <?php else: ?>
    <?php foreach ($offers as $offer): ?>
    <tr onclick="showDetails('<?php echo url_for("offers/show?id=".$offer->getId())?>', this, '<?php echo __("%type% Details", array("%type%"=>$offer->getType())) ?>');">
      <td class="user"><?php echo $user->getRaw('profileLink') ?></td>
      <td><?php echo __($offer->getCategory()) ?></td>
      <td><?php echo __($offer->getType()) ?></td>
      <td><?php echo $offer->getDescription() ?></td>
      <td width="23"><?php echo Utils::getInstance()->showInfo(__("view details"), "offers/show?id=".$offer->getId()); ?></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<?php echo link_to(__("View Offers & Requests of all users"), "offers"); ?>