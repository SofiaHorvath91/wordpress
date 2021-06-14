<?php

if ( !class_exists( 'Nedwp_Feedback_Modal_Export' ) ) {

	class Nedwp_Feedback_Modal_Export {

		public function send_headers( $file_type ) {

			$file_name = sprintf( '%1$s-%2$s', __( 'Feedback', 'nedwp-feedback-modal' ), date("Y-m-d") );
			$file_name = apply_filters( 'nedwp_fm_export_file_name', $file_name );
			$file = sanitize_file_name( sprintf( '%1$s.%2$s', $file_name, $file_type ) );

	        header( "Content-Type: application/force-download" );
	        header( "Content-Type: application/octet-stream" );
	        header( "Content-Type: application/download" );
			header( "Content-Disposition: attachment; filename={$file}" );
			header( "Expires: 0" );
			header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
			header( "Content-Transfer-Encoding: binary" );
			header( "Pragma: public" );

		}

		public function get_data() {

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

		public function array_to_CSV() {

			$data_CSV = array(
				array(
					__( 'ID', 'nedwp-feedback-modal' ),
					__( 'Opinion', 'nedwp-feedback-modal' ),
					__( 'Comment', 'nedwp-feedback-modal' ),
					__( 'Email', 'nedwp-feedback-modal' ),
					__( 'Date', 'nedwp-feedback-modal' )
				)
			);

			$index = count( $data_CSV );
			$data = $this->get_data();

			if ( is_array( $data ) && $data ) {

				foreach ( $data as $data_arr ) {

					$data_CSV[$index] = array();

					foreach ( $data_arr as $key => $value ) {
						$data_CSV[$index][] = $value;
					}

					$index++;
					
				}

			}

			return $data_CSV;
		}

		public function download( $type ) {

			$this->send_headers( $type );

			$out = fopen( 'php://output', 'w' );

			ob_start();

			if ( 'csv' === $type ) {

				$data_CSV = $this->array_to_CSV();

				foreach ( $data_CSV as $CSV_line ) {
					fputcsv( $out, $CSV_line );
				}

			} else if ( 'json' === $type ) {

				if ( $data = $this->get_data() ) {
					echo json_encode( $data );
				} else {
					echo json_encode( array() );
				}

			}

			echo ob_get_clean();

			fclose( $out );

			die();

		}

	}

}