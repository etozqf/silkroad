/*!
 * jQuery Browser Plugin 0.0.7
 * https://github.com/gabceb/jquery-browser-plugin
 *
 * Original jquery-browser code Copyright 2005, 2013 jQuery Foundation, Inc. and other contributors
 * http://jquery.org/license
 *
 * Modifications Copyright 2014 Gabriel Cebrian
 * https://github.com/gabceb
 *
 * Released under the MIT license
 *
 * Date: 12-12-2014
 */
/*global window: false */

(function (factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], function($) {
      factory($);
    });
  } else if (typeof module === 'object' && typeof module.exports === 'object') {
    // Node-like environment
    module.exports = factory(require('jquery'));
  } else {
    // Browser globals
    factory(window.jQuery);
  }
}(function(jQuery) {
  "use strict";

  function uaMatch( ua ) {
    // If an UA is not provided, default to the current browser UA.
    if ( ua === undefined ) {
      ua = window.navigator.userAgent;
    }
    ua = ua.toLowerCase();

    var match = /(edge)\/([\w.]+)/.exec( ua ) ||
        /(opr)[\/]([\w.]+)/.exec( ua ) ||
        /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(version)(applewebkit)[ \/]([\w.]+).*(safari)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+).*(version)[ \/]([\w.]+).*(safari)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("trident") >= 0 && /(rv)(?::| )([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];

    var platform_match = /(ipad)/.exec( ua ) ||
        /(ipod)/.exec( ua ) ||
        /(iphone)/.exec( ua ) ||
        /(kindle)/.exec( ua ) ||
        /(silk)/.exec( ua ) ||
        /(android)/.exec( ua ) ||
        /(windows phone)/.exec( ua ) ||
        /(win)/.exec( ua ) ||
        /(mac)/.exec( ua ) ||
        /(linux)/.exec( ua ) ||
        /(cros)/.exec( ua ) ||
        /(playbook)/.exec( ua ) ||
        /(bb)/.exec( ua ) ||
        /(blackberry)/.exec( ua ) ||
        [];

    var browser = {},
        matched = {
          browser: match[ 5 ] || match[ 3 ] || match[ 1 ] || "",
          version: match[ 2 ] || match[ 4 ] || "0",
          versionNumber: match[ 4 ] || match[ 2 ] || "0",
          platform: platform_match[ 0 ] || ""
        };

    if ( matched.browser ) {
      browser[ matched.browser ] = true;
      browser.version = matched.version;
      browser.versionNumber = parseInt(matched.versionNumber, 10);
    }

    if ( matched.platform ) {
      browser[ matched.platform ] = true;
    }

    // These are all considered mobile platforms, meaning they run a mobile browser
    if ( browser.android || browser.bb || browser.blackberry || browser.ipad || browser.iphone ||
      browser.ipod || browser.kindle || browser.playbook || browser.silk || browser[ "windows phone" ]) {
      browser.mobile = true;
    }

    // These are all considered desktop platforms, meaning they run a desktop browser
    if ( browser.cros || browser.mac || browser.linux || browser.win ) {
      browser.desktop = true;
    }

    // Chrome, Opera 15+ and Safari are webkit based browsers
    if ( browser.chrome || browser.opr || browser.safari ) {
      browser.webkit = true;
    }

    // IE11 has a new token so we will assign it msie to avoid breaking changes
    // IE12 disguises itself as Chrome, but adds a new Edge token.
    if ( browser.rv || browser.edge ) {
      var ie = "msie";

      matched.browser = ie;
      browser[ie] = true;
    }

    // Blackberry browsers are marked as Safari on BlackBerry
    if ( browser.safari && browser.blackberry ) {
      var blackberry = "blackberry";

      matched.browser = blackberry;
      browser[blackberry] = true;
    }

    // Playbook browsers are marked as Safari on Playbook
    if ( browser.safari && browser.playbook ) {
      var playbook = "playbook";

      matched.browser = playbook;
      browser[playbook] = true;
    }

    // BB10 is a newer OS version of BlackBerry
    if ( browser.bb ) {
      var bb = "blackberry";

      matched.browser = bb;
      browser[bb] = true;
    }

    // Opera 15+ are identified as opr
    if ( browser.opr ) {
      var opera = "opera";

      matched.browser = opera;
      browser[opera] = true;
    }

    // Stock Android browsers are marked as Safari on Android.
    if ( browser.safari && browser.android ) {
      var android = "android";

      matched.browser = android;
      browser[android] = true;
    }

    // Kindle browsers are marked as Safari on Kindle
    if ( browser.safari && browser.kindle ) {
      var kindle = "kindle";

      matched.browser = kindle;
      browser[kindle] = true;
    }

     // Kindle Silk browsers are marked as Safari on Kindle
    if ( browser.safari && browser.silk ) {
      var silk = "silk";

      matched.browser = silk;
      browser[silk] = true;
    }

    // Assign the name and platform variable
    browser.name = matched.browser;
    browser.platform = matched.platform;
    return browser;
  }

  // Run the matching process, also assign the function to the returned object
  // for manual, jQuery-free use if desired
  window.jQBrowser = uaMatch( window.navigator.userAgent );
  window.jQBrowser.uaMatch = uaMatch;

  // Only assign to jQuery.browser if jQuery is loaded
  if ( jQuery ) {
    jQuery.browser = window.jQBrowser;
  }

  return window.jQBrowser;
}));


// Generated by CoffeeScript 1.4.0
(function() {
  var formSubmit;

  formSubmit = function(form) {
    var i, _i, _len, _ref;
    _ref = $('input.required');
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      i = _ref[_i];
      try {
        if (i.value.length < 2) {
          $(i).parent('.ov').children('.info').html('不能为空');
          return false;
        }
      } catch (exc) {
        return false;
      }
    }
    if (form.email && form.email.value.length > 0) {
      if (!/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(form['email'].value)) {
        $(form['email']).parent('.ov').children('.info').html('email格式不正确');
        return false;
      }
    }
    if (form.phone && form.phone.value.length > 0) {
      if (!/[\d\+\.\(\)]/.test(form['phone'].value)) {
        $(form['phone']).parent('.ov').children('.info').html('电话格式不正确');
        return false;
      }
    }
    return true;
  };

  $(function() {
    var attachmentPicTemplate, attachmentVideoTemplate, editor, i, imgEmbed, _i, _len, _ref;
    attachmentPicTemplate = $('#attachment_pic_template').html();
    attachmentVideoTemplate = $('#attachment_video_template').html();
    new Uploader(document.getElementById('upload_pic'), {
      script: '?app=baoliao&controller=index&action=upload',
      multi: false,
      fileExt: '*.png;*.jpg;*.jpeg;*.gif',
      fileDesc: 'png|jpg|gif',
      fieldName: 'Filedata',
      sizeLimit: 1048576,
      jsonType: 1,
      uploadComplete: function(response) {
        var html;
        if (response) {
          $('#upload_info').empty();
          html = $(attachmentPicTemplate.replace('{src}', UPLOAD_URL + response.data).replace('{value}', response.data));
          $('#attachment_panel').append(html);
          html.find('.delete').bind('click', function(event) {
            $(event.currentTarget).parent('.attachment-img').remove();
            return imgEmbed.show();
          });
          if (maxUpload > 0 && $('#attachment_panel').children('.attachment-img').length === maxUpload) {
            return imgEmbed.hide();
          }
        } else {
          return $('#upload_info').html('图片上传失败');
        }
      }
    });
    $('#upload_video').videoUploader(function(response) {
      var data, html;
      data = eval("(" + response + ")");
      if (!data || data.length === 0) {
        return;
      }
      response = encodeURI(response);
      html = $(attachmentVideoTemplate.replace('{thumb}', data.thumb).replace('{value}', response));
      $('#attachment_video').remove();
      $('#attachment_panel').prepend(html);
      return html.find('.delete').bind('click', function(event) {
        return $('#attachment_video').remove();
      });
    });
    imgEmbed = $('#upload_pic').children('embed');
    $('#post_form').bind('submit', function(form) {
      return formSubmit(form.currentTarget);
    });
    _ref = $('input.required');
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      i = _ref[_i];
      $(i).bind('blur', function(event) {
        var info, obj;
        obj = $(event.currentTarget);
        info = obj.parent().find('.info');
        if (obj.val().length < 2) {
          return info.html('不能为空');
        } else {
          return info.html('');
        }
      });
    }
    editor = new $.Rte($('#content'), {
      disable: ['italic', 'fontsize', 'fontname', 'forecolor', 'insertunorderedlist', 'insertorderedlist', 'justify', 'createlink', 'insertimage', 'html']
    });
    return $('#content').val($.trim(editor.getCode()));
  });

}).call(this);
