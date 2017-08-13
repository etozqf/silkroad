(function() {
  var Account, Home, Menu, Setting, Template, countObject, error, getFirst,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  Account = (function() {
    function Account() {}

    Account.Resigner = (function() {
      function Resigner() {}

      Resigner.init = function() {
        var renderForm, renderList, renderTypeSelector;
        Template.depend([IMG_URL + 'js/lib/cmstop.table.js']).then(function() {
          return Template.render('&controller=account');
        }).then((function(_this) {
          return function(container) {
            _this.container = container;
            renderList();
            renderTypeSelector();
            return renderForm();
          };
        })(this)).otherwise(function(err) {
          return error(err);
        });
        renderTypeSelector = (function(_this) {
          return function() {
            return $('#account-type > li').bind('click', function(event) {
              var elm, type;
              elm = $(event.currentTarget);
              if (elm.hasClass('active')) {
                return false;
              }
              $('#account-type > li').removeClass('active');
              elm.addClass('active');
              type = elm.attr('data-type');
              _this.container.find('[name="account[type]"]').val(type);
              _this.container.find("[data-role]").hide();
              _this.container.find("[data-role=\"" + type + "-only\"]").show();
              return _this.container.find('#account-list').trigger('turn', type);
            }).eq(0).trigger('click');
          };
        })(this);
        renderForm = (function(_this) {
          return function() {
            _this.container.find('#account-add-form').ajaxForm(function(req) {
              var accountSuccess;
              if (!req.state) {
                return ct.error(req.error);
              }
              if (req.data.state === 'enable') {
                accountList[req.data.id] = req.data;
                _this.container.find('#account-add-form').trigger('update');
                accountSuccess = $('#account-success');
                $('[data-id]').html(req.data.id);
                accountSuccess.find('[data-btn="edit"]').bind('click', {
                  id: req.data.id
                }, function(event) {
                  accountSuccess.hide();
                  return _this.edit(event.data.id);
                });
                accountSuccess.find('[data-btn="close"]').bind('click', {
                  id: req.data.id
                }, function(event) {
                  accountSuccess.hide();
                  return Home.init(event.data.id);
                });
                accountSuccess.show();
                return;
              }
              _this.container.find('#account-add-form')[0].reset();
              return _this.container.find('#account-list').trigger('tableLoad');
            }, null, function() {
              if (countObject(accountList) >= 2) {
                ct.error('最多只允许创建两个微信账号');
                return false;
              }
            });
            _this.container.find('#account-tick').bind('click', function(event) {
              return _this.container.find('#account-add-form').trigger('submit');
            });
            _this.container.find('#account-add-form').bind('update', function(event) {
              var form;
              form = $(event.target);
              if (countObject(accountList) >= 2) {
                return form.hide();
              } else {
                return form.show();
              }
            });
            return _this.container.find('#account-add-form').trigger('update');
          };
        })(this);
        return renderList = (function(_this) {
          return function() {
            var table, tableElement;
            tableElement = _this.container.find('#account-list');
            table = new ct.table(tableElement, {
              rowCallback: function(id, tr) {
                if (table.type && table.type !== tr.attr('data-type')) {
                  return tr.remove();
                }
                (function(elm) {
                  var state, stateName;
                  state = elm.attr('data-state');
                  stateName = {
                    'enable': '启用',
                    'disable': '禁用',
                    'configured': '异常'
                  };
                  if (state === 'configured') {
                    elm.attrTips('tips');
                  }
                  return elm.html(stateName[state]);
                })(tr.find('.state'));
                tr.find('.wechat-account-name').attrTips('tips');
                tr.find('.enter').bind('click', {
                  id: id
                }, function(event) {
                  id = event.data.id;
                  if (window.accountList[id] && window.accountList[id].state === 'enable') {
                    return Home.init(id);
                  }
                });
                tr.find('.edit').bind('click', {
                  id: id
                }, function(event) {
                  return _this.edit(event.data.id);
                });
                return tr.find('.delete').bind('click', {
                  id: id
                }, function(event) {
                  return _this["delete"](event.data.id);
                });
              },
              jsonLoaded: function(json) {
                var k, v, _ref, _results;
                if (!json.state) {
                  return;
                }
                window.accountList = {};
                _ref = json.data;
                _results = [];
                for (k in _ref) {
                  v = _ref[k];
                  _results.push(window.accountList[v.id] = v);
                }
                return _results;
              },
              template: $('#account-list-template').html(),
              baseUrl: BaseUrl + 'ls'
            });
            _this.container.find('#account-list').bind('tableLoad', {
              table: table
            }, function(event) {
              return event.data.table.load();
            });
            return tableElement.bind('turn', function(event, type) {
              table.type = type;
              return table.load();
            });
          };
        })(this);
      };

      Resigner.edit = function(id, container) {
        if (container == null) {
          container = Resigner.container;
        }
        return ct.formDialog({
          title: '编辑微信账号',
          width: 280
        }, BaseUrl + ("edit&controller=account&id=" + id), function(req) {
          if (!req.state) {
            ct.error(req.error || '编辑失败');
            return false;
          }
          container.find('#account-list').trigger('tableLoad');
          return true;
        });
      };

      Resigner["delete"] = function(id) {
        return ct.confirm('确认要删除吗?', function() {
          return $.post(BaseUrl + 'rm&controller=account', {
            id: id
          }, function(req) {
            if (!req.state) {
              return ct.error(req.error || '删除失败');
            }
            delete accountList[id];
            Resigner.container.find('#account-add-form').trigger('update');
            return Resigner.container.find('#account-list').trigger('tableLoad');
          });
        });
      };

      return Resigner;

    })();

    Account.change = function(id) {
      return ct.formDialog({
        title: '切换微信账号',
        width: 280
      }, BaseUrl + ("change&controller=account&id=" + id), null, null, function(form, dialog) {
        id = form.serializeObject().id;
        if (id !== Home.id) {
          Home.init(id);
        }
        dialog.dialog('close');
        return false;
      });
    };

    return Account;

  })();

  Home = (function() {
    function Home() {}

    Home.init = function(id) {
      if (!accountList[id]) {
        if (!countObject(accountList)) {
          return Account.Resigner.init();
        }
        id = getFirst(accountList).id;
      }
      this.id = id;
      return Template.depend([IMG_URL + 'js/lib/jquery.cookie.js']).then(function() {
        return Template.render('home');
      }).then((function(_this) {
        return function(container) {
          _this.container = container;
          Home.menu(id, container);
          return $.cookie(COOKIE_PRE + 'wechat.account_id', id);
        };
      })(this)).otherwise(function(err) {
        return error(err);
      });
    };

    Home.menu = function(id, container) {
      var panel, type;
      panel = container.find('#menu');
      type = accountList[id].type;
      panel.empty();
      return Menu.children(type)[0].event();
    };

    Home.account = function(id, container) {
      var accountAdd, accountChange, accountListPanel, accountManage, accountName, hasAnother, i, item;
      accountName = $('#account-name');
      accountAdd = $('#account-add');
      accountChange = $('#account-change');
      accountManage = $('#account-manage');
      accountListPanel = $('#account-list');
      accountName.html(accountList[id].name);
      accountAdd.hide();
      accountChange.hide();
      if (countObject(accountList) < 2) {
        accountAdd.show();
      } else {
        hasAnother = false;
        for (i in accountList) {
          item = accountList[i];
          if (item.state === 'enable' && i !== id) {
            hasAnother = true;
          }
        }
        if (hasAnother) {
          accountChange.show();
        } else {
          accountAdd.show();
        }
      }
      accountAdd.unbind().bind('click', function() {
        return Account.Resigner.init();
      });
      accountChange.unbind().bind('click', function() {
        return Account.change(Home.id);
      });
      accountManage.unbind().bind('click', {
        container: container
      }, function(event) {
        return Account.Resigner.init();
      });
      return accountListPanel.unbind().bind('tableLoad', {
        id: id,
        container: container
      }, function(event) {
        id = event.data.id;
        return $.getJSON(BaseUrl + 'ls', function(req) {
          var _ref, _results;
          if (!req.state) {
            return;
          }
          _ref = req.data;
          _results = [];
          for (i in _ref) {
            item = _ref[i];
            accountList[item.id] = item;
            _results.push(Home.account(event.data.id, event.data.container));
          }
          return _results;
        });
      });
    };

    return Home;

  })();

  Menu = (function() {
    var template;

    function Menu() {}

    template = null;

    Menu.children = function(type) {
      var i, menu, menus;
      menus = new Array;
      for (i in Menu) {
        menu = Menu[i];
        if (!(menu !== this)) {
          continue;
        }
        if (!menu.type || menu.type.indexOf(type) === -1) {
          continue;
        }
        menus.push(menu);
      }
      return menus.sort(function(a, b) {
        if (a.sort > b.sort) {
          return 1;
        } else {
          return -1;
        }
      });
    };

    Menu.renderTop = function() {
      var menu, menuPanel, topMenu, _i, _len, _ref;
      menuPanel = this.container.find('#nav-menu');
      if (!menuPanel.length) {
        return;
      }
      _ref = Menu.children(accountList[Home.id].type);
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        menu = _ref[_i];
        topMenu = menu.__super__.top.call(menu);
        if (menu === this) {
          topMenu.addClass('active');
        }
        menuPanel.append(topMenu);
      }
      return Home.account(Home.id, this.container);
    };

    Menu.prototype.home = function() {
      var elm;
      if (template == null) {
        template = $('#menuTemplate').html();
      }
      elm = $(template);
      elm.html(this.title);
      elm.addClass("wechat-color-" + this.color);
      return elm.bind('click', this.event || new Function);
    };

    Menu.prototype.top = function() {
      var elm;
      elm = $('<li href="javascript:;"></li>');
      elm.append("<a href=\"javascript:;\">" + this.title + "</a>");
      elm.bind('click', (function(_this) {
        return function() {
          if (typeof _this.event === 'function') {
            return _this.event.call(_this);
          }
        };
      })(this));
      return elm;
    };

    return Menu;

  })();

  Setting = (function() {
    var cache;

    function Setting() {}

    cache = new Object;

    Setting.get = function(setting, params, focus) {
      var deferred;
      if (params == null) {
        params = null;
      }
      if (focus == null) {
        focus = false;
      }
      deferred = window.when.defer();
      if (cache[setting] && !focus) {
        deferred.resolve(cache[setting]);
      } else {
        $.getJSON(BaseUrl + setting, params, function(req) {
          if (!req.state) {
            return deferred.reject(req.error);
          }
          cache[setting] = req.data;
          return deferred.resolve(cache[setting]);
        });
      }
      return deferred.promise;
    };

    Setting.rm = function(setting) {
      delete cache[setting];
      return Setting;
    };

    return Setting;

  })();

  Template = (function() {
    var cache;

    function Template() {}

    cache = new Object;

    Template.container = $('#container');

    Template.depend = function(urls) {
      var deferreds, url, _fn, _i, _len;
      deferreds = new Array;
      _fn = function() {
        var deferred;
        deferred = window.when.defer();
        cmstop.fet(url, function() {
          return setTimeout(function() {
            return deferred.resolve();
          }, 0);
        });
        return deferreds.push(deferred.promise);
      };
      for (_i = 0, _len = urls.length; _i < _len; _i++) {
        url = urls[_i];
        _fn();
      }
      return window.when.all(deferreds);
    };

    Template.render = function(template, params, revoke) {
      var deferred;
      deferred = window.when.defer();
      if (typeof Template.revoke === 'function') {
        Template.revoke.call(Template, Template.container);
      }
      Template.revoke = revoke;
      (function(template, params, callback) {
        var res;
        if (cache[template] && cache[template].length > 0) {
          return callback(cache[template]);
        }
        return res = $.get(BaseUrl + template, params, (function(_this) {
          return function(req) {
            if (!req.state) {
              return deferred.reject(req.error);
            }
            cache[template] = req.data;
            return callback(req.data);
          };
        })(this));
      })(template, params, function(html) {
        Template.container.empty().html(html);
        return deferred.resolve(Template.container);
      });
      return deferred.promise;
    };

    return Template;

  })();

  countObject = function(obj) {
    var count, k, v;
    count = 0;
    for (k in obj) {
      v = obj[k];
      count++;
    }
    return count;
  };

  getFirst = function(obj) {
    var k, v;
    for (k in obj) {
      v = obj[k];
      return v;
    }
  };

  error = function(string) {
    if (string && /\.loc$/.test(location.host)) {
      return console.error(string);
    }
  };

  $((function(_this) {
    return function() {
      var id;
      if (_this.accountList && countObject(_this.accountList)) {
        id = $.cookie(COOKIE_PRE + 'wechat.account_id');
        if (!(id && _this.accountList[id])) {
          id = getFirst(_this.accountList).id;
        }
        return Home.init(id);
      } else {
        return Account.Resigner.init();
      }
    };
  })(this));

  Menu.Material = (function(_super) {
    __extends(Material, _super);

    function Material() {
      return Material.__super__.constructor.apply(this, arguments);
    }

    Material.type = ['service', 'subscribe'];

    Material.title = '素材管理';

    Material.color = 'orange';

    Material.sort = 1;

    Material.event = function() {
      var depend, init, renderSearch, renderSubMenu, revoke;
      Template.depend(['uploader/cmstop.uploader.js', 'js/cmstop.filemanager.js', IMG_URL + 'js/lib/json2.js']).then((function(_this) {
        return function() {
          return Template.render('&controller=material', null, revoke);
        };
      })(this)).then((function(_this) {
        return function(container) {
          _this.container = container;
          init();
          Menu.renderTop.call(_this);
          renderSubMenu();
          return renderSearch();
        };
      })(this)).otherwise(function(err) {
        return error(err);
      });
      init = (function(_this) {
        return function() {
          var scrollIng;
          _this.container.find('#material').css('height', document.documentElement.clientHeight + 'px');
          document.documentElement.scrollTop = 0;
          scrollIng = false;
          return $(window).bind('scroll.wechat', function() {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 5) {
              if (scrollIng) {
                clearTimeout(scrollIng);
              }
              return scrollIng = setTimeout(function() {
                return $(window).trigger('loadMore');
              }, 100);
            }
          });
        };
      })(this);
      revoke = (function(_this) {
        return function() {
          return $(window).unbind('scroll.wechat');
        };
      })(this);
      renderSubMenu = (function(_this) {
        return function() {
          var materialCount, panel, subMenuTemplate;
          panel = $('#submenu');
          subMenuTemplate = $('#submenu-template').html();
          materialCount = {};
          return Setting.get('count&controller=material').then(function(data) {
            var elm, first, item, m, menu, _i, _j, _len, _len1, _ref;
            for (_i = 0, _len = data.length; _i < _len; _i++) {
              item = data[_i];
              materialCount[item.type] = item.count;
            }
            _ref = ['List', 'Picture', 'Voice', 'Video'];
            for (_j = 0, _len1 = _ref.length; _j < _len1; _j++) {
              m = _ref[_j];
              menu = Menu.Material[m];
              if (accountList[Home.id].type === 'subscribe') {
                if (menu.serviceOnly) {
                  continue;
                }
              }
              depend(menu);
              elm = $(subMenuTemplate);
              elm.attr('data-cmd', m);
              elm.html("" + menu.title + "(" + (materialCount[m.toLowerCase()] || 0) + ")");
              if (typeof Menu.Material[m].ready === 'function') {
                Menu.Material[m].ready();
              }
              elm.bind('click', {
                cmd: m,
                elm: elm
              }, function(event) {
                var buttonNums, _panel;
                if (event.data.elm.hasClass('s_5')) {
                  return;
                }
                event.data.elm.parent().children('.s_5').removeClass('s_5');
                event.data.elm.addClass('s_5');
                _panel = _this.container.find("#" + event.data.cmd + "-panel");
                _panel.parent().children('.wechat-material-panel').hide();
                _panel.show();
                $(window).trigger('revokeMaterial');
                $('#search').trigger('reset');
                Menu.Material[event.data.cmd].init.call(_this, _panel);
                buttonNums = _panel.find('.wechat-add-buttons > span').length;
                return $('#search').css('left', 10 + buttonNums * 54 + 'px');
              });
              elm.bind('upper', function() {
                var count, string;
                elm = $(this);
                string = elm.html();
                count = elm.html().match(/\d+/)[0] >>> 0;
                return elm.html(string.replace(/\d+/, count + 1));
              });
              elm.bind('lower', function() {
                var count, string;
                elm = $(this);
                string = elm.html();
                count = elm.html().match(/\d+/)[0] >>> 0;
                return elm.html(string.replace(/\d+/, count - 1));
              });
              panel.append(elm);
            }
            first = panel.children('[data-cmd]:first');
            return first.trigger('click');
          }).otherwise(function(err) {
            return error(err);
          });
        };
      })(this);
      renderSearch = (function(_this) {
        return function() {
          return Template.depend([IMG_URL + 'js/lib/cmstop.datepicker.js', IMG_URL + 'js/lib/datepicker/style.css']).then(function() {
            var search;
            $('.input_calendar').DatePicker({
              'format': 'yyyy-MM-dd'
            });
            search = $('#search');
            search.bind('submit', function(event) {
              $(window).trigger('search', $(event.target).serializeObject());
              return false;
            });
            return search.find('.search > a').bind('click', function() {
              return search.trigger('submit');
            });
          }).otherwise(function(err) {
            return error(err);
          });
        };
      })(this);
      return depend = (function(_this) {
        return function(menu) {
          if (!(menu.depend && menu.depend.length)) {
            return $(window).trigger("" + menu.name + "Ready");
          }
          return Template.depend(menu.depend).then(function() {
            return $(window).trigger("" + menu.name + "Ready");
          }).otherwise(function(err) {
            return error(err);
          });
        };
      })(this);
    };

    return Material;

  })(Menu);

  Menu.Reply = (function(_super) {
    __extends(Reply, _super);

    function Reply() {
      return Reply.__super__.constructor.apply(this, arguments);
    }

    Reply.type = ['service', 'subscribe'];

    Reply.title = '自动回复';

    Reply.color = 'lemon';

    Reply.sort = 2;

    Reply.name = 'Reply';

    Reply.event = function() {
      var depend, init, renderSearch, renderSubMenu, revoke;
      Template.depend([IMG_URL + 'js/lib/cmstop.table.js', IMG_URL + 'js/lib/jquery.pagination.js', IMG_URL + 'js/lib/pagination/style.css']).then((function(_this) {
        return function() {
          return Template.render('&controller=reply&account=' + Home.id, null, revoke);
        };
      })(this)).then((function(_this) {
        return function(container) {
          _this.container = container;
          init();
          Menu.renderTop.call(_this);
          renderSubMenu();
          return renderSearch();
        };
      })(this)).otherwise(function(err) {
        return error(err);
      });
      init = (function(_this) {
        return function() {};
      })(this);
      revoke = (function(_this) {
        return function() {};
      })(this);
      renderSubMenu = (function(_this) {
        return function() {
          var elm, m, menu, panel, subMenuTemplate, _i, _len, _ref;
          panel = $('#submenu');
          subMenuTemplate = $('#submenu-template').html();
          panel.empty();
          _ref = ['Route', 'Index'];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            m = _ref[_i];
            menu = Menu.Reply[m];
            depend(menu);
            elm = $(subMenuTemplate);
            elm.html(menu.title);
            menu.ready();
            elm.bind('click', {
              menu: menu,
              name: m,
              elm: elm
            }, function(event) {
              var target, _panel;
              if (event.data.elm.hasClass('s_5')) {
                return;
              }
              event.data.elm.parent().children('.s_5').removeClass('s_5');
              event.data.elm.addClass('s_5');
              target = $(event.target);
              menu = event.data.menu;
              _panel = _this.container.find("#" + menu.name + "Panel");
              _panel.parent().children().hide();
              _panel.show();
              return menu.init(_panel);
            });
            panel.append(elm);
          }
          return panel.children(':first').trigger('click');
        };
      })(this);
      renderSearch = (function(_this) {
        return function() {
          return Template.depend([IMG_URL + 'js/lib/cmstop.datepicker.js', IMG_URL + 'js/lib/datepicker/style.css']).then(function() {
            var search;
            $('.input_calendar').DatePicker({
              'format': 'yyyy-MM-dd'
            });
            search = $('#search');
            search.bind('submit', function(event) {
              $(window).trigger('search', $(event.target).serializeObject());
              return false;
            });
            return search.find('.search > a').bind('click', function() {
              return search.trigger('submit');
            });
          }).otherwise(function(err) {
            return error(err);
          });
        };
      })(this);
      return depend = (function(_this) {
        return function(menu) {
          if (!(menu.depend && menu.depend.length)) {
            return $(window).trigger("" + menu.name + "Ready");
          }
          return Template.depend(menu.depend).then(function() {
            return $(window).trigger("" + menu.name + "Ready");
          }).otherwise(function(err) {
            return error(err);
          });
        };
      })(this);
    };

    return Reply;

  })(Menu);

  Menu.Material.List = (function() {
    var Lists;

    function List() {}

    List.title = '图文';

    List.color = 'brightred';

    List.depend = ['apps/wechat/js/waterfall.js', 'apps/system/js/datapicker.js', 'imageEditor/cmstop.imageEditor.js', IMG_URL + 'apps/special/css/list.css'];

    List.serviceOnly = false;

    List.name = 'List';

    Lists = (function() {
      var addingQueue, addingState, listInner, search, _List;

      function Lists() {}

      Lists.panel = null;

      Lists.list = [];

      search = {};

      addingQueue = [];

      addingState = 'ready';

      listInner = null;

      Lists.init = function(panel) {
        var item, min, _i, _len, _ref;
        Lists.search();
        if (Lists.panel) {
          min = 0;
          _ref = Lists.list;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            item = _ref[_i];
            if (min === 0 || min > item.id) {
              min = item.id;
            }
          }
          Lists.ls(min);
          if (countObject(search) > 0) {
            return $(window).trigger('search');
          }
        } else {
          $(window).one('ListReady', function() {
            Lists.panel = panel;
            Lists.ls();
            Lists.select();
            $(window).trigger('loadMore');
            listInner = $('#list-inner');
            return listInner.BlocksIt({
              numOfCol: 3,
              offsetX: 9,
              offsetY: 10,
              blockElement: '.wechat-list-box'
            });
          });
          if ($.fn.BlocksIt) {
            return $(window).trigger('ListReady');
          }
        }
      };

      Lists.ls = function(offset) {
        var lineCount, noMore, size;
        if (offset == null) {
          offset = 0;
        }
        lineCount = 5;
        noMore = false;
        size = parseInt((document.documentElement.clientHeight - 90) / 190, 10) * lineCount;
        return $(window).unbind('loadMore').bind('loadMore', function() {
          var where;
          if (noMore) {
            return;
          }
          where = $.extend({
            type: 'list',
            offset: offset,
            size: size - (Lists.list.length + 1) % lineCount
          }, search);
          return $.get(BaseUrl + 'ls&controller=material', where, function(req) {
            var item, _i, _len, _ref, _results;
            if (!req.state) {
              return ct.error(req.error);
            }
            if (req.data.length === 0) {
              noMore = true;
              return;
            }
            _ref = req.data;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              item = _ref[_i];
              if (!(typeof item === 'object' && typeof item.title === 'string')) {
                continue;
              }
              item.id = Number(item.id);
              if (!offset || offset > item.id) {
                offset = item.id;
              }
              _results.push(Lists.list.push(new _List(item.id, item.title, item.content, item.created)));
            }
            return _results;
          });
        });
      };

      Lists.select = function(editModel) {
        var typeName, url, _dialogReady, _dialogSubmit, _select;
        typeName = {
          single: '单图文',
          multiple: '多图文'
        };
        url = BaseUrl + 'add_list&controller=material&type=';
        _dialogReady = function(dialog, type, editModel) {
          
					function createItem(json, i){
						if (dialog.find('.list-item').length > 9) {
							return false;
						}
						return $(
						'<div class="list-item">'+
							'<div class="list-ctrl">'+
								'<span class="num">'+i+'</span>'+
								'<a class="ctrl pick" title="选取数据"></a>'+
								'<a class="ctrl up" title="上移"></a>'+
								'<a class="ctrl down" title="下移"></a>'+
								'<a class="ctrl remove" title="移除"></a>'+
							'</div>'+
							'<div class="list-thumb">'+
								'<div class="list-img">'+
									'<img src="'+(json.thumb||'')+'" />'+
									'<p><span class="edit" title="编辑图片">编辑</span><span class="up" title="上传图片">上传</span><span class="pick" title="选取图片">选择</span></p>'+
								'</div>'+
							'</div>'+
							'<div class="list-detail">'+
								'<input type="text" class="title" name="list[title][]" value="'+(json.title||'')+'" />'+
								'<input type="text" class="url" name="list[url][]"  value="'+(json.url||'')+'" />'+
								'<input type="text" class="thumb" name="list[thumb][]" value="'+(json.thumb||'')+'" />'+
								'<textarea class="description" name="list[description][]">'+(json.description||'')+'</textarea>'+
								'<input type="hidden" class="contentid" name="list[contentid][]" value="'+(json.contentid||'')+'">'+
							'</div>'+
							'<div class="clear"></div>'+
						'</div>');
					}
					function fillItem(item, json) {
						var c = item.find('input,img,textarea');
						c.filter('img,[name="list[thumb][]"]')
						json.title && c.filter('[name="list[title][]"]').val(json.title);
						json.url && c.filter('[name="list[url][]"]').val(json.url);
						json.description && c.filter('[name="list[description][]"]').val(json.description);
						json.contentid && c.filter('[name="list[contentid][]"]').val(json.contentid);
						json.thumb && (c.filter('[name="list[thumb][]"]').val(json.thumb), c.filter('img').attr('src', json.thumb));
						if (json.thumb) {
							img = item.find('.list-img>img');
							img.bind('load', function() {
								if (img[0].width < img[0].height) {
									img[0].height > 90 && (img.attr('height', 90));
								} else {
									img[0].width > 90 && (img.attr('width', 90));
								}
								
								$('<span class="edit" title="编辑图片">编辑</span>').prependTo(img.next()).bind('click', {'img': img}, function(event){
									var img = event.data.img;
									ct.editImage(img.attr('src'), function(json) {
							      		img.attr('src', UPLOAD_URL + json.file);
							        });
								});
							});
							img.attr({
								'src': json.thumb
							});
							img.removeAttr('width').removeAttr('height');
							img.show();
						}
					}
					var ctrlFunc = {
						up:function(item){
							moveItem(item, -1);
						},
						down:function(item){
							moveItem(item, 1);
						},
						remove:function(item){
							var adder = item.next();
							item.slideUp(160,function(){
								item.remove();
								adder.nextAll('.list-item').each(function(){
									var s = $('span.num', this);
									s.text(parseInt(s.text()) - 1);
								});
								adder.remove();
							});
						},
						pick:function(item){
							$.datapicker({
								picked:function(items){
									fillItem(item, items[0]);
								},
								multiple:false,
								url: '?app=system&controller=port&action=picker&modelid=1,2,4,7,6,8,9'
							});
						}
					};
					function prepareItem(item) {
						item.after(createAdder());
						item.find('a.ctrl').click(function(){
							ctrlFunc[this.className.substr(5)](item);
						});
						item.find(':text,textarea').focus(function(){
							this.style.cssText = 'background-image:none';
							$(this).css('background-image', 'none');
						}).blur(function(){
							this.value == '' && ( this.style.cssText = '');
							this.value == '' && $(this).css('background-image', '');
						}).each(function(){
							this.value != '' && (this.style.cssText = 'background-image:none');
							this.value != '' && $(this).css('background-image', 'none');
						});
						
						var img_v = item.find('input[name="list[thumb][]"]'),
							imgs = item.find('div.list-img').find('img,span'),
							editbtn = imgs.filter('.edit').hide(),
							img = imgs.filter('img').hide(), im = img[0];
						function used(src){
							var t = new Image;
							t.onload = function() {
								img.removeAttr('width').removeAttr('height');
								t.height > t.width
						    		? (t.height > 90 && (im.height = 90))
						    		: t.width > 90 && (im.width = 90);
								editbtn.show();
						    	im.src = t.src;
								img.show();
							}
							t.onerror = function() {
								im.src = '';
							};
							t.src = src;
						}
						img_v.change(function(){
							used(this.value);
						});
						im.src && used(im.src);
						editbtn.click(function(){
					        ct.editImage(img.attr('src'), function(json) {
					            img_v.val(UPLOAD_URL + json.file);
					            used(UPLOAD_URL + json.file+'?'+Math.random());
					        });
						});
						imgs.filter('.up').uploader({
							fileExt:'*.jpg;*.jpeg;*.gif;*.png;',
							fileDesc:'图像',
							multi:false,
							jsonType : 1,
							script:'?app=system&controller=upload&action=upload',
							start:function(){
								img.removeAttr('width').removeAttr('height').attr('src', 'images/loader.gif').show();
							},
							complete:function(json){
								if (json) {
									if (json.state) {
										var src	= (/https?:\/\//).test(json.file) ? json.file : UPLOAD_URL + json.file;
										img_v.val(src);
										used(src);
									} else {
										ct.error(json.error);
									}
								} else {
									ct.error('上传失败!');
								}
								return false;
							},
							error:function(data){
								ct.warn(data.file.name+'：上传失败，'+data.error.type+':'+data.error.info);
								return false;
							}
						});
						
						imgs.filter('.pick').click(function(){
							var url = '?app=system&controller=attachment&action=index&select=1&single=1&ext_limit=jpg,jpeg,png,gif';
							var d = ct.iframe({
								title:url,
								width:820,
								height:465
							},{
								ok:function(res){
									img_v.val(res.src);
									used(res.src);
									d.dialog('close');
								}
							});
						});
						dragSort(item.parent());
					}
					function moveItem(item, direct) {
						var tar = item[direct > 0 ? 'nextAll' : 'prevAll']('.list-item:first');
						if (! tar.length || item.is(':animated') || tar.is(':animated')) return;
						var a1 = $(document.createElement('div')).css('height', item[0].offsetHeight),
							a2 = $(document.createElement('div')).css('height', tar[0].offsetHeight),
							s1 = item.find('span.num'), s2 = tar.find('span.num'),
							num1 = s1.text(), num2 = s2.text(),
							pos1 = item.position(), pos2 = tar.position(),
							st = item.offsetParent()[0].scrollTop;
						item.css({position:'absolute',top:pos1.top+st,left:pos1.left,zIndex:2}).after(a1);
						tar.css({position:'absolute',top:pos2.top+st,left:pos2.left,zIndex:1}).after(a2);
						item.animate({top:pos2.top+st},160,function(){
							a2.replaceWith(item);
							s1.text(num2);
							item.css({position:'',zIndex:'',top:'',left:''});
						});
						tar.animate({top:pos1.top+st},160,function(){
							a1.replaceWith(tar);
							s2.text(num1);
							tar.css({position:'',zIndex:'',top:'',left:''});
						});
					}
					function createAdder(){
						if (type === 'single') return '';
						var adder = $('<div class="list-sepr"><div class="list-insert" title="插入一行"></div></div>').click(function(){
							if (dialog.find('.list-item').length > 9) {
								return ct.error('您最多只允许加入10条图文消息');
							}
							var n = adder.prevAll('.list-item').length + 1;
							adder.nextAll('.list-item').each(function(i){
								$('span.num', this).text(i+1+n);
							});
							var item = createItem({}, n).hide();
							item.find('img').hide();
							adder.after(item);
							item.slideDown(160);
							prepareItem(item);
						});
						return adder;
					};
					dragSort = (function() {
				        var OPTIONS = {
				                'handle': '.list-ctrl',
				                'number': 'span.num',
				                'child-class': 'list-item',
				                'sepr-class': 'list-sepr'
				            },
				            DOT = '.',
				            HELPER = 'drag-sort-helper',
				            inited = false,
				            seprPrev = undefined,
				            from = undefined,
				            to = undefined;
				        return function(elem, options) {
				            var o = $.extend({}, OPTIONS, options || {}),
				                childClass = DOT + o['child-class'],
				                seprClass = DOT + o['sepr-class'];
				            if (inited) {
				                elem.sortable('refresh');
				            } else {
				                elem.sortable({
				                    'axis': 'y',
				                    'handle': o['handle'],
				                    'items': childClass,
				                    'cancel': seprClass,
				                    'helper': 'clone',
				                    'placeholder': o['child-class'] + ' ' + HELPER,
				                    'opacity': 0.6,
				                    create: function() {
				                        inited = true;
				                    },
				                    start: function(ev, ui) {
				                        from = ui.item.parent().children(childClass).index(ui.item[0]);
				                        seprPrev = ui.item.prev(seprClass).hide();
				                        elem.find(DOT + HELPER).css('height', ui.item.height());
				                    },
				                    stop: function(ev, ui) {
				                        var nextIsSepr = ui.item.next(':first').is(seprClass),
				                            prevIsSepr = ui.item.prev(':first').is(seprClass),
				                            items = ui.item.parent().children(childClass);
				                        to = items.index(ui.item[0]);
				                        if (prevIsSepr || from > to && ! nextIsSepr) {
				                            seprPrev.insertAfter(ui.item);
				                        }
				                        if (nextIsSepr || from < to && ! prevIsSepr) {
				                            seprPrev.insertBefore(ui.item);
				                        }
				                        seprPrev.show();
				                        seprPrev = undefined;

				                        items.each(function(index) {
				                            $(this).find(o.number).text(index + 1);
				                        });
				                    }
				                });
				            }
				        };
				    })();
				;
          var adder, i, item, items, listArea, t;
          listArea = dialog.find('div.list-area').addClass('hasthumb');
          items = editModel ? editModel.content : [];
          adder = createAdder();
          listArea.html(adder);
          if (items && items.length) {
            for (i in items) {
              t = items[i];
              if (!((typeof t === 'object') && (typeof t.title === 'string'))) {
                continue;
              }
              item = createItem(t, (i >>> 0) + 1);
              item.find('img').hide();
              listArea.append(item);
              prepareItem(item);
            }
            dialog.dialog('option', 'position', 'center');
          } else if (type === 'single') {
            item = createItem({}, 0).hide();
            item.find('img').hide();
            listArea.append(item);
            item.slideDown(160);
            prepareItem(item);
          }
          dialog.find('#adder').click(function() {
            if (dialog.find('.list-item').length > 9) {
              return ct.error('您最多只允许加入10条图文消息');
            }
            return $.datapicker({
              picked: function(items) {
                var n, _results;
                n = Math.max(1, listArea.children('.list-item').length);
                _results = [];
                for (i in items) {
                  t = items[i];
                  if (typeof t !== 'object') {
                    continue;
                  }
                  if (type === 'single') {
                    listArea.children('.list-item:first').remove();
                  }
                  item = createItem(t, (n >>> 0) + (i >>> 0));
                  if (!item) {
                    ct.error('您最多只允许加入10条图文消息');
                    continue;
                  }
                  item.find('img').hide();
                  listArea.append(item);
                  _results.push(prepareItem(item));
                }
                return _results;
              },
              multiple: type === 'multiple',
              url: '?app=system&controller=port&action=picker&modelid=1,2,4,7,6,8,9'
            });
          });
          return dialog.find('#clear').click(function() {
            return ct.confirm('确定要清空吗？', function() {
              return listArea.empty().append(createAdder());
            });
          });
        };
        _dialogSubmit = function(dialog, type, editModel) {
          var content, contentid, data, i, item, key, rawData, _i, _len, _ref, _ref1;
          rawData = dialog.find('form').serializeObject();
          type = typeof rawData["list[contentid][]"] === 'string' ? 'single' : 'multiple';
          if (type === 'single') {
            if (!rawData["list[contentid][]"]) {
              return false;
            }
            if (!rawData["list[thumb][]"]) {
              ct.error('第一篇内容必须有配图');
              return false;
            }
            data = {
              title: rawData['list[title][]'],
              content: []
            };
            data.content.push({
              contentid: rawData['list[contentid][]'],
              title: rawData['list[title][]'],
              thumb: rawData['list[thumb][]'],
              url: rawData['list[url][]'],
              description: rawData['list[description][]']
            });
          } else {
            if (!rawData["list[title][]"][0]) {
              return false;
            }
            if (!rawData["list[thumb][]"][0]) {
              ct.error('第一篇内容必须有配图');
              return false;
            }
            content = [];
            _ref = rawData['list[contentid][]'];
            for (i in _ref) {
              contentid = _ref[i];
              item = {};
              _ref1 = ['title', 'thumb', 'url', 'contentid', 'description'];
              for (_i = 0, _len = _ref1.length; _i < _len; _i++) {
                key = _ref1[_i];
                item[key] = rawData["list[" + key + "][]"][i];
              }
              content.push(item);
            }
            data = {
              title: rawData["list[title][]"][0],
              content: content
            };
          }
          if (editModel) {
            editModel.callback(data);
          } else {
            addingQueue.push(data);
            Lists.add();
          }
          return true;
        };
        _select = function(type, editModel) {
          var content, i, j, s, title, v, _ref;
          if (editModel) {
            content = [];
            _ref = Lists.list;
            for (i in _ref) {
              v = _ref[i];
              if (v.id === editModel.id) {
                content = (function() {
                  var _ref1, _results;
                  _ref1 = v.content;
                  _results = [];
                  for (j in _ref1) {
                    s = _ref1[j];
                    _results.push(s);
                  }
                  return _results;
                })();
              }
            }
            editModel.content = content;
            title = "编辑" + typeName[type] + "消息";
          } else {
            title = "添加" + typeName[type] + "消息";
          }
          return ct.ajaxDialog({
            title: title,
            width: 500
          }, url + type, function(dialog) {
            return _dialogReady(dialog, type, editModel);
          }, function(dialog) {
            return _dialogSubmit(dialog, type, editModel);
          });
        };
        if (editModel) {
          return editModel.element.bind('click', function() {
            if (editModel.content.length > 1) {
              return _select('multiple', editModel);
            } else {
              return _select('multiple', editModel);
            }
          });
        } else {
          Lists.panel.find('.wechat-list-single').bind('click', function() {
            return _select('single');
          });
          return Lists.panel.find('.wechat-list-multiple').bind('click', function() {
            return _select('multiple');
          });
        }
      };

      Lists.search = function() {
        return $(window).unbind('search').bind('search', function(event, data) {
          var key, _i, _len, _ref;
          if (data == null) {
            data = {};
          }
          if (data.starttime) {
            data.starttime = new Date(data.starttime).getTime() / 1000;
          }
          if (data.endtime) {
            data.endtime = new Date(data.endtime).getTime() / 1000;
          }
          if (data.endtime) {
            data.endtime += 86399;
          }
          _ref = ['starttime', 'endtime', 'keyword'];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            key = _ref[_i];
            if (data[key]) {
              search[key] = data[key];
            } else {
              delete search[key];
            }
          }
          listInner.empty();
          Lists.ls();
          return $(window).trigger('loadMore');
        });
      };

      Lists.add = function() {
        $(document).one('pushList', function() {
          var list;
          if (!addingQueue.length) {
            return;
          }
          list = addingQueue.concat();
          addingQueue = [];
          return $.post(BaseUrl + 'add&controller=material', {
            type: 'list',
            multi: true,
            data: JSON.stringify(list)
          }, function(req) {
            var item, menu, _i, _len, _ref, _results;
            addingState = 'ready';
            $(document).trigger('pushList');
            if (!req.state) {
              return ct.error(req.error || '保存失败');
            }
            menu = $('[data-cmd="List"]');
            _ref = req.data;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              item = _ref[_i];
              if (!(typeof item === 'object' && typeof item.title === 'string')) {
                continue;
              }
              menu.trigger('upper');
              _results.push(Lists.list.push(new _List(item.id, item.title, item.content, item.created, true)));
            }
            return _results;
          });
        });
        if (addingState === 'ready') {
          addingState = 'doing';
          return $(document).trigger('pushList');
        }
      };

      _List = (function() {
        var autoFillPath, formartDate;

        _List.template = null;

        _List.prototype.id = null;

        _List.prototype.title = null;

        _List.prototype.elm = null;

        _List.prototype.content = null;

        _List.prototype.created = null;

        function _List(id, title, content, created, insertFirst) {
          var i, v, _ref;
          if (insertFirst == null) {
            insertFirst = false;
          }
          this.renderMultiple = __bind(this.renderMultiple, this);
          this.renderSingle = __bind(this.renderSingle, this);
          this.render = __bind(this.render, this);
          this.rm = __bind(this.rm, this);
          this.edit = __bind(this.edit, this);
          this.id = id;
          this.title = title;
          this.content = {};
          _ref = JSON.parse(content);
          for (i in _ref) {
            v = _ref[i];
            if (i && v && v.title && v.url) {
              this.content[i] = v;
            }
          }
          this.created = (Number(created)) * 1000;
          this.render(insertFirst);
        }

        _List.prototype.edit = function() {
          var i, v;
          return Lists.select({
            element: this.elm.find('.edit'),
            id: this.id,
            content: (function() {
              var _ref, _results;
              _ref = this.content;
              _results = [];
              for (i in _ref) {
                v = _ref[i];
                if (i && v && v.title && v.url) {
                  _results.push(v);
                }
              }
              return _results;
            }).call(this),
            callback: (function(_this) {
              return function(data) {
                var _ref;
                _this.title = data.title;
                _this.content = {};
                _ref = data.content;
                for (i in _ref) {
                  v = _ref[i];
                  if (i && v && v.title && v.url) {
                    _this.content[i] = v;
                  }
                }
                return $.post(BaseUrl + 'edit&controller=material', {
                  id: _this.id,
                  title: data.title,
                  content: JSON.stringify(data.content)
                }, function(req) {
                  var content, next;
                  content = _this.elm.find('.wechat-list-content');
                  content.empty();
                  if (countObject(data.content) < 1) {
                    return;
                  }
                  next = countObject(data.content) > 1 ? _this.renderMultiple(content) : _this.renderSingle(content);
                  return next.then(function() {
                    return listInner.refresh();
                  }).otherwise(function(err) {
                    return error(err);
                  });
                });
              };
            })(this)
          });
        };

        _List.prototype.rm = function() {
          var ok;
          ok = (function(_this) {
            return function() {
              return $.post(BaseUrl + 'rm&controller=material', {
                id: _this.id
              }, function(req) {
                if (!req.state) {
                  return ct.error(req.error);
                }
                $('[data-cmd="List"]').trigger('lower');
                return _this.elm.remove();
              });
            };
          })(this);
          return this.elm.find('.delete').bind('click', (function(_this) {
            return function() {
              return ct.confirm('确认要删除么?', ok);
            };
          })(this));
        };

        _List.prototype.render = function(insertFirst) {
          var content, next;
          if (this.template == null) {
            this.template = $('#listTemplate').html();
          }
          this.elm = $(this.template);
          content = this.elm.find('.wechat-list-content');
          if (countObject(this.content) < 1) {
            return;
          }
          next = countObject(this.content) === 1 ? this.renderSingle(content) : this.renderMultiple(content);
          return next.then((function(_this) {
            return function() {
              _this.edit();
              _this.rm();
              if (insertFirst) {
                return listInner.prepend(_this.elm);
              } else {
                return listInner.append(_this.elm);
              }
            };
          })(this)).otherwise((function(_this) {
            return function(err) {
              return error(err);
            };
          })(this));
        };

        _List.prototype.renderSingle = function(content) {
          var deferred, img, item;
          deferred = window.when.defer();
          item = getFirst(this.content);
          if (item && item.title) {
            content.append($("<div class=\"wechat-list-content-title\">" + item.title + "</div>"));
            content.append($("<div class=\"wechat-list-content-date\">" + (formartDate(this.created)) + "</div>"));
            content.append($("<div class=\"wechat-list-content-cover\"></div>"));
            content.append($("<div class=\"wechat-list-content-description\">" + item.description + "</div>"));
            img = new Image;
            img.onload = function() {
              content.find('.wechat-list-content-cover').append($(this));
              return deferred.resolve();
            };
            img.onerror = function() {
              return deferred.reject();
            };
          } else {
            setTimeout(function() {
              return deferred.resolve();
            }, 1);
          }
          img.src = autoFillPath(item.thumb);
          return deferred.promise;
        };

        _List.prototype.renderMultiple = function(content) {
          var contentid, deferred, deferreds, div, first, img, item, _fn, _ref;
          deferreds = [];
          content.append($("<div class=\"wechat-list-content-date\">" + (formartDate(this.created)) + "</div>"));
          item = getFirst(this.content);
          content.append($("<div class=\"wechat-list-content-cover\"><div class=\"wechat-list-content-cover-title\">" + item.title + "</div></div>"));
          deferred = window.when.defer();
          img = new Image;
          img.onload = function() {
            content.find('.wechat-list-content-cover').prepend($(this));
            return deferred.resolve();
          };
          img.onerror = function() {
            return deferred.resolve();
          };
          deferreds.push(deferred.promise);
          img.src = autoFillPath(item.thumb);
          img.removeAttribute('width');
          img.removeAttribute('height');
          first = true;
          _ref = this.content;
          _fn = function(item, div) {
            var d;
            if (!item.thumb) {
              return;
            }
            div.append("<div class=\"wechat-list-content-subthumb mar_r_8\"></div>");
            d = window.when.defer();
            img = new Image;
            img.onload = function() {
              div.find('.wechat-list-content-subthumb').append($(this));
              return d.resolve();
            };
            img.onerror = function() {
              return d.resolve();
            };
            img.src = autoFillPath(item.thumb);
            return deferreds.push(d.promise);
          };
          for (contentid in _ref) {
            item = _ref[contentid];
            if (!(contentid && (typeof item === 'object') && (typeof item.title === 'string'))) {
              continue;
            }
            if (first) {
              first = false;
              continue;
            }
            div = $('<div class="wechat-list-content-item"></div>');
            div.append("<div class=\"wechat-list-content-subtitle\">" + item.title + "</div>");
            _fn(item, div);
            content.append(div);
          }
          return window.when.all(deferreds);
        };

        autoFillPath = function(url) {
          if (!url) {
            return '';
          }
          if (url.indexOf('://') === -1) {
            return UPLOAD_URL + url;
          } else {
            return url;
          }
        };

        formartDate = function(time) {
          var date;
          date = new Date(time);
          return "" + (date.getFullYear()) + "-" + (date.getMonth() + 1) + "-" + (date.getDate());
        };

        return _List;

      })();

      return Lists;

    })();

    List.ready = function() {
      Lists.panel = null;
      return Lists.list = [];
    };

    List.init = function(panel) {
      return Lists.init(panel);
    };

    return List;

  })();

  Menu.Material.Picture = (function() {
    var Pictures, _Picture;

    function Picture() {}

    Picture.title = '图片';

    Picture.color = 'cyanblue';

    Picture.depend = ['imageEditor/cmstop.imageEditor.js'];

    Picture.serviceOnly = true;

    Picture.name = 'Picture';

    Pictures = (function() {
      var addingQueue, addingState, allowedExt, maxSize, search;

      function Pictures() {}

      Pictures.panel = null;

      Pictures.list = [];

      search = {};

      addingQueue = [];

      addingState = 'ready';

      allowedExt = ['jpg', 'jpeg'];

      maxSize = 128000;

      Pictures.init = function(panel) {
        var item, min, _i, _len, _ref;
        Pictures.search();
        if (Pictures.panel) {
          min = 0;
          _ref = Pictures.list;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            item = _ref[_i];
            if (min === 0 || min > item.id) {
              min = item.id;
            }
          }
          Pictures.ls(min);
          if (countObject(search) > 0) {
            return $(window).trigger('search');
          }
        } else {
          $(window).one('PictureReady', function() {
            Pictures.panel = panel;
            panel.find('#picture-select').bind('click', Pictures.select);
            Pictures.upload(panel.find('#picture-upload'));
            Pictures.ls();
            return $(window).trigger('loadMore');
          });
          if (ImageEditor) {
            return $(window).trigger('PictureReady');
          }
        }
      };

      Pictures.ls = function(offset) {
        var lineCount, noMore, size;
        if (offset == null) {
          offset = 0;
        }
        lineCount = 5;
        noMore = false;
        size = parseInt((document.documentElement.clientHeight - 90) / 190, 10) * lineCount;
        $(window).unbind('loadMore');
        return $(window).bind('loadMore', function() {
          var where;
          if (noMore) {
            return;
          }
          where = $.extend({
            type: 'picture',
            offset: offset,
            size: size - Pictures.list.length % lineCount
          }, search);
          return $.get(BaseUrl + 'ls&controller=material', where, function(req) {
            var item, _i, _len, _ref, _results;
            if (!req.state) {
              return ct.error(req.error);
            }
            if (req.data.length === 0) {
              noMore = true;
              return;
            }
            _ref = req.data;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              item = _ref[_i];
              item.id = Number(item.id);
              if (!offset || offset > item.id) {
                offset = item.id;
              }
              _results.push(Pictures.list.push(new _Picture(item.id, item.title, item.content)));
            }
            return _results;
          });
        });
      };

      Pictures.search = function() {
        return $(window).unbind('search').bind('search', function(event, data) {
          var key, _i, _len, _ref;
          if (data == null) {
            data = {};
          }
          if (data.starttime) {
            data.starttime = new Date(data.starttime).getTime() / 1000;
          }
          if (data.endtime) {
            data.endtime = new Date(data.endtime).getTime() / 1000;
          }
          if (data.endtime) {
            data.endtime += 86399;
          }
          _ref = ['starttime', 'endtime', 'keyword'];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            key = _ref[_i];
            if (data[key]) {
              search[key] = data[key];
            } else {
              delete search[key];
            }
          }
          Pictures.panel.children('.wechat-list').remove();
          Pictures.ls();
          return $(window).trigger('loadMore');
        });
      };

      Pictures.upload = function(elm) {
        var fileExt, item;
        fileExt = (function() {
          var _i, _len, _results;
          _results = [];
          for (_i = 0, _len = allowedExt.length; _i < _len; _i++) {
            item = allowedExt[_i];
            if (globalAllowedExt.indexOf(item) > -1) {
              _results.push("*." + item);
            }
          }
          return _results;
        })();
        return new $.uploader(elm, {
          script: '?app=system&controller=upload&action=upload',
          multi: true,
          fileDataName: 'Filedata',
          fileExt: fileExt.join(';'),
          fileDesc: '图片文件',
          sizeLimit: maxSize,
          jsonType: 1,
          uploadComplete: function(req) {
            if (!req.state) {
              return ct.error(req.error);
            }
            return addingQueue.push({
              content: req.file,
              title: req.name
            });
          },
          allcomplete: function() {
            return Pictures.add();
          },
          error: function(err) {
            if (err.type === "SizeLimit") {
              return ct.error('上传文件不大于 128KB');
            }
          }
        });
      };

      Pictures.select = function() {
        return ct.fileManager(function(res) {
          var item, _i, _len;
          for (_i = 0, _len = res.length; _i < _len; _i++) {
            item = res[_i];
            addingQueue.push({
              content: item.src,
              title: item.name
            });
          }
          return Pictures.add();
        }, allowedExt.join(','), true, maxSize);
      };

      Pictures.add = function() {
        $(document).one('pushPicture', function() {
          var pics;
          if (!addingQueue.length) {
            return;
          }
          pics = addingQueue.concat();
          addingQueue = [];
          return $.post(BaseUrl + 'add&controller=material', {
            type: 'picture',
            multi: true,
            data: JSON.stringify(pics)
          }, function(req) {
            var item, menu, _i, _len, _ref, _results;
            addingState = 'ready';
            $(document).trigger('pushPicture');
            if (!req.state) {
              return ct.error(req.error || '保存失败');
            }
            menu = $('[data-cmd="Picture"]');
            _ref = req.data;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              item = _ref[_i];
              menu.trigger('upper');
              _results.push(Pictures.list.push(new _Picture(item.id, item.title, item.content, true)));
            }
            return _results;
          });
        });
        if (addingState === 'ready') {
          addingState = 'doing';
          return $(document).trigger('pushPicture');
        }
      };

      return Pictures;

    })();

    _Picture = (function() {
      var boxHeight, boxWidth;

      _Picture.template = null;

      _Picture.prototype.id = null;

      _Picture.prototype.title = null;

      _Picture.prototype.src = null;

      _Picture.prototype.elm = null;

      boxWidth = boxHeight = null;

      function _Picture(id, title, src, insertFirst) {
        if (insertFirst == null) {
          insertFirst = false;
        }
        this.render = __bind(this.render, this);
        this.rm = __bind(this.rm, this);
        this.imageEditor = __bind(this.imageEditor, this);
        this.edit = __bind(this.edit, this);
        if (!(src.indexOf('://') > -1)) {
          src = UPLOAD_URL + src;
        }
        this.id = id;
        this.src = src;
        this.title = title;
        this.render(insertFirst);
      }

      _Picture.prototype.edit = function() {
        return this.elm.find('.wechat-list-title').bind('blur', (function(_this) {
          return function(event) {
            var textarea;
            textarea = $(event.target);
            if (!textarea.val() && _this.title === textarea.val()) {
              return;
            }
            _this.title = textarea.val();
            return $.post(BaseUrl + 'edit&controller=material', {
              id: _this.id,
              title: _this.title
            }, function(req) {});
          };
        })(this));
      };

      _Picture.prototype.imageEditor = function() {
        return this.elm.find('.edit').bind('click', (function(_this) {
          return function() {
            return ImageEditor.open(_this.src).bind('saved', function() {
              var newSrc;
              if (_this.src.indexOf('?') === -1) {
                newSrc = "" + _this.src + "?=" + (Date.now());
              } else {
                newSrc = "" + (_this.src.split('?')[0]) + "?=" + (Date.now());
              }
              return _this.elm.find('.wechat-list-img > img').attr('src', newSrc).removeAttr('width').removeAttr('height');
            });
          };
        })(this));
      };

      _Picture.prototype.rm = function() {
        var ok;
        ok = (function(_this) {
          return function() {
            return $.post(BaseUrl + 'rm&controller=material', {
              id: _this.id
            }, function(req) {
              if (!req.state) {
                return ct.error(req.error);
              }
              $('[data-cmd="Picture"]').trigger('lower');
              return _this.elm.remove();
            });
          };
        })(this);
        return this.elm.find('.delete').bind('click', (function(_this) {
          return function() {
            return ct.confirm('确认要删除么?', ok);
          };
        })(this));
      };

      _Picture.prototype.render = function(insertFirst) {
        if (this.template == null) {
          this.template = $('#pictureTemplate').html();
        }
        this.elm = $(this.template);
        this.elm.find('.wechat-list-img > img').bind('load', function() {
          var $this, box;
          $this = $(this);
          box = $this.parents('.wechat-list');
          box.removeClass('wechat-img-unload');
          if (!boxWidth) {
            boxWidth = parseInt(box.css('width'), 10);
          }
          if (!boxHeight) {
            boxHeight = parseInt(box.css('height'), 10);
          }
          if (this.width / boxWidth > this.height / boxHeight) {
            this.height = boxHeight;
          } else {
            this.width = boxWidth;
          }
          return $this.show();
        }).css('display', 'none').attr('src', this.src);
        this.imageEditor();
        this.rm();
        this.edit();
        this.elm.find('.wechat-list-title-picture').val(this.title);
        if (insertFirst) {
          return Pictures.panel.children('.wechat-list:first').before(this.elm);
        } else {
          return Pictures.panel.append(this.elm);
        }
      };

      return _Picture;

    })();

    Picture.ready = function() {
      Pictures.panel = null;
      return Pictures.list = [];
    };

    Picture.init = function(panel) {
      return Pictures.init(panel);
    };

    return Picture;

  })();

  Menu.Material.Video = (function() {
    var Videos, _Video;

    function Video() {}

    Video.title = '视频';

    Video.color = 'darkyellow';

    Video.depend = [];

    Video.serviceOnly = true;

    Video.name = 'Video';

    Videos = (function() {
      var addingQueue, addingState, allowedExt, maxSize, search;

      function Videos() {}

      Videos.panel = null;

      Videos.list = [];

      Videos.player = null;

      search = {};

      addingQueue = [];

      addingState = 'ready';

      allowedExt = ['mp4'];

      maxSize = 1000000;

      Videos.init = function(panel) {
        var item, min, _i, _len, _ref;
        Videos.search();
        if (Videos.panel) {
          min = 0;
          _ref = Videos.list;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            item = _ref[_i];
            if (min === 0 || min > item.id) {
              min = item.id;
            }
          }
          Videos.ls(min);
          if (countObject(search) > 0) {
            return $(window).trigger('search');
          }
        } else {
          $(window).one('videoReady', function() {
            Videos.panel = panel;
            panel.find('#video-select').bind('click', Videos.select);
            Videos.upload(panel.find('#video-upload'));
            Videos.ls();
            return $(window).trigger('loadMore');
          });
          return $(window).trigger('videoReady');
        }
      };

      Videos.upload = function(elm) {
        var fileExt, item;
        fileExt = (function() {
          var _i, _len, _results;
          _results = [];
          for (_i = 0, _len = allowedExt.length; _i < _len; _i++) {
            item = allowedExt[_i];
            if (globalAllowedExt.indexOf(item) > -1) {
              _results.push("*." + item);
            }
          }
          return _results;
        })();
        if (!(fileExt && fileExt.length)) {
          return elm.bind('click', function() {
            return ct.warn("没有上传类型可选择,请联系管理员设置<br />允许上传" + (allowedExt.join(',')) + "类型文件");
          });
        }
        return new $.uploader(elm, {
          script: '?app=system&controller=upload&action=upload',
          multi: false,
          fileDataName: 'Filedata',
          fileExt: fileExt.join(';'),
          fileDesc: '视频文件',
          sizeLimit: maxSize,
          jsonType: 1,
          uploadComplete: function(req) {
            if (!req.state) {
              return ct.error(req.error);
            }
            return addingQueue.push({
              title: req.name,
              content: {
                thumb: '',
                src: req.file,
                duration: 0
              }
            });
          },
          allcomplete: function() {
            return Videos.add();
          },
          error: function(err) {
            if (err.type === "SizeLimit") {
              return ct.error('上传文件不大于 1M');
            }
          }
        });
      };

      Videos.ls = function(offset) {
        var lineCount, noMore, size;
        if (offset == null) {
          offset = 0;
        }
        lineCount = 5;
        noMore = false;
        size = parseInt((document.documentElement.clientHeight - 90) / 190, 10) * lineCount;
        $(window).unbind('loadMore');
        return $(window).bind('loadMore', function() {
          var where;
          if (noMore) {
            return;
          }
          where = $.extend({
            type: 'video',
            offset: offset,
            size: size - Videos.list.length % lineCount
          }, search);
          return $.get(BaseUrl + 'ls&controller=material', where, function(req) {
            var item, _i, _len, _ref, _results;
            if (!req.state) {
              return ct.error(req.error);
            }
            if (req.data.length === 0) {
              noMore = true;
              return;
            }
            _ref = req.data;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              item = _ref[_i];
              item.id = Number(item.id);
              if (!offset || offset > item.id) {
                offset = item.id;
              }
              _results.push(Videos.list.push(new _Video(item.id, item.title, item.content)));
            }
            return _results;
          });
        });
      };

      Videos.search = function() {
        return $(window).unbind('search').bind('search', function(event, data) {
          var key, _i, _len, _ref;
          if (data == null) {
            data = {};
          }
          if (data.starttime) {
            data.starttime = new Date(data.starttime).getTime() / 1000;
          }
          if (data.endtime) {
            data.endtime = new Date(data.endtime).getTime() / 1000;
          }
          if (data.endtime) {
            data.endtime += 86399;
          }
          _ref = ['starttime', 'endtime', 'keyword'];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            key = _ref[_i];
            if (data[key]) {
              search[key] = data[key];
            } else {
              delete search[key];
            }
          }
          Videos.panel.children('.wechat-list').remove();
          Videos.ls();
          return $(window).trigger('loadMore');
        });
      };

      Videos.select = function() {
        return ct.fileManager(function(res) {
          var item, _i, _len;
          for (_i = 0, _len = res.length; _i < _len; _i++) {
            item = res[_i];
            addingQueue.push({
              title: item.name,
              content: {
                thumb: '',
                duration: 0,
                src: item.src
              }
            });
          }
          return Videos.add();
        }, allowedExt.join(','), true, maxSize);
      };

      Videos.add = function() {
        $(document).one('pushVideo', function() {
          var item;
          if (!addingQueue.length) {
            return;
          }
          item = addingQueue.concat();
          addingQueue = [];
          return $.post(BaseUrl + 'add&controller=material', {
            type: 'video',
            multi: true,
            data: JSON.stringify(item)
          }, function(req) {
            var menu, _i, _len, _ref, _results;
            addingState = 'ready';
            $(document).trigger('pushVideo');
            if (!req.state) {
              return ct.error(req.error || '保存失败');
            }
            menu = $('[data-cmd="Video"]');
            _ref = req.data;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              item = _ref[_i];
              menu.trigger('upper');
              _results.push(Videos.list.push(new _Video(item.id, item.title, item.content, true)));
            }
            return _results;
          });
        });
        if (addingState === 'ready') {
          addingState = 'doing';
          return $(document).trigger('pushVideo');
        }
      };

      return Videos;

    })();

    _Video = (function() {
      var timeFormat;

      _Video.template = null;

      _Video.prototype.id = null;

      _Video.prototype.title = null;

      _Video.prototype.src = null;

      _Video.prototype.elm = null;

      _Video.prototype.thumb = null;

      _Video.prototype.duration = null;

      function _Video(id, title, content, insertFirst) {
        var boxHeight, boxWidth;
        if (insertFirst == null) {
          insertFirst = false;
        }
        this.render = __bind(this.render, this);
        this.rm = __bind(this.rm, this);
        this.edit = __bind(this.edit, this);
        content = JSON.parse(content);
        if (!(content.thumb.indexOf('://') > -1)) {
          this.thumb = UPLOAD_URL + content.thumb;
        }
        if (!(content.src.indexOf('://') > -1)) {
          this.src = UPLOAD_URL + content.src;
        }
        this.id = id;
        this.title = title;
        this.duration = timeFormat(content.duration);
        this.render(insertFirst);
        boxWidth = boxHeight = null;
      }

      _Video.prototype.edit = function() {
        return this.elm.find('.edit').bind('click', (function(_this) {
          return function(event) {
            var editElm, textarea;
            if (_this.elm.find('.edit').attr('src') === 'images/yes.png') {
              return;
            }
            event.stopPropagation();
            editElm = $(event.target);
            textarea = _this.elm.find('.wechat-list-video-title');
            textarea.removeAttr('disabled');
            textarea.focus();
            setTimeout(function() {
              return _this.elm.find('.edit').attr('src', 'images/yes.png').one('click.submit', {
                textarea: textarea
              }, function(event) {
                event.stopPropagation();
                return event.data.textarea.trigger('_submit');
              });
            }, 1);
            return textarea.one('_submit', {
              editElm: editElm
            }, function(event) {
              var title;
              event.data.editElm.attr('src', 'images/edit.gif').unbind('click.submit');
              textarea = $(event.target);
              title = textarea.val();
              textarea.attr('disabled', 'disabled');
              if (title && _this.title !== title) {
                _this.title = title;
                return $.post(BaseUrl + 'edit&controller=material', {
                  id: _this.id,
                  title: _this.title
                });
              }
            });
          };
        })(this));
      };

      _Video.prototype.rm = function() {
        var ok;
        ok = (function(_this) {
          return function() {
            return $.post(BaseUrl + 'rm&controller=material', {
              id: _this.id
            }, function(req) {
              if (!req.state) {
                return ct.error(req.error);
              }
              $('[data-cmd="Video"]').trigger('lower');
              return _this.elm.remove();
            });
          };
        })(this);
        return this.elm.find('.delete').bind('click', (function(_this) {
          return function() {
            return ct.confirm('确认要删除么?', ok);
          };
        })(this));
      };

      _Video.prototype.render = function(insertFirst) {
        if (this.template == null) {
          this.template = $('#videoTemplate').html();
        }
        this.elm = $(this.template);
        this.elm.find('.wechat-list-video-title').val(this.title);
        this.rm();
        this.edit();
        if (insertFirst) {
          return Videos.panel.children('.wechat-list:first').before(this.elm);
        } else {
          return Videos.panel.append(this.elm);
        }
      };

      timeFormat = function(time) {
        var hours, minutes, seconds, string;
        hours = minutes = seconds = 0;
        time = parseInt(time, 10);
        seconds = time % 60;
        minutes = (time - seconds) / 60 % 60;
        hours = time - seconds - minutes * 60;
        string = "" + minutes + " : " + seconds;
        if (hours) {
          string += "" + hours + " : ";
        }
        return string;
      };

      return _Video;

    })();

    Video.ready = function() {
      Videos.panel = null;
      return Videos.list = [];
    };

    Video.init = function(panel) {
      return Videos.init(panel);
    };

    return Video;

  })();

  Menu.Material.Voice = (function() {
    var Voices, _Voice;

    function Voice() {}

    Voice.title = '语音';

    Voice.color = 'slightlygreen';

    Voice.depend = [IMG_URL + 'js/lib/jquery.jplayer.js'];

    Voice.serviceOnly = true;

    Voice.name = 'Voice';

    Voices = (function() {
      var addingQueue, addingState, allowedExt, maxSize, search;

      function Voices() {}

      Voices.panel = null;

      Voices.list = [];

      Voices.player = null;

      search = {};

      addingQueue = [];

      addingState = 'ready';

      allowedExt = ['mp3'];

      maxSize = 256000;

      Voices.init = function(panel) {
        var item, min, _i, _len, _ref;
        Voices.search();
        if (Voices.panel) {
          min = 0;
          _ref = Voices.list;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            item = _ref[_i];
            if (min === 0 || min > item.id) {
              min = item.id;
            }
          }
          Voices.ls(min);
          if (countObject(search) > 0) {
            return $(window).trigger('search');
          }
        } else {
          $(window).one('voiceReady', function() {
            Voices.panel = panel;
            panel.find('#voice-select').bind('click', Voices.select);
            Voices.upload(panel.find('#voice-upload'));
            Voices.ls();
            Voices.initPlayer();
            return $(window).trigger('loadMore');
          });
          if ($.jPlayer) {
            return $(window).trigger('voiceReady');
          }
        }
      };

      Voices.ls = function(offset) {
        var lineCount, noMore, size;
        if (offset == null) {
          offset = 0;
        }
        lineCount = 5;
        noMore = false;
        size = parseInt((document.documentElement.clientHeight - 90) / 190, 10) * lineCount;
        $(window).unbind('loadMore');
        return $(window).bind('loadMore', function() {
          var where;
          if (noMore) {
            return;
          }
          where = $.extend({
            type: 'voice',
            offset: offset,
            size: size - Voices.list.length % lineCount
          }, search);
          return $.get(BaseUrl + 'ls&controller=material', where, function(req) {
            var item, _i, _len, _ref, _results;
            if (!req.state) {
              return ct.error(req.error);
            }
            if (req.data.length === 0) {
              noMore = true;
              return;
            }
            _ref = req.data;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              item = _ref[_i];
              item.id = Number(item.id);
              if (!offset || offset > item.id) {
                offset = item.id;
              }
              _results.push(Voices.list.push(new _Voice(item.id, item.title, item.content)));
            }
            return _results;
          });
        });
      };

      Voices.search = function() {
        return $(window).unbind('search').bind('search', function(event, data) {
          var key, _i, _len, _ref;
          if (data == null) {
            data = {};
          }
          if (data.starttime) {
            data.starttime = new Date(data.starttime).getTime() / 1000;
          }
          if (data.endtime) {
            data.endtime = new Date(data.endtime).getTime() / 1000;
          }
          if (data.endtime) {
            data.endtime += 86399;
          }
          _ref = ['starttime', 'endtime', 'keyword'];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            key = _ref[_i];
            if (data[key]) {
              search[key] = data[key];
            } else {
              delete search[key];
            }
          }
          Voices.panel.children('.wechat-list').remove();
          Voices.ls();
          return $(window).trigger('loadMore');
        });
      };

      Voices.select = function() {
        return ct.fileManager(function(res) {
          var item, _i, _len;
          for (_i = 0, _len = res.length; _i < _len; _i++) {
            item = res[_i];
            addingQueue.push({
              title: item.name,
              content: {
                duration: 0,
                src: item.src
              }
            });
          }
          return Voices.add();
        }, allowedExt.join(','), true, maxSize);
      };

      Voices.upload = function(elm) {
        var fileExt, item;
        fileExt = (function() {
          var _i, _len, _results;
          _results = [];
          for (_i = 0, _len = allowedExt.length; _i < _len; _i++) {
            item = allowedExt[_i];
            if (globalAllowedExt.indexOf(item) > -1) {
              _results.push("*." + item);
            }
          }
          return _results;
        })();
        if (!(fileExt && fileExt.length)) {
          return elm.bind('click', function() {
            return ct.warn("没有上传类型可选择,请联系管理员设置<br />允许上传" + (allowedExt.join(',')) + "类型文件");
          });
        }
        return new $.uploader(elm, {
          script: '?app=system&controller=upload&action=upload',
          multi: true,
          sizeLimit: maxSize,
          fileDataName: 'Filedata',
          fileExt: fileExt.join(';'),
          fileDesc: '音频文件',
          jsonType: 1,
          uploadComplete: function(req) {
            if (!req.state) {
              return ct.error(req.error);
            }
            return addingQueue.push({
              title: req.name,
              content: {
                duration: 0,
                src: req.file
              }
            });
          },
          allcomplete: function() {
            return Voices.add();
          },
          error: function(err) {
            if (err.type === "SizeLimit") {
              return ct.error('上传文件不大于 256KB');
            }
          }
        });
      };

      Voices.add = function() {
        $(document).one('pushVoice', function() {
          var voices;
          if (!addingQueue.length) {
            return;
          }
          voices = addingQueue.concat();
          addingQueue = [];
          return $.post(BaseUrl + 'add&controller=material', {
            type: 'voice',
            multi: true,
            data: JSON.stringify(voices)
          }, function(req) {
            var item, menu, _i, _len, _ref, _results;
            addingState = 'ready';
            $(document).trigger('pushVoice');
            if (!req.state) {
              return ct.error(req.error || '保存失败');
            }
            menu = $('[data-cmd="Voice"]');
            _ref = req.data;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              item = _ref[_i];
              menu.trigger('upper');
              _results.push(Voices.list.push(new _Voice(item.id, item.title, item.content, true)));
            }
            return _results;
          });
        });
        if (addingState === 'ready') {
          addingState = 'doing';
          return $(document).trigger('pushVoice');
        }
      };

      Voices.initPlayer = function() {
        Voices.player = $('#jplayer');
        Voices.player.jPlayer({
          swfPath: IMG_URL + 'js/lib/jplayer',
          solution: 'html, flash',
          supplied: 'mp3, wav',
          ready: function(event) {
            return $(Voices.panel).trigger('playerLoaded');
          }
        });
        Voices.player.bind('play', function(event, data) {
          var mediaObject;
          Voices.player.jPlayer('stop');
          if (data) {
            mediaObject = {};
            mediaObject[data.ext] = data.src;
            Voices.player.jPlayer('clearMedia');
            Voices.player.jPlayer('setMedia', mediaObject);
          }
          return Voices.player.jPlayer('play');
        });
        Voices.player.bind('stop', function() {
          return Voices.player.jPlayer('stop');
        });
        return Voices.player.bind('pause', function() {
          return Voices.player.jPlayer('pause');
        });
      };

      Voices.revoke = function() {
        return Voices.player.trigger('stop');
      };

      return Voices;

    })();

    _Voice = (function() {
      var playState;

      _Voice.template = null;

      _Voice.prototype.id = null;

      _Voice.prototype.title = null;

      _Voice.prototype.src = null;

      _Voice.prototype.elm = null;

      _Voice.prototype.ext = null;

      playState = 'stop';

      function _Voice(id, title, content, insertFirst) {
        var src;
        if (insertFirst == null) {
          insertFirst = false;
        }
        this.render = __bind(this.render, this);
        this.play = __bind(this.play, this);
        this.rm = __bind(this.rm, this);
        this.edit = __bind(this.edit, this);
        src = JSON.parse(content).src;
        if (!(src.indexOf('://') > -1)) {
          this.src = UPLOAD_URL + src;
        }
        this.id = id;
        this.ext = this.src.split('.').pop();
        this.title = title;
        this.render(insertFirst);
      }

      _Voice.prototype.edit = function() {
        return this.elm.find('.edit').bind('mousedown', (function(_this) {
          return function() {
            var textarea, title;
            if (_this.elm.find('textarea.wechat-list-voice-title').length) {
              return;
            }
            title = _this.elm.find('div.wechat-list-voice-title');
            textarea = $("<textarea class=\"wechat-list-voice-title\">" + _this.title + "</textarea>");
            textarea.insertAfter(title.hide());
            return setTimeout(function() {
              textarea.one('blur', {
                title: title,
                textarea: textarea
              }, function(event) {
                var val;
                val = event.data.textarea.val();
                event.data.textarea.remove();
                if (val) {
                  event.data.title.html(val);
                }
                event.data.title.show();
                if (!textarea.val() && _this.title === textarea.val()) {
                  return;
                }
                _this.title = val;
                return $.post(BaseUrl + 'edit&controller=material', {
                  id: _this.id,
                  title: _this.title
                }, function(req) {});
              });
              return textarea.focus();
            }, 1);
          };
        })(this));
      };

      _Voice.prototype.rm = function() {
        var ok;
        ok = (function(_this) {
          return function() {
            return $.post(BaseUrl + 'rm&controller=material', {
              id: _this.id
            }, function(req) {
              if (!req.state) {
                return ct.error(req.error);
              }
              $('[data-cmd="Voice"]').trigger('lower');
              return _this.elm.remove();
            });
          };
        })(this);
        return this.elm.find('.delete').bind('click', (function(_this) {
          return function() {
            return ct.confirm('确认要删除么?', ok);
          };
        })(this));
      };

      _Voice.prototype.play = function() {
        var playerElm;
        playerElm = this.elm.find('.wechat-list-voice-play');
        playerElm.bind('click', (function(_this) {
          return function() {
            var mediaObject;
            if (playState === 'play') {
              playerElm.removeClass('wechat-pause');
              playerElm.addClass('wechat-play');
              playState = 'pause';
              return Voices.player.trigger('pause');
            } else {
              playState = 'play';
              playerElm.removeClass('wechat-play');
              playerElm.addClass('wechat-pause');
              mediaObject = {
                id: _this.id,
                ext: _this.ext,
                src: _this.src
              };
              return Voices.player.trigger('play', mediaObject);
            }
          };
        })(this));
        return Voices.panel.bind('play', (function(_this) {
          return function(event, mediaObject) {
            if (!mediaObject || _this.id === mediaObject.id || playState === 'stop') {
              return;
            }
            playerElm.removeClass('wechat-pause');
            playerElm.addClass('wechat-play');
            playState = 'stop';
            return Voices.player.trigger('stop');
          };
        })(this));
      };

      _Voice.prototype.render = function(insertFirst) {
        if (this.template == null) {
          this.template = $('#voiceTemplate').html();
        }
        this.elm = $(this.template);
        this.elm.find('.wechat-list-voice-title').html(this.title);
        this.edit();
        this.rm();
        this.play();
        if (insertFirst) {
          return Voices.panel.children('.wechat-list:first').before(this.elm);
        } else {
          return Voices.panel.append(this.elm);
        }
      };

      return _Voice;

    })();

    Voice.ready = function() {
      Voices.panel = null;
      return Voices.list = [];
    };

    Voice.init = function(panel) {
      Voices.init(panel);
      return $(window).one('revokeMaterial', (function(_this) {
        return function() {
          return Voices.revoke();
        };
      })(this));
    };

    return Voice;

  })();

  Menu.Reply.Index = (function() {
    var firstLoad;

    function Index() {}

    Index.title = '全文检索';

    Index.depend = [IMG_URL + 'js/lib/tree/style.css', IMG_URL + 'js/lib/cmstop.tree.js'];

    firstLoad = true;

    Index.name = 'Index';

    Index.ready = function() {};

    Index.init = function(panel) {
      var contextEnginForm, i, noModelSelected, radio, _ref;
      contextEnginForm = $('#contextEnginForm');
      if (!$('#category').next().length) {
        $('#category').placetree();
      }
      if (!firstLoad) {
        return;
      }
      firstLoad = false;
      noModelSelected = false;
      _ref = $('[name="modelid"]');
      for (i in _ref) {
        radio = _ref[i];
        if (radio.checked) {
          noModelSelected = true;
        }
      }
      if (!noModelSelected) {
        $('[name="modelid"]').eq(0).trigger('click');
      }
      return contextEnginForm.find('button').bind('click', function(event) {
        var data;
        data = contextEnginForm.serializeObject();
        if (!data.state) {
          ct.error('请选择状态');
          return false;
        } else if (!data.modelid || !data.modelid.length) {
          ct.error('请选择模型');
          return false;
        } else if (data.category === 1) {
          ct.error('请选择栏目');
          return false;
        }
        $.post(BaseUrl + 'context&controller=reply', data, function(req) {
          if (req.state) {
            return ct.ok('保存成功');
          } else {
            return ct.error('保存失败');
          }
        });
        return false;
      });
    };

    return Index;

  })();

  Menu.Reply.Route = (function() {
    var Routes, _Route, _date2Time, _renderType;

    function Route() {}

    Route.title = '关键词自动回复';

    Route.depend = [IMG_URL + 'js/lib/jquery-ui/dialog.css', IMG_URL + 'js/lib/json2.js', IMG_URL + 'js/lib/jquery.jplayer.js', IMG_URL + 'js/lib/cmstop.suggest.js', IMG_URL + 'js/lib/suggest/style.css'];

    Route.name = 'Route';

    Routes = (function() {
      var MATERIAL_NAME, tableApp;

      function Routes() {}

      MATERIAL_NAME = {
        "text": "文字",
        "list": "图文",
        "picture": "图片",
        "voice": "语音",
        "video": "视频"
      };

      tableApp = null;

      Routes.panel = null;

      Routes.list = null;

      Routes.init = function(panel) {
        var bindAdd, bindPlayer, bindPost, bindSearch, item, min, routeAdd, routePlayer, routePost, routeSearch, tableContent, _i, _len, _ref, _renderContent, _renderTags;
        bindAdd = function(routeAdd) {
          return routeAdd.bind('click', function() {
            return routePost.trigger('new');
          });
        };
        bindPost = function(routePost) {
          var contentSort, materialSelector;
          contentSort = 0;
          materialSelector = function(type, form) {
            var data, input;
            input = form.find('[name="content"]');
            data = JSON.parse(input.val() || '{}');
            if (countObject(data) >= 1 && typeof getFirst(data) !== 'function') {
              return ct.error('最多允许添加1条内容');
            }
            return ct.ajaxDialog({
              title: "" + (type === 'text' ? '添加' : '选择') + MATERIAL_NAME[type] + "素材"
            }, "" + BaseUrl + "selector&controller=material&type=" + type, (function(_this) {
              return function(dialog) {
                dialog.find('ul').css('position', 'relative');
                return dialog.trigger('resize');
              };
            })(this), (function(_this) {
              return function(dialog) {
                data = [dialog.find('form').serializeObject()];
                if (!data[0].content) {
                  ct.error('内容不能为空');
                  return false;
                }
                form.find('#route-post-content').trigger('update', data[0]);
                return input.val(JSON.stringify(data));
              };
            })(this), function() {
              return true;
            });
          };
          routePost.bind('new', function(event) {
            return ct.formDialog({
              title: '添加规则',
              width: 600,
              height: 480
            }, "" + BaseUrl + "add&controller=reply&type=" + accountList[Home.id].type, function(req) {
              return routePost.success(req);
            }, function(form) {
              routePost.formReady(form);
              return true;
            }, function(form) {
              return routePost.beforeSubmit(form);
            });
          });
          routePost.bind('edit', function(event, id) {
            return ct.formDialog({
              title: '编辑规则',
              width: 600,
              height: 480
            }, "" + BaseUrl + "edit&controller=reply&id=" + id + "&type=" + accountList[Home.id].type, function(req) {
              return routePost.success(req);
            }, function(form) {
              routePost.formReady(form);
              return true;
            }, function(form) {
              return routePost.beforeSubmit(form);
            });
          });
          routePost.success = function(req) {
            if (!req.state) {
              ct.error(req.error || '保存失败');
              return false;
            }
            tableApp.reload();
            return true;
          };
          routePost.formReady = function(form) {
            var content, id;
            content = form.data('content');
            content = content ? content[0] : {};
            id = form.data('id');
            suggest($('#tags'), {
              width: 450,
              limit: 5,
              delimiter: ',',
              url: "?app=system&controller=tag&action=suggest&tag=%s",
              listUrl: "?app=system&controller=tag&action=page&page=%s",
              paramVal: "tag",
              paramTxt: "tag",
              anytext: "1"
            });
            form.find('#route-post-content').unbind('update').bind('update', {
              form: form,
              content: content
            }, function(event, content) {
              var deleteImage, elm, type, _results;
              if (content == null) {
                content = {};
              }
              form = event.data.form;
              content = $.extend(event.data.content, content);
              elm = $('<div class="wechat-route-post-item wechat-route-post-item-picture"></div>');
              elm.appendTo(form.find('#route-post-content'));
              _renderType(content, elm);
              elm.addClass('wechat-route-post-item').addClass("wechat-route-post-item-" + content.type);
              deleteImage = $('<img class="hand delete" />');
              deleteImage.attr({
                width: 16,
                height: 16,
                title: '删除',
                alt: '删除',
                src: 'images/delete.gif'
              });
              elm.append(deleteImage);
              deleteImage.bind('click', {
                id: id,
                form: form
              }, function(event) {
                var contentInput;
                form = $(event.data.form);
                form.find('#route-post-content').empty();
                contentInput = form.find('[name="content"]');
                return contentInput.val('[]');
              });
              _results = [];
              for (type in MATERIAL_NAME) {
                _results.push(form.find("#reply-" + type).unbind().bind('click', {
                  type: type,
                  form: form
                }, function(event) {
                  return materialSelector(event.data.type, event.data.form);
                }));
              }
              return _results;
            }).trigger('update');
            return form.find('[tips]').each(function() {
              return $(this).attrTips('tips', 'tips_green');
            });
          };
          return routePost.beforeSubmit = function(form) {
            var data;
            data = form.serializeObject();
            if (!data.name) {
              ct.error('规则名不能为空');
              return false;
            }
            if (!data.tags) {
              ct.error('关键词不能为空');
              return false;
            }
            if (!data.content) {
              ct.error('内容不能为空');
              return false;
            }
            return true;
          };
        };
        bindSearch = function(routeSearch) {
          routeSearch.bind('submit', function(event) {
            var data;
            data = $(this).serializeObject();
            if (data.starttime) {
              data.starttime = _date2Time(data.starttime);
            }
            if (data.endtime) {
              data.endtime = _date2Time(data.endtime);
            }
            tableApp.load(data);
            return false;
          });
          return routeSearch.find('a').bind('click', function() {
            return routeSearch.trigger('submit');
          });
        };
        bindPlayer = function(routePlayer) {
          return routePlayer.jPlayer({
            swfPath: IMG_URL + 'js/lib/jplayer',
            solution: 'html, flash',
            supplied: 'mp3, wav',
            ready: function(event) {
              return routePlayer.bind('play', function(event, mediaObject) {
                var player;
                player = $(this);
                player.jPlayer('stop');
                player.jPlayer('clearMedia');
                player.jPlayer('setMedia', mediaObject);
                return player.jPlayer('play');
              });
            }
          });
        };
        routeAdd = panel.find('#route-add');
        routePost = panel.find('#routePost');
        routeSearch = panel.find('#routeSearch');
        routePlayer = $('#player');
        if (Routes.panel) {
          min = 0;
          _ref = Routes.list;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            item = _ref[_i];
            if (min === 0 || min > item.id) {
              min = item.id;
            }
          }
        } else {
          Routes.panel = panel;
          bindAdd(routeAdd);
          bindPost(routePost);
          bindSearch(routeSearch);
          $(window).one('RouteReady', function() {
            bindPlayer(routePlayer);
            if (accountList[Home.id].type === 'subscribe') {
              $('[data-service-only]').remove();
            }
            return $(window).trigger('scroll.wechat');
          });
          if ($.jPlayer) {
            $(window).trigger('RouteReady');
          }
          tableContent = {};
          tableApp = new ct.table('#routeList > table', {
            baseUrl: BaseUrl + 'ls&controller=reply',
            jsonLoaded: function(json) {
              var i, v, _ref1, _results;
              _ref1 = json.data;
              _results = [];
              for (i in _ref1) {
                v = _ref1[i];
                _results.push(tableContent[v.id] = v.content);
              }
              return _results;
            },
            rowCallback: function(id, tr) {
              _renderTags(tr.find('[data-role="tags"]'));
              _renderContent(tr.find('[data-role="content"]'), tableContent[id]);
              tr.find('.edit').bind('click', {
                id: id
              }, function(event) {
                return Routes.panel.find('#routePost').trigger('edit', event.data.id);
              });
              return tr.find('.delete').bind('click', {
                id: id
              }, function(event) {
                return ct.confirm('确定要删除吗?', function() {
                  return $.post(BaseUrl + 'rm&controller=reply', {
                    id: event.data.id
                  }, function(req) {
                    if (!req.state) {
                      return ct.error(req.error || '删除失败');
                    }
                    return tableApp.reload();
                  });
                });
              });
            },
            template: $('#route-list-template').html(),
            pagesizeVar: 'size',
            pageSize: 15
          });
          $('#route-table-refresh').unbind().bind('click', function() {
            return tableApp.reload();
          });
          $('#route-table-delete').unbind().bind('click', function() {
            var ids;
            ids = [];
            $('#routeList').find('input[type="checkbox"]:checked').each(function(i, k) {
              return ids.push($(k).parents('tr').attr('id').substr(4));
            });
            if (ids.length === 0) {
              return;
            }
            return ct.confirm('确定要删除吗?', function() {
              return $.post(BaseUrl + 'rm&controller=reply', 'id[]=' + ids.join('&id[]='), function(req) {
                if (!req.state) {
                  return ct.error(req.error || '删除失败');
                }
                return tableApp.reload();
              });
            });
          });
        }
        tableApp.load();
        _renderTags = function(element) {
          var e, tags;
          tags = null;
          try {
            tags = JSON.parse(element.text());
          } catch (_error) {
            e = _error;
            tags = [];
          }
          return element.html(tags.join(', '));
        };
        return _renderContent = function(element, content) {
          var e;
          try {
            content = JSON.parse(content)[0];
          } catch (_error) {
            e = _error;
            return element.html('');
          }
          element.html(MATERIAL_NAME[content.type]);
          item = $('<div class="bubble"><div></div></div>');
          _renderType(content, item.children());
          return element.attr('tips', item.html()).attrTips('tips');
        };
      };

      Routes.add = function(data) {
        return $.post(BaseUrl + 'add&controller=reply', data, function(req) {
          if (!req.state) {
            return ct.error(req.error || '添加失败');
          }
          return Routes.list.push(new _Route(req.data.id, req.data.name, req.data.tags, req.data.content, req.data.reply_all, true));
        });
      };

      return Routes;

    })();

    _Route = (function() {
      _Route.prototype.id = null;

      _Route.prototype.name = null;

      _Route.prototype.tags = null;

      _Route.prototype.content = null;

      _Route.prototype.replyAll = null;

      _Route.prototype.elm = null;

      function _Route(id, name, tags, content, replyAll, insertFirst) {
        if (insertFirst == null) {
          insertFirst = false;
        }
        this.render = __bind(this.render, this);
        this.id = id;
        this.name = name;
        this.tags = JSON.parse(tags);
        this.content = JSON.parse(content);
        this.replyAll = replyAll;
        this.render();
        if (insertFirst) {
          $('#routeList').prepend(this.elm);
        } else {
          $('#routeList').append(this.elm);
        }
      }

      _Route.prototype.render = function() {
        return tableApp.load();
      };

      return _Route;

    })();

    Route.ready = function() {
      Routes.panel = null;
      return Routes.list = [];
    };

    Route.init = function(panel) {
      return Routes.init(panel);
    };

    _renderType = function(content, item) {
      var isTips, result, src, thumb;
      isTips = item.parents('.bubble').length > 0;
      switch (content.type) {
        case 'text':
          result = $('<p>' + content.content.replace(/[\r\n]/g, '<br/>') + '</p>');
          item.append(result);
          break;
        case 'picture':
          if (typeof content.content === 'string') {
            content.content = JSON.parse(content.content);
          }
          src = content.content.src;
          if (src.indexOf('://') === -1) {
            src = UPLOAD_URL + src;
          }
          result = $('<img class="wechat-route-post-item-picture-img" />');
          result.one('error', function() {
            return this.src = IMG_URL + 'images/space.gif';
          });
          result.attr('src', src);
          result.removeAttr('width');
          result.removeAttr('height');
          item.append(result);
          break;
        case 'voice':
          if (isTips) {
            item.remove();
          } else {
            if (typeof content.content === 'string') {
              content.content = JSON.parse(content.content);
            }
            result = $('<div class="wechat-list-voice-play"></div>');
            result.bind('click', {
              src: content.content.src
            }, (function(_this) {
              return function(event) {
                var ext, mediaObject;
                src = event.data.src;
                ext = src.split('.').pop();
                if (src.indexOf('://') === -1) {
                  src = UPLOAD_URL + src;
                }
                mediaObject = {};
                mediaObject[ext] = src;
                return $('#player').trigger('play', mediaObject);
              };
            })(this));
          }
          item.append(result);
          break;
        case 'video':
          if (isTips) {
            result = $('<p>' + content.content.title + '</p>');
            item.append(result);
          } else {
            thumb = '/images/icons/mp4.png';
            result = $("<img class=\"wechat-route-post-item-picture-img\" alt=\"\" />");
            result.one('error', function() {
              return this.src = IMG_URL + 'images/space.gif';
            });
            result.attr('src', thumb);
            item.append(result);
            item.append('<div class="wechat-video-flag"></div>');
          }
          break;
        case 'list':
          if (isTips) {
            content = typeof content.content === 'object' ? content.content : JSON.parse(content.content);
            result = $('<p>' + getFirst(content.data).title + '</p>');
            item.append(result);
          } else {
            result = content.template;
            item.addClass('wechat-list-box');
            item.append(result);
          }
          break;
      }
      return result;
    };

    _date2Time = function(date) {
      var time;
      time = new Date(date) / 1000;
      return time >>> 0;
    };

    return Route;

  })();

}).call(this);
