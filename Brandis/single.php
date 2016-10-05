<?php get_header(); 

?>
<div class="container">
   
    <div class="row listview margintop2" role="main" >
        <table id="wrapper">	
		<?php				
			while (have_posts()) : the_post();
				$start = get_field('von');
                 $end = get_field('bis');

                 $cx=0;
                 $catArrPost = get_the_category($post->ID);
                 $slugArr =[];
                 foreach($catArrPost as $postCat){
                     $slugArr[] = $postCat->slug;
                 }
                 ?>
                 
                 <div class="item_wrapper">
                       <tr class="item <?php echo implode(' ',$slugArr) ?> ">
                         <td class="zeitraum">
                             <div class="zeit"><?php echo displayDate($start, $end);  ?></div>
                             <div>
                             <?php 
                                if(!is_mobile()){
                                    foreach($catArrPost as $postCat){
                                         echo '<div><i class="mdi mdi-tag-multiple icon"></i> '.$postCat->name.'</div>';
                                     }
                                }
                             
                              ?></div>
                             
                         </td>
                         <td class="inhalt">
                             <h2><?php echo $post->post_title  ?></h2>
                             <?php
                                 $video_link = get_field('video', $post->ID);
                                 if($video_link){
                                     echo '<div class="videoWrapper">'.wp_oembed_get($video_link).'</div>';
                                 }
                                 
                                 $audio_link = get_field('audio', $post->ID);
                                 if($audio_link != ''){
                                     $attr = array(
                                        	'src'      => get_field('audio', $post->ID)
                                      );
                                      $audio  = wp_audio_shortcode( $attr );
                                      $audio_bild = get_field('audio_bild', $post->ID);
                                      
                                      echo '<div class="audioWrapper">';
                                      if($audio_bild)
                                        echo '<div class="audio_img"><img src="'.$audio_bild.'"></div>';
                                      echo $audio.'</div>';
                                     
                                 }
                            
                                 $bilder = get_field('bilder', $post->ID);
                                if($bilder){
                                    echo '<div class="sliderWrapper">';
                                    foreach($bilder as $bildObj){ ?>
                                        <div class="slick-container">
                                        <img src="<?php echo $bildObj['bild']  ?>">
                                        <div class="caption">Quelle: <?php echo $bildObj['quelle']  ?></div>
                                        </div>
                                   <?php }
                                   echo '</div';
                                }
                          
                             ?>

                             <div>
                                 <?php 
                                 if(!empty($post->post_content)){
                                     //echo apply_filters('the_content', $post->post_content);  
                                     echo $post->post_content;  
                                 }
                                 ?>
                             </div>
                         </td>
                       </tr>
                    </div><!-- item_wrapper -->
                    
                 <?php
												
			endwhile;
		?>			
	     </table>

    </div>

</div><!-- container -->
<?php get_footer(); ?>