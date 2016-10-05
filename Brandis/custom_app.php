<?php 

/**
 * Template Name: App Template
 * 
 */
 
 get_header(); 
 
 
 //get coordinates get_geocode_latlng( $post->ID );
 
 $geoData['type'] = 'FeatureCollection';
 
 $args = array( 
     'numberposts' => 100, 
     'order'=> 'DESC', 
     'orderby' => 'date'
);
 $postlist = get_posts( $args );
 
 
 $tl_Obj = array();
 $tl_Obj[0]['id'] = 'Brandis Stories';
 $tl_Obj[0]['focus_date'] = '2005-06-20 12:00:00';
 $tl_Obj[0]['initial_zoom'] = '49';
 //$tl_Obj[0]['image_lane_height'] = 0;
 //$tl_Obj[0]['collapsed'] = true;
 
 $catArrShort = [];
 $args = array( 
      'order'=> 'DESC', 
      'orderby' => 'count'
 );
 $categories = get_categories($args);
 $c=0;
 foreach($categories as $cat){
        $catArrShort[$c]['term_id'] = $cat->term_id;
        $catArrShort[$c]['label'] = $cat->name;
        $catArrShort[$c]['description'] = $cat->description;
        $catArrShort[$c]['idx'] = $c;
        $c++;
 }
  
 $events = [];
 $c = 0;
     
//Kampagne
$campaign = get_posts(array('post_type' => array('kampagne'),'numberposts' => 1 ));

 foreach($postlist as $post){
     
 
    $start = get_field('von');
    $end = get_field('bis');
      
    //if(get_geocode_lng( $post->ID )){
    if(get_wpgeo_longitude( $post->ID )){
         //build feature props for leaflet
         $feature['type']   = "Feature";
         $feature['id']     = $post->ID.'';
         $feature['content'] = $post->post_content;
     
         $catArr = get_the_category( $post->ID );

         //$feature['properties']['categories']    = get_the_category( $post->ID );
         $feature['geometry']['type']     = "Point";
         //$feature['geometry']['coordinates']     = array( get_geocode_lng( $post->ID ), get_geocode_lat( $post->ID ));
         $feature['geometry']['coordinates']     = array( get_wpgeo_longitude( $post->ID ), get_wpgeo_latitude( $post->ID ));

        

         $feature['startdate'] = $start;
         $feature['enddate'] = $end;
         $feature['title'] = $post->post_title;
         $feature['date_display'] = 'ye';
         $feature['css_class'] = 'timeline-item';
         $feature['icon'] = 'none';
     
     
         $geoData['features'][] = $feature;
     }
     
    
    if(substr_count($start , "-") > 0){
        if(substr($start, 0, 1) =='-'){
            
            $bcStartDate =substr($start, 1, strlen($start)-1);
            $tmp = explode("-",$bcStartDate);
            $year = '-'.$tmp[0];
            $month = $tmp[1];
            $day = $tmp[2];
            
        }else{
            $tmp = explode("-",$start);
            if(substr_count($start , "-") == 2){
            
                $day = $tmp[2];
                $month = $tmp[1];
                $year = $tmp[0];
            }else{ //nur mm.YYYY
                $day = '01';
                $month = $tmp[1];
                $year = $tmp[0];
            }
        }
    }else{
        $day = '01';
        $month = '01';
        $year = $start;
    }
     
     $tl_events[$c]['start_date']['year'] = $year;
     $tl_events[$c]['start_date']['month'] = $month;
     $tl_events[$c]['start_date']['day'] = $day;
     
     if(substr_count($end , "-") > 0){
         if(substr($end, 0, 1) =='-'){

             $bcEndDate =substr($end, 1, strlen($end)-1);
             $tmp = explode("-",$bcEndDate);
             $year = '-'.$tmp[0];
             $month = $tmp[1];
             $day = $tmp[2];

         }else{
             $tmp = explode("-",$end);
             if(substr_count($end , "-") == 2){
                 $day = $tmp[2];
                 $month = $tmp[1];
                 $year = $tmp[0];
             }else{ //nur mm.YYYY
                 $day = '01';
                 $month = $tmp[1];
                 $year = $tmp[2];
             }
         }
     }else{
         $day = '01';
         $month = '01';
         $year = $end;
     }
     $tl_events[$c]['end_date']['year'] = $year;
      $tl_events[$c]['end_date']['month'] = $month;
      $tl_events[$c]['end_date']['day'] = $day;
     

     $tl_events[$c]['text']['headline'] = $post->post_title;
    // $tl_events[$c]['location']['lat'] = trim(get_geocode_lat( $post->ID ));
    // $tl_events[$c]['location']['lon'] = trim(get_geocode_lng( $post->ID ));
     $tl_events[$c]['unique_id'] = $post->ID.''; //should be string
     
     
     
      
     
     
     
     
    $c++;
 }
 //json for timeline
 $tlData['events'] = $tl_events;
 
?>
<script>

    var App = {};

    var geoJsonData = [<?php echo json_encode($geoData); ?>];
    
    var timeline_json = <?php echo json_encode($tlData); ?>;
    
    var categories_json = <?php echo json_encode($catArrShort); ?>;
    
    var baseUrl =  "<?php bloginfo('stylesheet_directory') ?>" ;
    

</script>
<div class="container">
    <div class="row "  role="main">	
    
	    <div id="map"></div>
	    <div id='timeline'> </div>
    </div>
   
<div id='campaign'> 
<?php
    if(count($campaign) > 0){
        $txt = $campaign[0]->post_content;
        $tmp=explode(" ",$txt);
	   $teilArr=array_chunk($tmp, 13);
	   $teilstring = implode(" ",$teilArr[0]);

		if(isset($teilArr[1])){ //mdi-arrow-right
			$teilstring .= '... <div class="weiterlink"><a href="'.get_post_permalink($campaign[0]->ID).'" title="weiter" ><i class="mdi mdi-arrow-right icon"></i> </a></div>';
		}
        $campaignTxt = $teilstring;
        
        echo' <div class="close_campaign"><a href="#" ><i class="mdi mdi-close icon"></i></a></div>';
        echo'<h2><a href="'.get_post_permalink($campaign[0]->ID).'">'.$campaign[0]->post_title.'</a></h2>';
        
        echo '<p>'.$campaignTxt.'</p>';
    }

?>
</div>	
	
</div>
<?php get_footer(); ?>