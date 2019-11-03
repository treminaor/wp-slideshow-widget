$(document).ready(function() {	
	var addButton = $('#add_slide');
	var wrapper = $('.wp_slideshow_widget_slideshow'); //Fields wrapper
	var selectIndex = wrapper.length;
	
	var x = 1; //initlal text box count
	$(addButton).click(function(e){ //on add input button click
		$(wrapper).append('<option>' + selectIndex + '</option>'); //add input box
	});
});