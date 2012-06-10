(function() {
  var root,
    __slice = [].slice;

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  root.DEBUGGING = true;

  root.DEBUGGING_STACKTRACE = false;

  root.CACHE = {};

  root.TIMERS = {};

  require(["jquery"], function() {
    var edit_account, update_password;
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
    if (update_password = $("form#password")) {
      require(["password_strength"], function() {
        return $(update_password).find('input#new_password').on('keyup', function() {
          var password_input, password_strength, strength;
          password_strength = $("p#password_strength");
          password_input = $('input#new_password');
          if (password_input.val().length) {
            if (password_input.val().length < 5) {
              $(password_strength).css('color', 'red').html('Your password is too short. Please use at least 5 characters.');
            } else if (password_input.val().length > 128) {
              $(password_strength).css('color', 'red').html('Your password is too long. Please use no more than 128 characters.');
            } else {
              strength = PasswordStrength.test("", password_input.val());
              if (strength.isStrong()) {
                $(password_strength).css('color', 'green').html('Your password is strong.');
              } else if (strength.isGood()) {
                $(password_strength).css('color', 'orange').html('Your password is fairly weak.');
              } else if (strength.isWeak()) {
                $(password_strength).css('color', 'red').html('Your password is dangerously weak.');
              } else {
                $(password_strength).css('color', 'red').html('Your password is not acceptable.');
              }
            }
            $('div#password_strength_container:hidden').slideDown("slow", function() {
              return $(password_strength).fadeIn("slow");
            });
          }
          return true;
        });
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
