
jQuery(document).ready(function() {
  $('#sidenav li a').each(function (i,e) {
    if (e.parentNode.parentNode.id == "sidenav")
    {
      e.href = "#";
      $(e).bind('click', function (evt) {
        e.parentNode.id = "submenu_"+i;

        var a = $("#"+e.parentNode.id+" ul").toggle("slow", function () {
          $(this).parent().toggleClass('active');
        });
      });
    }
  });
});


function showDetails(link, tr, title)
{
  var sidebar = jQuery('#sideright');
  var sidebar_content = jQuery('#sideright_content');


  if (showDetails.old_tr != undefined)
    jQuery(showDetails.old_tr).removeClass('highlight');
  jQuery(tr).addClass('highlight');
  showDetails.old_tr = tr;

  sidebar_content.show();
  sidebar_content.addClass('loading');
  sidebar_content.html("<table width='100%'><thead><tr><th>"+title+"</th></tr></thead><tbody><tr><td><br/><br/><br/><br/><br/><br/></td></tr></tbody></table>");
  sidebar_content.load(link, function () {
    sidebar_content.hide();
    sidebar_content.removeClass('loading');
    sidebar_content.slideDown('slow');
  });

  location.href=location.hash || '#';
}
showDetails.old_tr = undefined; // not really the best solution i know..


