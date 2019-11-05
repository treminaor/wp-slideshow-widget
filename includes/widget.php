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
  jQuery('#out-of-the-box-demo').slippry();
</script>