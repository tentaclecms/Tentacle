(function($){$.sticky=function(d,t,o){return $.fn.sticky(d,t,o)};$.fn.sticky=function(d,t,o){var s={'sp':'fast','x':5000,'p':'ptc'};if(o){$.extend(s,o)}if(!d){d=this.html()}var e=true;var f='no';var g=Math.floor(Math.random()*99999);$('.sticky-note').each(function(){if($(this).html()==d&&$(this).is(':visible')){f='yes'}if($(this).attr('id')==g){g=Math.floor(Math.random()*9999999)}});if(!$('body').find('.queue.'+s.p).html()){$('body').append('<div class="queue '+s.p+'"></div>')}if(e){$('.queue.'+s.p).prepend('<div class="'+t+' sticky" id="'+g+'"></div>');$('#'+g).append('<a class="close" rel="'+g+'">&times;</a>');$('#'+g).append('<p class="sticky-note" rel="'+g+'">'+d+'</p>');$('#'+g).slideDown(s.sp);e=true}$('.sticky').ready(function(){if(s.x){$('#'+g).delay(s.x).fadeOut(s.sp)}});$('.sticky .close').click(function(){$('#'+$(this).attr('rel')).dequeue().fadeOut(s.sp)})}})(jQuery);