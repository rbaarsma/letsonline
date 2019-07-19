<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("Change E-mail"),
  'action'  => url_for('user_update', array('form_type'=>'email')),
  'submit'  => __("Save")
)) ?>