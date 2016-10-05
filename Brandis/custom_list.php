<?php 

/**
 * Template Name: List Template
 * 
 */
 
 get_header(); 
 $preselectedCat = false;

  if(get_query_var('cat')!== null ){
       if(get_query_var('cat') > 0){
           $thisCat = get_category(get_query_var('cat'));
           $preselectedCatID = $thisCat->term_id;
           $preselectedCat = '.'.$thisCat->slug;
       }
   }
 
 $categories = get_categories();
  $c=0;
  foreach($categories as $cat){
         $catArrShort[$c]['term_id'] = $cat->term_id;
         $catArrShort[$c]['name'] = $cat->name;
         $c++;
  }

 $args = array( 
     'numberposts' => 1000,     
      'category__and'=> $catArr,
     'meta_key' => 'bis',
     'meta_type' => 'DATE',
     'orderby' => 'meta_value',
     'order' => 'DESC'
);


 $postlist = get_posts( $args );
 
 //get Years Range for slider
 $yearsArr=[];
 foreach($postlist as $post){
     $yearsArr[] = getYear(get_field('von',$post->ID));
     $yearsArr[] = getYear(get_field('bis',$post->ID));
 }
 //die("hier:".var_dump($postlist));
 array_unique($yearsArr);
  sort($yearsArr,SORT_NUMERIC);
  
  $sliderStart = $yearsArr[0];
  $sliderEnd = $yearsArr[count($yearsArr)-1];
  
?>
<script>
    
    var FilterApp = {};
    var baseUrl =  "<?php bloginfo('stylesheet_directory') ?>" ;
    var listUrl =  "<?php echo home_url('/') ?>list" ;
    
    var preselectedCat = "<?php echo $preselectedCat ?>"; 
    var sliderStart = "<?php echo $sliderStart ?>"; 
     var sliderEnd = "<?php echo $sliderEnd ?>";
     var thisYear = "<?php echo date("Y") +5 ?>";
     var startYear = "<?php echo $sliderStart -5 ?>";
    

</script>
<div class="container">
    <div class="row margintop2  ">
         <div>Nach Kategorien filtern: </div>
         <ul class="categorylist">
            <!--<li><a class="filter" id ="cat_neu" title="Neueste Beiträge">Neu</a></li>-->
             <?php
                 foreach($categories as $cat){
                         echo '<li><a class="filter" data-termId ="'.$cat->term_id.'" data-filter=".'.$cat->slug.'" title="'.$cat->description.'">'.$cat->name.'</a></li>';
                   }
             ?>
         </ul>
         
    </div>
    <div class="row listview " role="main" >
        

                <div class=" timeslider">
                  
                     <div id="slider"></div>
                  

                </div>
<table id="contentWrapper">
    <?php
    
    $c = 0;

    
     foreach($postlist as $post){

         $start = get_field('von');
         $end = get_field('bis');
         
         
         
         $catArrPost = get_the_category($post->ID);
         $slugArr =[];
         foreach($catArrPost as $postCat){
             $slugArr[] = $postCat->slug;
         }
          ?>
          

              <tr class="item <?php echo implode(' ',$slugArr) ?> ">
                <td class="zeitraum"><span><?php 
                    echo displayDate($start, $end);
                ?></span></td>
                <td class="inhalt">
                    <h2><a href="<?php echo get_permalink($post->ID)  ?>"><?php echo $post->post_title  ?></a></h2>
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
                                 //echo var_dump($bilder);
                                 echo '<div class="sliderWrapper">';
                                 foreach($bilder as $bildObj){ 
                                     
                                     $attachId = fjarrett_get_attachment_id_by_url( $bildObj['bild'] ) ;
                                     $alt = get_post_meta( $attachId, '_wp_attachment_image_alt', true);
                                     $caption = get_post_field('post_excerpt', $attachId);
                                     ?>
                                     <div class="slick-container">
                                     <img src="<?php echo $bildObj['bild']  ?>" alt="<?php echo $alt  ?>">
                                     <div class="caption"><?php echo $caption  ?></div>
                                     <div class="caption">Quelle: <i><?php echo $bildObj['quelle']  ?></i></div>
                                     </div>
                                <?php }
                                echo '</div';
                             }
                         
               
                    ?>
                
                    <div>
                        <?php echo apply_filters('the_content', $post->post_content);  ?>
                    </div>
                </td>
              </tr>

          
      <?php
       
        $c++;
     }
     
     ?>
        </table>
   
	</div>

	
</div><!-- container -->

<?php get_footer(); ?>