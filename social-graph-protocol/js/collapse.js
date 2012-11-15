jQuery(document).ready(function(){
    jQuery('.collapse').click(function() {
	  jQuery('.toggleMenu-' + jQuery.trim(jQuery(this).attr('id').replace('toggle-a', '')) ).toggle();
	  
	  if( jQuery('.toggleMenu-' + jQuery.trim(jQuery(this).attr('id').replace('toggle-a', ''))).is(":hidden") ) {
	  	jQuery(this).html('Expand List');
	  	jQuery(this).parent('p').attr('style','font-weight:bold;font-size:14px;margin-bottom:-30px;');
	  }
	  else {
	  	jQuery(this).html('Collapse List');
	  	jQuery(this).parent('p').attr('style','margin-bottom:-10px;font-weight:bold;font-size:14px;');
	  }
	  
	  if(jQuery.trim(jQuery(this).attr('id').replace('toggle-a', ''))=='6'){
	  	jQuery(this).parent('p').attr('style','font-weight:bold;font-size:14px;');
	  }
	});
});