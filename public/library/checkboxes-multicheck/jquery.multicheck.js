
/*
 * jQuery Multicheck Plugin v0.0.7
 * https://github.com/jurrick/jquery-multicheck
 *
 * Copyright 2014 Yury Snegirev
 * Released under the MIT license
 */
"use strict";
var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

(function($) {
  var MultiCheck;
  MultiCheck = (function() {
    var DEFAULTS;

    DEFAULTS = {
      label_wrap: ''
    };

    function MultiCheck(element, options) {
      this.getOptions = __bind(this.getOptions, this);
      this.$select = $(element);
      this.options = this.getOptions(options);
      this.init();
    }

    MultiCheck.prototype.init = function() {
      var $container, checkboxes;
      this.$select.hide();
      checkboxes = '';
      this.$select.children('option').each(function() {
        var $option, checkbox, label_class,title,selected;
        $option = $(this);
        label_class = '';
        selected='';
        title='';
        if ($option.data('label-class') != null) {
          label_class = " class=\"" + ($option.data('label-class')) + "\"";
        }
        if ($option.data('title') != null) {
          title = " title=\"" + ($option.data('title')) + "\"";
        }
        if ($option.attr('selected') != null) {
          selected = "checked='checked'";
        }
        checkbox = "<label" + label_class + " "+ title +">\n  <input type=\"checkbox\" "+" " +selected+ " value=\"" + ($option.val()) + "\" /> " + ($option.text()) + "\n</label>";
        return checkboxes += checkbox;
      });
      $container = $("<div class=\"multicheck-container\">\n  " + checkboxes + "\n</div>");
      $container = $container.insertAfter(this.$select);
      if (!!this.options['label_wrap']) {
        $container.children('label').wrap(this.options['label_wrap']);
      }
      return $container.on('change', 'input:checkbox', (function(_this) {
        return function(e) {
          var $ch, $option;
          $ch = $(e.target);
          $option = _this.$select.children("[value=\"" + ($ch.val()) + "\"]");
          return $option.prop({
            selected: $ch.is(':checked')
          });
        };
      })(this));
    };

    MultiCheck.prototype.getOptions = function(options) {
      return $.extend({}, DEFAULTS, options);
    };

    return MultiCheck;

  })();
  jQuery.fn.multicheck = function(options) {
    if (options == null) {
      options = null;
    }
    return this.each(function() {
      return new MultiCheck(this, options);
    });
  };
  return $.fn.multicheck.Constructor = MultiCheck;
})(jQuery);
