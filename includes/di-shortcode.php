<?php
add_action('wp_enqueue_scripts', 'di_head');
function  di_head() {
	wp_register_style( 'slick-di-style', plugins_url( 'css/styles.css', dirname(__FILE__) ) );  
	wp_enqueue_style('slick-di-style');
	
	wp_register_style( 'slick-di-font-aweseom-min', plugins_url( 'css/font-awesome.min.css', dirname(__FILE__) ) );  
	wp_enqueue_style('slick-di-font-aweseom-min');

	wp_enqueue_script( 'docit', plugins_url( 'js/docit.js',  dirname(__FILE__) ) ); 
}

add_shortcode( 'docit', 'docit_shortcode_func' );

//Main Funtion
function docit_shortcode_func($atts){
	
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

global $di_root_slug;
global $di_list_of_taxs;

extract( shortcode_atts( array(
	'id' => '',
	'intro' => '',
), $atts ) );
	
global $docit_att;
$docit_att = array(
		'id' => $id,
		'intro' => $intro,
	);
	
ob_start(); 


		
//Start Doc It Menu 
echo '<div class="docit-menu-wrap">';

	if(!empty($id)){
		$taxonomies = array($id);
	}
	else	{
	  // get available taxonomies
	    $taxonomies = $di_list_of_taxs;
	}

	// loop all taxonomies
	foreach($taxonomies as $taxonomy) { 
		if(!empty($id)){
			$pre_tax_slug = str_replace('di_', '', $taxonomy);
			$tax_slug = str_replace('_', '-', $pre_tax_slug );
			
		}
		else{
			$tax_slug = $taxonomy->slug;
		}
		
		$args = array(
			'orderby'       => 'none', 
			'order'         => 'ASC',
			'hide_empty'    => true, 
			'exclude'       => '', 
			'exclude_tree'  => '', 
			'include'       => '',
			'number'        => '', 
			'fields'        => 'all', 
			'slug'          => '', 
			'parent'         => '0',
			'hierarchical'  => true, 
			'child_of'      => '', 
			'get'           => '', 
			'name__like'    => '',
			'pad_counts'    => false, 
			'offset'        => '', 
			'search'        => '', 
			'cache_domain'  => 'core'
		); 
		// Gets every "category" (term) in this taxonomy to get the respective posts
		 $terms = get_terms($taxonomy, $args);
		 
if(empty($terms))	{
	echo '<div class="di-empty-category">Please attach post to this category to see sidebar.</div>';
}
if($terms)	{
		// Add Main Titles
		foreach($terms AS $term ) {
			
			
			echo '<ul class="docit-menu">';
			
			if ($term->parent =='0'){
				//main category title (on bottom is posts if no sub category) 
				
				echo '<li class="docit-main-header"><a href="'.get_site_url().'/'.$di_root_slug.'/'.$tax_slug.'/'.$term->slug.'" class="docit-main-cat-title">'.$term-> name.'</a><div><i class="icon-angle-down"></i></div></li>';
			}
				$term_id_children = $term->term_id;
								  
						  $term_children_args = array(
							  'orderby'       => 'none', 
							  'order'         => 'ASC',
							  'hide_empty'    => false, 
							  'exclude'       => '', 
							  'exclude_tree'  => '', 
							  'include'       => '',
							  'number'        => '', 
							  'fields'        => 'all', 
							  'slug'          => '', 
							  'parent'         => $term_id_children,
							  'hierarchical'  => false, 
							  'child_of'      => '', 
							  'get'           => '', 
							  'name__like'    => '',
							  'pad_counts'    => false, 
							  'offset'        => '', 
							  'search'        => '', 
							  'cache_domain'  => 'core'
						  ); 
						  // Gets every "category" (term) in this taxonomy to get the respective posts
						   $termchildren = get_terms($taxonomy, $term_children_args);
	
							$child_count = 0;
							//Add first sub-category
							$check_list = array();
							foreach ($termchildren as $termchild) {
								$child_count++;

								
								$child_term = get_term($termchild->term_id, $taxonomy);
								
								$termchildren_lvl2_args = array(
									'orderby'       => 'none', 
									'order'         => 'ASC',
									'hide_empty'    => false, 
									'exclude'       => '', 
									'exclude_tree'  => '', 
									'include'       => '',
									'number'        => '', 
									'fields'        => 'all', 
									'slug'          => '', 
									'parent'         => $child_term->term_id,
									'hierarchical'  => false, 
									'child_of'      => '', 
									'get'           => '', 
									'name__like'    => '',
									'pad_counts'    => false, 
									'offset'        => '', 
									'search'        => '', 
									'cache_domain'  => 'core'
								); 
								// Gets every "category" (term) in this taxonomy to get the respective posts
								$termchildren_lvl2 = get_terms($taxonomy, $termchildren_lvl2_args);
									
									$child_check = array();
									$child_child_term_check = array();
									if(!empty($termchildren_lvl2))	{
									   //Pre check Children Posts ID's and create array.
									   foreach ($termchildren_lvl2 as $pre_subkey => $pre_subvalue) {
										  $pre_child_of_child_term = get_term($pre_subvalue, $taxonomy);
										  $child_child_term_check[] = $pre_child_of_child_term_term_id->term_id;
										  $pre_sub_posts_args = array (
														'orderby' => 'menu_order',
														'order' => 'ASC',
														'taxonomy'=> $taxonomy,
														'term'=> $pre_child_of_child_term->slug,
														'posts_per_page' => -1,
														'suppress_filters' => true, 
													  );
										   $pre_sub_posts = new WP_Query($pre_sub_posts_args);
											if ($pre_sub_posts-> have_posts()) {		
												 //loop through posts
												while ($pre_sub_posts-> have_posts()) {
													$pre_sub_posts->the_post();
													$child_check[] = $pre_sub_posts->post->ID;
												}//end while
											}//end if	  
									   }//end foreach
									}//end if								

									
									echo '<li class="docit-main-cat-title"><ul class="docit-sub-menu">';
									//If this sub category is NOT a parent add posts now otherwise don't
									if(!in_array($child_term->term_id,$check_list) && !in_array($child_term->parent,$termchildren)) {
									  
									  	
									  
									 	 echo '<li class="docit-sub-header '.$child_term->slug.'"><a href="'.get_site_url().'/'.$di_root_slug.'/'.$tax_slug.'/'.$child_term->slug.'" class="docit-sub-cat-title">'.$child_term->name.'</a><div><i class="icon-angle-down"></i></div></li>';								  	 			 
									}

																					
										if(!in_array($child_term->term_id,$check_list) && !in_array($child_term->parent,$termchildren)) {
											$no_parent_sub_posts_args = array (
														'order' => 'ASC',
														'orderby' => 'menu_order',
														'taxonomy'=> $taxonomy,
														'term'=> $child_term->slug,
														'posts_per_page' => -1,
														'suppress_filters' => true, 
													  );
											$posts = new WP_Query($no_parent_sub_posts_args);									

										     //loop through posts
											  while ($posts->have_posts()) {
												  
												  //get the post
												  $posts->the_post();
												  
								  				  // show post titles for this cat
												  if (!in_array($posts->post->ID,$child_check)){
												  	echo '<li class="docit-sub-post '.$posts->post->post_name.'" id="'.get_the_ID().'"><a href="'.get_site_url().'/'.$di_root_slug.'/'.$posts->post->post_name.'" class="docit-post-title">'. $posts-> post-> post_title .'</a></li>';
												  }
												   //Update temporary value
												  $posts_count++;
												  //Add Id to $checklist
												  $check_list[]=$child_term->term_id;
												 
											  }//end while
										  }//end if
									
									
									//If this sub category is a parent (Has Children) add children titles then posts [second sub category]
								    if(!empty($termchildren_lvl2))	{
										
													echo '<li class="docit-sub-sub-menu-wrap"><ul class="docit-sub-sub-menu">';
														
														 $check_list_lvl2 = array();
														  //Add children here
														  foreach ($termchildren_lvl2 as $subkey => $subvalue) {
													  
													  
															 $child_of_child_term = get_term($subvalue, $taxonomy);
																 $sub_posts_args = array (
																  'orderby' => 'menu_order',
																  'order' => 'ASC',
																  'taxonomy'=> $taxonomy,
																  'term'=> $child_of_child_term->slug,
																  'posts_per_page' => -1,
																  'suppress_filters' => true, 
																);
															 $sub_posts = new WP_Query($sub_posts_args);
															  
															  if ( $sub_posts-> have_posts() ) {
																 if(!in_array($child_of_child_term->term_id,$check_list_lvl2)){
																  	echo '<li class="docit-sub-sub-header '.$child_of_child_term->slug.'"><a href="'.get_site_url().'/'.$di_root_slug.'/'.$tax_slug.'/'.$child_of_child_term->slug.'" class="docit-sub-sub-cat-title">'. $child_of_child_term->name .'</a></li>';
																 }
																	   //loop through posts
																	  while ( $sub_posts-> have_posts() ) {
																		   //get the post
																		  $sub_posts-> the_post();
																		  // show post titles for this cat
																		  echo '<li class="docit-sub-sub-post '.$sub_posts->post->post_name.'"><a href="'.get_site_url().'/'.$di_root_slug.'/'.$sub_posts->post->post_name.'" class="docit-post-title">'. $sub_posts->post->post_title .'</a></li>';
														  
																		   //Update temporary value
																		  $posts_count++;
																		  $check_list_lvl2[] = $child_of_child_term->term_id;
																	  }//endwhile
																
															  }	  
														  }//end foreach
											
											echo '</ul></li>';//end sub sub ul
									
									 
									}
									
									echo '</ul></li>';//end sub menu	
												
							}
							
									
									
							//If no Sub categories add posts under main title
							if ($child_count == 0){
								
								$post_args = array (
								'orderby' => 'menu_order',
								'order' => 'ASC',
								'taxonomy'=> $taxonomy,
								'term'=> $term->slug,
								'posts_per_page' => -1,
								'suppress_filters' => true, 
								);
								// get posts
									$posts = new WP_Query($post_args );
							
									// check for posts
									if ( $posts-> have_posts() ) {
										// how your header (gold,silver,bronze)
													   
							
										// loop through posts
										while ( $posts-> have_posts() ) {
											// get the post
											$posts-> the_post();
							
											// show post titles for this cat
											echo '<li class="docit-main-link '.$posts->post->post_name.'"><a href="'.get_site_url().'/'.$di_root_slug.'/'.$posts->post->post_name.'" class="docit-post-title">'. $posts-> post-> post_title .'</a></li>';
							
											// Update temporary value
											$posts_count++;
										}//end while
									}//end If
							}//end if
			echo '</ul>';//end main ul	
		}
	
	}//endiif Terms
	
	}
		
	echo '</div>';
	?>
<?php

 $url = $_SERVER['REQUEST_URI']; //returns the current URL
 	$tokens = explode('/', $url);
	$final_url = $tokens[sizeof($tokens)-2];		
		  if(!empty($final_url)) {?>
			  <script>
              jQuery(document).ready(function () {  
             
                    jQuery('.docit-menu-wrap .<?php echo $final_url;?>').addClass('di-active');
               
              });
              </script>
 	<?php }
	 wp_reset_query();
 return ob_get_clean(); 

}

?>