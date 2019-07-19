/*
 * A class to handle browser query strings such as test=1&blaat=test
 */
Query = function (q) {
  q = q || window.location.search.substring(1); // default query

  var query = {};
  var arr = q.split("&");

  $(arr).each(function (key, val) {
    if (!val)
      return;
    var obj = val.split("=");
    query[obj[0]] = obj[1];
  });

  this.query = query;
};
Query.prototype.set = function (key, val) {
  this.query[key] = val;
};
Query.prototype.get = function (key) {
  return this.query[key];
};
Query.prototype.toObject = function () {
  return this.query;
};
Query.prototype.toString = function () {
  if (jQuery.isEmptyObject(this.query))
    return "";
  
  var arr = [];
  jQuery.each(this.query, function (key, val) {
    arr.push(key+"="+val);
  });

  return arr.join("&");
};

/*
 * Created for filtering pagination through ajax.
 */
Filter = function (container, ajax_url) {
  this.ajax_url   = ajax_url;
  this.filters    = {};
  this.loader     = jQuery('<div id="loader" style="position: absolute; top: 0; left: 0; z-index: 99999;"><img src="/images/loader.gif" id="loader" />Loading</div>');
  this.loader.hide();
  this.container  = container;
  this.loaded     = false;
  this.timeout    = [];

  _this = this;
  jQuery(document).ready(function () {_this.load(); });
};
Filter.prototype.load = function () {
  this.data = jQuery("#"+this.container);
  jQuery(this.data).before(this.loader);

  // we keep our search query for reloads by appending it behind #
  if (window.location.href.indexOf("#") > -1)
  {
    var parts = window.location.href.split("#");
    var query = parts[parts.length-1];
    if (query.length > 0)
    {
      this.filters = new Query(query).toObject();
      this.reload();

      jQuery.each(this.filters, function (key, val) {
        var obj = $('#filter_'+key)[0];
        if (obj)
          if (obj.tagName.toLowerCase() == "select")
            jQuery.each(obj.options, function (k,elem) {
              if (elem.value == val)
              {
                obj.selectedIndex = k;
                return false;
              }
            });
          else if (obj.tagName.toLowerCase() == "input")
            obj.value = val;
      });
    }
  }

  this.loaded = true;
  if (this.onload instanceof Function)
    this.onload.call();
};
Filter.prototype.setFilter = function (key, val) {
  this.filters[key] = val;
};
Filter.prototype.getFilter = function (key) {
  return this.filters[key];
};
Filter.prototype.page = function (page) {
    if (page == this.getFilter('page'))
      return;

    this.setFilter('page', page);
    this.reload();
};
Filter.prototype.getFilterQuery = function () {
  var query = new Query();

  jQuery.each(this.filters,function (key, val) {
    query.set(key, escape(val));
  });
  return query.toString();
};
Filter.prototype.filter = function (obj) {
  this.setFilter('page', 1);
  this.setFilter(obj.attr('id').substring(7), obj.val());
};
Filter.prototype.reload = function () {
  var query = this.getFilterQuery();

  this.data.addClass('loading');
  this.data.html("<table width='100%'><thead><tr><th>Vraag & Aanbod</th></tr></thead><tbody><tr><td><br/><br/><br/><br/><br/><br/></td></tr></tbody></table>");
  this.data.show();

  var url = this.ajax_url+'?'+query;
  var data = this.data;

  jQuery(this.data).load(url, {}, function() {
    data.hide();
    data.removeClass('loading');
    data.slideDown('slow');


    window.location.href = "#"+query;
  });
};
Filter.prototype.addSelect = function (id) {
  var _this = this;
  if (!this.loaded)
  {
    jQuery(document).ready(function () { _this.addSelect(id); })
    return;
  }

  var select = jQuery('#filter_'+id);
  select.bind('change', function () { _this.filter(select); } );
};
Filter.prototype.doFilter = function () {
  this.reload();
};
Filter.prototype.addInput = function (id) {
  var _this = this;
  if (!this.loaded)
  {
    jQuery(document).ready(function () { _this.addInput(id); })
    return;
  }

  var input = jQuery('#filter_'+id);
  input.bind('keyup', function (evt) {
    if (input.val().length > 2  && input.val() != _this.getFilter(id))
    {
      // trick to have a little delay
      window.clearTimeout(_this.timeout[id]);
      _this.timeout[id] = window.setTimeout(function () {_this.filter(input);}, 200);
    }
    else if (_this.getFilter(id) && input.val().length <= 2)
    {
      _this.timeout[id] = window.setTimeout(function () {
        _this.setFilter(id, '');
        //_this.reload();
      }, 200);
    }
  } );
}
