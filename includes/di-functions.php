<?php
/************************************************
 	Function file for Doc It
************************************************/
add_option( 'doc_it_menu_labelz', '', '', 'yes' );

//Settings option. Add Padding to #Prime Wrapper. DO NOT MESS WITH SPACING BELOW.
$docit_include_custom_css_checked_padding =  get_option('doc-it-color-options-settings-custom-css-main-wrapper-padding');
	if ($docit_include_custom_css_checked_padding == '1') { 
	add_action('wp_enqueue_scripts', 'di_color_options_head_padding');
function  di_color_options_head_padding() { ?>
<style type="text/css">
	#docit-primary {
	<?php //padding
		$docit_include_custom_css_padding =  get_option('doc-it-color-options-main-wrapper-padding-input');
		if ($docit_include_custom_css_padding == ' ' || $docit_include_custom_css_padding == '') { ?> 
	<?php } 
		else { ?>
	padding: <?php echo get_option('doc-it-color-options-main-wrapper-padding-input');?>  !important;
	<?php } ?>
	<?php //margin 
	$docit_include_custom_css_margin =  get_option('doc-it-color-options-main-wrapper-margin-input');
		if ($docit_include_custom_css_margin == ' ' || $docit_include_custom_css_margin == '') { ?> 
	<?php } 
		else { ?>margin: <?php echo get_option('doc-it-color-options-main-wrapper-margin-input');?>  !important;
	<?php } ?>
	<?php //max width
	$docit_include_custom_css_max_width =  get_option('doc-it-color-options-main-wrapper-width-input');
		if ($docit_include_custom_css_max_width == ' ' || $docit_include_custom_css_max_width == '') { ?> 
	<?php } 
		else { ?>max-width: <?php echo get_option('doc-it-color-options-main-wrapper-width-input');?>  !important;
	<?php } ?> 	}
	<?php //Menu on Right
	$docit_include_custom_menu_position =  get_option('doc-it-color-options-menu-position');
		if ($docit_include_custom_menu_position == '2') { ?>
#docit-primary .docit-menu-wrap {
		float: right !important;
		margin-left: 3% ;
		margin-right: 0% !important;
		}
	<?php } ?>
</style>
		<?php
	}
 }
 //Settings option. Add Custom CSS to the header of docit pages only
$docit_include_custom_css_checked_css =  get_option( 'doc-it-color-options-settings-custom-css' );
	if ($docit_include_custom_css_checked_css == '1') { 
	
	add_action('wp_enqueue_scripts', 'di_color_options_head_css');
function  di_color_options_head_css() {
		?>
<style type="text/css"><?php echo get_option('doc-it-color-options-main-wrapper-css-input');?></style>
		<?php
	}
 } 
 //Settings option. Closes Menu... Works but not in effect. More work to debug this issue.
$docit_include_custom_display_menu_closed =  get_option( 'doc-it-display-menu-closed' );
	if ($docit_include_custom_display_menu_closed == '1') { 
	
	add_action('wp_enqueue_scripts', 'di_display_menu_closed');
function  di_display_menu_closed() {
		?>
<style type="text/css">#docit-primary .docit-sub-sub-menu-wrap, #docit-primary .docit-sub-post { display:none; }</style>
		<?php
	}
 } 
 //Settings options. Rainbow Color Code Option
 $docit_include_custom_colored_code =  get_option( 'doc-it-color-coded' );
	if ($docit_include_custom_colored_code == '1') { 
	
	add_action('wp_enqueue_scripts', 'di_head_color_code');
function  di_head_color_code() {
		
	  wp_register_style('di_head_color_code', plugins_url( 'colorCode/css/custom.css',  dirname(__FILE__) ) );
	  wp_enqueue_style('di_head_color_code'); 
	  wp_enqueue_script( 'di_head_color_code_main', plugins_url( 'colorCode/js/rainbow.min.js',  dirname(__FILE__) ),'','',true ); 

	}
 }
//Create Introductions
function register_intro_posts() {
        register_post_type( 'docit_intro', array(
                'labels' => array(
						'menu_name' => 'Introductions',
                        'name' => 'Doc It Introductions',
                        'singular_name' => 'Doc It Introduction',
                ),
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => 'edit.php?post_type=docit',
                'supports' => array( 'title' ,'thumbnail', 'editor' ),
        ) );
		
		flush_rewrite_rules();
}
add_action( 'init', 'register_intro_posts'); 
 
//Create Taxenomy for Doc It
add_action( 'init', 'register_taxonomy_di_categories' );
  function register_taxonomy_di_categories() {
 	
	$tax_arrays = get_option('doc_it_menu_labelz'); 
 	
	
 	global $di_root_slug;
	$di_root_slug = 'docs';	
 
	global $di_list_of_taxs;
	
if (!empty($tax_arrays)){
	$di_list_of_taxs = array();
	 
		 array_filter($tax_arrays);
		foreach ($tax_arrays as $tax)	{
			//Check for bad Characters 
			$tax = preg_replace("/[^A-Za-z0-9 '-]/","",$tax);
			if (!empty($tax)){
					//Clean things up a bit.
					$menu_name_lenth = (strlen($tax) > 21) ? substr($tax,0,18).'...' : $tax;
					$qv_tax_lower = strtolower($tax);
					$qv_tax = str_replace(' ', '_', $qv_tax_lower);
					$qv_tax_slug = str_replace(' ', '-', $qv_tax_lower);
						
					  $labels2 = array( 
						  'label' =>_x($tax, 'di_'.$qv_tax),
						  'name' => _x($tax, 'di_'.$qv_tax),
						  'singular_name' => _x($tax, 'di_'.$qv_tax),
						  'search_items' => _x( 'Search '.$tax.'s', 'di_'.$qv_tax),
						  'all_items' => _x( 'All '.$tax.'s', 'di_'.$qv_tax ),
						  'parent_item' => _x( 'Parent '.$tax, 'di_'.$qv_tax),
						  'parent_item_colon' => _x( 'Parent '.$tax.':', 'di_'.$qv_tax ),
						  'edit_item' => _x( 'Edit '.$tax, 'di_'.$qv_tax ),
						  'update_item' => _x( 'Update'.$tax, 'di_'.$qv_tax ),
						  'add_new_item' => _x( 'Add New '.$tax, 'di_'.$qv_tax ),
						  'new_item_name' => _x( 'New '.$tax, 'di_'.$qv_tax ),
						  'separate_items_with_commas' => _x( 'Separate '.$tax.' with commas', 'di_'.$qv_tax ),
						  'add_or_remove_items' => _x( 'Add or remove '.$tax, 'di_'.$qv_tax ),
						  'choose_from_most_used' => _x( 'Choose from the most used '.$tax, 'di_'.$qv_tax ),
						  'menu_name' => _x( $menu_name_lenth, 'di_'.$qv_tax),
					  );
				  
					  $args1 = array( 
						  
						  'labels' => $labels2,
						  'public' => true,
						  'show_in_nav_menus' => true,
						  'show_ui' => true,
						  //'show_tagcloud' => true,
						  'hierarchical' => true,
						  'update_count_callback' => '_update_post_term_count',
						  'rewrite' => array('slug' => $di_root_slug.'/'.$qv_tax_slug),
						  'query_var' => 'di_'.$qv_tax
					  );
					  
					  $di_list_of_taxs[$qv_tax]['label'] = $tax;
					  $di_list_of_taxs[$qv_tax]['slug'] = $qv_tax_slug;
					  $di_list_of_taxs[$qv_tax]['name'] = $tax;
					  $di_list_of_taxs[$qv_tax]['query_var'] = 'di_'.$qv_tax;
					   
						register_taxonomy( 'di_'.$qv_tax.'', array('Doc It'), $args1 );
						 flush_rewrite_rules();
					}  
			}
	}
}
 
	
	


function docit_cpt_post_types( $post_types ) {
    $post_types[] = 'Doc It';
    return $post_types;
}
//Create Doc It Custom Post type
add_filter( 'cpt_post_types', 'docit_cpt_post_types' );

function di_cpt_init() {
	
	$tax_arrays = get_option('doc_it_menu_labelz'); 
	
	global $di_root_slug;
	
	if (!empty($tax_arrays)){
		$tax_functions = array();
		foreach ($tax_arrays as $tax)	{
			$qv_tax_lower = strtolower($tax);
			$qv_tax = str_replace(' ', '_', $qv_tax_lower);
			$tax_functions[] = 'di_'.$qv_tax;
		}
	$is_taxes = "taxonomies";
	}
	else	{
		$tax_functions ='';	
		$is_taxes = "";
	}
	
    $args = array(
		'label' => 'Doc It',
		'labels' => array (
               'menu_name' => 'Documents',
               'name' => 'All Your Documents',
			   'singular_name' => 'Document',
			   'add_new_item' => 'Add New Document',
            ),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => true,
		'rewrite' => array('slug' => $di_root_slug),
		'query_var' => 'Doc It',
		'menu_icon' => '',
		'supports' => array(
		'title',
		'editor',
		'thumbnail',
		'excerpt',
		'trackbacks',
		'custom-fields',
		'comments',
		'revisions',
		'thumbnail',
		'author',
		),
		
		// Set the available taxonomies here
        $is_taxes => $tax_functions
		 );
		 
	    register_post_type( 'Doc It', $args );
		
		flush_rewrite_rules();
}
add_action( 'init', 'di_cpt_init' );

add_filter( 'attribute_escape', 'rename_second_doc_it_submenu_name', 10, 2 );



/*
 * Renames the first occurence of 'See All Academias' to 'Academias'
 * and deactivates itself then.
 * @param $safe_text
 * @param $text
 */
function rename_second_doc_it_submenu_name( $safe_text, $text )
{
    if ( 'Documents' !== $text )
    {
        return $safe_text;
    }

    // We are on the main menu item now. The filter is not needed anymore.
    remove_filter( 'attribute_escape', 'rename_second_doc_it_submenu_name' );

    return 'Doc It';
}

add_action('admin_menu', 'Doc_It_Submenu_Pages');

function Doc_It_Submenu_Pages() {   
	//settings Info Page
	add_submenu_page( 
          'edit.php?post_type=docit'
        , 'Doc It Settings' 
        , 'Settings'
        , 'manage_options'
        , 'doc-it-settings-page'
        , 'doc_it_settings_page'
    );
	
	//System Info Page
	add_submenu_page( 
          'edit.php?post_type=docit'
        , 'System Info' 
        , 'System Info'
        , 'manage_options'
        , 'doc-it-system-info-submenu-page'
        , 'doc_it_system_info_page'
    );
}

add_action('admin_enqueue_scripts', 'doc_it_admin_css');
// THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
function doc_it_admin_css() {
    wp_register_style( 'doc_it_admin', plugins_url( 'admin/css/admin.css', dirname(__FILE__) ) );  
	wp_enqueue_style('doc_it_admin');
}

if (isset($_GET['page']) && $_GET['page'] == 'doc-it-system-info-submenu-page') {
  add_action('admin_enqueue_scripts', 'doc_it_system_info_css');
  // THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
  function doc_it_system_info_css() {
	  wp_register_style( 'doc-it-settings-admin-css', plugins_url( 'admin/css/admin-settings.css',  dirname(__FILE__) ) );
	  wp_enqueue_style('doc-it-settings-admin-css'); 
  }
}

if (isset($_GET['page']) && $_GET['page'] == 'doc-it-settings-page') {
  add_action('admin_enqueue_scripts', 'doc_it_settings');
  // THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
  function doc_it_settings() {
	  wp_register_style( 'doc_it_settings_css', plugins_url( 'admin/css/settings-page.css',  dirname(__FILE__) ) );
	  wp_enqueue_style('doc_it_settings_css'); 
	  wp_enqueue_script( 'doc_it_settings_js', plugins_url( 'admin/js/admin.js',  dirname(__FILE__) ) ); 
  }
}

// Create new Page Template for Doc It
function DocIt_page_template($template)
{
    global $post;
	
	if(has_shortcode( $post->post_content, 'docit')) {
		 $template = dirname( __FILE__ ) . '/templates/docit-page-template.php';
	}
	return $template;
}
add_filter('page_template', 'DocIt_page_template',99);

// Create new Post Template for Doc It
function DocIt_post_template($single_template) {
     global $post;

     if ($post->post_type == 'docit') {
          $single_template = dirname( __FILE__ ) . '/templates/docit-post-template.php';
     }
     return $single_template;
}
add_filter('single_template','DocIt_post_template',99);

// Create new Archive Template for Doc It
function DocIt_archive_template($archive_template ) {
     global $post;

     if (is_archive ( 'docit' )) {
          $archive_template = dirname( __FILE__ ) . '/templates/docit-archive-template.php';
     }
     return $archive_template;
}

add_filter('archive_template', 'DocIt_archive_template',99) ;

// DocIt Breadcrumbs
function doc_it_breadcrumb() {
	echo '<div id="breadcrumb"><i class="icon-home"></i>';
    // Are there any taxonomies to get terms from?
    if (is_single()) {    
		//Get single taxs
		$single_taxs = di_post_main_slug();	
	
		 if ( FALSE == is_wp_error($single_taxs)) {
		//Print Single Tax Breadcrumb
			  foreach ($single_taxs as $main_parent){
				  $main_parent_url = get_term_link($main_parent->term_id, $main_parent->taxonomy );
				  if ( FALSE == is_wp_error($main_parent_url)) {
				  	print '<a href="'.$main_parent_url.'">'.$main_parent->name.'</a> » ';
				  }
					  //If Main Parent has Children
					  if(!empty($main_parent->children))	{
						  foreach	($main_parent->children as $first_child){
							  $first_child_url = get_term_link( $first_child->term_id, $first_child->taxonomy );
							  
							   if ( FALSE == is_wp_error($first_child_url)) {
								 echo'<a href="'.$first_child_url.'">'.$first_child->name.'</a> » ';
							  }
							  //If First Child has Children
							  if(!empty($first_child->children))	{
								  foreach	($first_child->children as $second_child){
									  $second_child_url = get_term_link($second_child->term_id,$second_child->taxonomy);
									  echo'<a href="'.$second_child_url.'">'.$second_child->name.'</a> » ';
								  }//endforeach
							  }//endif
						  }//endforach
					  }//endif
			  }//endforeach
		}//end if single
	
		echo the_title();
		
	}//endif single
	else	{
	 $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	
				  
			  //get current term info
		   
			//store curent term's id as first in the array
			$breadcrumbarray[] = $term->term_id;
			//transfer the term info object so we don't mess it up
			$tempterm = $term;
			//backward crawl terms...
			//if the current term in the crawl has a parent - get it's parent's id...
			while ($tempterm->parent != 0) {
			  $tempterm = get_term_by('id',$tempterm->parent,get_query_var( 'taxonomy' ));
			  // and store it in the array
			  $breadcrumbarray[] .= $tempterm->term_id;
			}
			//now reverse order the array so it goes from parent to child...
			$breadcrumbarray = array_reverse($breadcrumbarray);
			//now we'll loop through our array to display each item in the parent to child order and with links...
			$isfirst = true;
			foreach($breadcrumbarray as $termid) {
			  if (!$isfirst) echo " » ";
			  $isfirst = false;
			  // get all the info again for the current term id in the array
			  $terminfo = get_term_by('id',$termid,get_query_var( 'taxonomy' ));
			  //show links for all terms except the current one..
			  if ($terminfo->term_id != $term->term_id) {
			  //get the URL for that terms's page
			  $url = get_term_link( $terminfo->term_id, get_query_var( 'taxonomy' ) );
			  echo '<a href="'.$url.'">'.$terminfo->name.'</a>';
			  } else {
			  echo $terminfo->name;
			  }
			}
	}//endelse
	echo '</div><!--breadcrumb-->';
}

function di_post_main_tax() {

    // Get an array of all taxonomies for this post
    $taxonomies = get_taxonomies( '', 'names' );

    // Are there any taxonomies to get terms from?
    if ($taxonomies) {    

        // Call the wp_get_post_terms function to retrieve all terms. It accepts an array of taxonomies as argument. 
        $arr_terms = wp_get_post_terms(get_the_ID(), array_values( $taxonomies ) , array( "fields" => "all"));
		 
		 if ($arr_terms)	{
		  $tax_array = array();
		  foreach ($arr_terms as $term){
		  
			  $tax_name = $term->taxonomy;
			  $tax_array[] = $tax_name;
		  }	
  
		  $clean_parents = array_unique($tax_array);
		  $final_tax_array = implode(',',$clean_parents);
		  return $final_tax_array;
		}
	}
}	

function di_post_main_slug() {

	global $di_list_of_taxs;
    // Get an array of all taxonomies for this post
    $taxonomies = get_taxonomies( '', 'names' );

    // Are there any taxonomies to get terms from?
    if ( $taxonomies ) {    

        // Call the wp_get_post_terms function to retrieve all terms. It accepts an array of taxonomies as argument. 
        $arr_terms = wp_get_post_terms( get_the_ID(), array_values($taxonomies) ,array( "fields" => "all" ));
	

			  $categoryHierarchy = array();
			  
			  di_sort_terms_hierarchicaly($arr_terms, $categoryHierarchy);
			 
			  return $categoryHierarchy;
		
		
	}
		
	
}	
//Sort Post Tax Terms into Hierarchy
function di_sort_terms_hierarchicaly(Array &$cats, Array &$into, $parentId = 0)
{
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        di_sort_terms_hierarchicaly($cats, $topCat->children, $topCat->term_id);
    }
}
//Start code for sorting
$docitorder = new DocItOrder_Engine();

class DocItOrder_Engine {

	function __construct() {
			
	if ( !get_option( 'docitorder_options' ) )
	
			add_action( 'admin_init', array( &$this, 'docitorder_install' ) );
			add_action( 'admin_init', array( &$this, 'refresh' ) );
			add_action( 'init', array( &$this, 'enable_objects' ) );
	
			add_action( 'wp_ajax_update-menu-order', array( &$this, 'update_menu_order' ) );
	
			add_filter( 'pre_get_posts', array( &$this, 'docitorder_filter_active' ) );
			add_filter( 'pre_get_posts', array( &$this, 'docitorder_pre_get_posts' ) );
	
			add_filter( 'get_previous_post_where', array( &$this, 'docitorder_previous_post_where' ) );
			add_filter( 'get_previous_post_sort', array( &$this, 'docitorder_previous_post_sort' ) );
			add_filter( 'get_next_post_where', array( &$this, 'docitorder_next_post_where' ) );
			add_filter( 'get_next_post_sort', array( &$this, 'docitorder_next_post_sort' ) );
	}

	function docitorder_install() {
		global $wpdb;

		//Initialize Options

		$post_types = get_post_types( array(
							'public' => true
								), 'objects' );

		foreach ( $post_types as $post_type ) {
			$init_objects[] = $post_type->name;
		}
		$input_options = array( 'objects' => $init_objects );

		update_option( 'docitorder_options', $input_options );


		// Initialize : menu_order from date_post

		$docitorder_options = get_option( 'docitorder_options' );
		$objects = $docitorder_options['objects'];

		foreach ( $objects as $object ) {
			$sql = "SELECT
						ID
					FROM
						$wpdb->posts
					WHERE
						post_type = '" . $object . "'
						AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
					ORDER BY
						post_date DESC
					";

			$results = $wpdb->get_results( $sql );

			foreach ( $results as $key => $result ) {
				$wpdb->update( $wpdb->posts, array( 'menu_order' => $key + 1 ), array( 'ID' => $result->ID ) );
			}
		}
	}


	function enable_objects() {
		$docitorder_options = get_option( 'docitorder_options' );
		$objects = $docitorder_options['objects'];
		$tex = $docitorder_options['taxonomy'];
		if ( is_array( $tex ) ) {
			$active = true;
		}

		if ( is_array( $objects ) ) {
			$active = true;

			// for Pages or Custom Post Types
			if ( isset( $_GET['post_type'] ) ) {
				if ( in_array( $_GET['post_type'], $objects ) ) {
					$active = true;
				}
				// for Posts
			} else {
				$post_list = strstr( $_SERVER["REQUEST_URI"], 'wp-admin/edit.php' );
				if ( $post_list && in_array( 'post', $objects ) ) {
					$active = true;
				}
			}

			if ( $active ) {
				$this->load_script_css();
			}
		}
	}

	function load_script_css() {
		if ( is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'docit'){
			// load JavaScript
			wp_enqueue_script( 'jQuery' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'docitorderjs', DOCIT_URL . '/admin/js/docitorder.js', array( 'jquery' ), null, true );
			// load CSS
			wp_enqueue_style( 'docitorder', DOCIT_URL . '/admin/css/docitorder.css', array( ), null );
		}
	}

	function refresh() {

		global $wpdb;

		$docitorder_options = get_option( 'docitorder_options' );
		$objects = $docitorder_options['objects'];

		if ( is_array( $objects ) ) {
			foreach ( $objects as $object ) {
				$sql = "SELECT
							ID
						FROM
							$wpdb->posts
						WHERE
							post_type = '" . $object . "'
							AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
						ORDER BY
							menu_order ASC
						";

				$results = $wpdb->get_results( $sql );

				foreach ( $results as $key => $result ) {
					$wpdb->update( $wpdb->posts, array( 'menu_order' => $key + 1 ), array( 'ID' => $result->ID ) );
				}
			}
		}
	}

	function update_menu_order() {
		global $wpdb;

		parse_str( $_POST['order'], $data );

		if ( is_array( $data ) ) {


			$id_arr = array( );
			foreach ( $data as $key => $values ) {
				foreach ( $values as $position => $id ) {
					$id_arr[] = $id;
				}
			}


			$menu_order_arr = array( );
			foreach ( $id_arr as $key => $id ) {
				$results = $wpdb->get_results( "SELECT menu_order FROM $wpdb->posts WHERE ID = " . $id );
				foreach ( $results as $result ) {
					$menu_order_arr[] = $result->menu_order;
				}
			}

			sort( $menu_order_arr );

			foreach ( $data as $key => $values ) {
				foreach ( $values as $position => $id ) {
					$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_order_arr[$position] ), array( 'ID' => $id ) );
				}
			}
		}
	}


	function docitorder_previous_post_where( $where ) {
		global $post;

		$docitorder_options = get_option( 'docitorder_options' );
		$objects = $docitorder_options['objects'];

		if ( in_array( $post->post_type, $objects ) ) {
			$current_menu_order = $post->menu_order;
			$where = "WHERE p.menu_order > '" . $current_menu_order . "' AND p.post_type = '" . $post->post_type . "' AND p.post_status = 'publish'";
		}
		return $where;
	}

	function docitorder_previous_post_sort( $orderby ) {
		global $post;

		$docitorder_options = get_option( 'docitorder_options' );
		$objects = $docitorder_options['objects'];

		if ( in_array( $post->post_type, $objects ) ) {
			$orderby = 'ORDER BY p.menu_order ASC LIMIT 1';
		}
		return $orderby;
	}

	function docitorder_next_post_where( $where ) {
		global $post;

		$docitorder_options = get_option( 'docitorder_options' );
		$objects = $docitorder_options['objects'];

		if ( in_array( $post->post_type, $objects ) ) {
			$current_menu_order = $post->menu_order;
			$where = "WHERE p.menu_order < '" . $current_menu_order . "' AND p.post_type = '" . $post->post_type . "' AND p.post_status = 'publish'";
		}
		return $where;
	}

	function docitorder_next_post_sort( $orderby ) {
		global $post;

		$docitorder_options = get_option( 'docitorder_options' );
		$objects = $docitorder_options['objects'];

		if ( in_array( $post->post_type, $objects ) ) {
			$orderby = 'ORDER BY p.menu_order DESC LIMIT 1';
		}
		return $orderby;
	}

	function docitorder_filter_active( $wp_query ) {

		if ( isset( $wp_query->query['suppress_filters'] ) )
			$wp_query->query['suppress_filters'] = false;
		if ( isset( $wp_query->query_vars['suppress_filters'] ) )
			$wp_query->query_vars['suppress_filters'] = false;
		return $wp_query;
	}

	function docitorder_pre_get_posts( $wp_query ) {
		$docitorder_options = get_option( 'docitorder_options' );
		$objects = $docitorder_options['objects'];

		if ( is_array( $objects ) ) {



			if ( is_admin() && !defined( 'DOING_AJAX' ) ) {
				if ( isset( $wp_query->query['post_type'] ) ) {
					if (isset($_GET['post_type']) && $_GET['post_type'] == 'docit') {
						if ( in_array( $wp_query->query['post_type'], $objects ) ) {
							$wp_query->set( 'orderby', 'menu_order' );
							// $wp_query->set( 'order', 'ASC' );
						}
					}
				}

				
			} else {

				$active = false;



				if ( empty( $wp_query->query ) ) {
					if ( in_array( 'post', $objects ) ) {
						$active = true;
					}
				} else {



					if ( isset( $wp_query->query['suppress_filters'] ) ) {


						if ( is_array( $wp_query->query['post_type'] ) ) {
							$post_types = $wp_query->query['post_type'];
							foreach ( $post_types as $post_type ) {
								if ( in_array( $post_type, $objects ) ) {
									$active = true;
								}
							}
						} else {
							if ( in_array( $wp_query->query['post_type'], $objects ) ) {
								$active = true;
							}
						}
					} else {


						if ( isset( $wp_query->query['post_type'] ) ) {


							if ( is_array( $wp_query->query['post_type'] ) ) {
								$post_types = $wp_query->query['post_type'];
								foreach ( $post_types as $post_type ) {
									if ( in_array( $post_type, $objects ) ) {
										$active = true;
									}
								}
							} else {
								if ( in_array( $wp_query->query['post_type'], $objects ) ) {
									$active = true;
								}
							}
						} else {
							if ( in_array( 'post', $objects ) ) {
								$active = true;
							}
						}
					}
				}

				if ( $active ) {
					if ( !isset( $wp_query->query['orderby'] ) || $wp_query->query['orderby'] == 'post_date' )
						$wp_query->set( 'orderby', 'menu_order' );
					if ( !isset( $wp_query->query['order'] ) || $wp_query->query['order'] == 'DESC' )
						$wp_query->set( 'order', 'ASC' );
				}
			}
		}
	}

}


/* =============================================================================================================
 * Taxonomy Sort
  =============================================================================================================== */

	class Taxonomy_Order_Engine {

		/**
		 * Simple class constructor
		 */
		function __construct() {
			if ( is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'docit'){
				// admin initialize
				add_action( 'admin_init', array( $this, 'admin_init' ) );
	
				// front-end initialize
				add_action( 'init', array( $this, 'init' ) );
			}
		}

		/**
		 * Initialize administration
		 */
		function admin_init() {
			if ( is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'docit'){
				// load scripts
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	
	
				// ajax to save the sorting
				add_action( 'wp_ajax_get_inline_boxes', array( $this, 'inline_edit_boxes' ) );
	
				// reorder terms when someone tries to get terms
				add_filter( 'get_terms', array( $this, 'reorder_terms' ) );
			}
		}

		/**
		 * Initialize front-page
		 *
		 */
		function init() {
			// reorder terms when someone tries to get terms
			add_filter( 'get_terms', array( $this, 'reorder_terms' ) );
		}

		/**
		 * Load scripts 
		 */
		function enqueue_scripts() {
			// allow enqueue only on tags/taxonomy page
			if ( get_current_screen()->base != 'edit-tags' )
				return;
			// load jquery and plugin's script
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'taxonomyorder', DOCIT_URL .'/admin/js/taxonomy_order.js', array( 'jquery', 'jquery-ui-sortable' ) );

			wp_enqueue_style( 'docitorder', DOCIT_URL . '/admin/css/docitorder.css', array( ), null );
		}

		/**
		 * Do the sorting
		 */
		function inline_edit_boxes() {
			// loop through rows
			foreach ( $_POST['rows'] as $key => $row ) {
				// skip empty
				if ( !isset( $row ) || $row == "" )
					continue;

				// update order
				update_post_meta($row, 'docit_tax_admin_order', ( $key + 1 ) );
			}

			// kill it for ajax
			exit;
		}

		/**
		 * Order terms 
		 */
		function reorder_terms( $objects ) {
			// we do not need empty objects
			if ( empty( $objects ) )
				return $objects;

			// placeholder for ordered objects
			$placeholder = array( );

			// invalid key counter (if key is not set)
			$invalid_key = 9000;

			// loop through objects
			foreach ( $objects as $key => $object ) {
				// increase invalid key count
				$invalid_key++;

				// continue if no term_id
				if(is_object($object)){
				if ( !isset( $object->term_id ) ){
				continue;
				}else{
				$term_id = $object->term_id;
				}
				}else{
				$term_id = $key;
				}


				// get the order key
				$term_order = get_post_meta( $object->term_id, 'docit_tax_admin_order', true );

				// use order key if exists, invalid key if not
				$term_key = ( $term_order != "" && $term_order != 0 ) ? (int) $term_order : $invalid_key;

 
				$placeholder[$term_key] = $object;
			}

 
			ksort( $placeholder );

 
			return $placeholder;
		}

	}
 
	new Taxonomy_Order_Engine;

function di_next_previous_post($postid, $tax_parent) {
	// get and echo previous and next post in the same taxonomy
	global $di_reindexed_next_prev;        
	$thisindex = array_search($postid, $di_reindexed_next_prev);
	
		$previd = $di_reindexed_next_prev[$thisindex-1];
		$nextid = $di_reindexed_next_prev[$thisindex+1];
	 
	if ( !empty($previd) ) {
	   echo '<i class="icon-chevron-left"></i> <a class="docit-prev-post" rel="prev" href="' . get_permalink($previd). '">'.get_the_title($previd).'</a>';
	}
	if ( !empty($nextid) ) {
	   echo '<i class="icon-chevron-right"></i> <a class="docit-next-post" rel="next" href="' . get_permalink($nextid). '">'.get_the_title($nextid).'</a>';
	} 
}
?>