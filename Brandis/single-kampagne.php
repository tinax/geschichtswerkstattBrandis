<?php 

/*
Template Name: Campaign Template
Single Post Template: [Campaign Template]
*
*/


get_header(); 
$category = get_the_category();
$post = get_post();

?>

<div class="container">
    <div class="row margintop1 ">
        <?php
        
            if(get_field('header-image',$post->ID)){
                echo '<div class="marginbottom1"><img src="'.get_field('header-image',$post->ID).'"></div>';
            }else{
                echo '<div class="margintop2"> </div>';
            }
        ?>
         <h2>Kampagne vom <?php echo get_the_time('d.m.Y') ?></h2>
        
         
    </div>
    <div class="row listview " role="main" >
    
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
                             
                             <div>
                             <?php 
                             
                                foreach($catArrPost as $postCat){
                                     echo '<div><i class="mdi mdi-tag-multiple icon"></i> '.$postCat->name.'</div>';
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

                             ?>

                             <div>
                                 <?php echo apply_filters('the_content', $post->post_content);  ?>
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