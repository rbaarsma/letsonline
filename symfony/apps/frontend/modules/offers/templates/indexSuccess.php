<?php $pager->renderFilter($form, url_for("offers_ajax_list")); ?>
<div id="data">
<?php include_partial('offers/list', array('offers' => $offers, 'pager' => $pager)) ?>
</div>