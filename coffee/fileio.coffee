root = exports ? this

validDownloadableExtensions = ['jpg', 'png', 'gif', 'gz', 'zip', 'rar', 'pdf', 'iso', 'dmg'];

$ ->
	# Cache the file drops for performance.
	root.CACHE['filedrops'] = $(".filedrop")

	# Apply a hover effect to display "drag to upload" (and other tips) on file drops.
	$(".filedrop").hover \
		(->
			return if $(@).hasClass("dropped")
			$(@).children("div.overlay").stop(true,false).slideDown(150)
			),
		(->
			return if $(@).hasClass("dropped")
			$(@).children("div.overlay").stop(true,false).slideUp(50)
			)

	# Elements that can be dragged out of the browser and downloaded.
	$(".downloadable").on "dragstart", (e) ->

		log = ["Drag started on image."]
		$(@).addClass "dragstart"

		url = $(@).attr "data-download"
		url ?= $(@).attr "src"
		url ?= $(@).css "background-image" unless $(@).css("background-image") is "none"

		unless url
			event.preventDefault()
			log.push "No image URL could be determined. Aborted drag."
			root.logEvent [log]
			return false

		filename = $(@).attr("data-filename")
		filename ?= url.substring url.lastIndexOf('/') + 1

		if filename.indexOf("?") is not -1
			filename = filename.substring(0, filename.indexOf("?"))

		unless filename
			event.preventDefault()
			log.push "No image filename could be determined. Aborted drag."
			root.logEvent [log]
			return false

		extension = filename.substr filename.lastIndexOf('.') + 1
		log.push url
		log.push extension
		log.push filename

		unless extension
			event.preventDefault()
			log.push "File extension could not be determined. Aborted drag."
			root.logEvent [log]
			return false

		unless validDownloadableExtensions.indexOf(extension) > -1
			event.preventDefault()
			log.push "File extension " + extension + " is not in the whitelist. Aborted drag."
			root.logEvent [log]
			return false

		e.originalEvent.dataTransfer.dropEffect = 'copy';
		e.originalEvent.dataTransfer.setData('DownloadURL',
			['application/octet-stream', filename, url].join(':')
		);

		root.logEvent [log]
		return

	###
	DOCUMENT drag and drop events.
	###

	$("*").on "dragenter", (e) ->
		if $(@).hasClass("filedrop") or $(@).parents(".filedrop").length
			if $(@).hasClass("filedrop")
				e.stopImmediatePropagation();
				return if $(@).hasClass("dropped")
				$("body").removeClass "dragging"
				$(@).addClass("dragging")

			#console.log "Dragging over filedrop."
		else
			e.stopImmediatePropagation();
			$("body").addClass "dragging"
			#console.log "Dragging over document."

		return

	$("*").on "dragleave", (e) ->
		if $(@).hasClass("filedrop") or $(@).parents(".filedrop").length
			if $(@).hasClass("filedrop")
				e.stopImmediatePropagation();
				return if $(@).hasClass("dropped")
				$(@).removeClass("dragging")

			#console.log "Dragged out of filedrop."
		else
			e.stopImmediatePropagation();
			if e.originalEvent.pageX is 0
				$("body").removeClass "dragging"
				#console.log "Dragged out of document."

		return

	$("*").on "drop", (e) ->
		e.preventDefault();
		e.stopImmediatePropagation();

		$(".dragging").removeClass "dragging"

		if $(@).hasClass("filedrop") or $(@).parents(".filedrop").length
			if $(@).hasClass("filedrop")
				$(@).addClass("dropped")
			else
				$(@).parents(".filedrop").addClass "dropped"

		return false

	###
	$(window).on "dragleave", (e) ->
		e.stopImmediatePropagation();
		$("body").removeClass "dragging"

		return

	$(".filedrop").on "dragenter", (e) ->
		e.stopImmediatePropagation();
		$("body").removeClass "dragging"

		if not $(@).hasClass "dragging"
			$(@).addClass "dragging"
			#root.forceRedraw $(@)
			console.log "Dragged over filedrop."

		return

	$(".filedrop").on "dragleave", (e) ->
		e.stopImmediatePropagation();
		if $(@).hasClass "dragging"
			$(@).removeClass "dragging"
			root.forceRedraw $(@)
			console.log "Dragged out of filedrop."

		return
	###

	###
	$(document).on "dragenter dragover", (e) ->
		return if $(@).data('dragging-zone')

		#root.delay "FILEDROP_DETECTION", 5000, -> $(document).trigger 'dragexit'

		if e.originalEvent.pageX isnt "0" and not $(@).data('dragging')
			$(@).data('dragging', true)
			root.CACHE['filedrops'].addClass('dragover-global');
			root.logEvent ["Dragging into the window."]

		return

	$(document).on "dragexit dragleave", (e) ->
		return unless e
		return unless $(@).data('dragging') or $(@).data('dragging-zone')

		pageX = e.originalEvent.pageX
		scrnX = e.originalEvent.screenX - 10

		pageY = e.originalEvent.pageY
		scrnY = e.originalEvent.screenY - 10

		if pageX is 0 or pageX >= scrnX or pageY is 0 or pageY >= scrnY

			if $(@).data('dragging')
				$(@).data('dragging', false)
				root.logEvent ["Dragged outside the window scope."]

			$(@).data('dragging-zone', false)
			root.CACHE['filedrops'].removeClass('dragover-global dragover').each -> root.forceRedraw $(@)

		return
	###

	###
	$(document).on "mouseout", (e) ->
		if $(@).data('dragging-zone') or $(@).data('dragging')
			e = e ? window.event
			from = e.originalEvent.relatedTarget ? e.originalEvent.toElement
			if not from or from.nodeName is "HTML"
				$(@).trigger "dragexit"

		return
	###

	###
	$(document).on "drop", (e) ->
		if $(@).data('dragging')
			$(@).data('dragging', false)
			$(@).data('dragging-zone', false)
			root.CACHE['filedrops'].removeClass 'dragover-global dragover'.each -> root.forceRedraw $(@)
			e.stopPropagation();
			e.preventDefault();
			return false

		return
	###

	###
	FILEDROP drag and drop events.
	###

	###
	$(".filedrop").on "dragenter dragover", (e) ->

		#root.delay "FILEDROP_DETECTION", 5000, -> $(document).trigger 'dragexit'

		$(@).addClass "dragover"

		#if $(document).data('dragging-zone') isnt $(@)
		#	$(document).data('dragging-zone', $(@))
		#	$(@).addClass "dragover"

		#if $(document).data('dragging')
		#	$(document).data('dragging', false)
		#	$(@).removeClass 'dragover-global'

		return

	$(".filedrop").on "dragexit dragleave", (e) ->

		if $(document).data('dragging-zone')
			$(document).data('dragging-zone', $(@))
			$(@).removeClass 'dragover'
			root.logEvent ["Dragged out of file drop."]

		return

	$(".filedrop").on "drop", (e) ->
		return
	###

	###
	Done.
	###

	return
