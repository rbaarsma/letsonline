<?php slot('actions'); ?>
<a href="<?php echo $form->getObject()->getProfileUrl(); ?>"><?php echo __("View Public Profile"); ?></a>
<?php end_slot(); ?>

<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("Public Profile"),
  'action'  => url_for('user_update', array('form_type'=>'profile')),
  'submit'  => __("Save")
)) ?>

