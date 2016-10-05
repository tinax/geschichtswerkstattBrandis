<?php 

/**
 * Template Name: List Campaigns
 * 
 */
 
 get_header(); 
 $preselectedCat = false;

  if(get_query_var('cat')!== null ){
       if(get_query_var('cat') > 0){
           $thisCat = get_category(get_query_var('cat'));
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
     'orderby' => 'meta_value',
     'order' => 'DESC'
);
 $postlist = get_posts( $args );
 

 
?>
<script>
    
    var FilterApp = {};
    var baseUrl =  "<?php bloginfo('stylesheet_directory') ?>" ;
    var listUrl =  "<?php echo home_url('/') ?>list" ;
    
    var preselectedCat = "<?php echo $preselectedCat ?>"; 
    

</script>
<div class="container">
    <div class="row filter-list ">
         <div>Nach Kategorien filtern: </div>
         <ul class="categorylist">
             <?php
                 foreach($categories as $cat){
                         echo '<li><a class="filter" data-termId ="'.$cat->term_id.'" data-filter=".'.$cat->slug.'">'.$cat->name.'</a></li>';
                   }
             ?>
         </ul>
         
    </div>
    <div class="row listview " role="main" >
        <table id="wrapper">
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
          <div class="item_wrapper">
              <tr class="item <?php echo implode(' ',$slugArr) ?> ">
                <td class="zeitraum"><span><?php echo ($start != $end) ? $start.' - '.$end : $end;  ?></span></td>
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
           </div><!-- item_wrapper -->   
          
      <?php
       
        $c++;
     }
     
     ?>
        </table>
   
	</div>
	
</div><!-- container -->
<?php get_footer(); ?>