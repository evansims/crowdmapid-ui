(function() {
  var root,
    __slice = [].slice;

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  root.DEBUGGING = true;

  root.DEBUGGING_STACKTRACE = false;

  root.CACHE = {};

  root.TIMERS = {};

  root.AUTH_DIALOG = null;

  require(["jquery"], function() {
    var account_register, edit_account, facebook_login, update_password;
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
    if (facebook_login = $("a#facebookLogin")) {
      $(facebook_login).on("click", function(e) {
        root.facebookAuthorizationOpen("/login/facebook/");
        e.preventDefault();
        return false;
      });
    }
    if (account_register = $("form#account_register")) {
      require(["password_strength"], function() {
        return $(account_register).find('input#password').on('keyup', function() {
          var password_input, password_strength, strength;
          password_strength = $("p#password_strength");
          password_input = $('input#password');
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

  root.facebookAuthorizationOpen = function(url) {
    var left, multiScreenLeft, multiScreenTop, top;
    multiScreenLeft = window.screenLeft !== void 0 ? window.screenLeft : screen.left;
    multiScreenTop = window.screenTop !== void 0 ? window.screenTop : screen.top;
    left = ((screen.width / 2) - 325) + multiScreenLeft;
    top = ((screen.height / 2) - 150) + multiScreenTop;
    root.AUTH_DIALOG = window.open(url, "facebook_authorization", "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,copyhistory=0,width=650,height=300,top=" + top + ",left=" + left);
    root.facebookAuthorizationWatch();
  };

  root.facebookAuthorizationWatch = function() {
    var callback;
    console.log(root.AUTH_DIALOG);
    if (root.AUTH_DIALOG && root.AUTH_DIALOG.closed) {
      location.reload();
    } else {
      root.AUTH_DIALOG.focus();
      callback = function() {
        return root.facebookAuthorizationWatch();
      };
      setTimeout(callback, 1000);
    }
  };

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
