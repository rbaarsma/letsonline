<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => __("Change offer or request"),
  'action'  => url_for('personal_offers_update', array('id'=>$form->getObject()->getId())),
  'links'   => array(
    link_to(__("Back to list"), 'personal_offers'),
    link_to(__("Delete"), 'personal_offers_delete', array('id'=>$form->getObject()->getId()), 'confirm='.__("Are you sure?"))
  ),
  'submit'  => __("Save")
)) ?>