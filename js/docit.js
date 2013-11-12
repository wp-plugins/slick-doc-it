jQuery( document ).ready(function() {
  jQuery( ".docit-main-header" ).click(function() {  
		 jQuery(this).nextUntil().find('.docit-sub-post').slideToggle();
			jQuery(this).nextUntil().find('.docit-sub-sub-menu-wrap').slideToggle();
});
jQuery(".docit-main-header a").click(function(e) {
   e.stopPropagation();
})   
jQuery( ".docit-sub-header" ).click(function() {
    jQuery(this).nextUntil('div').slideToggle();
}); 
jQuery(".docit-sub-header a").click(function(e) {
   e.stopPropagation();
})
  });