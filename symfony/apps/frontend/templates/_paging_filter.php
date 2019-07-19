<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<table class="form" width="100%">
  <thead>
    <tr>
      <th colspan="2"><?php echo __("Search"); ?></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td colspan="2">
        <input type="button" value="<?php echo __("Search"); ?>" onclick="filter.doFilter();" />
        <a href=""><?php echo __("reset search"); ?></a>
      </td>
    </tr>
  </tfoot>
  <tbody>
    <?php echo $form->render(); ?>
  </tbody>
</table>