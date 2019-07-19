<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php echo $form->renderFormTag($action); ?>
  <table>
    <thead>
      <tr>
        <th colspan="2"><?php echo $title ?></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="<?php echo $submit ? $submit : __("Save") ?>" />
          <?php if (isset($links)): ?>
            <?php echo implode(" | ", $sf_data->getRaw('links')) ?>
          <?php endif; ?>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
</form>