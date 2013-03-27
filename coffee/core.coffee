root = exports ? this

# Globals
root.DEBUGGING             = true
root.DEBUGGING_STACKTRACE  = false
root.CACHE                 = {}
root.TIMERS                = {}
root.AUTH_DIALOG           = null

# Load jQuery
require ["jquery"], ->

	if edit_account = $("form#edit_account")

		$(edit_account).append(
			$('<input/>')
				.attr('type', 'hidden')
				.attr('name', 'email')
				.attr('id', 'email')
				.val('')
		);

		$(edit_account).find('a.promote').on 'click', ->
			return if $(edit_account).data("locked")

			if email = $(@).closest('li').data('email')
				$(edit_account).children("#activity").val('promote')
				$(edit_account).children("#email").val(email)
				$(edit_account).data("locked", true)
				$(edit_account).submit()

			return false

		$(edit_account).find('a.remove').on 'click', ->
			return if $(edit_account).data("locked")

			if email = $(@).closest('li').data('email')
				$(edit_account).children("#activity").val('remove')
				$(edit_account).children("#email").val(email)
				$(edit_account).data("locked", true)
				$(edit_account).submit()

			return false

	if facebook_login = $("a#facebookLogin")
		$(facebook_login).on "click", (e) ->
			root.facebookAuthorizationOpen("/login/facebook/")
			e.preventDefault();
			return false;

	if account_register = $("form#account_register")
		require ["password_strength"], ->

			$(account_register).find('input#password').on 'keyup', ->
				password_strength = $("p#password_strength");
				password_input = $('input#password');

				if password_input.val().length

					if password_input.val().length < 5
						$(password_strength).css('color', 'red').html('Your password is too short. Please use at least 5 characters.');

					else if password_input.val().length > 128
						$(password_strength).css('color', 'red').html('Your password is too long. Please use no more than 128 characters.');

					else
						strength = PasswordStrength.test("", password_input.val());

						if strength.isStrong()
							$(password_strength).css('color', 'green').html('Your password is strong.');

						else if strength.isGood()
							$(password_strength).css('color', 'orange').html('Your password is fairly weak.');

						else if strength.isWeak()
							$(password_strength).css('color', 'red').html('Your password is dangerously weak.');

						else
							$(password_strength).css('color', 'red').html('Your password is not acceptable.');

					$('div#password_strength_container:hidden').slideDown "slow", ->
						$(password_strength).fadeIn("slow");

				return true

	if update_password = $("form#password")
		require ["password_strength"], ->

			$(update_password).find('input#new_password').on 'keyup', ->
				password_strength = $("p#password_strength");
				password_input = $('input#new_password');

				if password_input.val().length

					if password_input.val().length < 5
						$(password_strength).css('color', 'red').html('Your password is too short. Please use at least 5 characters.');

					else if password_input.val().length > 128
						$(password_strength).css('color', 'red').html('Your password is too long. Please use no more than 128 characters.');

					else
						strength = PasswordStrength.test("", password_input.val());

						if strength.isStrong()
							$(password_strength).css('color', 'green').html('Your password is strong.');

						else if strength.isGood()
							$(password_strength).css('color', 'orange').html('Your password is fairly weak.');

						else if strength.isWeak()
							$(password_strength).css('color', 'red').html('Your password is dangerously weak.');

						else
							$(password_strength).css('color', 'red').html('Your password is not acceptable.');

					$('div#password_strength_container:hidden').slideDown "slow", ->
						$(password_strength).fadeIn("slow");

				return true

	# Import File I/O
	require ["fileio"]

	return

root.facebookAuthorizationOpen = (url) ->
	multiScreenLeft = if window.screenLeft != undefined then window.screenLeft else screen.left
	multiScreenTop = if window.screenTop != undefined then window.screenTop else screen.top

	left = ((screen.width/2) - 325) + multiScreenLeft;
	top = ((screen.height/2) - 150) + multiScreenTop;

	root.AUTH_DIALOG = window.open(url, "facebook_authorization", "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,copyhistory=0,width=650,height=300,top="+top+",left="+left);
	root.facebookAuthorizationWatch()

	return

root.facebookAuthorizationWatch = () ->
	console.log(root.AUTH_DIALOG);

	if root.AUTH_DIALOG && root.AUTH_DIALOG.closed
		location.reload();
	else
		root.AUTH_DIALOG.focus();

		callback = -> root.facebookAuthorizationWatch()

		setTimeout callback, 1000

	return

root.forceRedraw = (element) ->
	return unless element

	element.hide();
	element.each -> @.offsetHeight
	element.fadeIn(50);

	return

root.delay = (name, ms, func) ->
	clearTimeout(root.TIMERS[name]) if root.TIMERS[name]
	root.TIMERS[name] = setTimeout func, ms

root.logEvent = (events...) ->
	return unless root.DEBUGGING
	return unless events
	return unless console
	return unless console.log

	stack = []

	if root.DEBUGGING_STACKTRACE
		try
			throw new Error "STACKTRACE"
		catch e
			stack = e.stack
			stack = stack.replace("Error: STACKTRACE", "Stacktrace:")
			stack = stack.split("\n")

	date = new Date()
	message = $.merge($.merge([date.toTimeString()], events[0]), stack)
	console.log(message.join("\n   "))

	return
