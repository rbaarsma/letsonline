<?php echo $form->getValue('credentials'); ?>

<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("Edit User"),
  'action'  => url_for('admin_members/update?id='.$form->getObject()->getId()),
  'submit'  => __("Save")
)) ?>

<?php if ($form->getObject()->isNew()): ?>
  <script type="text/javascript">
    document.getElementById('user_password').value = generatePassword();
  </script>
<?php endif; ?>