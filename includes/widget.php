<div class="widget">
  <?php echo isset($instance['title']) ? ('<h2 class="widgettitle">' . $instance['title'] . '</h2>') : ''; ?>
  <ul id="out-of-the-box-demo">
    <?php
      for($i = 0; $i < count($instance['slideshow']); $i++) {
        echo '<li><a href="#slide' . $i . '">';
        echo '<img src="' . wp_get_attachment_url($instance['slideshow'][$i]) . '">';
        echo '</a></li>';
      }
    ?>
  </ul>
</div>

<script>
jQuery(function($){
  $(document).ready(function() {  
    var jspeed = parseInt("<?php echo $instance['slippryjs_speed']; ?>");
    var jpause = parseInt("<?php echo $instance['slippryjs_pause']; ?>");
    var jadaptiveHeight = parseInt("<?php echo $instance['slippryjs_adaptiveHeight']; ?>");
    var jpager = parseInt("<?php echo $instance['slippryjs_pager']; ?>");
    var jcontrols = parseInt("<?php echo $instance['slippryjs_controls']; ?>");

    var slipprySettings = new Object();
    slipprySettings.speed = (jspeed ? jspeed : 800);
    slipprySettings.pause = (jpause ? jpause : 3000);
    slipprySettings.pager = (jpager ? true : false);
    slipprySettings.controls = (jcontrols ? true : false);
    slipprySettings.adaptiveHeight = (jadaptiveHeight ? true : false);

    $('#out-of-the-box-demo').slippry(slipprySettings);
  });
});
</script>