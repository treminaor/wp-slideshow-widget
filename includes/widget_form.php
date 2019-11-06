<p>
	<h4><em>General Settings</em></h4>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>">
		<?php _e( 'Widget Title', 'wp_slideshow_widget_plugin' ); ?>
	</label>
	<input class="widefat" placeholder="none" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'slippryjs_pause' ); ?>">
		<?php _e( 'Pause Time (in ms)', 'wp_slideshow_widget_plugin' ); ?>
	</label>
	<input class="widefat" placeholder="3000" id="<?php echo $this->get_field_id( 'slippryjs_pause' ); ?>" name="<?php echo $this->get_field_name( 'slippryjs_pause' ); ?>" type="number" value="<?php echo esc_attr( $instance['slippryjs_pause'] ); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'slippryjs_speed' ); ?>">
		<?php _e( 'Transition Speed (in ms)', 'wp_slideshow_widget_plugin' ); ?>
	</label>
	<input class="widefat" placeholder="800" id="<?php echo $this->get_field_id( 'slippryjs_speed' ); ?>" name="<?php echo $this->get_field_name( 'slippryjs_speed' ); ?>" type="number" value="<?php echo esc_attr( $instance['slippryjs_speed'] ); ?>" />
</p>
<p>
	<input type="hidden" name="<?php echo $this->get_field_name('slippryjs_pager'); ?>" value="">
	<input class="checkbox" type="checkbox" value="1" id="<?php echo $this->get_field_id('slippryjs_pager'); ?>" name="<?php echo $this->get_field_name('slippryjs_pager'); ?>" <?php checked( $instance['slippryjs_pager'] ); ?> />
	<label for="<?php echo $this->get_field_id('slippryjs_pager'); ?>">
		<?php _e( 'Show Pagination?', 'wp_slideshow_widget_plugin' ); ?>
	</label>
	<br/>
	<input type="hidden" name="<?php echo $this->get_field_name('slippryjs_adaptiveHeight'); ?>" value="">
	<input class="checkbox" type="checkbox" value="1" id="<?php echo $this->get_field_id('slippryjs_adaptiveHeight'); ?>" name="<?php echo $this->get_field_name('slippryjs_adaptiveHeight'); ?>" <?php checked( $instance['slippryjs_adaptiveHeight'] ); ?> />
	<label for="<?php echo $this->get_field_id('slippryjs_adaptiveHeight'); ?>">
		<?php _e( 'Adaptive Slider Height?', 'wp_slideshow_widget_plugin' ); ?>
	</label>
	<br/>
	<input type="hidden" name="<?php echo $this->get_field_name('slippryjs_controls'); ?>" value="">
	<input class="checkbox" type="checkbox" value="1" id="<?php echo $this->get_field_id('slippryjs_controls'); ?>" name="<?php echo $this->get_field_name('slippryjs_controls'); ?>" <?php checked( $instance['slippryjs_controls'] ); ?> />
	<label for="<?php echo $this->get_field_id('slippryjs_controls'); ?>">
		<?php _e( 'Show Next/Prev Controls?', 'wp_slideshow_widget_plugin' ); ?>
	</label>	
</p>

<p>
	<h4><em>Add/Edit Slideshow Images</em></h4>
	<?php $slideshow_id = $this->get_field_id( 'wpsw_select' ); ?>
	<?php $slideshow_data = $this->get_field_name( 'slideshow[]' ); ?>
	<label for="<?php echo $slideshow_id ?>">
		<?php _e( 'Slideshow Position', 'wp_slideshow_widget_plugin' ); ?>
	</label>
	<select class="wp_slideshow_widget_slideshow" id="<?php echo $slideshow_id; ?>" name="<?php echo $this->get_field_name( 'wpsw_select[]' ); ?>" >
		<?php
			$index = 0;
			$slides = $instance['slideshow'];
			foreach($slides as $slide) {
				echo '<option name="'. $slide. '" value="' . $index . '">' . $index . '</option>';
				$index++;
			}
		?>
	</select>
	<button id="add_slide" type="button">Add</button>
	<button id="remove_slide" type="button">Remove</button>
	<span id="wpsw_empty_warning"><br><em>Slideshow is empty, add a slide to begin.</em></span>
	<div id="wpsw_image_preview">
	</div>
	<button class="wpsw_upload_button" type="button">Upload Image</button>
</p>
<hr/>

<script>
	/*
	@todo: need to array-ify the selectBox options on form submission so that PHP seens the full list of objects and not just the selected one. Google says to do this on form submission from js by creating an array: https://stackoverflow.com/questions/15190464/how-do-i-post-all-options-in-a-select-list
		In order to do this I need to catch the form save(), which is triggered from an input element. So far I can't get the selector to find the element. 
	 */
jQuery(function($){
	$(document).ready(function() {	
		var selectID = "<?php echo $slideshow_id ?>";
		var selectBox = $('#' + selectID);
		var selectBoxLength = selectBox.children().length;
		var widgetContainer = selectBox.closest('.widget-content');
		var widgetID = widgetContainer.parent().find('.widget-id').prop('value');
		var emptyWarning = widgetContainer.children('#wpsw_empty_warning');
		var previewArea = widgetContainer.children('#wpsw_image_preview');
		var addButton = widgetContainer.children('#add_slide');
		var removeButton = widgetContainer.children('#remove_slide');
		var uploadButton = widgetContainer.children('.wpsw_upload_button');
		var submitButton = widgetContainer.parent().find('input[name=savewidget]');
		
		var custom_uploader;
		var attachment;

		//Intialize form state based on slideshow data
		if(selectBoxLength == 0) {
			uploadButton.hide();
			selectBox.prop('disabled', true);
			removeButton.prop('disabled', true);
		}
		else {
			emptyWarning.hide();
			uploadButton.html('Remove Image');
			updateSlideIndex();
		}

		/**
		 * Catch the form submission so we can send all selectBox option values instead of just the selected index.
		 */
		submitButton.on('click', function() {
			var i; 
			var postName = "<?php echo $slideshow_data ?>";
			$('input[name="' + postName + '"]').remove();
			for(i=0; i<selectBox.children().length; i++) {
				var attach_id = $(selectBox.prop('options')[i]).attr('name');
				if(attach_id)
					widgetContainer.append('<input type="hidden" name="' + postName + '" value="' + attach_id + '"/>');
			}
		});

		/**
		 * Add a slide
		 */
		addButton.on('click', function () {
			if(uploadButton.is(":visible") && uploadButton.html() == 'Upload Image') {
				alert("You must add content to the current slide before adding another one.");
			}
			else {
				var selected = getSelectedObject();
				
				if(selected) { //selectBox was populated, add an additional option
					var newIndex = parseInt(selected.value) + 1;
					$(selected).after('<option value="' + newIndex + '">' + newIndex + '</option>');
					selectBox.val(newIndex);
				}
				else { //selectBox was empty
					selectBox.append('<option value="0">0</option>');
				}
				
				selectBoxLength++;
				rebuildOptionValues();

				emptyWarning.hide();
				selectBox.prop('disabled', false);
				removeButton.prop('disabled', false);
				uploadButton.html('Upload Image');
				uploadButton.show();
				previewArea.empty();
			}
		});

		/**
		 * Remove a slide
		 */
		removeButton.on('click', function () {
			var oldLength = selectBoxLength;
			var newLength = selectBoxLength - 1;
			
			if(selectBoxLength - 1 < 0) { //length should never go negative.
				newLength = 0;
			}

			var removeThis = getSelectedObject();
			var removedIndex = removeThis.value;
			removeThis.remove();

			selectBoxLength = newLength;

			if(removedIndex == 0 && newLength > 0) { //they removed the beginning of a still non-singular stack
				selectBox.val(1); 
			}
			else if(removedIndex == newLength && newLength > 0) { //they removed the end of a still non-singular stack
				selectBox.val(newLength - 1);
			}
			else if(newLength > 0) { //they removed something from the middle of a stack.
				//selectBox.val(newLength);
				selectBox.val(newLength);
			}
			else { //they removed the only entry in the stack
				//do nothing
			}
			
			allowWidgetSaveChanges();
			rebuildOptionValues();
			updateSlideIndex();
		});

		selectBox.on('change', function(){
			updateSlideIndex();
		});
		
		/**
		 * Upload a slide image.
		 */
		uploadButton.on('click', function() {
			if(uploadButton.html() == 'Remove Image') {
				previewArea.empty();
				$(getSelectedObject()).attr('name', ''); 
				uploadButton.html('Upload Image');
				return;
			}

		    var button = $(this);
		    custom_uploader = wp.media.frames.file_frame = wp.media({
		        title: 'Choose File',
		        library: {
		        	type: 'image'
		        },
		        button: {
		            text: 'Choose File'
		        },
		        frame: 'select',
		        multiple: false
		    });

		    custom_uploader.on('select', function() { 
		        var attachment = custom_uploader.state().get('selection').first().toJSON();
		        custom_uploader.close();
		        uploadButton.html('Remove Image');
		        $(getSelectedObject()).attr('name', attachment.id);
		        setPreviewImage(attachment.url);
		        allowWidgetSaveChanges();
		    })
		    .open();
		});

		/**
		 * WordPress doesn't see change events on dynamically added elements, so we can control when changes are detected by manually triggering a change on any element.
		 */
		function allowWidgetSaveChanges() {
			addButton.trigger('change');
			submitButton = widgetContainer.find('input[type=submit]');
			console.log(submitButton);
		}

		/**
		 * Returns the selected option object.
		 * @return {Object}
		 */
		function getSelectedObject() {
			return selectBox.prop('options')[selectBox.prop('selectedIndex')];
		}

		/**
		 * Clears the previewArea, updates the image preview to the selected index.
		 */
		function updateSlideIndex() {
			previewArea.empty();
			var selected = getSelectedObject();
			if(selected) {
			    var previewID = $(selected).attr('name');
			    wp.media.attachment(previewID).fetch().then(function (data) {
				  setPreviewImage(wp.media.attachment(previewID).get('url'));
				});
			    
			}
			else{
				uploadButton.hide();
				emptyWarning.show();
				selectBox.prop('disabled', true);
				removeButton.prop('disabled', true);
			}

		}

		/**
		 * Rebuilds the indexed number values of selectBox after an index has been removed.
		 */
		function rebuildOptionValues() {
			var i; 
			for(i=0; i<selectBox.children().length; i++) {
				$(selectBox.prop('options')[i]).attr('value', i)
				$(selectBox.prop('options')[i]).html(i);
			}
		}

		/**
		 * Sets the content of the preview image area based on an image URL.
		 * @param {string}
		 */
		function setPreviewImage(attachment_url) {
			previewArea.empty();
			if(attachment_url) {
		    	previewArea.append('<img class="true_pre_image" src="' + attachment_url + '" style="max-height:100px;max-width:300px;display:block;" />');
			}
		}
	});
});
</script>