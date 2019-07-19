if (window.attachEvent) // IE only
  jQuery(document).ready(function() {
    jQuery("#nav li").each(function (i,e) {
      var e = jQuery(e); // make available for referencing
      e.mouseover(function (evt) { e.addClass('ie_hover'); });
      e.mouseout(function (evt) { e.removeClass('ie_hover'); });
    });
  });


/*
JQuery(document).ready(function() {

   function show() {
     var menu = JQuery("#nav ul");
     menu.children(".actions").slideDown();
   }

   function hide() {
     var menu = JQuery("#nav ul");
     menu.children(".actions").slideUp();
   }

   JQuery("#nav ul").hoverIntent({
     sensitivity: 1, // number = sensitivity threshold (must be 1 or higher)
     interval: 50,   // number = milliseconds for onMouseOver polling interval
     over: show,     // function = onMouseOver callback (required)
     timeout: 300,   // number = milliseconds delay before onMouseOut
     out: hide       // function = onMouseOut callback (required)
   });

 });
*/