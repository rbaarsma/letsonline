<?php if ($pager->haveToPaginate()): ?>
  <div class="pagination">
    <a href="#" onclick="filter.page(1);">
      <img src="/images/first.png" alt="<?php echo __("First page"); ?>" title="<?php echo __("First page"); ?>" />
    </a>

    <a href="#" onclick="filter.page(<?php echo $pager->getPreviousPage() ?>);">
      <img src="/images/previous.png" alt="<?php echo __("Previous page"); ?>" title="<?php echo __("Previous page"); ?>" />
    </a>

    <?php foreach ($pager->getLinks() as $page): ?>
      <?php if ($page == $pager->getPage()): ?>
        <?php echo $page ?>
      <?php else: ?>
        <a href="#" onclick="filter.page(<?php echo $page ?>);"><?php echo $page ?></a>
      <?php endif; ?>
    <?php endforeach; ?>

    <a href="#" onclick="filter.page(<?php echo $pager->getNextPage() ?>);">
      <img src="/images/next.png" alt="<?php echo __("Next page"); ?>" title="<?php echo __("Next page"); ?>" />
    </a>

    <a href="#" onclick="filter.page(<?php echo $pager->getLastPage() ?>);">
      <img src="/images/last.png" alt="<?php echo __("Last page"); ?>" title="<?php echo __("Last page"); ?>" />
    </a>
  </div>
<?php endif; ?>