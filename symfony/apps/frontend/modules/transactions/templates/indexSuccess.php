<?php if ($received_reminders->count() > 0):?>
<div>
  <table id="transactions_received_reminders" class="list" width="100%">
    <thead>
      <tr>
        <th colspan="6"><?php echo __("Reminders to accept")?></th>
      </tr>
    </thead>
    <tbody>    
    <?php foreach ($sf_data->getRaw('received_reminders') as $reminder): ?>
      <tr onclick="showDetails('<?php echo url_for("transactions_show", array("id"=>$reminder->getId()))?>', this, '<?php echo __("Received Reminder"); ?>');">
        <td class="date"><?php echo $reminder->getFormattedDate() ?></td>
        <td class="user" width="75"><?php echo $reminder->getReceiver()->getProfileLink() ?></td>
        <td><?php echo $reminder->getDescription() ?></td>
        <td width="50">
          <span class="neg">
            <?php echo $reminder->getTransactionAmount($sf_context->getRaw('user')->getObject()) ?>
          </span>
        </td>
        <td width="23"><?php echo Utils::getInstance()->icon_to("confirm.png", __("confirm payment"), "transactions_confirm", array("id"=>$reminder->getId())); ?></td>
        <td width="23"><?php echo Utils::getInstance()->showInfo(__("view details"), "transactions_show", array("id"=>$reminder->getId())); ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php if ($sent_reminders->count() > 0):?>
<div class="accordion">
  <table id="transactions_sent_reminders" class="list" width="100%">
    <thead>
      <tr>
        <th colspan="6"><?php echo __("Sent Reminders")?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($sf_data->getRaw('sent_reminders') as $reminder): ?>
      <tr onclick="showDetails('<?php echo url_for("transactions_show", array("id"=>$reminder->getId()))?>', this, '<?php echo __("Sent Reminder"); ?>');">
        <td class="date"><?php echo $reminder->getFormattedDate() ?></td>
        <td class="user" width="75"><?php echo $reminder->getSender()->getProfileLink() ?></td>
        <td><?php echo $reminder->getDescription() ?></td>
        <td width="50">
          <span class="pos">
            <?php echo $reminder->getTransactionAmount($sf_context->getRaw('user')->getObject()) ?>
          </span>
        </td>
        <td width="23"><?php echo Utils::getInstance()->icon_to("delete.png", __("remove reminder"), "transactions_delete", array("id"=>$reminder->getId())); ?></td>
        <td width="23"><?php echo Utils::getInstance()->showInfo(__("view details"), "transactions_show", array("id"=>$reminder->getId())); ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php include_partial('transactions', array('payments' => $payments, 'pager'=>$pager)) ?>