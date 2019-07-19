<div class="pagination_desc">
  <strong><?php echo count($pager) ?></strong> <?php echo __("total"); ?>

  <?php if ($pager->haveToPaginate()): ?>
    - page <strong><?php echo $pager->getPage() ?>/<?php echo $pager->getLastPage() ?></strong>
  <?php endif; ?>
</div>