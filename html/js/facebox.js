/*
 * Facebox (for jQuery)
 * version: 1.1 (03/01/2008)
 * @requires jQuery v1.2 or later
 *
 * Examples at http://famspam.com/facebox/
 *
 * Licensed under the MIT:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2007, 2008 Chris Wanstrath [ chris@ozmm.org ]
 *
 * Usage:
 *  
 *  jQuery(document).ready(function() {
 *    jQuery('a[rel*=facebox]').facebox() 
 *  })
 *
 *  <a href="#terms" rel="facebox">Terms</a>
 *    Loads the #terms div in the box
 *
 *  <a href="terms.html" rel="facebox">Terms</a>
 *    Loads the terms.html page in the box
 *
 *  <a href="terms.png" rel="facebox">Terms</a>
 *    Loads the terms.png image in the box
 *
 *
 *  You can also use it programmatically:
 * 
 *    jQuery.facebox('some html')
 *
 *  This will open a facebox with "some html" as the content.
 *    
 *    jQuery.facebox(function() { ajaxes })
 *
 *  This will show a loading screen before the passed function is called,
 *  allowing for a better ajax experience.
 *
 */
(function($) {
  $.facebox = function(data, klass) {
	  alert(data);
    $.facebox.init()
    $.facebox.loading()
    $.isFunction(data) ? data.call($) : $.facebox.reveal(data, klass)
  }

  $.facebox.settings = {
    loading_image : '/images/loading.gif',
    close_image   : '/images/closelabel.gif',
    image_types   : [ 'png', 'jpg', 'jpeg', 'gif' ],
    facebox_html  : '\
  <div id="facebox" style="display:none;"> \
    <div class="f_popup"> \
      <table> \
        <tbody> \
          <tr> \
            <td class="f_tl"/><td class="f_b"/><td class="f_tr"/> \
          </tr> \
          <tr> \
            <td class="f_b"/> \
            <td class="f_body"> \
              <div class="f_content"> \
              </div> \
              <div class="f_footer"> \
                <a href="#" class="f_close"> \
                  <img src="/images/closelabel.gif" title="close" class="f_close_image" /> \
                </a> \
              </div> \
            </td> \
            <td class="f_b"/> \
          </tr> \
          <tr> \
            <td class="f_bl"/><td class="f_b"/><td class="f_br"/> \
          </tr> \
        </tbody> \
      </table> \
    </div> \
  </div>'
  }

  $.facebox.loading = function() {
    if ($('#facebox .loading').length == 1) return true

    $('#facebox .f_content').empty()
    $('#facebox .f_body').children().hide().end().
      append('<div class="f_loading"><img src="'+$.facebox.settings.loading_image+'"/></div>')

    var pageScroll = $.facebox.getPageScroll()
    $('#facebox').css({
      top:	pageScroll[1] + ($.facebox.getPageHeight() / 10),
      left:	pageScroll[0]
    }).show()

    $(document).bind('keydown.facebox', function(e) {
      if (e.keyCode == 27) $.facebox.close()
    })
  }

  $.facebox.reveal = function(data, klass) {
    if (klass) $('#facebox .f_content').addClass(klass)
    $('#facebox .f_content').append(data)
    $('#facebox .f_loading').remove()
    $('#facebox .f_body').children().fadeIn('normal')
  }

  $.facebox.close = function() {
    $(document).trigger('close.facebox')
    return false
  }

  $(document).bind('close.facebox', function() {
    $(document).unbind('keydown.facebox')
    $('#facebox').fadeOut(function() {
      $('#facebox .f_content').removeClass().addClass('f_content')
    })
  })

  $.fn.facebox = function(settings) {
    $.facebox.init(settings)

    var image_types = $.facebox.settings.image_types.join('|')
    image_types = new RegExp('\.' + image_types + '$', 'i')

    function click_handler() {
      $.facebox.loading(true)

      // support for rel="facebox[.inline_popup]" syntax, to add a class
      var klass = $(this).attr("relbox").match(/facebox\[\.(\w+)\]/)
      if (klass) klass = klass[1]

      // div
      if (this.href.match(/#/)) {
        var url    = window.location.href.split('#')[0]
        var target = this.href.replace(url,'')
        $.facebox.reveal($(target).clone().show(), klass)

      // image
      } else if (this.href.match(image_types)) {
        var image = new Image()
        image.onload = function() {
          $.facebox.reveal('<div class="f_image"><img src="' + image.src + '" /></div>', klass)
        }
        image.src = this.href

      // ajax
      } else {
        $.get(this.href, function(data) { $.facebox.reveal(data, klass) })
      }

      return false
    }

    this.click(click_handler)
    return this
  }

  $.facebox.init = function(settings) {
    if ($.facebox.settings.inited) {
      return true
    } else {
      $.facebox.settings.inited = true
    }

    if (settings) $.extend($.facebox.settings, settings)
    $('body').append($.facebox.settings.facebox_html)

    var preload = [ new Image(), new Image() ]
    preload[0].src = $.facebox.settings.close_image
    preload[1].src = $.facebox.settings.loading_image

    $('#facebox').find('.f_b:first, .f_bl, .f_br, .f_tl, .f_tr').each(function() {
      preload.push(new Image())
      preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1')
    })

    $('#facebox .f_close').click($.facebox.close)
    $('#facebox .f_close_image').attr('src', $.facebox.settings.close_image)
  }

  // getPageScroll() by quirksmode.com
  $.facebox.getPageScroll = function() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
      yScroll = self.pageYOffset;
      xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
      yScroll = document.documentElement.scrollTop;
      xScroll = document.documentElement.scrollLeft;
    } else if (document.body) {// all other Explorers
      yScroll = document.body.scrollTop;
      xScroll = document.body.scrollLeft;	
    }
    return new Array(xScroll,yScroll) 
  }

  // adapter from getPageSize() by quirksmode.com
  $.facebox.getPageHeight = function() {
    var windowHeight
    if (self.innerHeight) {	// all except Explorer
      windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
      windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
      windowHeight = document.body.clientHeight;
    }	
    return windowHeight
  }
})(jQuery);
