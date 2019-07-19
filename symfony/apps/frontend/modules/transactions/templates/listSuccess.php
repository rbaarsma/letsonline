<?php $pager->renderFilter($form, url_for("transactions_ajax_list")); ?>
<div id="data">
  <?php include_partial('transactions', array('payments' => $payments, 'pager'=>$pager)) ?>
</div>
