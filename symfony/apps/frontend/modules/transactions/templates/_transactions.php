<?php $user = $sf_data->getRaw('sf_user')->getObject(); ?>

<div>
  <table id="transactions_payments" class="list" width="100%">
    <thead>
      <tr>
        <th colspan="6"><?php echo __("Transactions")?></th>
      </tr>
    </thead>
    <tbody>
    <?php if ($payments->count() == 0):?>
    <tr><td colspan="6"><?php echo __("No transactions found.")?></td></tr>
    <?php else: ?>
    <?php foreach ($sf_data->getRaw('payments') as $payment): ?>
      <tr<?php echo time()-strtotime($payment->getUpdatedAt()) < 5 ? " class='new'" : "" ?> onclick="showDetails('<?php echo url_for("transactions_show", array("id"=>$payment->getId()))?>', this, '<?php echo __("Transaction Coupon")?>');">
        <td class="date"><?php echo $payment->getFormattedDate() ?></td>
        <td class="user" width="75"><?php echo $payment->getOtherUser($user)->getProfileLink() ?></td>
        <td><?php echo $payment->getDescription() ?></td>
        <td width="50">
          <span class="<?php echo $payment->getTransactionAmount($user) < 0 ? "neg" : "pos" ?>">
            <?php echo $payment->getTransactionAmount($user) ?>
          </span>
        </td>
        <td width="23"><?php echo Utils::getInstance()->showInfo(__("view details"), "transactions_show", array("id"=>$payment->getId())); ?></td>
      </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>


<?php if ($sf_request->getUrlParameter('action') == "index"): ?>
  <?php if ($pager->haveToPaginate()): ?>
    <a href="<?php echo url_for("transactions_list", array("page"=>2)); ?>"
      ><?php echo __("%count% more", array("%count%"=>count($pager) - sfConfig::get('app_paging_limit'))) ?></a>
    
  <?php endif; ?>
<?php else: ?>
  <?php $pager->render() ?>
<?php endif; ?>

