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
     'numberposts' => 500, 
     'meta_key' => 'bis',    
      'meta_type' => 'DATE',
     'category' => $preselectedCatID,
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
    var preselectedCatID = "<?php echo $preselectedCatID ?>"; 
    
    var sliderStart = "<?php echo $sliderStart ?>"; 
     var sliderEnd = "<?php echo $sliderEnd ?>";
    

</script>
<div class="container">
    
    <div class="row listview margintop2" role="main" >
        <table class="wrapper">
        
            <div class="item_wrapper">
                  <tr class="item ">
                    <td class="zeitraum ">
                        <div class="cat-anzeige"><i class="mdi mdi-tag-multiple icon"></i> <?php echo $thisCat->name ?></div>
                    </td>
                    <td class="inhalt">
                            <i><?php echo $thisCat->description;  ?></i>

                    </td>
                  </tr>
             </div><!-- item_wrapper -->
             
              <div class="item_wrapper">
                    <tr class="item ">
                      <td colspan="2" class="timeslider ">
                         <div id="slider"></div>
                      </td>

                    </tr>
               </div><!-- item_wrapper -->
               </table> 
               
<table id="contentWrapper">
    <?php
    
    $c = 0;
    

     foreach($postlist as $post){

         $start = get_field('von');
         $end = get_field('bis');
         
         $cx=0;
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