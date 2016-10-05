<?php 

get_header(); 

?>
<div class="container margintop3 normalpage">
	<div class="row" role="main">		
		<?php				
			while (have_posts()) : the_post();					
				get_template_part('content', 'page');													
			endwhile;
		?>		
	</div>
</div>
<?php get_footer(); ?>				