<?php use_javascript('jquery.js'); ?>
<?php use_javascript('jquery-ui.js'); ?>
<?php use_stylesheet('jquery-ui.css'); ?>

<?php include_partial('global/form', array(
  'form'    => $form,
  'title'   => $type=="payment" ? __("Send Payment") : __("Send Reminder"),
  'action'  => url_for("transactions_create_$type"),
  'submit'  => __("Send")
)) ?>

<script type="text/javascript">
  jQuery(document).ready(function() {
      jQuery('#coupon_date').datepicker({
          dateFormat: '<?php echo Utils::getInstance()->getJsDateFormat() ?>',
          maxDate: '+0d' // maxdate = now
      });
  });
</script>