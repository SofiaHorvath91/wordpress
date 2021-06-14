<?php

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( !class_exists( 'Nedwp_Feedback_Modal_Admin_List' ) ) {
	class Nedwp_Feedback_Modal_Admin_List extends WP_List_Table {

		public function __construct() {

			parent::__construct(
				array(
					'singular' => 'nedwp_fm_fb',
					'plural' => 'nedwp_fm_fbs',
					'ajax' => false
				)
			);

	    }

	    public function prepare_items() {

	    	$this->process_bulk_action();
	        $columns = $this->get_columns();
	        $hidden = $this->get_hidden_columns();
	        $sortable = $this->get_sortable_columns();
	        $data = $this->table_data();
	        $current_page = $this->get_pagenum();
	        $per_page = 10;

	        $this->set_pagination_args( array(
	            'total_items' => count( $data ),
	            'per_page' => $per_page
	        ) );

	        $data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
	        $this->_column_headers = array( $columns, $hidden, $sortable );
	        $this->items = $data;
	        
	    }

	    public function get_columns() {

	        $columns = array(
	        	'cb' => '<input type="checkbox" />',
	        	'id' => __( 'ID', 'nedwp-feedback-modal' ),
	            'opinion' => __( 'Opinion', 'nedwp-feedback-modal' ),
	            'comment' => __( 'Comment', 'nedwp-feedback-modal' ),
	            'email' => __( 'Email', 'nedwp-feedback-modal' ),
	            'date_in' => __( 'Date', 'nedwp-feedback-modal' )
	        );

	        return $columns;
	    }

	    public function get_hidden_columns() {
	        return array( 'id' );
	    }

		public function get_sortable_columns() {
			return array(
				'date_in' => array( 'date_in', true ),
				'opinion' => array( 'opinion', true )
			);
		}

	    private function table_data() {

	    	global $wpdb;

	        $table_name = $wpdb->prefix . 'nedwp_fm';
	        $orderby = 'date_in';
	        $order = 'desc';

	        if ( !empty( $_GET['orderby'] ) ) {

	        	$orderby_input = strtolower( sanitize_text_field( $_GET['orderby'] ) );

	        	if ( in_array( $orderby_input, array( 'date_in', 'opinion' ) ) ) {
	        		$orderby = $orderby_input;
	        	}

	        }

	        if ( !empty( $_GET['order'] ) ) {

	        	$order_input = strtolower( sanitize_text_field( $_GET['order'] ) );

	        	if ( in_array( $order_input, array( 'asc', 'desc' ) ) ) {
	        		$order = $order_input;
	        	}

	        }

			$sql = "SELECT * FROM $table_name ORDER BY $orderby $order";

			return $wpdb->get_results( $sql, 'ARRAY_A' );
	    }

		function no_items() {
			esc_html_e( 'No feedback founds.', 'nedwp-feedback-modal' );
		}

	    public function column_default( $item, $column_name ) {
	        return $item[$column_name];
	    }

	    private function sort_data( $a, $b ) {

	        $orderby = 'date_in';
	        $order = 'desc';

	        if ( !empty( $_GET['orderby'] ) ) {

	        	$orderby_input = strtolower( sanitize_text_field( $_GET['orderby'] ) );

	        	if ( in_array( $orderby_input, array( 'date_in', 'opinion' ) ) ) {
	        		$orderby = $orderby_input;
	        	}

	        }

	        if ( !empty( $_GET['order'] ) ) {

	        	$order_input = strtolower( sanitize_text_field( $_GET['order'] ) );

	        	if ( in_array( $order_input, array( 'asc', 'desc' ) ) ) {
	        		$order = $order_input;
	        	}

	        }

	        $result = strcmp( $a[$orderby], $b[$orderby] );

	        return ( $order === 'desc' ) ? $result : -$result;
	    }

		public function get_bulk_actions() {
		    return array(
		    	'delete' => __( 'Delete', 'nedwp-feedback-modal' )
		    );
		}

		public function column_cb( $item ) {
		    return sprintf(
				'<input type="checkbox" name="%1$s[]" value="%2$s" />',
				esc_attr( $this->_args['singular'] ),
				esc_attr( $item['id'] )
		    );
		}

		function column_opinion( $item ) {

			$delete_nonce = wp_create_nonce( 'nedwp_fm_del_item' );

			$actions = array(
				'delete' => sprintf( '<a href="?page=%1$s&action=delete&f_id=%2$s&_wpnonce=%3$s">%4$s</a>',
					esc_attr( $_REQUEST['page'] ),
					esc_attr( absint( $item['id'] ) ),
					esc_attr( $delete_nonce ),
					esc_html__( 'Delete', 'nedwp-feedback-modal' )
				)
			);

			return $item['opinion'] . $this->row_actions( $actions );
		}

		public function process_bulk_action() {

		    $action = $this->current_action();

		    // Process action
		    if ( $action && 'delete' === $action ) {

			    // Vertify nonce
			    if ( !empty( $_REQUEST['_wpnonce'] ) ) {

			    	if ( !empty( $_POST['_wpnonce'] ) ) {
				        $nonce = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
				        $nonce_action = sprintf( 'bulk-%1$s', $this->_args['plural'] );
			    	} else {
			    		$nonce = sanitize_text_field( $_GET['_wpnonce'] );
			    		$nonce_action = 'nedwp_fm_del_item';
			    	}

			        if ( !wp_verify_nonce( $nonce, $nonce_action ) ) {
			            wp_die( _e( 'Not valid.', 'nedwp-feedback-modal' ) );
			        }

			    }
				
				global $wpdb;
				$table_name = $wpdb->prefix . 'nedwp_fm';

				// Bulk
				if ( !empty( $_POST['nedwp_fm_fb'] ) && is_array( $_POST['nedwp_fm_fb'] ) ) {

					$selected_ids = $_POST['nedwp_fm_fb'];

					foreach ( $selected_ids as $selected_id ) {
						$selected_id = absint( $selected_id );
						$wpdb->delete( $table_name, array( 'id' => $selected_id ), array( '%d' ) );
					}

				} else {

					// Single
					if ( isset( $_GET['f_id'] ) ) {
						$id = absint( $_GET['f_id'] );
						$wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ) );
					}

				}

		    }

		}

		protected function bulk_actions( $which = '' ) {

			// Bulk action
		    if ( is_null( $this->_actions ) ) {
		        $this->_actions = $this->get_bulk_actions();
		        $this->_actions = apply_filters( "bulk_actions-{$this->screen->id}", $this->_actions );
		        $two = '';
		    } else {
		        $two = '2';
		    }
		 
		    if ( empty( $this->_actions ) ) {
		        return;
		    }

		    printf( '<label for="bulk-action-selector-%1$s" class="screen-reader-text">%2$s</label>',
		    	esc_attr( $which ),
		    	esc_html__( 'Select bulk action', 'nedwp-feedback-modal' )
			);

			printf( '<select name="action%1$s" id="bulk-action-selector-%2$s" />',
				esc_attr( $two ),
				esc_attr( $which )
			);

				printf( '<option value="-1">%1$s</option>', esc_html__( 'Bulk Actions', 'nedwp-feedback-modal' ) );

				foreach ( $this->_actions as $name => $title ) {

					$class = ( 'edit' === $name ) ? 'hide-if-no-js' : '';

					printf( '<option value="%1$s" class="%2$s">%3$s</option>',
						esc_attr( $name ),
						esc_attr( $class ),
						esc_html( $title )
					);
					
				}

			print( '</select>' );

			submit_button( __( 'Apply', 'nedwp-feedback-modal' ), 'action', '', false, array( 'id' => "doaction$two" ) );

			if ( nedwp_fm_opt( 'exporting' ) ) {

				// Export CSV
				printf( '<a href="%1$s&export_data=1&export_type=csv" class="nedwp-btn-export button">%2$s</a>',
					esc_url( $_SERVER['REQUEST_URI'] ),
					esc_html__( 'Export all CSV', 'nedwp-feedback-modal' )
				);

				// Export JSON
				printf( '<a href="%1$s&export_data=1&export_type=json" class="nedwp-btn-export button">%2$s</a>',
					esc_url( $_SERVER['REQUEST_URI'] ),
					esc_html__( 'Export all JSON', 'nedwp-feedback-modal' )
				);

			}

		}

	}

}