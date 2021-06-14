<?php

/**
 * Admin View: Tab - Skins
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$FTA = new Feed_Them_All();
global  $mif_skins ;
$fta_settings = $FTA->fta_get_settings();

if ( isset( $fta_settings['plugins']['instagram']['default_page_id'] ) ) {
    $page_id = $fta_settings['plugins']['instagram']['default_page_id'];
} else {
    $page_id = '';
}


if ( isset( $fta_settings['plugins']['instagram']['instagram_connected_account'] ) ) {
    $mif_personal_connected_accounts = $fta_settings['plugins']['instagram']['instagram_connected_account'];
} else {
    $mif_personal_connected_accounts = [];
}

$esf_insta_business_accounts = esf_insta_business_accounts();
?>
<div id="mif-skins" class="col s12 mif_tab_c slideLeft">
    <div class="row">

        <div class="mif_skin_head_wrap">
            <h5><?php 
esc_html_e( "Want to customize the layout of Instagram feed?", 'easy-facebook-likebox' );
?></h5>
            <p><?php 
esc_html_e( "Skins allows you to totally customize the look and feel of your feed in real-time using WordPress customizer. Skin holds all the design settings like feed layout (fullwidth, Grid, etc), show hide elements, page header, and single post colors, margins and a lot of cool settings separately. Questions?", 'easy-facebook-likebox' );
?>
                <a target="_blank"
                   href="<?php 
echo  esc_url( 'https://easysocialfeed.com/documentation/how-to-use-skins/' ) ;
?>"><?php 
esc_html_e( "See this support document", 'easy-facebook-likebox' );
?></a>
            </p>
        </div>

        <a class="btn waves-effect mif_create_skin waves-light"
           href="javascript:void(0);"><?php 
esc_html_e( "Create New Skin", 'easy-facebook-likebox' );
?>
            <i class="material-icons left">add_circle_outline</i></a>

        <!-- New Skin Html Starts Here -->
        <div class="mif_new_skin col s12">
            <form name="mif_new_skin_details" id="mif_new_skin_details">
                <a class="waves-effect waves-light mif_show_all_skins btn"
                   href="javascript:void(0);"><?php 
esc_html_e( "Show All Skins", 'easy-facebook-likebox' );
?>
                    <i class="material-icons left">list</i></a>
                <div class="input-field">
                    <i class="material-icons prefix">title</i>
                    <input id="mif_skin_title" required name="mif_skin_title"
                           type="text">
                    <label for="mif_skin_title"
                           class=""><?php 
esc_html_e( "Title (optional)", 'easy-facebook-likebox' );
?></label>
                </div>

                <div class="input-field">
                    <i class="material-icons prefix">description</i>
                    <textarea id="mif_skin_description" required
                              name="mif_skin_description"
                              class="materialize-textarea"></textarea>
                    <label for="mif_skin_description"
                           class=""><?php 
esc_html_e( "Description (optional)", 'easy-facebook-likebox' );
?></label>
                </div>


                <div class="input-field">

                    <div class="mdl-textfield mdl-js-textfield mif_skin_feat_img_wrap">
                        <i class="material-icons prefix">image</i>
                        <input class="mdl-textfield__input" type="text"
                               id="mif_skin_feat_img"
                               placeholder="<?php 
esc_html_e( "Skin Image (optional)", 'easy-facebook-likebox' );
?>"
                               value="" name="mif_skin_feat_img">
                        <label class="mdl-textfield__label"
                               for="mif_skin_feat_img"></label>
                        <i class="btn waves-effect waves-light waves-input-wrapper">
                            <input type="button" class=""
                                   value="<?php 
esc_html_e( "Upload Skin Image", 'easy-facebook-likebox' );
?>"
                                   id="mif_skin_feat_img_btn"/>
                            <i class="material-icons left">file_upload</i>
                        </i>
                    </div>
                </div>

                <div class="input-field">
                    <i class="material-icons prefix">developer_board</i>
                    <select id="mif_selected_layout" class="mif_selected_layout"
                            name="mif_selected_layout" required>

                        <option value="grid"><?php 
esc_html_e( "Grid", 'easy-facebook-likebox' );
?></option>


						<?php 
?>

                            <option value="free-masonary"><?php 
esc_html_e( "Masonry", 'easy-facebook-likebox' );
?></option>
                            <option value="free-carousel"><?php 
esc_html_e( "Carousel", 'easy-facebook-likebox' );
?></option>
                            <option value="free-half_width"><?php 
esc_html_e( "Half Width", 'easy-facebook-likebox' );
?></option>
                            <option value="free-full_width"><?php 
esc_html_e( "Full Width", 'easy-facebook-likebox' );
?></option>
						<?php 
?>

                    </select>
                    <label for="mif_selected_layout"
                           class=""><?php 
esc_html_e( "Layout", 'easy-facebook-likebox' );
?></label>
                </div>

                <div class="input-field">
                    <i class="material-icons prefix">account_circle</i>
                    <select id="mif_skin_selected" class="mif_selected_account"
                            name="mif_selected_account" required>

						<?php 
$esf_insta_edit_disable = 'disabled';

if ( esf_insta_instagram_type() == 'personal' && !empty($mif_personal_connected_accounts) ) {
    $esf_insta_edit_disable = '';
    foreach ( $mif_personal_connected_accounts as $personal_id => $mif_personal_connected_account ) {
        ?>

                                <option value="<?php 
        echo  $personal_id ;
        ?>"><?php 
        echo  $mif_personal_connected_account['username'] ;
        ?></option>

							<?php 
    }
} else {
    $esf_insta_business_accounts = esf_insta_business_accounts();
    
    if ( $esf_insta_business_accounts ) {
        $esf_insta_edit_disable = '';
        foreach ( $esf_insta_business_accounts as $mif_insta_single_account ) {
            ?>

                                    <option value="<?php 
            echo  $mif_insta_single_account->id ;
            ?>"
                                            data-icon="<?php 
            echo  $mif_insta_single_account->profile_picture_url ;
            ?>"><?php 
            echo  $mif_insta_single_account->username ;
            ?></option>

								<?php 
        }
    } else {
        ?>

                                <option value="" disabled
                                        selected><?php 
        esc_html_e( "No accounts found, Please connect your Instagram account with plugin first", 'easy-facebook-likebox' );
        ?></option>

							<?php 
    }
    
    ?>


						<?php 
}

?>
                    </select>
                    <label for="mif_skin_selected"
                           class=""><?php 
esc_html_e( "Please select your account to see in preview", 'easy-facebook-likebox' );
?></label>
                </div>
                <i class="btn waves-effect create_new_skin_fb_wrap waves-light waves-input-wrapper">
                    <input type="submit" class="create_new_skin_sub"
                           name="create_new_skin_sub"
                           value="<?php 
esc_html_e( "Create", 'easy-facebook-likebox' );
?>"/>
                    <i class="material-icons right">add_circle_outline</i>
                </i>
            </form>
        </div>

        <div class="mif_all_skins col s12">

			<?php 

if ( $mif_skins ) {
    foreach ( $mif_skins as $mif_skin ) {
        $customizer_url = admin_url( 'customize.php' );
        if ( isset( $page_permalink ) ) {
            $customizer_url = add_query_arg( [
                'url'              => urlencode( $page_permalink ),
                'autofocus[panel]' => 'mif_skins_panel',
                'mif_skin_id'      => $mif_skin['ID'],
                'mif_customize'    => 'yes',
            ], $customizer_url );
        }
        $selected_layout = null;
        if ( isset( $mif_skin['design']['layout_option'] ) ) {
            $selected_layout = ucfirst( str_replace( "_", " ", $mif_skin['design']['layout_option'] ) );
        }
        ?>

                    <div class="card col mif_single_skin s3 mif_skin_<?php 
        echo  $mif_skin['ID'] ;
        ?>">
                        <div class="card-image waves-effect waves-block waves-light">

							<?php 
        
        if ( get_the_post_thumbnail_url( $mif_skin['ID'], 'thumbnail' ) ) {
            ?>

                                <img class="activator"
                                     src="<?php 
            echo  esc_url( get_the_post_thumbnail_url( $mif_skin['ID'], 'thumbnail' ) ) ;
            ?>">

							<?php 
        } else {
            ?>

                                <img class="activator"
                                     src="<?php 
            echo  FTA_PLUGIN_URL ;
            ?>admin/assets/images/skin-placeholder.jpg">

							<?php 
        }
        
        ?>

                        </div>
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4"><?php 
        echo  $mif_skin['title'] ;
        ?><i
                                        class="material-icons right">more_vert</i></span>

                        </div>

						<?php 
        
        if ( $selected_layout ) {
            ?>

                            <span class="selected_layout"><?php 
            esc_html_e( "Layout", 'easy-facebook-likebox' );
            ?>:<?php 
            echo  $selected_layout ;
            ?></span>

						<?php 
        }
        
        ?>


                        <div class="mif_cta_holder">
                            <label><?php 
        esc_html_e( "Please select your account first for preview ignorer to add/edit the skin. (This selection is only for preview, it can be used with any account)", 'easy-facebook-likebox' );
        ?></label>
                            <select class="mif_selected_account_<?php 
        echo  $mif_skin['ID'] ;
        ?>"
                                    required>
								<?php 
        
        if ( esf_insta_instagram_type() == 'personal' && !empty($mif_personal_connected_accounts) ) {
            foreach ( $mif_personal_connected_accounts as $personal_id => $mif_personal_connected_account ) {
                ?>

                                        <option value="<?php 
                echo  $personal_id ;
                ?>"><?php 
                echo  $mif_personal_connected_account['username'] ;
                ?></option>

									<?php 
            }
        } else {
            
            if ( $esf_insta_business_accounts ) {
                foreach ( $esf_insta_business_accounts as $mif_insta_single_account ) {
                    ?>

                                            <option value="<?php 
                    echo  $mif_insta_single_account->id ;
                    ?>"
                                                    data-icon="<?php 
                    echo  $mif_insta_single_account->profile_picture_url ;
                    ?>"><?php 
                    echo  $mif_insta_single_account->username ;
                    ?></option>

										<?php 
                }
            } else {
                ?>

                                        <option value="" disabled
                                                selected><?php 
                esc_html_e( "No accounts found, Please connect your Instagram account with plugin first", 'easy-facebook-likebox' );
                ?></option>

									<?php 
            }
            
            ?>


								<?php 
        }
        
        ?>
                            </select>

                            <a class="btn waves-effect  esf_insta_skin_redirect <?php 
        echo  sanitize_text_field( $esf_insta_edit_disable ) ;
        ?> waves-light"
                               data-page_id="<?php 
        echo  $page_id ;
        ?>"
                               data-skin_id="<?php 
        echo  $mif_skin['ID'] ;
        ?>"
                               href="javascript:void(0);"><span><?php 
        esc_html_e( "Edit", 'easy-facebook-likebox' );
        ?></span><i
                                        class="material-icons right">edit</i></a>

                            <a class="btn waves-effect right esf_insta_skin_delete_confrim waves-light"
                               data-skin_id="<?php 
        echo  $mif_skin['ID'] ;
        ?>"
                               href="javascript:void(0);"><span><?php 
        esc_html_e( "Delete", 'easy-facebook-likebox' );
        ?></span><i
                                        class="material-icons right">delete_forever</i></a>

                            <a class="btn waves-effect esf_insta_copy_skin_id waves-light"
                               data-clipboard-text="<?php 
        echo  $mif_skin['ID'] ;
        ?>"
                               href="javascript:void(0);"><?php 
        esc_html_e( "Copy Skin ID", 'easy-facebook-likebox' );
        ?>
                                <i class="material-icons right">content_copy</i></span>
                            </a>
                        </div>

                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4"><?php 
        echo  $mif_skin['title'] ;
        ?><i
                                        class="material-icons right">close</i></span>
                            <p><?php 
        echo  $mif_skin['description'] ;
        ?></p>
                        </div>
                    </div>
				<?php 
    }
} else {
    ?>

            <blockquote
                    class="error"><?php 
    esc_html_e( "Whoops! No skin found. Create new skin from button above to totally customize your feed in real-time.", 'easy-facebook-likebox' );
    ?>
				<?php 
}


if ( efl_fs()->is_plan( 'instagram_premium', true ) or efl_fs()->is_plan( 'combo_premium', true ) ) {
} else {
    ?>

                    <div class="card col mif_single_skin mif_single_skin_free s3">
                        <a class="skin_free_full modal-trigger"
                           href="#mif-free-masonry-upgrade"></a>
                        <div class="card-image waves-effect waves-block waves-light">
                            <a class=" modal-trigger"
                               href="#mif-free-masonry-upgrade"> <img class=""
                                                                      src="<?php 
    echo  FTA_PLUGIN_URL ;
    ?>admin/assets/images/skin-placeholder.jpg">
                            </a>
                        </div>
                        <div class="card-content">
                            <a class=" modal-trigger"
                               href="#mif-free-masonry-upgrade"> <span
                                        class="card-title  grey-text text-darken-4"><?php 
    esc_html_e( "Skin - Masonry layout", 'easy-facebook-likebox' );
    ?><i
                                            class="material-icons right">more_vert</i></span>
                            </a>
                        </div>
                        <span class="selected_layout"><?php 
    esc_html_e( "Layout: Masonry", 'easy-facebook-likebox' );
    ?></span>
                        <div class="mif_cta_holder">
                            <label><?php 
    esc_html_e( "Please select your page first for preview ignorer to add/edit the skin. (This selection is only for preview, it can be used with any page)", 'easy-facebook-likebox' );
    ?></label>
                            <select class="mif_selected_account" required>
								<?php 
    
    if ( esf_insta_instagram_type() == 'personal' && !empty($mif_personal_connected_accounts) ) {
        foreach ( $mif_personal_connected_accounts as $personal_id => $mif_personal_connected_account ) {
            ?>

                                        <option value="<?php 
            echo  $personal_id ;
            ?>"><?php 
            echo  $mif_personal_connected_account['username'] ;
            ?></option>

									<?php 
        }
    } else {
        
        if ( $esf_insta_business_accounts ) {
            foreach ( $esf_insta_business_accounts as $mif_insta_single_account ) {
                ?>

                                            <option value="<?php 
                echo  $mif_insta_single_account->id ;
                ?>"
                                                    data-icon="<?php 
                echo  $mif_insta_single_account->profile_picture_url ;
                ?>"><?php 
                echo  $mif_insta_single_account->username ;
                ?></option>

										<?php 
            }
        } else {
            ?>

                                        <option value="" disabled
                                                selected><?php 
            esc_html_e( "No accounts found, Please connect your Instagram account with plugin first", 'easy-facebook-likebox' );
            ?></option>

									<?php 
        }
        
        ?>


								<?php 
    }
    
    ?>
                            </select>
                            <a class="btn waves-effect  waves-light esf_insta_skin_redirect_free  modal-trigger"
                               href="#mif-free-masonry-upgrade"><span><?php 
    esc_html_e( "Edit", 'easy-facebook-likebox' );
    ?></span><i
                                        class="material-icons right">edit</i></a>

                            <a class="btn waves-effect right esf_insta_skin_delete_free waves-light modal-trigger"
                               href="#mif-free-masonry-upgrade"><span><?php 
    esc_html_e( "Delete", 'easy-facebook-likebox' );
    ?></span><i
                                        class="material-icons right">delete_forever</i></a>

                            <a class="btn waves-effect esf_insta_copy_skin_id modal-trigger  waves-light"
                               href="#mif-free-masonry-upgrade"><?php 
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

                    <div class="card col mif_single_skin mif_single_skin_free s3">
                        <a class="skin_free_full modal-trigger"
                           href="#mif-free-carousel-upgrade"></a>
                        <div class="card-image waves-effect waves-block waves-light">
                            <a class=" modal-trigger"
                               href="#mif-free-carousel-upgrade"> <img class=""
                                                                       src="<?php 
    echo  FTA_PLUGIN_URL ;
    ?>admin/assets/images/skin-placeholder.jpg">
                            </a>
                        </div>
                        <div class="card-content">
                            <a class=" modal-trigger"
                               href="#mif-free-carousel-upgrade"> <span
                                        class="card-title  grey-text text-darken-4"><?php 
    esc_html_e( "Skin - Carousel layout", 'easy-facebook-likebox' );
    ?><i
                                            class="material-icons right">more_vert</i></span>
                            </a>
                        </div>
                        <span class="selected_layout"><?php 
    esc_html_e( "Layout: Carousel", 'easy-facebook-likebox' );
    ?></span>
                        <div class="mif_cta_holder">
                            <label><?php 
    esc_html_e( "Please select your page first for preview ignorer to add/edit the skin. (This selection is only for preview, it can be used with any page)", 'easy-facebook-likebox' );
    ?></label>
                            <select class="mif_selected_account" required>
								<?php 
    
    if ( esf_insta_instagram_type() == 'personal' && !empty($mif_personal_connected_accounts) ) {
        foreach ( $mif_personal_connected_accounts as $personal_id => $mif_personal_connected_account ) {
            ?>

                                        <option value="<?php 
            echo  $personal_id ;
            ?>"><?php 
            echo  $mif_personal_connected_account['username'] ;
            ?></option>

									<?php 
        }
    } else {
        
        if ( $esf_insta_business_accounts ) {
            foreach ( $esf_insta_business_accounts as $mif_insta_single_account ) {
                ?>

                                            <option value="<?php 
                echo  $mif_insta_single_account->id ;
                ?>"
                                                    data-icon="<?php 
                echo  $mif_insta_single_account->profile_picture_url ;
                ?>"><?php 
                echo  $mif_insta_single_account->username ;
                ?></option>

										<?php 
            }
        } else {
            ?>

                                        <option value="" disabled
                                                selected><?php 
            esc_html_e( "No accounts found, Please connect your Instagram account with plugin first", 'easy-facebook-likebox' );
            ?></option>

									<?php 
        }
        
        ?>


								<?php 
    }
    
    ?>
                            </select>
                            <a class="btn waves-effect  waves-light esf_insta_skin_redirect_free modal-trigger"
                               href="#mif-free-carousel-upgrade"><span><?php 
    esc_html_e( "Edit", 'easy-facebook-likebox' );
    ?></span><i
                                        class="material-icons right">edit</i></a>

                            <a class="btn waves-effect right esf_insta_skin_delete_free waves-light modal-trigger"
                               href="#mif-free-carousel-upgrade"><span><?php 
    esc_html_e( "Delete", 'easy-facebook-likebox' );
    ?></span><i
                                        class="material-icons right">delete_forever</i></a>

                            <a class="btn waves-effect esf_insta_copy_skin_id modal-trigger  waves-light"
                               href="#mif-free-carousel-upgrade"><?php 
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
    esc_html_e( "This is the Carousel demo skin included in premium version", 'easy-facebook-likebox' );
    ?></p>
                        </div>
                    </div>

                    <div class="card col mif_single_skin mif_single_skin_free s3">
                        <a class="skin_free_full modal-trigger"
                           href="#mif-free-half_width-upgrade"></a>
                        <div class="card-image waves-effect waves-block waves-light">
                            <a class=" modal-trigger"
                               href="#mif-free-half_width-upgrade"> <img
                                        class=""
                                        src="<?php 
    echo  FTA_PLUGIN_URL ;
    ?>admin/assets/images/skin-placeholder.jpg">
                            </a>
                        </div>
                        <div class="card-content">
                            <a class=" modal-trigger"
                               href="#mif-free-half_width-upgrade"> <span
                                        class="card-title  grey-text text-darken-4"><?php 
    esc_html_e( "Skin - Half Width layout", 'easy-facebook-likebox' );
    ?><i
                                            class="material-icons right">more_vert</i></span>
                            </a>
                        </div>
                        <span class="selected_layout"><?php 
    esc_html_e( "Layout: Half Width", 'easy-facebook-likebox' );
    ?></span>
                        <div class="mif_cta_holder">
                            <label><?php 
    esc_html_e( "Please select your page first for preview ignorer to add/edit the skin. (This selection is only for preview, it can be used with any page)", 'easy-facebook-likebox' );
    ?></label>
                            <select class="mif_selected_account" required>
								<?php 
    
    if ( esf_insta_instagram_type() == 'personal' && !empty($mif_personal_connected_accounts) ) {
        foreach ( $mif_personal_connected_accounts as $personal_id => $mif_personal_connected_account ) {
            ?>

                                        <option value="<?php 
            echo  $personal_id ;
            ?>"><?php 
            echo  $mif_personal_connected_account['username'] ;
            ?></option>

									<?php 
        }
    } else {
        
        if ( $esf_insta_business_accounts ) {
            foreach ( $esf_insta_business_accounts as $mif_insta_single_account ) {
                ?>

                                            <option value="<?php 
                echo  $mif_insta_single_account->id ;
                ?>"
                                                    data-icon="<?php 
                echo  $mif_insta_single_account->profile_picture_url ;
                ?>"><?php 
                echo  $mif_insta_single_account->username ;
                ?></option>

										<?php 
            }
        } else {
            ?>

                                        <option value="" disabled
                                                selected><?php 
            esc_html_e( "No accounts found, Please connect your Instagram account with plugin first", 'easy-facebook-likebox' );
            ?></option>

									<?php 
        }
        
        ?>


								<?php 
    }
    
    ?>
                            </select>
                            <a class="btn waves-effect  waves-light esf_insta_skin_redirect_free modal-trigger"
                               href="#mif-free-half_width-upgrade"><span><?php 
    esc_html_e( "Edit", 'easy-facebook-likebox' );
    ?></span><i
                                        class="material-icons right">edit</i></a>

                            <a class="btn waves-effect right esf_insta_skin_delete_free waves-light modal-trigger"
                               href="#mif-free-half_width-upgrade"><span><?php 
    esc_html_e( "Delete", 'easy-facebook-likebox' );
    ?></span><i
                                        class="material-icons right">delete_forever</i></a>

                            <a class="btn waves-effect esf_insta_copy_skin_id modal-trigger  waves-light"
                               href="#mif-free-half_width-upgrade"><?php 
    esc_html_e( "Copy Skin ID", 'easy-facebook-likebox' );
    ?>
                                <i class="material-icons right">content_copy</i></span>
                            </a>
                        </div>

                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4"><?php 
    esc_html_e( "Layout: Half Width", 'easy-facebook-likebox' );
    ?><i
                                        class="material-icons right">close</i></span>
                            <p><?php 
    esc_html_e( "This is the Half Width demo skin included in premium version", 'easy-facebook-likebox' );
    ?></p>
                        </div>
                    </div>

                    <div class="card col mif_single_skin mif_single_skin_free s3">
                        <a class="skin_free_full modal-trigger"
                           href="#mif-free-full_width-upgrade"></a>
                        <div class="card-image waves-effect waves-block waves-light">
                            <a class=" modal-trigger"
                               href="#mif-free-full_width-upgrade"> <img
                                        class=""
                                        src="<?php 
    echo  FTA_PLUGIN_URL ;
    ?>admin/assets/images/skin-placeholder.jpg">
                            </a>
                        </div>
                        <div class="card-content">
                            <a class=" modal-trigger"
                               href="#mif-free-full_width-upgrade"> <span
                                        class="card-title  grey-text text-darken-4"><?php 
    esc_html_e( "Skin - Full Width layout", 'easy-facebook-likebox' );
    ?><i
                                            class="material-icons right">more_vert</i></span>
                            </a>
                        </div>
                        <span class="selected_layout"><?php 
    esc_html_e( "Layout: full_width", 'easy-facebook-likebox' );
    ?></span>
                        <div class="mif_cta_holder">
                            <label><?php 
    esc_html_e( "Please select your page first for preview ignorer to add/edit the skin. (This selection is only for preview, it can be used with any page)", 'easy-facebook-likebox' );
    ?></label>
                            <select class="mif_selected_account" required>
								<?php 
    
    if ( esf_insta_instagram_type() == 'personal' && !empty($mif_personal_connected_accounts) ) {
        foreach ( $mif_personal_connected_accounts as $personal_id => $mif_personal_connected_account ) {
            ?>

                                        <option value="<?php 
            echo  $personal_id ;
            ?>"><?php 
            echo  $mif_personal_connected_account['username'] ;
            ?></option>

									<?php 
        }
    } else {
        
        if ( $esf_insta_business_accounts ) {
            foreach ( $esf_insta_business_accounts as $mif_insta_single_account ) {
                ?>

                                            <option value="<?php 
                echo  $mif_insta_single_account->id ;
                ?>"
                                                    data-icon="<?php 
                echo  $mif_insta_single_account->profile_picture_url ;
                ?>"><?php 
                echo  $mif_insta_single_account->username ;
                ?></option>

										<?php 
            }
        } else {
            ?>

                                        <option value="" disabled
                                                selected><?php 
            esc_html_e( "No accounts found, Please connect your Instagram account with plugin first", 'easy-facebook-likebox' );
            ?></option>

									<?php 
        }
        
        ?>


								<?php 
    }
    
    ?>
                            </select>
                            <a class="btn waves-effect  waves-light esf_insta_skin_redirect_free modal-trigger"
                               href="#mif-free-full_width-upgrade"><span><?php 
    esc_html_e( "Edit", 'easy-facebook-likebox' );
    ?></span><i
                                        class="material-icons right">edit</i></a>

                            <a class="btn waves-effect right esf_insta_skin_delete_free waves-light modal-trigger"
                               href="#mif-free-full_width-upgrade"><span><?php 
    esc_html_e( "Delete", 'easy-facebook-likebox' );
    ?></span><i
                                        class="material-icons right">delete_forever</i></a>

                            <a class="btn waves-effect esf_insta_copy_skin_id modal-trigger  waves-light"
                               href="#mif-free-full_width-upgrade"><?php 
    esc_html_e( "Copy Skin ID", 'easy-facebook-likebox' );
    ?>
                                <i class="material-icons right">content_copy</i></span>
                            </a>
                        </div>

                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4"><?php 
    esc_html_e( "Layout: Full width", 'easy-facebook-likebox' );
    ?><i
                                        class="material-icons right">close</i></span>
                            <p><?php 
    esc_html_e( "This is the full Width demo skin included in premium version", 'easy-facebook-likebox' );
    ?></p>
                        </div>
                    </div>

				<?php 
}

?>

        </div>

    </div>
</div>

<div id="mif-remove-skin" class="modal mif-remove-skin mif-confirm-modal">
    <div class="modal-content">
        <span class="mif-close-modal modal-close"><i
                    class="material-icons dp48">close</i></span>
        <div class="mif-modal-content"><span class="mif-lock-icon"><i
                        class="material-icons dp48">error_outline</i> </span>
            <h5><?php 
esc_html_e( "Are you sure?", 'easy-facebook-likebox' );
?></h5>
            <p><?php 
esc_html_e( "Do you really want to delete the skin? It will delete all the settings values of the skin.", 'easy-facebook-likebox' );
?></p>
            <a class="waves-effect waves-light btn modal-close"
               href="javascript:void(0)"><?php 
esc_html_e( "Cancel", 'easy-facebook-likebox' );
?></a>
            <a class="waves-effect waves-light btn esf_insta_skin_delete modal-close"
               href="javascript:void(0)"><?php 
esc_html_e( "Delete", 'easy-facebook-likebox' );
?></a>
        </div>
    </div>

</div>