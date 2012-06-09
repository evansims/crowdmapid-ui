root = exports ? this

# Globals
root.DEBUGGING             = true
root.DEBUGGING_STACKTRACE  = false
root.CACHE                 = {}
root.TIMERS                = {}

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

	# Import File I/O
	require ["fileio"]

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
