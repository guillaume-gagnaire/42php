function 							Viewer(viewer_id, clickedEl) {
	this.images = [];
	this.col = false;
	this.viewed = 0;
	
	(function(o, c, i){
		$('[data-viewer='+i+']').each(function(index){
			var url = '';
			var height = 0;
			var width = 0;
			
			if (this.tagName.toLowerCase() == 'img') {
				url = this.src;
				height = this.height;
				width = this.width;
			}
			if (this.hasAttribute('data-viewer-src'))
				url = this.getAttribute('data-viewer-src');
			if (this.hasAttribute('data-height'))
				height = this.getAttribute('data-height');
			if (this.hasAttribute('data-width'))
				width = this.getAttribute('data-width');
			if (url != '')
				o.images.push({
					url: url,
					height: height,
					width: width
				});
			if (c == this)
				o.viewed = index;
		});
		
		var c = $('[data-viewer-column='+i+']');
		if (c.length)
			o.col = c[0];
	})(this, clickedEl, viewer_id);
	
	if (!this.images.length)
		return;
	
	/*
	** Draw
	*/
	this.background = null;
	this.window = null;
	this.closeBttn = null;
	this.column = null;
	this.viewer = null;
	this.viewerContainer = null;
	this.nextBttn = null;
	this.prevBttn = null;
	
	this.setupWindow = function() {
		// Background
		this.background = document.createElement('div');
		$(this.background).addClass('viewer-background').appendTo('body');
		
		(function(o){
			$(o.background).click(function(){
				o.close();
			});
		})(this);
		
		
		// Window
		this.window = document.createElement('div');
		$(this.window).addClass('viewer-window').appendTo($(this.background));
		if (this.col)
			$(this.window).addClass('with-column');
		(function(o){
			$(o.window).click(function(event){
				event.stopPropagation();
			});
		})(this);
		
		// Viewer
		this.viewerContainer = document.createElement('div');
		$(this.viewerContainer).addClass('viewer-container').appendTo($(this.window));
		this.viewer = document.createElement('div');
		$(this.viewer).addClass('viewer-viewer').appendTo($(this.viewerContainer));
		
		
		// closeBttn
		this.closeBttn = document.createElement('a');
		$(this.closeBttn).addClass('viewer-close').html('<i class="fa fa-close"></i>').appendTo($(this.window));
		(function(o){
			$(o.closeBttn).click(function(){
				o.close();
			});
		})(this);
		
		
		// Column
		if (this.col) {
			this.column = document.createElement('div');
			$(this.column).addClass('viewer-column').appendTo($(this.window));
			$(this.column).html($(this.col).html());
		}
		
		
		// nextBttn
		this.nextBttn = document.createElement('a');
		$(this.nextBttn).addClass('viewer-next').html('<i class="fa fa-chevron-right"></i>').appendTo($(this.viewer));
		(function(o){
			$(o.nextBttn).click(function(){
				o.next();
			});
		})(this);
		
		
		// prevBttn
		this.prevBttn = document.createElement('a');
		$(this.prevBttn).addClass('viewer-prev').html('<i class="fa fa-chevron-left"></i>').appendTo($(this.viewer));
		(function(o){
			$(o.prevBttn).click(function(){
				o.prev();
			});
		})(this);
		
		
		this.display();
	};
	
	this.display = function() {
		if (!this.images[this.viewed])
			this.viewed = 0;
		this.viewer.style.backgroundImage = 'url(' + this.images[this.viewed].url + ')';
		this.resize();
	};
	
	this.close = function() {
		$(this.background).remove();
	};
	
	this.resize = function() {
		if (this.images[this.viewed].height > $(this.viewer).height() || this.images[this.viewed].width > $(this.viewer).width())
			this.viewer.style.backgroundSize = 'contain';
		else
			this.viewer.style.backgroundSize = 'auto';
	};
	
	/*
	** Events
	*/
	this.next = function() {
		if (this.viewed == this.images.length - 1)
			this.viewed = 0;
		else
			this.viewed += 1;
		this.display();
	};
	
	this.prev = function() {
		if (this.viewed == 0)
			this.viewed = this.images.length - 1;
		else
			this.viewed -= 1;
		this.display();
	};
	
	(function(o){
		// resize handle
		$(window).resize(function(){
			o.resize();
		}).keyup(function(e){
			switch (e.keyCode) {
			case 37:
				o.prev();
				break;
			case 39:
				o.next();
				break;
			case 27:
				o.close();
				break;
			}
		});
	})(this);
	
	this.setupWindow();
}

$(function(){
	$(document).on('click', '[data-viewer]', function(){
		new Viewer($(this).attr('data-viewer'), this);
	});
});