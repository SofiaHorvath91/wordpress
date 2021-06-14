<?php
/**
 * Admin View: Tab - Moderate
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$FTA = new Feed_Them_All();
$fta_settings = $FTA->fta_get_settings();
?>
<div id="efbl-moderate" class="col s12 efbl_tab_c slideLeft">
    <div class="row">
        <div class="efbl_tabs_holder">
                <div id="efbl-moderate-wrap" class="tab-content efbl_tab_c_holder">
                    <h5><?php esc_html_e('Want to show or hide only specific posts?'); ?></h5>
                    <p><?php esc_html_e('Select posts to hide or show from feed.'); ?>.</p>

                    <div class="input-field col s12 efbl_fields">
                        <select id="efbl_moderate_feed_type"
                                class="icons efbl_moderate_feed_type" <?php do_action('esf_fb_feed_type_attr'); ?>>
                            <option value="" ><?php esc_html_e( "Select one", 'easy-facebook-likebox' ); ?></option>
                            <option value="page" ><?php esc_html_e( "Page", 'easy-facebook-likebox' ); ?></option>
                            <option value="group" ><?php esc_html_e( "Group", 'easy-facebook-likebox' ); ?></option>
                        </select>
                        <label><?php esc_html_e( "Feed type", 'easy-facebook-likebox' ); ?></label>
                    </div>
                    <div class="input-field col s12 efbl_fields efbl-moderate-page-id-wrap">
                        <select id="efbl_moderate_page_id"
                                class="icons efbl_moderate_page_id" <?php do_action('esf_fb_page_attr'); ?>>
			                <?php
                            $type = "page";
                            if ( $fta_settings['plugins']['facebook']['approved_pages'] ) {
				                $i = 0;
				                foreach ( $fta_settings['plugins']['facebook']['approved_pages'] as $efbl_page ) { $i++;
					                if ( $efbl_page['id'] ) {

							             $username = $efbl_page['id'];

						                if( $i == 1 ){
							                $first_page_id = $username;
						                }
						                ?>
                                        <option value="<?php echo $username; ?>"
                                                data-icon="<?php echo efbl_get_page_logo( $efbl_page['id'] ) ?>" <?php if( $i == 1 ){ ?> selected <?php } ?>><?php echo $efbl_page['name']; ?></option>

					                <?php }
				                }

			                } else { ?>

                                <option value="" disabled
                                        selected><?php esc_html_e( "No page(s) found, Please connect your Facebook page with plugin first from authenticate tab", 'easy-facebook-likebox' ); ?></option>

			                <?php }
			                ?>
                        </select>
                        <label><?php esc_html_e( "Select Page", 'easy-facebook-likebox' ); ?></label>
                    </div>

	                <?php if ( isset( $fta_settings['plugins']['facebook']['approved_groups'] ) && ! empty( $fta_settings['plugins']['facebook']['approved_groups'] ) ) { ?>
                        <div class="input-field col s12 efbl_fields efbl-group-id-wrap">
                            <select id="efbl_moderate_group_id"
                                    class="icons efbl_moderate_group_id" <?php do_action('esf_fb_group_attr'); ?>>
				                <?php if ( $fta_settings['plugins']['facebook']['approved_groups'] ) {
					                $i = 0;
					                foreach ( $fta_settings['plugins']['facebook']['approved_groups'] as $efbl_group ) { $i++;
						                if ( $efbl_group->id ) {
							                if( $i == 1 ){
								                $first_page_id = $efbl_group->id;
								                $type = "group";
							                }
							                ?>
                                            <option value="<?php echo $efbl_group->id; ?>"
                                                    data-icon="<?php echo efbl_get_page_logo( $efbl_group->id ) ?>" <?php if( $i == 1 ){ ?> selected <?php } ?>><?php echo $efbl_group->name; ?></option>

						                <?php }
					                }

				                } else { ?>

                                    <option value="" disabled
                                            selected><?php esc_html_e( "No group(s) found, Please connect your Facebook group with plugin first from authenticate tab", 'easy-facebook-likebox' ); ?></option>

				                <?php }
				                ?>
                            </select>
                            <label><?php esc_html_e( "Select Group", 'easy-facebook-likebox' ); ?></label>
                        </div>
	                <?php } ?>

                    <div class="efbl-moderate-type-wrap">
                        <div class="efbl-moderate-type">
                            <input name="efbl_moderate_type"
                                   type="radio" class="with-gap"
                                   value="hide" checked id="efbl_hide"/>
                            <label for="efbl_hide"><?php esc_html_e( "Hide the selected posts", 'easy-facebook-likebox' ); ?></label>
                        </div>
                        <div class="efbl-moderate-type">
                            <input name="efbl_moderate_type"
                                   type="radio" class="with-gap"
                                   value="show"  id="efbl_show"/>
                            <label for="efbl_show"><?php esc_html_e( "Only show the selected posts", 'easy-facebook-likebox' ); ?></label>
                        </div>
                    </div>
                    <button class="btn waves-effect efbl-get-moderate-feed waves-light"><?php esc_html_e( "Get feed", 'easy-facebook-likebox' ); ?></button>
                    <div class="efbl-moderate-visual-wrap <?php if ( efl_fs()->is_free_plan() || efl_fs()->is_plan( 'instagram_premium', true ) ){ ?> efbl-moderate-free-view <?php } ?>">
                        <?php
                        if ( efl_fs()->is_free_plan() || efl_fs()->is_plan( 'instagram_premium', true ) ) {
	                        global $efbl_skins;
	                        $skin_id = '';
	                        if ( isset( $efbl_skins ) ) {
		                        foreach ( $efbl_skins as $skin ) {
			                        if ( $skin['layout'] == 'grid' ) {
				                        $skin_id = $skin['ID'];
			                        }
		                        }
	                        }

	                        $shortcode = '[efb_feed fanpage_id="' . $first_page_id . '" type="' . $type . '" test_mode="false" is_moderate="true" skin_id="' . $skin_id . '" words_limit="25" post_limit="30" links_new_tab="1"]';
	                        echo do_shortcode( $shortcode );
                        }
                        ?>
                    </div>

                    <?php if ( efl_fs()->is_free_plan() || efl_fs()->is_plan( 'instagram_premium', true ) ){ ?>
                        <div class="efbl-moderate-pro">
                            <a href="<?php echo efl_fs()->get_upgrade_url() ?>&trial=true"
                               class="trial-btn"><?php esc_html_e( "Free 7-day PRO trial", 'easy-facebook-likebox' ); ?>
                            </a>
                            <a href="<?php echo efl_fs()->get_upgrade_url() ?>"
                               class="waves-effect waves-light btn pro-btn"><i
                                        class="material-icons right">lock_open</i><?php esc_html_e( "Upgrade to pro", 'easy-facebook-likebox' ); ?>
                            </a>
                            <p><?php esc_html_e( 'Upgrade today and get a 10% discount! On the checkout click on "Have a promotional code?" and enter', 'easy-facebook-likebox' ); ?> <code>ESPF10</code></p>
                        </div>
                    <?php } ?>

                </div>

            </div>
    </div>
</div>