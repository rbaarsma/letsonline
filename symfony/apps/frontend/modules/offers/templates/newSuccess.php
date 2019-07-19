<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("New offer or request"),
  'action'  => url_for('offers/create'),
  'submit'  => __("Add")
)) ?>
