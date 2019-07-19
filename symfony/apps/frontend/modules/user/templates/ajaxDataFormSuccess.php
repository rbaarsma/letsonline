<?php slot('actions'); ?>
<?php echo link_to(__("Change Password"), "user_ajax_edit", array('form_type'=>'password')); ?>
<?php echo link_to(__("Change E-mail"), "user_ajax_edit", array('form_type'=>'email')); ?>
<?php end_slot(); ?>

<?php

$form->getWidgetSchema()->setHelp('email', Utils::getInstance()->icon_to("edit.png", __("Change E-mail Address"), "user_ajax_edit", array('form_type'=>'email'), array("style"=>"float:right;")));
$form->getWidgetSchema()->setHelp('password', Utils::getInstance()->icon_to("edit.png", __("Change Password"), "user_ajax_edit", array('form_type'=>'password'), array("style"=>"float:right;")));
?>

<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("Personal Data"),
  'action'  => url_for('user_update', array('form_type'=>'data')),
  'submit'  => __("Save")
)) ?>