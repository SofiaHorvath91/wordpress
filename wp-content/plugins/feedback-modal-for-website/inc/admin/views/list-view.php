<?php

if ( !current_user_can( $data_capability ) ) :
	wp_die( _e( 'You do not have sufficient permissions to access this page.', 'nedwp-feedback-modal' ) );
endif;

?>
<div class="wrap">

	<div id="icon-users" class="icon32"></div>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php settings_errors(); ?>

	<div id="nedwp-fm-admin-list">
		<form method="post" action="">
	        <?php $list->display(); ?>
	    </form>
	</div>
    
</div>