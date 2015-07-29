<?php namespace Doc_It;
class DocItOrder_Engine extends Doc_It_Core {
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
		$tex = isset($docitorder_options['taxonomy']) ? $docitorder_options['taxonomy'] : '';
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
		global $pagenow;
		if ( is_admin() && $pagenow == 'edit-tags.php' )
			return;
		// load JavaScript
		if (isset($_GET['post_type']) && $_GET['post_type'] == 'docit') {
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
					if ( in_array( $wp_query->query['post_type'], $objects ) ) {
						$wp_query->set( 'orderby', 'menu_order' );
						$wp_query->set( 'order', 'ASC' );
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
						if ( isset($wp_query->query['post_type']) && is_array( $wp_query->query['post_type'] ) ) {
							$post_types = $wp_query->query['post_type'];
							foreach ( $post_types as $post_type ) {
								if ( in_array( $post_type, $objects ) ) {
									$active = true;
								}
							}
						} else {
							if (isset($wp_query->query['post_type']) && in_array( $wp_query->query['post_type'], $objects ) ) {
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
new DocItOrder_Engine();
?>