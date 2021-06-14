<?php
/**
 * Admin View: Tab - Clear Cache
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wpdb;
$FTA          = new Feed_Them_All();
$fta_settings = $FTA->fta_get_settings();


?>
<div id="efbl-cached" class="col s12 efbl_tab_c slideLeft">
    <h5><?php esc_html_e( "Cached Pages", 'easy-facebook-likebox' ); ?></h5>
    <p><?php esc_html_e( "Following are the pages cached data from Facebook API. Delete the cache to refresh your feeds manually", 'easy-facebook-likebox' ); ?></p>

	<?php

	$efbl_trans_sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
    FROM  $wpdb->options
    WHERE `option_name` LIKE '%transient_%'
    ORDER BY `option_name`";

	$efbl_trans_results = $wpdb->get_results( $efbl_trans_sql );
	$efbl_trans_posts   = [];
	$efbl_trans_group   = [];
	$efbl_trans_bio     = [];

	if ( $efbl_trans_results ) {
		foreach ( $efbl_trans_results as $efbl_trans_result ) {

			/*
			 * Checking EFBL exists in transient slug then save that in efbl transient array.
			 */
			if ( strpos( $efbl_trans_result->name, 'efbl' ) !== false && strpos( $efbl_trans_result->name, 'posts' ) !== false && strpos( $efbl_trans_result->name, 'timeout' ) == false ) {
				$efbl_trans_posts[ $efbl_trans_result->name ] = $efbl_trans_result->value;
			}

			/*
			  * Checking EFBL exists in transient slug then save that in efbl transient array.
			  */
			if ( strpos( $efbl_trans_result->name, 'efbl' ) !== false && strpos( $efbl_trans_result->name, 'bio' ) !== false && strpos( $efbl_trans_result->name, 'timeout' ) == false ) {
				$efbl_trans_bio[ $efbl_trans_result->name ] = $efbl_trans_result->value;
			}

			/*
		 * Checking EFBL exists in transient slug then save that in efbl transient array.
		 */
			if ( strpos( $efbl_trans_result->name, 'efbl' ) !== false && strpos( $efbl_trans_result->name, 'group' ) !== false && strpos( $efbl_trans_result->name, 'timeout' ) == false ) {
				$efbl_trans_group[ $efbl_trans_result->name ] = $efbl_trans_result->value;
			}

		}
	}

	if ( $efbl_trans_bio ) { ?>
        <ul class="collection with-header efbl_bio_collection">
            <li class="collection-header">
                <h5><?php esc_html_e( "Page(s) Bio", 'easy-facebook-likebox' ); ?></h5>
            </li>

			<?php foreach ( $efbl_trans_bio as $key => $value ) {
				$pieces     = explode( '-', $key );
				$trans_name = array_pop( $pieces );

				$approved_pages = $fta_settings['plugins']['facebook']['approved_pages'];

				$bio_name = '';

				if ( isset( $approved_pages[ $trans_name ] ) ) {

					$efbl_post = $approved_pages[ $trans_name ];

					$bio_name = $efbl_post['name'];

				}

				?>

                <li class="collection-item <?php echo $key ?>">
                    <div><?php echo $bio_name ?>
                        <a href="javascript:void(0);"
                           data-efbl_collection="efbl_bio_collection"
                           data-efbl_trans="<?php echo $key ?>"
                           class="secondary-content efbl_del_trans"><i
                                    class="material-icons">delete</i></a>
                    </div>
                </li>

			<?php } ?>
        </ul>

	<?php }

	if ( $efbl_trans_posts ) { ?>

        <ul class="collection with-header efbl_posts_collection">
            <li class="collection-header">
                <h5><?php esc_html_e( "Page(s) Feed", 'easy-facebook-likebox' ); ?></h5>
            </li>

			<?php foreach ( $efbl_trans_posts as $key => $value ) {

				$filter = '';

				$pieces = explode( '_', $key );

				$page_name = array_pop( $pieces );

				$second_pieces = explode( '-', $page_name );

				$page_name = $second_pieces['0'];

				$key = str_replace( ' ', '', $key );

				$filter = $pieces['3'];
				?>

                <li class="collection-item <?php echo $key ?>">
                    <div><?php echo $page_name ?> <?php if ( $filter ): ?>(<?php echo ucfirst( $filter ); ?>) <?php endif; ?>
                        <a href="javascript:void(0);"
                           data-efbl_trans="<?php echo $key ?>"
                           class="secondary-content efbl_del_trans"><i
                                    class="material-icons">delete</i></a>
                    </div>
                </li>

			<?php } ?>
        </ul>
	<?php }
	if ( $efbl_trans_group ) { ?>

        <ul class="collection with-header efbl_posts_collection">
            <li class="collection-header">
                <h5><?php esc_html_e( "Group(s) Feed", 'easy-facebook-likebox' ); ?></h5>
            </li>

			<?php foreach ( $efbl_trans_group as $key => $value ) {

				$pieces = explode( '_', $key );
                $page_name = $pieces[4];

                if( isset( $fta_settings['plugins']['facebook']['approved_groups'] ) ){
	                $approved_groups = $fta_settings['plugins']['facebook']['approved_groups'];
	                $approved_groups = $approved_groups;
	                $post_key = array_search( $page_name, array_column( $approved_groups, 'id' ) );
	                $page_name = $approved_groups[$post_key]->name;
				}


				$key = str_replace( ' ', '', $key );

				?>

                <li class="collection-item <?php echo $key ?>">
                    <div><?php echo $page_name ?>
                        <a href="javascript:void(0);"
                           data-efbl_trans="<?php echo $key ?>"
                           class="secondary-content efbl_del_trans"><i
                                    class="material-icons">delete</i></a>
                    </div>
                </li>

			<?php } ?>
        </ul>
	<?php }?>

</div>
