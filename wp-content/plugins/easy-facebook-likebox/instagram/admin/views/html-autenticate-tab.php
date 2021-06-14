<?php
/**
 * Admin View: Tab - Authenticate
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$FTA = new Feed_Them_All();

$fta_settings = $FTA->fta_get_settings();

if ( isset( $fta_settings['plugins']['instagram']['instagram_connected_account'] ) ) {

	$mif_personal_connected_accounts = $fta_settings['plugins']['instagram']['instagram_connected_account'];

} else {

	$mif_personal_connected_accounts = [];

}

if ( ( isset( $_GET['access_token'] ) && ! empty( $_GET['access_token'] ) ) || ( isset( $_GET['mif_access_token'] ) && ! empty( $_GET['mif_access_token'] ) ) ) {

	if ( ! empty( $_GET['access_token'] ) ) {
		$access_token = $_GET['access_token'];
		$action       = 'mif_save_business_access_token';
		$remove_pram  = 'access_token';
	}

	if ( ! empty( $_GET['mif_access_token'] ) ) {
		$access_token = $_GET['mif_access_token'];
		$action       = 'mif_save_access_token';
		$remove_pram  = 'mif_access_token';
	}

	$access_token = preg_replace( '/[^A-Za-z0-9]/', "", $access_token );

	if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) { ?>

        <script>
          jQuery(document).ready(function($) {

            function MIFremoveURLParameter(url, parameter) {
              var urlparts = url.split('?');
              if (urlparts.length >= 2) {
                var prefix = encodeURIComponent(parameter) + '=';
                var pars = urlparts[1].split(/[&;]/g);
                for (var i = pars.length; i-- > 0;) {
                  if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                  }
                }
                url = urlparts[0] + '?' + pars.join('&');
                return url;
              }
              else { return url; }
            }

            jQuery('#toast-container').slideUp('slow');

            Materialize.Toast.removeAll();

            /*
			 * Show the dialog for Saving.
			 */
            Materialize.toast('Please wait! Authenticating...', 50000000);

            var url = window.location.href;

            url = MIFremoveURLParameter(url, "<?php echo $remove_pram; ?>");

            jQuery('#efbl_access_token').text("<?php echo $access_token; ?>");

            var data = {
              'action': '<?php echo $action; ?>',
              'access_token': '<?php echo $access_token; ?>',
              'id': 'insta',
            };

            jQuery.ajax({

              url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
              type: 'post',
              data: data,
              dataType: 'json',
              success: function(response) {

                window.history.pushState('newurl', 'newurl', url);

                Materialize.Toast.removeAll();

                if (response.success) {

                  var pages_html = response.data['1'];

                  // console.log(response.data);return;
                  Materialize.toast(response.data['0'], 3000);
                  jQuery('#toast-container').addClass('efbl_green');
                  jQuery('.efbl_all_pages').
                      html(' ').
                      html(response.data['1']).
                      slideDown('slow');
                  jQuery('.fta_noti_holder').fadeOut('slow');
                  setTimeout(function() {
                    window.location.href = '#mif-shortcode';
                    window.location.reload();
                  }, 2000);
                }
                else {
                  Materialize.toast(response.data, 3000);

                  jQuery('#toast-container').addClass('efbl_red');

                }
              },
            });

          });
        </script>
		<?php
	}
}
?>
<div id="mif-general" class="col s12 mif_tab_c slideLeft">
    <h5><?php esc_html_e( "Let's connect your account with plugin", 'easy-facebook-likebox' ); ?></h5>
    <p><?php esc_html_e( "Click the button below, log into your Instagram account and authorize the app to get access token.", 'easy-facebook-likebox' ); ?></p>
    <a class="mif_auth_btn mif_auth_btn_st btn waves-effect waves-light modal-trigger"
       href="#mif-authentication-modal">
        <img src="<?php echo ESF_INSTA_PLUGIN_URL ?>/admin/assets/images/insta-logo.png"/><?php esc_html_e( "Connect My Instagram Account", 'easy-facebook-likebox' ); ?>
    </a>
    <div class="row auth-row">
            <div class="efbl_all_pages col s12 " <?php if( !esf_insta_has_connected_account() ){ ?> style="display: none;" <?php } ?>>

			<?php if ( $mif_personal_connected_accounts && esf_insta_instagram_type() == 'personal' ) {

				foreach ( $mif_personal_connected_accounts as $personal_id => $mif_personal_connected_account ) { ?>

                    <ul class="collection with-header">
                        <li class="collection-header">
                            <h5><?php esc_html_e( "Connected Instagram Account", 'easy-facebook-likebox' ); ?></h5>
                            <a href="#mif-remove-at"
                               class="modal-trigger fta-remove-at-btn tooltipped"
                               data-type="personal" data-position="left"
                               data-delay="50"
                               data-tooltip="<?php esc_html_e( "Delete Access Token", 'easy-facebook-likebox' ); ?>"><i
                                        class="material-icons">delete_forever</i></a>
                        </li>
                        <li class="collection-item li-<?php echo $personal_id ?>">

                            <span class="title"><?php echo $mif_personal_connected_account['username'] ?></span>
                            <p><?php esc_html_e( "ID", 'easy-facebook-likebox' ); ?>
                                : <?php echo $personal_id ?> <i
                                        class="material-icons efbl_copy_id tooltipped"
                                        data-position="right"
                                        data-clipboard-text="<?php echo $personal_id ?>"
                                        data-delay="100"
                                        data-tooltip="<?php esc_html_e( "Copy", 'easy-facebook-likebox' ); ?>">content_copy</i>
                            </p>
                        </li>
                    </ul>

				<?php }
			} else {


				if ( isset( $fta_settings['plugins']['facebook']['approved_pages'] ) && ! empty( $fta_settings['plugins']['facebook']['approved_pages'] ) ) { ?>

                    <ul class="collection with-header">
                        <li class="collection-header">
                            <h5><?php esc_html_e( "Connected Instagram Account", 'easy-facebook-likebox' ); ?></h5>
                            <a href="#fta-remove-at"
                               class="modal-trigger fta-remove-at-btn tooltipped"
                               data-position="left" data-delay="50"
                               data-tooltip="<?php esc_html_e( "Delete Access Token", 'easy-facebook-likebox' ); ?>"><i
                                        class="material-icons">delete_forever</i></a>
                        </li>

						<?php foreach ( $fta_settings['plugins']['facebook']['approved_pages'] as $efbl_page ) {

							if ( isset( $efbl_page['instagram_connected_account'] ) ) {

								$fta_insta_connected_account = $efbl_page['instagram_connected_account'];


								if ( isset( $fta_insta_connected_account->ig_id ) && ! empty( $fta_insta_connected_account->ig_id ) ) { ?>

                                    <li class="collection-item avatar fta_insta_connected_account li-<?php echo $fta_insta_connected_account->ig_id; ?>">

                                        <a href="https://www.instagram.com/<?php echo $fta_insta_connected_account->username; ?>"
                                           target="_blank">
                                            <img src="<?php echo $fta_insta_connected_account->profile_picture_url; ?>"
                                                 alt="" class="circle">
                                        </a>
                                        <span class="title"><?php echo $fta_insta_connected_account->name; ?></span>
                                        <p><?php echo $fta_insta_connected_account->username; ?>
                                            <br> <?php esc_html_e( "ID", 'easy-facebook-likebox' ); ?>
                                            : <?php echo $fta_insta_connected_account->id; ?>
                                            <i class="material-icons efbl_copy_id tooltipped"
                                               data-position="right"
                                               data-clipboard-text="<?php echo $fta_insta_connected_account->id; ?>"
                                               data-delay="100"
                                               data-tooltip="<?php esc_html_e( "Copy", 'easy-facebook-likebox' ); ?>">content_copy</i>
                                        </p>
                                    </li>
								<?php }
							}

						} ?>
                    </ul>

				<?php }
			} ?>

        </div>
    </div>

    <p><?php esc_html_e( "Please note: This does not give us permission to manage your Instagram accounts, It simply allows the plugin to retrieve access token and show feeds to your website.", 'easy-facebook-likebox' ); ?></p>
</div>
<div id="mif-authentication-modal" class="mif-authentication-modal modal">
    <div class="modal-content">

        <div class="mif-modal-content">
            <h6><?php esc_html_e( "Are you connecting a Personal or Business Instagram Profile?", 'easy-facebook-likebox' ); ?></h6>

            <div class="mif-auth-btn-holder">

                <input class="with-gap" name="mif_login_type"
                       data-url="<?php echo $personal_auth_url; ?>"
                       value="basic" type="radio" id="mif_basic_type" checked/>
                <label for="mif_basic_type"><?php esc_html_e( "Personal", 'easy-facebook-likebox' ); ?></label>
                <a href="#" class="mif_info_link"><i
                            class="material-icons">info</i></a>
                <div class="mif_auth_info_holder">
                    <p><?php esc_html_e( 'The "Personal" option can display feeds from personal Instagram account. It is limited to display only pictures, videos, username and caption.', 'easy-facebook-likebox' ); ?></p>
                </div>
            </div>
            <div class="mif-auth-btn-holder">
                <input class="with-gap" name="mif_login_type"
                       data-url="<?php echo $auth_url; ?>" value="business"
                       type="radio" id="mif_business_type"/>
                <label for="mif_business_type"><?php esc_html_e( "Business", 'easy-facebook-likebox' ); ?></label>
                <a href="#" class="mif_info_link"><i
                            class="material-icons">info</i></a>
                <div class="mif_auth_info_holder">
                    <p><?php esc_html_e( 'Used for displaying a user feed from a "Business" or "Creator" Instagram account. A Business or Creator account is required for displaying avatar,bio,comments and likes. See this ', 'easy-facebook-likebox' ); ?>
                        <a href="<?php echo esc_url( 'https://easysocialfeed.com/documentation/how-to-connect-instagram-account-with-facebook-page/' ); ?>"
                           target="_blank"> <?php esc_html_e( "support guide", 'easy-facebook-likebox' ); ?> </a><?php esc_html_e( "to convert personal account to business account.", 'easy-facebook-likebox' ); ?>
                    </p>
                </div>
            </div>
            <a href="<?php echo $personal_auth_url; ?>"
               class="waves-effect waves-light btn mif-auth-modal-btn"><?php esc_html_e( "Connect", 'easy-facebook-likebox' ); ?></a>

        </div>
    </div>

</div> 