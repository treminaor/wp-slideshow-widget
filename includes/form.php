<p>
	<h4><em>General Settings</em></h4>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>">
		<?php _e( 'Widget Title', 'wp_slideshow_widget_plugin' ); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
</p>

<p>
	<h4><em>Add/Edit Slideshow Images</em></h4>
	<label for="<?php echo $this->get_field_id( 'index' ); ?>">
		<?php _e( 'Slideshow Position', 'wp_slideshow_widget_plugin' ); ?>
	</label>
	<select class="wp_slideshow_widget_slideshow" id="<?php echo $this->get_field_id( 'index' ); ?>" name="<?php echo $this->get_field_name( 'index' ); ?>" >
		<?php
			$index = 0;
			?><div id="options_wrapper"><?php
			foreach($this->get_slideshow_array() as $slide) {
				echo "<option>$index</option>";
				$index++;
			}
			?></div><?php
			if($index == 0) {
				echo "<option>Empty</option>";
			}
		?>
	</select>
	<button id="add_slide" type="button">Add Slide</button>
	<button id="remove_slide" type="button">Remove Slide</button>
</p>
<hr/>