// Generated by CoffeeScript 1.6.3
(function() {
  var $, container, error, list, sizeCheck, success, vaild;

  $ = jQuery;

  container = null;

  list = function(url, horizon, vertical) {
    var del, input0, input1, input2, label0, label1, label2, panel;
    panel = $('<div class="ad-more-item"></div>');
    label0 = $('<label><span class="name">广告链接：</span></label>');
    input0 = $('<input />').attr({
      type: 'text',
      name: 'url[]',
      value: url || ''
    });
    label0.append(input0);
    label1 = $('<label><span class="name">横屏广告图片：</span></label>');
    input1 = $('<input />').attr({
      type: 'text',
      name: 'horizon[]',
      size: 20,
      readonly: true,
      value: horizon || ''
    });
    label1.append(input1);
    input1.imageInput(true);
    input1.bind('change', function() {
      return $(this).data('changed', true);
    });
    label2 = $('<label><span class="name">竖屏广告图片：</span></label>');
    input2 = $('<input />').attr({
      type: 'text',
      name: 'vertical[]',
      size: 20,
      readonly: true,
      value: vertical || ''
    });
    label2.append(input2);
    input2.imageInput(true);
    input2.bind('change', function() {
      return $(this).data('changed', true);
    });
    del = $('<img />').addClass('delete hand').attr({
      src: 'images/delete.gif'
    });
    del.one('click', {
      panel: panel
    }, function(event) {
      return event.data.panel.remove();
    });
    return container.append(panel.append(label0).append(label1).append(label2).append(del));
  };

  error = function(panel, msg) {
    panel.addClass('error');
    panel.append('<div class="error">' + msg + '</div>');
    panel.one('click', function() {
      panel.unbind().removeClass('error');
      return panel.find('.error').remove();
    });
    return ct.endLoading();
  };

  vaild = function(callback) {
    var changedElement, panel, ret;
    changedElement = new Array();
    ret = true;
    panel = null;
    $.each(container.find('.ad-more-item'), function(i, k) {
      var h, u, v;
      panel = $(k);
      u = panel.find('input[name="url[]"]');
      h = panel.find('input[name="horizon[]"]');
      v = panel.find('input[name="vertical[]"]');
      if (!(h.val() || v.val() || u.val())) {
        panel.remove();
        return true;
      }
      if (!(h.val() && v.val() && u.val())) {
        ret = false;
        return false;
      }
      if (h.data('changed')) {
        changedElement.push(h);
      }
      if (v.data('changed')) {
        return changedElement.push(v);
      }
    });
    if (ret) {
      return sizeCheck(changedElement, callback);
    } else {
      return error(panel, '缺少参数');
    }
  };

  sizeCheck = function(changedElement, callback) {
    var elm, img, src;
    if (changedElement.length === 0) {
      return callback();
    }
    elm = changedElement.pop();
    img = $('<img />');
    img.bind('load', {
      name: elm.attr('name'),
      panel: elm.parents('.ad-more-item'),
      changedElement: changedElement,
      callback: callback
    }, function(event) {
      var name;
      name = event.data.name;
      if (name === 'horizon[]') {
        if (!(this.width === horizonWidth && this.height === horizonHeight)) {
          error(event.data.panel, "横屏广告必须是" + horizonWidth + "x" + horizonHeight + "的图片");
          return false;
        }
      }
      if (name === 'vertical[]') {
        if (!(this.width === verticalWidth && this.height === verticalHeight)) {
          error(event.data.panel, "竖屏广告必须是" + verticalWidth + "x" + verticalHeight + "的图片");
          return false;
        }
      }
      return sizeCheck(event.data.changedElement, event.data.callback);
    });
    img.bind('error', {
      name: elm.attr('name'),
      panel: elm.parents('.ad-more-item'),
      changedElement: changedElement,
      callback: callback
    }, function(event) {
      error(event.data.panel, '图片加载失败');
      return false;
    });
    src = elm.val();
    if (src.substr(0, 4) !== 'http') {
      src = UPLOAD_URL + src;
    }
    return img.attr('src', src);
  };

  success = function() {
    var data, form;
    form = $('form');
    data = form.serializeObject();
    return $.post(form.attr('src'), data, function(res) {
      if (res.state) {
        return ct.ok('保存成功');
      } else {
        return ct.error(res.error || '保存失败');
      }
    }, 'json');
  };

  $(function() {
    var i, _i, _ref;
    container = $('#ad-more');
    if (data && data.url && data.url.length > 0) {
      for (i = _i = 0, _ref = data.url.length; 0 <= _ref ? _i < _ref : _i > _ref; i = 0 <= _ref ? ++_i : --_i) {
        list(data.url[i], data.horizon[i], data.vertical[i]);
      }
    } else {
      list();
    }
    $('#add').bind('click', function() {
      if ($('.ad-more-item').length < 5) {
        return list();
      }
    });
    return $('#submit').bind('click', function() {
      ct.startLoading(0, '正在校验图片尺寸');
      return vaild(function() {
        ct.endLoading();
        return success();
      });
    });
  });

}).call(this);