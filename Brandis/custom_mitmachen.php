<?php 

/**
 * Template Name: Mitmachen
 * 
 */
  acf_form_head();
 get_header(); 


 
?>

<div class="container">
    <div class="row margintop3 mitmachen">
        
        <div class="col-md-offset-2 col-md-8">
        <h2>Ihr Beitrag für die Geschichtswerkstatt Brandis</h2>
        <?php
        
         
             $new_post = array(
                     'post_id'            => 'new', // Create a new post
                     'uploader'           => 'basic',

                     'field_groups'       => array(167), // Create post field group ID(s)
                     'form'               => true,
                     'return'             => home_url('/danke'),//'%post_url%', // Redirect to new post url
                     'html_before_fields' => '',
                     'html_after_fields'  => '',
                     'submit_value'       => 'Beitrag senden',
                     'updated_message'    => 'Danke!'
                 );
                 acf_form( $new_post );
       ?>
       </div>
	</div>
	
</div><!-- container -->
<?php get_footer(); ?>