<?php

// theme setup
if (!function_exists('brandis_setup')):
	function brandis_setup() {	
	 //   register_nav_menus( array(
	 //   	'primary'   => __('Primary Menu', 'brandis'),			
	 //   	'footer'   => __('Footer Menu', 'brandis')	
	 //   ));
	//	add_theme_support('post-thumbnails');
		add_image_size('featured', 1170, 400, true);	
		//add_theme_support('automatic-feed-links');
		// editor style
		function brandis_editor_style() {
		  add_editor_style( get_template_directory_uri() . '/assets/css/editor-style.css' );
		}
		add_action('after_setup_theme', 'brandis_editor_style');
		// set content width  
		//if (!isset($content_width)) {$content_width = 750;}	
		
		
	}
endif; 
add_action('after_setup_theme', 'brandis_setup');

//test wether image upload errors get fixed
function change_graphic_lib($array) {
return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
}
add_filter( 'wp_image_editors', 'change_graphic_lib' );

//remove emoji
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
//change acf style for honeypot
function my_acf_admin_enqueue_scripts() {
	
	// register style
    wp_register_style( 'my-acf-input-css', get_stylesheet_directory_uri() . '/assets/css/my-acf-input.css', false, '1.0.0' );
    wp_enqueue_style( 'my-acf-input-css' );
    
}

add_action( 'acf/input/admin_enqueue_scripts', 'my_acf_admin_enqueue_scripts' );

// load css 
function brandis_css() {	

	wp_enqueue_style('brandis_bootstrap_css', get_template_directory_uri() . '/assets/css/bootstrap.min.css');	   
	wp_enqueue_style('brandis_style', get_stylesheet_uri());
}
//add_action('wp_enqueue_scripts', 'brandis_css');

// load javascript
function brandis_javascript() {	
	wp_enqueue_script('brandis_bootstrap_js', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '3.1.1', true); 	
	wp_enqueue_script('ajax-script', get_template_directory_uri() . '/assets/js/brandis.js', array('jquery'), '1.0', true);
	//wp_enqueue_script('ajax-script-2', get_template_directory_uri() . '/assets/js/brandis-tg.js', array('jquery'), '1.0', true);
	wp_localize_script( 'ajax-script', 'my_ajax_object',
                array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'brandis_javascript');

// html5 shiv
function brandis_html5_shiv() {
    echo '<!--[if lt IE 9]>';
    echo '<script src="'. get_template_directory_uri() .'/assets/js/html5shiv.js"></script>';
    echo '<![endif]-->';
}
add_action('wp_head', 'brandis_html5_shiv');
/** Navigationspunkte aus dem WordPress-Dashboard entfernen */ 
function remove_menus () {
	global $menu;
	global $current_user;
	get_currentuserinfo();
	$restricted = array(__('Kommentare'));
	$more_restrictions = array(__('Werkzeuge'), __('Design'), __('Plugins'),  __('cpt_main_menu'), __('Eigene Felder'));

	if(!in_array('administrator',$current_user->roles) ){
	    foreach($more_restrictions as $r)
		    array_push($restricted, $r); 
        remove_menu_page( 'edit.php?post_type=acf-field-group' );
	}
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){ unset($menu[key($menu)]) ;}
	}
}
add_action('admin_menu', 'remove_menus');
/***************   custom query vars*****************************************/
function add_query_vars_filter( $vars ){
  $vars[] = "my_var";
  return $vars;
}
//add_filter( 'query_vars', 'add_query_vars_filter' );

//ajax
add_action( 'wp_ajax_getIdsByCategories', 'getIdsByCategories' );
add_action( 'wp_ajax_nopriv_getIdsByCategories', 'getIdsByCategories' );

add_action( 'wp_ajax_getPostContent', 'getPostContent' );
add_action( 'wp_ajax_nopriv_getPostContent', 'getPostContent' );

function getPostContent() {
   
    $out = [];
    $out['error'] = "N";
    

    $post = get_post($_POST['postID']);
    if($post){
        $out['post_title']  = $post->post_title;
        
        $out['post_content']  = apply_filters('the_content', $post->post_content);
        $video_link = get_field('video', $post->ID);
        if($video_link)
            $out['video']  = wp_oembed_get($video_link);
   
   //bilder
   $bilder = get_field('bilder', $post->ID);
   $c = 0;
   $bildArr = array();
   if(count($bilder) > 0){
       foreach($bilder as $bild){
           $attachId = fjarrett_get_attachment_id_by_url( $bild['bild'] ) ;
           $bildArr[$c]['bild'] = $bild['bild'];
           $bildArr[$c]['quelle'] = $bild['quelle'];
           $bildArr[$c]['alt'] = get_post_meta( $attachId, '_wp_attachment_image_alt', true);
           $bildArr[$c]['caption'] = get_post_field('post_excerpt', $attachId);
          
          $c++;
       }
       $out['bilder'] = $bildArr;
       
   }

        $audio_link = get_field('audio', $post->ID);
        if($audio_link != ''){
            $attr = array(
               	'src'      => get_field('audio', $post->ID)
             );
             $out['audio']  = wp_audio_shortcode( $attr );
             $audio_bild = get_field('audio_bild', $post->ID);
             if($audio_bild)
                 $out['audio_bild']  = '<img src="'.$audio_bild.'">';
        }
            
        
    }else{
       $out['error'] = "J";
       $out['msg'] = "Fehler!? - Es gibt kein Post mit der ID:".$_POST['postID'];
   }
    //echo var_dump($post);
    echo json_encode($out);
    wp_die();
}

add_action( 'wp_ajax_getNewest', 'getNewest' );
add_action( 'wp_ajax_nopriv_getNewest', 'getNewest' );


function getNewest() {
   
    $out = [];
    $out['error'] = "N";
    
    $postIDs = [];
    
    $args = array( 
         'numberposts' => 5,  
         'orderby' => 'date',
         'order' => 'DESC'
    );
     $postlist = get_posts( $args );
    foreach($postlist as $post){
            $postIDs[] = $post->ID;
     }
       
    if(count($postIDs) > 0){
        $out['postIDs']  = $postIDs;
        
    }else{
       $out['error'] = "J";
       $out['msg'] = "gibt keine Posts?";
   }
    
    echo json_encode($out);
    wp_die();
}

add_action( 'wp_ajax_filterByYearAndKeyword', 'filterByYearAndKeyword' );
add_action( 'wp_ajax_nopriv_filterByYearAndKeyword', 'filterByYearAndKeyword' );

function filterByYearAndKeyword() {
   
    $out = [];
    $out['error'] = "N";
    $out['postIDs'] =[];
    
    $min = $_POST['min'];
    $max = $_POST['max'];
    $diff = $max - $min;
    
    $yearsArr = [];
    for($i=0; $i <= $diff; $i++){
        $yearsArr[] = $min + $i;
    }

    $campaign = false;
    $all_posts = [];
    $postIDs = [];
    $catArr = $_POST['filterArr'];
   // if(!isset($catArr))
   //     $catArr = array();
    $posts = array();
    
    if($catArr[0] == 'cat_neu'){
        $args = array( 
             'numberposts' => 5,  
             'orderby' => 'date',
             'order' => 'DESC'
        );
        $all_posts = get_posts($args );
  
       foreach($all_posts as $post){
          
             //time check

             $start = get_field('von',$post->ID);
             $end = get_field('bis',$post->ID);
             $startYear = getYear($start);
             $endYear = getYear($end);


             
                 $postIDs[] = $post->ID;
                 $outPosts = [];
                 $outPosts['displayDate'] = displayDate($start, $end);
                 $outPosts['permalink'] = get_permalink($post->ID);
                 $outPosts['post_title'] = $post->post_title;
                 $outPosts['post_content'] = apply_filters('the_content', $post->post_content);
                 $video_link = get_field('video', $post->ID);
                 if($video_link){
                     $outPosts['video'] = wp_oembed_get($video_link);
                 }else{
                     $outPosts['video'] ='';
                 }
                 $out['posts'][] = $outPosts;
                 $c++;
             

           
       }
    }else{
       
            $args = array( 
                  'numberposts' => 1000,  
                  'category__and'=> $catArr,
                 'meta_key' => 'bis',
                 'meta_type' => 'DATE',
                 'orderby' => 'meta_value',
                 'order' => 'DESC'
                 
            );


       $all_posts = get_posts($args );
   
        $campaign = get_posts(array('category__in'=> $catArr, 'post_type' => array('kampagne'),'numberposts' => 1 ));
       
       foreach($all_posts as $post){
           if(!in_array($post->ID, $postIDs )){
             //time check

             $start = get_field('von',$post->ID);
             $end = get_field('bis',$post->ID);
             $startYear = getYear($start);
             $endYear = getYear($end);


             if(in_array($startYear,$yearsArr) ||  in_array($endYear,$yearsArr)){
                 $postIDs[] = $post->ID;
                 $outPosts = [];
                 $outPosts['displayDate'] = displayDate($start, $end);
                 $outPosts['permalink'] = get_permalink($post->ID);
                 $outPosts['post_title'] = $post->post_title;
                 $outPosts['post_content'] = apply_filters('the_content', $post->post_content);
                 //video
                 $video_link = get_field('video', $post->ID);
                 if($video_link){
                     $outPosts['video'] = wp_oembed_get($video_link);
                 }else{
                     $outPosts['video'] ='';
                 }
                 //audio
                  $audio_link = get_field('audio', $post->ID);
                  if($audio_link != ''){
                      $attr = array(
                         	'src'      => get_field('audio', $post->ID)
                       );
                       $outPosts['audio']  = wp_audio_shortcode( $attr );
                       $audio_bild = get_field('audio_bild', $post->ID);
                       if($audio_bild)
                           $outPosts['audio_bild']  = '<img src="'.$audio_bild.'">';
                  }else{
                      $outPosts['audio'] ='';
                  }
                 //bilder
                   $bilder = get_field('bilder', $post->ID);
                   
                   
                   if(count($bilder) > 0){
                        $c = 0;
                        $bildArr = array();

                       foreach($bilder as $bild){
                          $attachId = fjarrett_get_attachment_id_by_url( $bild['bild'] ) ;
                          $bildArr[$c]['bild'] = $bild['bild'];
                          $bildArr[$c]['quelle'] = $bild['quelle'];
                          $bildArr[$c]['alt'] = get_post_meta( $attachId, '_wp_attachment_image_alt', true);
                          $bildArr[$c]['caption'] = get_post_field('post_excerpt', $attachId);

                         $c++;
                      }
                      $outPosts['bilder'] = $bildArr;
                   }else{
                       $outPosts['bilder'] ='';
                   }
                   
                 $out['posts'][] = $outPosts;
                 $c++;
             }
             if(isset($campaign) && count($campaign) > 0){
                    $out['campaign_title'] = '<a href="'.get_post_permalink($campaign[0]->ID).'">'.$campaign[0]->post_title.'</a>';
                    //90 zeichen  oder 13 Wörter
                    $txt = $campaign[0]->post_content;
                    $tmp=explode(" ",$txt);
         		   $teilArr=array_chunk($tmp, 13);
         		   $teilstring = implode(" ",$teilArr[0]);

         			if(isset($teilArr[1])){ //mdi-arrow-right
         				$teilstring .= '... <div class="weiterlink"><a href="'.get_post_permalink($campaign[0]->ID).'" title="weiter" ><i class="mdi mdi-arrow-right icon"></i> </a></div>';
         			}
                    $out['campaign_txt'] = $teilstring;
                }

           }
       }
    
    
    
   }
    
   
   
   //errorhandling and feedback
   if(count($postIDs) > 0){
       $out['postIDs'] = $postIDs;
       //$out['posts'] = json_encode($outPosts);
       
   }else{
       $out['error'] = "J";
       $out['msg'] = "Es gibt keine Posts für categories:".implode(", ",$catArr).' im gefragten Zeitfenster';
   }
    
 
	
    //echo "hier:::".var_dump($postIDs);
    echo json_encode($out);
    wp_die();
}

/*
add_action( 'wp_ajax_getAllCategories', 'getAllCategories' );
add_action( 'wp_ajax_nopriv_getAllCategories', 'getAllCategories' );


function getAllCategories() {
   
    $out = [];
    $out['error'] = "N";
    
    $catArr = [];
    $categories = get_categories();
    $c=0;
    foreach($categories as $cat){
           $catArr[$c]['term_id'] = $cat->term_id;
           $catArr[$c]['name'] = $cat->name;
           $c++;
     }
    if(count($catArr) > 0){
        $out['categories']  = $catArr;
        
    }else{
       $out['error'] = "J";
       $out['msg'] = "Es gibt  categories?!";
   }
    
    echo json_encode($out);
    wp_die();
}

*/
function getIdsByCategories() {
   
    $out = [];
    $out['error'] = "N";
    $out['postIDs'] =[];
    
    
    $all_posts = [];
    $postIDs = [];
    $catArr = $_POST['categories'];
    
    $posts = array();
    $campaign = false;
    
   $args = array( 
           'numberposts' => 500, 
           'category__and'=> $catArr,
           'meta_key' => 'bis',
           'meta_type' => 'DATE',
           'orderby' => 'meta_value',
           'order' => 'DESC'
      );
      $all_posts = query_posts($args );
  
    $campaign = get_posts(array('category__in'=> $catArr, 'post_type' => array('kampagne'),'numberposts' => 1 ));

   foreach($all_posts as $post){
       if(!in_array($post->ID, $postIDs ))
        $postIDs[] = $post->ID;
   }
   //errorhandling and feedback
   if(count($postIDs) > 0){
       $out['postIDs'] = $postIDs;
       if(isset($campaign) && count($campaign) > 0){
           $out['campaign_title'] = '<a href="'.get_post_permalink($campaign[0]->ID).'">'.$campaign[0]->post_title.'</a>';
           //90 zeichen  oder 13 Wörter
           $txt = $campaign[0]->post_content;
           $tmp=explode(" ",$txt);
		   $teilArr=array_chunk($tmp, 13);
		   $teilstring = implode(" ",$teilArr[0]);

			if(isset($teilArr[1])){ //mdi-arrow-right
				$teilstring .= '... <div class="weiterlink"><a href="'.get_post_permalink($campaign[0]->ID).'" title="weiter" ><i class="mdi mdi-arrow-right icon"></i> </a></div>';
			}
           $out['campaign_txt'] = $teilstring;
       }
       
   }else{
       $out['error'] = "J";
       $out['msg'] = "Es gibt keine Posts für categories:".implode(", ",$catArr);
   }
    
 
	
    //echo "hier:::".var_dump($postIDs);
    echo json_encode($out);
    wp_die();
}

function getYear($date){
    
    if(substr_count($date , "-") > 0){
        if(substr($date, 0, 1) =='-'){
            $bcDate =substr($date, 1, strlen($date)-1);
            $tmp = explode("-",$bcDate);
            $year = '-'.$tmp[0];
        }else{
            $tmp = explode("-",$date);
            $year = $tmp[0];
        }
        
    }else{
        $year = $date;
    }
    return $year;
    
}
function displayDate($startdate, $enddate){
    
    if(substr_count($startdate , "-") > 0){
        if(substr($startdate, 0, 1) =='-'){
            
            $bcStartDate =substr($startdate, 1, strlen($startdate)-1);
            //echo "<br>bcDate::".$bcStartDate.'----ende::'.$enddate;
            $tmp = explode("-",$bcStartDate);
            $start_year = $tmp[0];
            $start_month = $tmp[1];
            $start_day = $tmp[2];
            
        }else{
            $tmp = explode("-",$startdate);
            $start_year = $tmp[0];
            $start_month = $tmp[1];
            $start_day = $tmp[2];
        }
       
        if(substr($start_year, -4, 2) == '00'){
            $start_year = substr($start_year, -2, 2) ;
            
            
        }
        elseif(substr($start_year, -4, 1) == '0'){
            $start_year = substr($start_year, -3, 3) ;
            
        }
        
        
    }
    if(substr_count($enddate , "-") > 0){
        if(substr($enddate, 0, 1) =='-'){
            
            $bcEndDate =substr($enddate, 1, strlen($enddate)-1);
            //echo "<br>bcEndDate::".$bcEndDate.'----ende::'.$enddate;
            $tmp = explode("-",$bcEndDate);
            $end_year = $tmp[0];
            $end_month = $tmp[1];
            $end_day = $tmp[2];
        }else{
            $tmp = explode("-",$enddate);
            $end_year = $tmp[0];
            $end_month = $tmp[1];
            $end_day = $tmp[2];
            
        }
        if(substr($end_year, -4, 2) == '00'){
            $end_year = substr($end_year, -2, 2) ;
            
            
        }
        elseif(substr($end_year, -4, 1) == '0'){
            $end_year = substr($end_year, -3, 3) ;
            
        }
        
    }
    //specials
    
    if($start_year == $end_year && $start_month == '01' && $start_day == '01' && $end_month == '12' && $end_day == '31'){
        $displayDate = $start_year;
    }elseif($start_year != $end_year && $start_month == '01' && $start_day == '01' && $end_month == '12' && $end_day == '31'){
        $displayDate = $start_year.' - '.$end_year;
    }elseif($startdate == $enddate ){
        $displayDate = $start_day.'.'.$start_month .'.'.$start_year;        
        
    }else{
        
        $displayDate = $start_day.'.'.$start_month .'.'.$start_year.' - '.$end_day.'.'.$end_month .'.'.$end_year;

    }
    if(isset($bcStartDate))
        $displayDate .=' v. Chr';
    return $displayDate;
    
}
/***************** save contributions ************/
add_filter('acf/pre_save_post' , 'pre_save_contribution' );
function pre_save_contribution( $post_id ) {

	// check if this is to be a new post
	if( $post_id != 'new' ) {
		return $post_id;
	}

	// Create a new post
	$post = array(
		'post_type'     => 'contributions', // Your post type ( post, page, custom post type )
		'post_status'   => 'draft', // (publish, draft, private, etc.)
		'post_title'    => 'Neuer Bürger Beitag vom '.date('d.m.Y'),
		'post_content'  => strip_tags($_POST['acf']['field_57cdb5516487f']), 
	);

	// insert the post
	if($_POST['acf']['field_57d6c98f63321'] != ''){
	    //honey!
	    die("Spammer?!");
    }else{
    	$post_id = wp_insert_post( $post );

    

        //nun mail an Mitarbeiter
        $post = get_post( $post_id );


    	$name = strip_tags($_POST['acf']['field_57cdb4acadd70']); 
    	$email = strip_tags($_POST['acf']['field_57cdb4fc6487e']);

        $adminName = get_bloginfo('name');
        $adminEmail =get_bloginfo('admin_email');
        $url = get_bloginfo('url');
    	// email data
        $to = $adminEmail;
       
        $headers = array('From: ' . $adminName . ' <' . $adminEmail . '>','Content-Type: text/html; charset=UTF-8');
    
        $subject = $post->post_title;
        $body = 'Neuer Beitrag von '.$name .' ('.$email.')';
        $body .= '<br><br><i>'.$post->post_content.'</i>';
        $body .= '<br><br>ansehen unter: <a href="'.$url.'">'.$url.'</a>';
   
   
        // send email
        wp_mail($to, $subject, $body, $headers );

    	return $post_id;
	}
    wp_die();
}


/**
 * Return an ID of an attachment by searching the database with the file URL.
 *
 * First checks to see if the $url is pointing to a file that exists in
 * the wp-content directory. If so, then we search the database for a
 * partial match consisting of the remaining path AFTER the wp-content
 * directory. Finally, if a match is found the attachment ID will be
 * returned.
 *
 * @param string $url The URL of the image (ex: http://mysite.com/wp-content/uploads/2013/05/test-image.jpg)
 * 
 * @return int|null $attachment Returns an attachment ID, or null if no attachment is found
 */
function fjarrett_get_attachment_id_by_url( $url ) {
	// Split the $url into two parts with the wp-content directory as the separator
	$parsed_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

	// Get the host of the current site and the host of the $url, ignoring www
	$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

	// Return nothing if there aren't any $url parts or if the current host and $url host do not match
	if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
		return;
	}

	// Now we're going to quickly search the DB for any attachment GUID with a partial path match
	// Example: /uploads/2013/05/test-image.jpg
	global $wpdb;

	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );

	// Returns null if no attachment is found
	return $attachment[0];
}


/**************************************************************************** ttt ****/

?>