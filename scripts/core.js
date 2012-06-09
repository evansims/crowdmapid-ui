(function() {
  var root,
    __slice = [].slice;

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  root.DEBUGGING = true;

  root.DEBUGGING_STACKTRACE = false;

  root.CACHE = {};

  root.TIMERS = {};

  require(["jquery"], function() {
    var edit_account;
    if (edit_account = $("form#edit_account")) {
      $(edit_account).append($('<input/>').attr('type', 'hidden').attr('name', 'email').attr('id', 'email').val(''));
      $(edit_account).find('a.promote').on('click', function() {
        var email;
        if ($(edit_account).data("locked")) {
          return;
        }
        if (email = $(this).closest('li').data('email')) {
          $(edit_account).children("#activity").val('promote');
          $(edit_account).children("#email").val(email);
          $(edit_account).data("locked", true);
          $(edit_account).submit();
        }
        return false;
      });
      $(edit_account).find('a.remove').on('click', function() {
        var email;
        if ($(edit_account).data("locked")) {
          return;
        }
        if (email = $(this).closest('li').data('email')) {
          $(edit_account).children("#activity").val('remove');
          $(edit_account).children("#email").val(email);
          $(edit_account).data("locked", true);
          $(edit_account).submit();
        }
        return false;
      });
    }
    require(["fileio"]);
  });

  root.forceRedraw = function(element) {
    if (!element) {
      return;
    }
    element.hide();
    element.each(function() {
      return this.offsetHeight;
    });
    element.fadeIn(50);
  };

  root.delay = function(name, ms, func) {
    if (root.TIMERS[name]) {
      clearTimeout(root.TIMERS[name]);
    }
    return root.TIMERS[name] = setTimeout(func, ms);
  };

  root.logEvent = function() {
    var date, events, message, stack;
    events = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
    if (!root.DEBUGGING) {
      return;
    }
    if (!events) {
      return;
    }
    if (!console) {
      return;
    }
    if (!console.log) {
      return;
    }
    stack = [];
    if (root.DEBUGGING_STACKTRACE) {
      try {
        throw new Error("STACKTRACE");
      } catch (e) {
        stack = e.stack;
        stack = stack.replace("Error: STACKTRACE", "Stacktrace:");
        stack = stack.split("\n");
      }
    }
    date = new Date();
    message = $.merge($.merge([date.toTimeString()], events[0]), stack);
    console.log(message.join("\n   "));
  };

}).call(this);
