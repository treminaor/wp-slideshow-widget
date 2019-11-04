<p>
	<h4><em>General Settings</em></h4>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>">
		<?php _e( 'Widget Title', 'wp_slideshow_widget_plugin' ); ?>
	</label>
	<input class="widefat" placeholder="none" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
</p>

<p>
	<h4><em>Add/Edit Slideshow Images</em></h4>
	<?php $slideshow_id = $this->get_field_id( 'slideshow' ); ?>
	<label for="<?php echo $slideshow_id ?>">
		<?php _e( 'Slideshow Position', 'wp_slideshow_widget_plugin' ); ?>
	</label>
	<select class="wp_slideshow_widget_slideshow" id="<?php echo $slideshow_id; ?>" name="<?php echo $slideshow_id; ?>" >
		<?php
			$index = 0;
			foreach($this->get_slideshow_array() as $slide) {
				echo "<option>$index</option>";
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
jQuery(function($){
	$(document).ready(function() {	
		var selectID = "<?php echo $slideshow_id ?>";
		var selectBox = $('#' + selectID);
		var selectBoxLength = selectBox.length - 1;
		var widgetContainer = selectBox.closest('.widget-content');
		var emptyWarning = widgetContainer.children('#wpsw_empty_warning');
		var previewArea = widgetContainer.children('#wpsw_image_preview');
		var addButton = widgetContainer.children('#add_slide');
		var removeButton = widgetContainer.children('#remove_slide');
		var uploadButton = widgetContainer.children('.wpsw_upload_button');
		
		var custom_uploader;
		var attachment;

		//Intialize form state for empty slideshow. By default the form php assumes the slide is populated with data on load.
		if(selectBoxLength == 0) {
			uploadButton.hide();
			selectBox.prop('disabled', true);
		}

		/**
		 * Add a slide
		 */
		addButton.on('click', function () {
			if($(getSelectedObject()).attr('name') || selectBoxLength == 0) {
				selectBox.append('<option value="' + selectBoxLength + '">' + selectBoxLength + '</option>');
				selectBox.val(selectBoxLength);
				selectBoxLength++;
				
				emptyWarning.hide();
				selectBox.prop('disabled', false);
				uploadButton.html('Upload Image');
				uploadButton.show();
				previewArea.empty();
			}
			else
				alert("You must add content to the current slide before adding another one.");
		});

		/**
		 * Remove a slide
		 */
		removeButton.on('click', function () {
			var oldLength = selectBoxLength;
			var newLength = selectBoxLength - 1;
			if(selectBoxLength - 1 < 0) {
				newLength = 0;
			}
			var removeThis = selectBox.prop('options')[selectBox.prop('selectedIndex')];
			var removedIndex = removeThis.value;
			removeThis.remove();

			selectBoxLength = newLength;
			console.log('removedIndex: ' + removedIndex + ', oldLength: ' + oldLength + ', newLength: ' + newLength + ', selectBoxLength: ' + selectBoxLength);
			if(removedIndex == 0 && newLength > 0) { //they removed the beginning of a still non-singular stack
				selectBox.val(1); 
				console.log('Removed something from the beginning of a non-singular stack');
			}
			else if(removedIndex == newLength && newLength > 0) { //they removed the end of a still non-singular stack
				selectBox.val(newLength - 1);
				console.log('Removed something from the end of a non-singular stack');
			}
			else if(newLength > 0) { //they removed something from the middle of a stack.
				//selectBox.val(newLength);
				selectBox.val(newLength);
				console.log('Removed something from the middle of a stack (length is therefore inferred to be >= 3')
			}
			else { //they removed the only entry in the stack
				console.log('selectBox has no entries now');
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
				$(selectBox.prop('options')[selectBox.prop('selectedIndex')]).attr('name', ''); 
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
		        $(selectBox.prop('options')[selectBox.prop('selectedIndex')]).attr('name', attachment.id);
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
			var selected = selectBox.prop('options')[selectBox.prop('selectedIndex')];
			if(selected) {
		   		var slideshowIndex = selected.value;
			    var attachment = wp.media.attachment($(selected).attr('name')).attributes;
			    setPreviewImage(attachment.url);
			}
			else{
				uploadButton.hide();
				emptyWarning.show();
				selectBox.prop('disabled', true);
			}

		}

		/**
		 * Rebuilds the indexed number values of selectBox after an index has been removed.
		 */
		function rebuildOptionValues() {
			var i; 
			for(i=0; i<selectBox.length + 1; i++) {
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
		    previewArea.append('<img class="true_pre_image" src="' + attachment_url + '" style="max-height:100px;max-width:300px;display:block;" />');
		}
	});
});
</script>