<?php $user = $sf_data->getRaw('sf_user')->getObject(); ?>

<?php slot('actions'); ?>
<?php echo link_to(__("Contact %user%", array("%user%"=>$coupon->getOtherUser($user))), "user_email_slug", array("slug"=>$coupon->getOtherUser($user)->getSlug())); ?>
<?php end_slot(); ?>

<div>
  <table>
    <thead>
      <th colspan="2">
        <?php if (!$coupon->getConfirmed()): ?>
          <?php if ($coupon->getSenderId() != $sf_user->getId()): ?>
            <?php echo __("Sent Reminder"); ?>
          <?php else: ?>
            <?php echo __("Received Reminder"); ?>
          <?php endif; ?>
        <?php else: ?>
          <?php echo __("Transaction Coupon"); ?>
        <?php endif; ?>
      </th>
    </thead>
    <?php if (!$coupon->getConfirmed()): ?>
    <tfoot>
      <td colspan="2">
        <?php if ($coupon->getSenderId() != $sf_user->getId()): ?>
        <form action="<?php echo url_for("transactions_delete", array("id"=>$coupon->getId())); ?>" method="get">
          <input type="submit" value="<?php echo __("Delete") ?>" />
        <?php else: ?>
        <form action="<?php echo url_for("transactions_confirm", array("id"=>$coupon->getId())); ?>" method="get">
          <input type="submit" value="<?php echo __("Confirm") ?>" />
        <?php endif; ?>
          <a href="<?php echo url_for("user_email_slug", array("slug"=>$coupon->getOtherUser($user)->getSlug(), "coupon"=>$coupon->getId())) ?>"><?php echo __("Email %user%", array("%user%"=>$coupon->getOtherUser($user))) ?></a>
        </form>
      </td>
    </tfoot>
    <?php endif; ?>
    <tbody>
      <tr>
        <th><?php echo __("Status"); ?></th>
        <td class="<?php echo $coupon->getConfirmed() ? "pos" : "neg"?>">
          <?php if ($coupon->getConfirmed()): ?>
            <?php if ($coupon->isPositive($user)): ?>
              <?php echo __("payment received"); ?>
            <?php else: ?>
              <?php echo __("payment sent"); ?>
            <?php endif; ?>
          <?php else: ?>
            <?php echo __("waiting for %user% to confirm reminder",
              array("%user%"=>$coupon->getSenderId() != $sf_user->getId() ? $coupon->getSender() : "<i>".__("you")."</i>")); ?>
          <?php endif; ?>
        </td>
      </tr>
      <tr>
        <th><?php echo __("From"); ?></th>
        <td><?php echo $coupon->getSender()->getRaw('profileLink'); ?></td>
      </tr>
      <tr>
        <th><?php echo __("To"); ?></th>
        <td><?php echo $coupon->getReceiver()->getRaw('profileLink'); ?></td>
      </tr>
      <tr>
        <th><?php echo __("Amount"); ?></th>
        <td class="<?php echo $coupon->isPositive($user) ? "pos" : "neg"?>">
          <?php echo $coupon->getTransactionAmount($user) ?>
        </td>
      </tr>
      <tr>
        <th><?php echo __("Date"); ?></th>
        <td><?php echo $coupon->getFormattedDate(); ?></td>
      </tr>
      <tr>
        <th><?php echo __("Description"); ?></th>
        <td><?php echo $coupon->getDescription(); ?></td>
      </tr>
    </tbody>
  </table>
</div>
