<?php
/**
 * Admin View: Tab - Clear Cache
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wpdb;

$FTA = new Feed_Them_All();

$fta_settings = $FTA->fta_get_settings();

if ( isset( $fta_settings['plugins']['instagram']['access_token'] ) ) {

	$access_token = $fta_settings['plugins']['instagram']['access_token'];

}


$mif_trans_sql = "SELECT `option_name` AS `name`, `option_value` AS `value` FROM  {$wpdb->options} WHERE `option_name` LIKE '%transient_%'            ORDER BY `option_name`";

$mif_trans_results = $wpdb->get_results( $mif_trans_sql );

$mif_trans_posts = [];

$mif_trans_bio = [];

$mif_trans_stories = [];


if ( $mif_trans_results ) {
	foreach ( $mif_trans_results as $mif_trans_result ) {

		if ( strpos( $mif_trans_result->name, 'esf_insta' ) !== false && strpos( $mif_trans_result->name, 'posts' ) !== false && strpos( $mif_trans_result->name, 'timeout' ) == false ) {
			$mif_trans_posts[ $mif_trans_result->name ] = $mif_trans_result->value;
		}

		if ( strpos( $mif_trans_result->name, 'esf_insta' ) !== false && strpos( $mif_trans_result->name, 'stories' ) !== false && strpos( $mif_trans_result->name, 'timeout' ) == false ) {
			$mif_trans_stories[ $mif_trans_result->name ] = $mif_trans_result->value;
		}


		if ( strpos( $mif_trans_result->name, 'esf_insta' ) !== false && strpos( $mif_trans_result->name, 'bio' ) !== false && strpos( $mif_trans_result->name, 'timeout' ) == false ) {
			$mif_trans_bio[ $mif_trans_result->name ] = $mif_trans_result->value;
		}

	}
}

?>
<div id="mif-cache" class="col s12 mif_tab_c slideLeft">
    <div class="mif-swipe-cache_wrap">
        <h5><?php esc_html_e( "Cached Feeds", 'easy-facebook-likebox' ); ?></h5>
        <p><?php esc_html_e( "Following are the feeds cached data from Instagram API. Delete the cache to refresh your feeds manually", 'easy-facebook-likebox' ); ?></p>

		<?php

		if ( $mif_trans_bio ) { ?>

            <ul class="collection with-header mif_bio_collection">
                <li class="collection-header">
                    <h5><?php esc_html_e( "Profile Bio", 'easy-facebook-likebox' ); ?></h5>
                </li>

				<?php foreach ( $mif_trans_bio as $key => $value ) {
					$pieces     = explode( '-', $key );
					$trans_name = array_pop( $pieces );

					?>

                    <li class="collection-item <?php echo $key; ?>">
                        <div><?php echo $trans_name; ?>
                            <a href="javascript:void(0);"
                               data-mif_collection="mif_bio_collection"
                               data-mif_trans="<?php echo $key; ?>"
                               class="secondary-content mif_del_trans"><i
                                        class="material-icons">delete</i></a>
                        </div>
                    </li>
				<?php } ?>

            </ul>

		<?php }
		if ( $mif_trans_stories ) { ?>

            <ul class="collection with-header mif_bio_collection">
                <li class="collection-header">
                    <h5><?php esc_html_e( "Stories", 'easy-facebook-likebox' ); ?></h5>
                </li>

				<?php foreach ( $mif_trans_stories as $key => $value ) {
					$pieces     = explode( '-', $key );
					$trans_name = array_pop( $pieces );

					?>

                    <li class="collection-item <?php echo $key; ?>">
                        <div><?php echo $trans_name; ?>
                            <a href="javascript:void(0);"
                               data-mif_collection="mif_bio_collection"
                               data-mif_trans="<?php echo $key; ?>"
                               class="secondary-content mif_del_trans"><i
                                        class="material-icons">delete</i></a>
                        </div>
                    </li>
				<?php } ?>

            </ul>

		<?php }

		if ( $mif_trans_posts ) { ?>

            <ul class="collection with-header mif_users_collection">
                <li class="collection-header">
                    <h5><?php esc_html_e( "Feeds", 'easy-facebook-likebox' ); ?></h5>
                </li>

				<?php foreach ( $mif_trans_posts as $key => $value ) {
					$pieces     = explode( '-', $key );
					$trans_name = array_pop( $pieces );
					$trans_name = $pieces['1'];

					if ( strpos( $key, 'hashtag' ) !== false ) {

						$hashtag_pieces = explode( '-', $key );

						if ( isset( $hashtag_pieces['4'] ) ) {
							$trans_name = '#' . $hashtag_pieces['4'];
						}

					}


					?>

                    <li class="collection-item <?php echo $key; ?>">
                        <div><?php echo $trans_name; ?>
                            <a href="javascript:void(0);"
                               data-mif_collection="mif_users_collection"
                               data-mif_trans="<?php echo $key; ?>"
                               class="secondary-content mif_del_trans"><i
                                        class="material-icons">delete</i></a>
                        </div>
                    </li>
				<?php } ?>
            </ul>

		<?php }


		if ( empty( $mif_trans_posts ) && empty( $mif_trans_bio ) ) { ?>

            <p><?php esc_html_e( "Whoops! nothing cached at the moment.", 'easy-facebook-likebox' ); ?></p>

		<?php } ?>
    </div>
</div>
