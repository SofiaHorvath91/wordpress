<?php
/**
 * Admin View: Page - Easy Social Feed
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$FTA = new Feed_Them_All();

$fta_all_plugs = $FTA->fta_plugins();
$fta_settings = $FTA->fta_get_settings();
if( isset($fta_settings['hide_plugin'] ) ){
	$hide_plugin = $fta_settings['hide_plugin'];
}

if( isset($fta_settings['hide_upgrade'] ) ){
	$hide_upgrade = $fta_settings['hide_upgrade'];
}

if( isset( $hide_plugin ) && isset( $hide_upgrade ) ){
	$hide_sidebar_class = 'esf-sidebar-is-hide';
}else{
	$hide_sidebar_class = '';
}
?>
    <div class="esf_loader_wrap">
        <div class="esf_loader_inner">
            <div class="loader"></div>
        </div>
    </div>
    <h1 class="esf-main-heading"><?php esc_html_e( "Easy Social Feed (Previously Easy Facebook Likebox)", 'easy-facebook-likebox' ); ?></h1>

    <div class="fta_wrap_outer <?php echo $hide_sidebar_class; ?>" <?php if( efl_fs()->is_free_plan() ){?> style="width: 78%" <?php } ?>>
        <div class="fta_wrap z-depth-1">
        <div class="fta_wrap_inner">
            <div class="fta_tabs_holder">
                <div class="fta_tabs_header">
                    <div class="fta_sliders_wrap">
                        <div id="fta_sliders">
            <span>
              <div class="box"></div>
            </span>
                            <span>
              <div class="box"></div>
            </span>
                            <span>
              <div class="box"></div>
            </span>
                        </div>

                    </div>
                </div>
                <div class="fta_tab_c_holder">
                    <div class="row">
                        <h5><?php esc_html_e( "Welcome to the modules management page", 'easy-facebook-likebox' ); ?></h5>
                        <p><?php esc_html_e( "You can disable the module which you are not using. It will help us to include only required resources to make your site load faster", 'easy-facebook-likebox' ); ?>.</p>

						<?php if ( $fta_all_plugs ) : ?>

                            <div class="fta_all_plugs col s12">

								<?php foreach ( $fta_all_plugs as $fta_plug ) :


									if ( $fta_plug['activate_slug'] ) {

										$slug = $fta_plug['activate_slug'];

									} else {

										$slug = '';
									}

									if ( $fta_plug['status'] ) {

										$status = $fta_plug['status'];

									} else {

										$status = '';
									}

									if ( ( $fta_plug['status'] ) && ( $fta_plug['status'] == 'activated' ) ) {

										$btn = __( 'Deactivate', 'easy-facebook-likebox' );

									} else {

										$btn = __( 'Activate', 'easy-facebook-likebox' );
									}


									?>
                                    <div class="card col fta_single_plug s5 fta_plug_<?php echo $slug ?>  fta_plug_<?php echo $status; ?>">

                                        <div class="card-content">

											<?php if ( $fta_plug['name'] ) { ?>

                                                <span class="card-title  grey-text text-darken-4"><?php echo $fta_plug['name'] ?></span>

											<?php } ?>

                                        </div>
                                        <hr>
                                        <div class="fta_cta_holder">

											<?php if ( $fta_plug['description'] ) { ?>

												<?php echo $fta_plug['description'] ?>

											<?php } ?>

                                            <a class="btn waves-effect fta_plug_activate waves-light"
                                               data-status="<?php echo $status; ?>"
                                               data-plug="<?php echo $slug ?>"
                                               href="#"><?php echo $btn; ?></a>

											<?php if ( $fta_plug['slug'] ) { ?>

                                                <a class="btn waves-effect fta_setting_btn right waves-light"
                                                   href="<?php echo esc_url( admin_url( 'admin.php?page=' . $fta_plug['slug'] ) ); ?>"><?php esc_html_e( "Settings", 'easy-facebook-likebox' ); ?></a>

											<?php } ?>

                                        </div>

                                    </div>
								<?php endforeach; ?>
                            </div>
						<?php endif;
						?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

<?php if ( efl_fs()->is_free_plan() ) {
	if( !isset( $hide_plugin ) && !isset( $hide_upgrade ) ){
	$mt_plugins = $this->mt_plugins_info();
	?>

    <div class="fta-other-plugins-sidebar">


<?php if ( $mt_plugins ) { ?>

    <div class="fta-other-plugins-wrap z-depth-1 esf-hide-plugin">

        <div class="fta-other-plugins-head">
            <div class="dashicons dashicons-no-alt esf-hide-free-sidebar" data-id="plugin"></div>
            <h5><?php esc_html_e( 'Love this plugin?', 'easy-facebook-likebox' ); ?></h5>
            <p><?php esc_html_e( 'Then why not try our other FREE plugins.', 'easy-facebook-likebox' ); ?></p>
        </div>

        <div class="fta-plugins-carousel">
            <div class="carousel carousel-slider center">

				<?php foreach ( $mt_plugins as $slug => $mt_plugin ) {

					$install_link = $this->mt_plugin_install_link( $slug ); ?>

                    <div class="carousel-item"
                         href="<?php esc_attr_e( $slug ); ?>">

						<?php if ( $mt_plugin->name ) { ?>

                            <h2><?php esc_html_e( $mt_plugin->name ); ?></h2>

						<?php } ?>

						<?php if ( $mt_plugin->description ) { ?>

                            <p><?php echo nl2br( esc_html( $mt_plugin->description ) ); ?></p>

						<?php } ?>

						<?php if ( $mt_plugin->active_installs ) { ?>

                            <p><?php if ( strpos( $mt_plugin->active_installs, 'Just' ) !== false ) {
									esc_html_e( $mt_plugin->active_installs );
								} else {
									esc_html_e( 'Active Installs: ', 'easy-facebook-likebox' );
									esc_html_e( $mt_plugin->active_installs );
								} ?></p>

						<?php } ?>

                        <span title="<?php esc_html_e( '5-Star Rating', 'easy-facebook-likebox' ) ?>"
                              class="stars">★ ★ ★ ★ ★ </span>

                        <div class="fta-carousel-actions">
                            <a href="<?php echo esc_url( $install_link ); ?>"><?php if ( filter_var( $install_link, FILTER_VALIDATE_URL ) === false ) {
									esc_html_e( 'Already Installed', 'easy-facebook-likebox' );
								} else {
									esc_html_e( 'Install Now Free', 'easy-facebook-likebox' );
								} ?></a>

                            <a class="right"
                               href="https://wordpress.org/plugins/<?php esc_attr_e( $slug ); ?>"
                               target="_blank"><?php esc_html_e( 'More Info', 'easy-facebook-likebox' ) ?></a>
                        </div>

                    </div>
				<?php } ?>

            </div>
        </div>
    </div>

<?php } ?>

	    <?php $banner_info = $this->esf_upgrade_banner();
		if( !isset( $fta_settings['hide_upgrade']) ){ ?>

        <div class="espf-upgrade z-depth-2 esf-hide-upgrade">
            <div class="dashicons dashicons-no-alt esf-hide-free-sidebar" data-id="upgrade"></div>
            <h2><?php  esc_html_e( $banner_info['name'] ); ?>
                <b><?php  esc_html_e( $banner_info['bold'] ); ?></b></h2>
            <p><?php  esc_html_e( $banner_info['fb-description'] ); ?></p>
            <p><?php  esc_html_e( $banner_info['discount-text'] ); ?> <code><?php  esc_html_e( $banner_info['coupon'] ); ?></code>
            </p>
            <a href="<?php echo esc_url( efl_fs()->get_upgrade_url() ) ?>"
               class="waves-effect waves-light btn"><i class="material-icons right">lock_open</i><?php esc_html_e( $banner_info['button-text'] ); ?>
            </a>
        </div>

        <?php } ?>

    </div>

<?php
}
} ?>