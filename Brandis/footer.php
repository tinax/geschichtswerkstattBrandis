<footer>


	<div id="footer-meta" class="container ">
		<div class="row pull-right">
	
	        &copy; <?php echo date("Y"); ?> <a href="<?php echo home_url('/'); ?>/about" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
    	    |
    	    <a href="<?php echo home_url('/'); ?>/impressum">Impressum</a>
	  </div>
	</div>
		
</footer>
<?php wp_footer(); ?>  

<!-- leaflet -->
   

   <script src="<?php bloginfo("stylesheet_directory") ?>/assets/js/plugins.min.js"></script>
  
  <?php /*
  <script src="<?php bloginfo('stylesheet_directory') ?>/assets/js/leaflet-src.js"></script>
   
  
  <!-- timeline-->
   <script src="<?php bloginfo('stylesheet_directory') ?>/assets/js/timeline-custom.min.js"></script>
  <!--JQRangeslider -->
  <script src="<?php bloginfo('stylesheet_directory') ?>/assets/js/jquery-ui.min.js"></script>
  <script src="<?php bloginfo('stylesheet_directory') ?>/assets/js/jQAllRangeSliders-min.js"></script>
  <!-- slick slider -->
  <script src="<?php bloginfo('stylesheet_directory') ?>/assets/js/slick.min.js"></script>
   */  ?>
    <script type="text/javascript" src="http://maps.stamen.com/js/tile.stamen.js?v1.3.0"></script>
    <script src="<?php bloginfo('stylesheet_directory') ?>/assets/js/leaflet.markercluster.js"></script>
</body>
</html>