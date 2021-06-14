<?php

/**
 * Admin View: Tab - Skins
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
global  $efbl_skins ;
$FTA = new Feed_Them_All();
$efbl_page_options = '';
$fta_settings = $FTA->fta_get_settings();

if ( isset( $fta_settings['plugins']['facebook']['approved_pages'] ) ) {
    foreach ( $fta_settings['plugins']['facebook']['approved_pages'] as $efbl_page ) {
        $efbl_page_options .= '<option value="' . $efbl_page['id'] . '" data-icon="' . efbl_get_page_logo( $efbl_page['id'] ) . '">' . $efbl_page['name'] . '</option>';
        $efbl_redirect_class = 'efbl_skin_redirect';
    }
} else {
    $efbl_page_options = '<option value="" disabled selected>' . __( 'No page found, Please connect your Facebook page with plugin first from authentication tab', 'easy-facebook-likebox' ) . '</option>';
    $efbl_redirect_class = 'disabled';
}

$page_id = null;
/* Getting the demo page id. */
if ( isset( $fta_settings['plugins']['facebook']['default_page_id'] ) && !empty($fta_settings['plugins']['facebook']['default_page_id']) ) {
    $page_id = $fta_settings['plugins']['facebook']['default_page_id'];
}
?>
<div id="efbl-skins" class="col s12 efbl_tab_c slideLeft">
    <div class="efbl_skin_head_wrap">
        <h5><?php 
esc_html_e( "Want to customize the layout of post feed?", 'easy-facebook-likebox' );
?></h5>
        <p><?php 
esc_html_e( "Skins allows you to totally customize the look and feel of your post feed in real-time using WordPress customizer. Skin holds all the design settings like feed layout (fullwidth, Grid, etc), show hide elements, page header, and single post colors, margins and a lot of cool settings separately. Questions?", 'easy-facebook-likebox' );
?>
            <a target="_blank"
               href="<?php 
echo  esc_url( 'https://easysocialfeed.com/documentation/how-to-use-skins/' ) ;
?>"><?php 
esc_html_e( "See this support document.", 'easy-facebook-likebox' );
?></a>
        </p>
    </div>
    <a class="btn waves-effect efbl_create_skin waves-light"
       href="javascript:void(0);">
		<?php 
esc_html_e( "Create New Skin", 'easy-facebook-likebox' );
?> <i
                class="material-icons left">add_circle_outline</i></a>

    <!-- New Skin Html Starts Here -->
    <div class="efbl_new_skin col s12">
        <form name="efbl_new_skin_details" id="efbl_new_skin_details">
            <a class="waves-effect waves-light efbl_show_all_skins btn"
               href="javascript:void(0);"><?php 
esc_html_e( "Show All Skins", 'easy-facebook-likebox' );
?>
                <i class="material-icons left">list</i></a>
            <div class="input-field">
                <i class="material-icons prefix">title</i>
                <input id="efbl_skin_title" required name="efbl_skin_title"
                       type="text">
                <label for="efbl_skin_title"
                       class=""><?php 
esc_html_e( "Title (optional)", 'easy-facebook-likebox' );
?></label>
            </div>

            <div class="input-field">
                <i class="material-icons prefix">description</i>
                <textarea id="efbl_skin_description" required
                          name="efbl_skin_description"
                          class="materialize-textarea"></textarea>
                <label for="efbl_skin_description"
                       class=""><?php 
esc_html_e( "Description (optional)", 'easy-facebook-likebox' );
?></label>
            </div>


            <div class="input-field">

                <div class="mdl-textfield mdl-js-textfield efbl_skin_feat_img_wrap">
                    <i class="material-icons prefix">image</i>
                    <input class="mdl-textfield__input" type="text"
                           id="efbl_skin_feat_img" placeholder="(optional)"
                           value="" name="efbl_skin_feat_img">
                    <label class="mdl-textfield__label"
                           for="efbl_skin_feat_img"></label>

                    <i class="btn waves-effect waves-light waves-input-wrapper">
                        <input type="button" class=""
                               value="<?php 
esc_html_e( "Upload Skin Image", 'easy-facebook-likebox' );
?>"
                               id="efbl_skin_feat_img_btn"/>
                        <i class="material-icons left">file_upload</i>
                    </i>
                </div>
            </div>

            <div class="input-field">
                <i class="material-icons prefix">developer_board</i>
                <select id="efbl_selected_layout" class="efbl_selected_layout"
                        name="efbl_selected_layout" required>

                    <option value="thumbnail"><?php 
esc_html_e( "Thumbnail", 'easy-facebook-likebox' );
?></option>
                    <option value="half"><?php 
esc_html_e( "Half Width", 'easy-facebook-likebox' );
?></option>
                    <option value="full"><?php 
esc_html_e( "Full Width", 'easy-facebook-likebox' );
?></option>

					<?php 

if ( efl_fs()->is_plan( 'facebook_premium', true ) or efl_fs()->is_plan( 'combo_premium', true ) ) {
} else {
    ?>
                        <option value="free-grid"><?php 
    esc_html_e( "Grid", 'easy-facebook-likebox' );
    ?></option>
                        <option value="free-masonry"><?php 
    esc_html_e( "Masonry", 'easy-facebook-likebox' );
    ?></option>
                        <option value="free-carousel"><?php 
    esc_html_e( "Carousel", 'easy-facebook-likebox' );
    ?></option>

					<?php 
}

?>

                </select>
                <label for="efbl_selected_layout"
                       class=""><?php 
esc_html_e( "Layout", 'easy-facebook-likebox' );
?></label>
            </div>

            <div class="input-field">
                <i class="material-icons prefix">account_circle</i>
                <select id="efbl_account_selected" class="efbl_selected_account"
                        name="efbl_selected_account" required>
					<?php 
echo  $efbl_page_options ;
?>
                </select>
                <label for="efbl_account_selected"
                       class=""><?php 
esc_html_e( "Please select your Facebook page for preview. It will be for preview only, you can still use this skin for any page.", 'easy-facebook-likebox' );
?></label>
            </div>

            <i class="btn waves-effect create_new_skin_fb_wrap waves-light waves-input-wrapper">
                <input type="submit" class="create_new_skin_fb"
                       name="create_new_skin_fb"
                       value="<?php 
esc_html_e( "Create", 'easy-facebook-likebox' );
?>"/>
                <i class="material-icons right">add_circle_outline</i>
            </i>
        </form>
    </div>
    <!-- New Skin Html Ends Here -->

    <!-- Skin Html Starts Here -->
    <div class="efbl_all_skins row">
		<?php 
/* Getting permalink from ID. */
$page_permalink = get_permalink( $page_id );
if ( isset( $efbl_skins ) ) {
    foreach ( $efbl_skins as $efbl_skin ) {
        $customizer_url = admin_url( 'customize.php' );
        /* If permalink got successfully */
        if ( isset( $page_permalink ) ) {
            /* Include permalinks for making*/
            $customizer_url = add_query_arg( [
                'url'              => urlencode( $page_permalink ),
                'autofocus[panel]' => 'efbl_customize_panel',
                'efbl_skin_id'     => $efbl_skin['ID'],
                'efbl_customize'   => 'yes',
            ], $customizer_url );
        }
        $img_url = get_the_post_thumbnail_url( $efbl_skin['ID'], 'thumbnail' );
        $selected_layout = null;
        if ( isset( $efbl_skin['layout'] ) ) {
            $selected_layout = ucfirst( $efbl_skin['layout'] );
        }
        if ( !$img_url ) {
            $img_url = FTA_PLUGIN_URL . 'admin/assets/images/skin-placeholder.jpg';
        }
        ?>

                <div class="card col efbl_single_skin s3 efbl_skin_<?php 
        echo  $efbl_skin['ID'] ;
        ?>">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator" src="<?php 
        echo  $img_url ;
        ?>">
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4"><?php 
        echo  $efbl_skin['title'] ;
        ?><i
                                    class="material-icons right">more_vert</i></span>
                    </div>

					<?php 
        
        if ( $selected_layout ) {
            ?>

                        <span class="selected_layout"><?php 
            esc_html_e( "Layout: ", 'easy-facebook-likebox' );
            echo  $selected_layout ;
            ?></span>

					<?php 
        }
        
        ?>

                    <div class="efbl_cta_holder">
                        <label>
							<?php 
        esc_html_e( "Please select your page first for preview ignorer to add/edit the skin. (This selection is only for preview, it can be used with any page.)", 'easy-facebook-likebox' );
        ?>

                        </label>
                        <select class="efbl_selected_account_<?php 
        echo  $efbl_skin['ID'] ;
        ?>"
                                required>
							<?php 
        echo  $efbl_page_options ;
        ?>
                        </select>

                        <a class="btn waves-effect  waves-light <?php 
        echo  $efbl_redirect_class ;
        ?>"
                           data-page_id="<?php 
        echo  $page_id ;
        ?>"
                           data-skin_id="<?php 
        echo  $efbl_skin['ID'] ;
        ?>"
                           href="javascript:void(0);"><span><?php 
        esc_html_e( "Edit", 'easy-facebook-likebox' );
        ?></span><i
                                    class="material-icons right">edit</i></a>

                        <a class="btn waves-effect right efbl_skin_delete_confrim waves-light"
                           data-skin_id="<?php 
        echo  $efbl_skin['ID'] ;
        ?>"
                           href="javascript:void(0);"><span><?php 
        esc_html_e( "Delete", 'easy-facebook-likebox' );
        ?></span><i
                                    class="material-icons right">delete_forever</i></a>

                        <a class="btn waves-effect efbl_copy_skin_id waves-light"
                           data-clipboard-text="<?php 
        echo  $efbl_skin['ID'] ;
        ?>"
                           href="javascript:void(0);"><?php 
        esc_html_e( "Copy Skin ID", 'easy-facebook-likebox' );
        ?>
                            <i class="material-icons right">content_copy</i></span>
                        </a>
                    </div>

                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4"><?php 
        echo  $efbl_skin['title'] ;
        ?><i
                                    class="material-icons right">close</i></span>
                        <p><?php 
        echo  $efbl_skin['description'] ;
        ?></p>
                    </div>
                </div>
			<?php 
    }
}

if ( efl_fs()->is_free_plan() || efl_fs()->is_plan( 'instagram_premium', true ) ) {
    ?>

            <div class="card col efbl_single_skin efbl_single_skin_free s3">
                <a class="skin_free_full modal-trigger"
                   href="#efbl-free-grid-upgrade"></a>
                <div class="card-image waves-effect waves-block waves-light">
                    <a class=" modal-trigger" href="#efbl-free-grid-upgrade">
                        <img class=""
                             src="<?php 
    echo  FTA_PLUGIN_URL ;
    ?>admin/assets/images/skin-placeholder.jpg">
                    </a>
                </div>
                <div class="card-content">
                    <a class=" modal-trigger" href="#efbl-free-grid-upgrade">
                        <span class="card-title  grey-text text-darken-4"><?php 
    esc_html_e( "Skin - Grid layout", 'easy-facebook-likebox' );
    ?><i
                                    class="material-icons right">more_vert</i></span>
                    </a>
                </div>
                <span class="selected_layout"><?php 
    esc_html_e( "Layout: Grid", 'easy-facebook-likebox' );
    ?></span>
                <div class="efbl_cta_holder">
                    <label><?php 
    esc_html_e( "Please select your page first for preview ignorer to add/edit the skin. (This selection is only for preview, it can be used with any page.)", 'easy-facebook-likebox' );
    ?></label>
                    <select class="efbl_selected_account" required>
						<?php 
    echo  $efbl_page_options ;
    ?>
                    </select>
                    <a class="btn waves-effect  waves-light efbl_skin_redirect_free  modal-trigger"
                       href="#efbl-free-grid-upgrade"><span><?php 
    esc_html_e( "Edit", 'easy-facebook-likebox' );
    ?></span><i
                                class="material-icons right">edit</i></a>

                    <a class="btn waves-effect right efbl_skin_delete_free waves-light modal-trigger"
                       href="#efbl-free-grid-upgrade"><span><?php 
    esc_html_e( "Delete", 'easy-facebook-likebox' );
    ?></span><i
                                class="material-icons right">delete_forever</i></a>

                    <a class="btn waves-effect efbl_copy_skin_id modal-trigger  waves-light"
                       href="#efbl-free-grid-upgrade"><?php 
    esc_html_e( "Copy Skin ID", 'easy-facebook-likebox' );
    ?>
                        <i class="material-icons right">content_copy</i></span>
                    </a>
                </div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4"><?php 
    esc_html_e( "Layout: Grid", 'easy-facebook-likebox' );
    ?><i
                                class="material-icons right">close</i></span>
                    <p><?php 
    esc_html_e( "This is the Grid demo, skin is included in premium version", 'easy-facebook-likebox' );
    ?></p>
                </div>
            </div>

            <div class="card col efbl_single_skin efbl_single_skin_free s3">
                <a class="skin_free_full modal-trigger"
                   href="#efbl-free-masonry-upgrade"></a>
                <div class="card-image waves-effect waves-block waves-light">
                    <a class=" modal-trigger" href="#efbl-free-masonry-upgrade">
                        <img class=""
                             src="<?php 
    echo  FTA_PLUGIN_URL ;
    ?>admin/assets/images/skin-placeholder.jpg">
                    </a>
                </div>
                <div class="card-content">
                    <a class=" modal-trigger" href="#efbl-free-masonry-upgrade">
                        <span class="card-title  grey-text text-darken-4"><?php 
    esc_html_e( "Skin - Masonry layout", 'easy-facebook-likebox' );
    ?><i
                                    class="material-icons right">more_vert</i></span>
                    </a>
                </div>
                <span class="selected_layout"><?php 
    esc_html_e( "Layout: Masonry", 'easy-facebook-likebox' );
    ?></span>
                <div class="efbl_cta_holder">
                    <label><?php 
    esc_html_e( "Please select your page first for preview ignorer to add/edit the skin. (This selection is only for preview, it can be used with any page.)", 'easy-facebook-likebox' );
    ?></label>
                    <select class="efbl_selected_account" required>
						<?php 
    echo  $efbl_page_options ;
    ?>
                    </select>
                    <a class="btn waves-effect  waves-light efbl_skin_redirect_free  modal-trigger"
                       href="#efbl-free-masonry-upgrade"><span><?php 
    esc_html_e( "Edit", 'easy-facebook-likebox' );
    ?></span><i
                                class="material-icons right">edit</i></a>

                    <a class="btn waves-effect right efbl_skin_delete_free waves-light modal-trigger"
                       href="#efbl-free-masonry-upgrade"><span><?php 
    esc_html_e( "Delete", 'easy-facebook-likebox' );
    ?></span><i
                                class="material-icons right">delete_forever</i></a>

                    <a class="btn waves-effect efbl_copy_skin_id modal-trigger  waves-light"
                       href="#efbl-free-masonry-upgrade"><?php 
    esc_html_e( "Copy Skin ID", 'easy-facebook-likebox' );
    ?>
                        <i class="material-icons right">content_copy</i></span>
                    </a>
                </div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4"><?php 
    esc_html_e( "Layout: Masonry", 'easy-facebook-likebox' );
    ?><i
                                class="material-icons right">close</i></span>
                    <p><?php 
    esc_html_e( "This is the Masonry demo skin included in premium version", 'easy-facebook-likebox' );
    ?></p>
                </div>
            </div>

            <div class="card col efbl_single_skin efbl_single_skin_free s3">
                <a class="skin_free_full modal-trigger"
                   href="#efbl-free-carousel-upgrade"></a>
                <div class="card-image waves-effect waves-block waves-light">
                    <a class=" modal-trigger"
                       href="#efbl-free-carousel-upgrade"> <img class=""
                                                                src="<?php 
    echo  FTA_PLUGIN_URL ;
    ?>admin/assets/images/skin-placeholder.jpg">
                    </a>
                </div>
                <div class="card-content">
                    <a class=" modal-trigger"
                       href="#efbl-free-carousel-upgrade"> <span
                                class="card-title  grey-text text-darken-4"><?php 
    esc_html_e( "Skin - Carousel layout", 'easy-facebook-likebox' );
    ?><i
                                    class="material-icons right">more_vert</i></span>
                    </a>
                </div>
                <span class="selected_layout"><?php 
    esc_html_e( "Layout: Carousel", 'easy-facebook-likebox' );
    ?></span>
                <div class="efbl_cta_holder">
                    <label><?php 
    esc_html_e( "Please select your page first for preview ignorer to add/edit the skin. (This selection is only for preview, it can be used with any page.)", 'easy-facebook-likebox' );
    ?></label>
                    <select class="efbl_selected_account" required>
						<?php 
    echo  $efbl_page_options ;
    ?>
                    </select>
                    <a class="btn waves-effect  waves-light efbl_skin_redirect_free  modal-trigger"
                       href="#efbl-free-carousel-upgrade"><span><?php 
    esc_html_e( "Edit", 'easy-facebook-likebox' );
    ?></span><i
                                class="material-icons right">edit</i></a>

                    <a class="btn waves-effect right efbl_skin_delete_free waves-light modal-trigger"
                       href="#efbl-free-carousel-upgrade"><span><?php 
    esc_html_e( "Delete", 'easy-facebook-likebox' );
    ?></span><i
                                class="material-icons right">delete_forever</i></a>

                    <a class="btn waves-effect efbl_copy_skin_id modal-trigger  waves-light"
                       href="#efbl-free-carousel-upgrade"><?php 
    esc_html_e( "Copy Skin ID", 'easy-facebook-likebox' );
    ?>
                        <i class="material-icons right">content_copy</i></span>
                    </a>
                </div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4"><?php 
    esc_html_e( "Layout: Carousel", 'easy-facebook-likebox' );
    ?><i
                                class="material-icons right">close</i></span>
                    <p><?php 
    esc_html_e( "This is the carousel demo skin included in premium version", 'easy-facebook-likebox' );
    ?></p>
                </div>
            </div>


		<?php 
}

?>
    </div>
    <!-- Skin Html Ends Here -->


</div>