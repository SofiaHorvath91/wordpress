<?php
/**
 * Admin View: Page - Welcome
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rest_upgrade_url = 'https://easysocialfeed.com/wp-json/wellcome-video/link';

$response = wp_remote_get( $rest_upgrade_url );

$responseBody = wp_remote_retrieve_body( $response );

$video_link = json_decode( $responseBody );

if ( is_wp_error( $video_link ) ) {
	$video_link = 'https://www.youtube.com/embed/9ZvHmlozcHA';
}
?>

<div class="esf_loader_wrap">
    <div class="esf_loader_inner">
        <div class="loader esf_welcome_loader"></div>
    </div>
</div>
<div class="fta_wrap z-depth-1 esf_wc_wrap">
    <div class="fta_wrap_inner">
        <div class="fta_tab_c_holder">
            <div class="row">
                <div class="esf_wc_header">
                    <div class="esf_wc_header_top">
                        <img class="esf-plugin-logo" src="<?php echo FTA_PLUGIN_URL ?>/admin/assets/images/plugin-logo.png">
                        <h1><?php esc_html_e( "Welcome", 'easy-facebook-likebox' ); ?> </h1>
                    </div>
                    <p><?php esc_html_e( "Easy Social Feed plugin has the following awesome features", 'easy-facebook-likebox' ); ?></p>
                </div>
                <div class="esf_wc_boxes_wrap">
                    <div class="esf_wc_box">
                        <div class="esf_wc_box_img">
                            <img src="<?php echo FTA_PLUGIN_URL ?>/admin/assets/images/instagram-feed-icon.png"/>
                        </div>
                        <div class="esf_wc_box_content">
                            <h5><?php esc_html_e( "Custom Instagram Feed", 'easy-facebook-likebox' ); ?></h5>
                            <p><?php esc_html_e( "Display your stunning photos and videos from your Instagram account on your site. It’s responsive, highly customizable, and SEO-friendly", 'easy-facebook-likebox' ); ?>.</p>
                            <div class="esf_wc_box_btns_holder">
                                <a class="waves-effect waves-light btn"
                                   href="<?php echo esc_url( admin_url( 'admin.php?page=mif' ) ) ?>">
                                    <i class="material-icons right">arrow_forward</i><?php esc_html_e( "Use this feature", 'easy-facebook-likebox' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="esf_wc_box">
                        <div class="esf_wc_box_img">
                            <img src="<?php echo FTA_PLUGIN_URL ?>/admin/assets/images/facebook-feed-icon.png"/>
                        </div>
                        <div class="esf_wc_box_content">
                            <h5><?php esc_html_e( "Custom Facebook Feed", 'easy-facebook-likebox' ); ?></h5>
                            <p><?php esc_html_e( "Display a customizable, responsive, and SEO-friendly feed of your Facebook posts on your site. Supports all types of posts, including images, videos, status, and events", 'easy-facebook-likebox' ); ?>.</p>
                            <div class="esf_wc_box_btns_holder">
                                <a class="waves-effect waves-light btn"
                                   href="<?php echo esc_url( admin_url( 'admin.php?page=easy-facebook-likebox' ) ) ?>">
                                    <i class="material-icons right">arrow_forward</i> <?php esc_html_e( "Use this feature", 'easy-facebook-likebox' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="esf_wc_box">
                        <div class="esf_wc_box_img">
                            <img src="<?php echo FTA_PLUGIN_URL ?>/admin/assets/images/likebox-icon.png"/>
                        </div>
                        <div class="esf_wc_box_content">
                            <h5><?php esc_html_e( "Facebook Page Plugin (Like box)", 'easy-facebook-likebox' ); ?></h5>
                            <p><?php esc_html_e( "Displays a Facebook Page Plugin. The Facebook Page Plugin is a social plugin that enables Facebook Page owners to attract and gain Likes from their own website.", 'easy-facebook-likebox' ); ?></p>
                            <div class="esf_wc_box_btns_holder">
                                <a class="waves-effect waves-light btn"
                                   href="<?php echo esc_url( admin_url( 'admin.php?page=easy-facebook-likebox#efbl-likebox' ) ); ?>">
                                    <i class="material-icons right">arrow_forward</i> <?php esc_html_e( "Use this feature", 'easy-facebook-likebox' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="esf-quick-setup-wrap">
                    <h5><?php esc_html_e( "Quick Start Video", 'easy-facebook-likebox' ); ?></h5>
                    <iframe height="600"
                            src="<?php echo esc_url( $video_link ); ?>"
                            frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>