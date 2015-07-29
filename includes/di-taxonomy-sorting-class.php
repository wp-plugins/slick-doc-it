<?php namespace Doc_It;
class Taxonomy_Order_Engine extends Doc_It_Core {
	/**
	 * Simple class constructor
	 */
	function __construct() {
		// admin initialize
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		// front-end initialize
		add_action( 'init', array( $this, 'init' ) );
	}
	/**
	 * Initialize administration
	 */
	function admin_init() {
		// load scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		// ajax to save the sorting
		add_action( 'wp_ajax_get_inline_boxes', array( $this, 'inline_edit_boxes' ) );
		// reorder terms when someone tries to get terms
		add_filter( 'get_terms', array( $this, 'reorder_terms' ) );
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
		if (isset($_GET['post_type']) && $_GET['post_type'] == 'docit') {
			// load jquery and plugin's script
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'taxonomyorder', DOCIT_URL .'/admin/js/taxonomy_order.js', array( 'jquery', 'jquery-ui-sortable' ) );
			wp_enqueue_style( 'docitorder', DOCIT_URL . '/admin/css/docitorder.css', array( ), null );
		}
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
			if (is_object($object)) {
				if ( !isset( $object->term_id ) ) {
					continue;
				}else {
					$term_id = $object->term_id;
				}
			}else {
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
?>