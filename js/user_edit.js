UserEdit = {
  'default': 'data',
  'load': function (form) {
    form = form || UserEdit['default'];
    jQuery('#loader').show();
    var url = '/user/edit/'+form;
    jQuery('#user_edit').load(url, {}, function() { jQuery('#loader').hide(); });
  }
};

jQuery(document).ready(function() {
  UserEdit.load();
});