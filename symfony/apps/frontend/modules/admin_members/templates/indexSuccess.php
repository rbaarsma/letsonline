<?php $pager->renderFilter($form, url_for("admin_members_ajax_list")); ?>
<div id="data">
  <?php include_partial('admin_members/list', array('users'=>$pager->getResults(), 'pager'=>$pager)) ?>
</div>