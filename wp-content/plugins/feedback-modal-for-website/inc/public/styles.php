<?php ob_start(); ?>
/* Content font */
div#nedwp-fm {
    font-family: <?php echo esc_attr( $content_font ); ?>;
}
/* Primary color */
div#nedwp-fm div#nedwp-fm-modal-done-screen-icon i,
div#nedwp-fm div#nedwp-fm-modal-done-screen-overlay i,
div#nedwp-fm a.nedwp-active > div.nedwp-fm-modal-opinion-text
{
    color: <?php echo esc_attr( nedwp_fm_opt( 'primary_color' ) ); ?>;
}
@media screen and (min-width: 769px) {
    div#nedwp-fm div.nedwp-fm-modal-opinion-text:hover:not(:focus) {
        color: <?php echo esc_attr( nedwp_fm_opt( 'primary_color' ) ); ?>;
    }
}
div#nedwp-fm div#nedwp-fm-toggle,
div#nedwp-fm div#nedwp-fm-modal-head,
div#nedwp-fm div.nedwp-fm-modal-screen-action,
div#nedwp-fm div.nedwp-fm-modal-opinion-name
{
    background-color: <?php echo esc_attr( nedwp_fm_opt( 'primary_color' ) ); ?>;
}
div#nedwp-fm input[type="text"].nedwp-input:focus,
div#nedwp-fm input[type="email"].nedwp-input:focus,
div#nedwp-fm input[type="tel"].nedwp-input:focus,
div#nedwp-fm input[type="url"].nedwp-input:focus,
div#nedwp-fm input[type="number"].nedwp-input:focus,
div#nedwp-fm input[type="search"].nedwp-input:focus,
div#nedwp-fm input[type="password"].nedwp-input:focus,
div#nedwp-fm textarea.nedwp-input:focus,
div#nedwp-fm a.nedwp-active > div.nedwp-fm-modal-opinion-text
{
    border-color: <?php echo esc_attr( nedwp_fm_opt( 'primary_color' ) ); ?>;
}
@media screen and (min-width: 769px) {
    div#nedwp-fm div.nedwp-fm-modal-opinion-text:hover:not(:focus) {
        border-color: <?php echo esc_attr( nedwp_fm_opt( 'primary_color' ) ); ?>;
    }
}
div#nedwp-fm circle#nedwp-fm-modal-loader-path {
    stroke: <?php echo esc_attr( nedwp_fm_opt( 'primary_color' ) ); ?>;
}
/* Secondary color */
div#nedwp-fm div.nedwp-fm-modal-opinion-text {
    border-color: <?php echo esc_attr( nedwp_fm_opt( 'second_color' ) ); ?>;
}
/* Ovls color */
div#nedwp-fm div#nedwp-fm-toggle,
div#nedwp-fm span#nedwp-fm-modal-title,
div#nedwp-fm #nedwp-fm-modal-close,
div#nedwp-fm div.nedwp-fm-modal-screen-action > span,
div#nedwp-fm div.nedwp-fm-modal-screen-action > i
{
    color: <?php echo esc_attr( nedwp_fm_opt( 'ovls_color' ) ); ?>;
}
div#nedwp-fm div#nedwp-fm-modal,
div#nedwp-fm div#nedwp-fm-modal-loader
{
    background-color: <?php echo esc_attr( nedwp_fm_opt( 'ovls_color' ) ); ?>;
}
div#nedwp-fm.nedwp-fm-left div#nedwp-fm-modal::after {
    border-right-color: <?php echo esc_attr( nedwp_fm_opt( 'ovls_color' ) ); ?>;
}

div#nedwp-fm.nedwp-fm-right div#nedwp-fm-modal::after {
    border-left-color: <?php echo esc_attr( nedwp_fm_opt( 'ovls_color' ) ); ?>;
}
/* Texts color */
div#nedwp-fm,
div#nedwp-fm input[type="text"].nedwp-input:focus,
div#nedwp-fm input[type="email"].nedwp-input:focus,
div#nedwp-fm input[type="tel"].nedwp-input:focus,
div#nedwp-fm input[type="url"].nedwp-input:focus,
div#nedwp-fm input[type="number"].nedwp-input:focus,
div#nedwp-fm input[type="search"].nedwp-input:focus,
div#nedwp-fm input[type="password"].nedwp-input:focus,
div#nedwp-fm textarea.nedwp-input:focus,
div#nedwp-fm span.nedwp-fm-modal-title
{
    color: <?php echo esc_attr( nedwp_fm_opt( 'texts_color' ) ); ?>;
}
div#nedwp-fm .nedwp-input:focus::-webkit-input-placeholder {
    color: <?php echo esc_attr( nedwp_fm_opt( 'texts_color' ) ); ?>;
}
div#nedwp-fm .nedwp-input:focus::-moz-placeholder {
    color: <?php echo esc_attr( nedwp_fm_opt( 'texts_color' ) ); ?>;
}
div#nedwp-fm .nedwp-input:focus:-moz-placeholder {
    color: <?php echo esc_attr( nedwp_fm_opt( 'texts_color' ) ); ?>;
}
div#nedwp-fm .nedwp-input:focus::-ms-input-placeholder {
    color: <?php echo esc_attr( nedwp_fm_opt( 'texts_color' ) ); ?>;
}
div#nedwp-fm .nedwp-input:focus:-ms-input-placeholder {
    color: <?php echo esc_attr( nedwp_fm_opt( 'texts_color' ) ); ?>;
}
/* Inputs color */
div#nedwp-fm input[type="text"].nedwp-input,
div#nedwp-fm input[type="email"].nedwp-input,
div#nedwp-fm input[type="tel"].nedwp-input,
div#nedwp-fm input[type="url"].nedwp-input,
div#nedwp-fm input[type="number"].nedwp-input,
div#nedwp-fm input[type="search"].nedwp-input,
div#nedwp-fm input[type="password"].nedwp-input,
div#nedwp-fm textarea.nedwp-input,
div#nedwp-fm div.nedwp-fm-modal-opinion-text
{
    color: <?php echo esc_attr( nedwp_fm_opt( 'inputs_color' ) ); ?>;
}
div#nedwp-fm .nedwp-input::-webkit-input-placeholder {
    color: <?php echo esc_attr( nedwp_fm_opt( 'inputs_color' ) ); ?>;
}
div#nedwp-fm .nedwp-input::-moz-placeholder {
    color: <?php echo esc_attr( nedwp_fm_opt( 'inputs_color' ) ); ?>;
}
div#nedwp-fm .nedwp-input:-moz-placeholder {
    color: <?php echo esc_attr( nedwp_fm_opt( 'inputs_color' ) ); ?>;
}
div#nedwp-fm .nedwp-input::-ms-input-placeholder {
    color: <?php echo esc_attr( nedwp_fm_opt( 'inputs_color' ) ); ?>;
}
div#nedwp-fm .nedwp-input:-ms-input-placeholder {
    color: <?php echo esc_attr( nedwp_fm_opt( 'inputs_color' ) ); ?>;
}
div#nedwp-fm input[type="text"].nedwp-input,
div#nedwp-fm input[type="email"].nedwp-input,
div#nedwp-fm input[type="tel"].nedwp-input,
div#nedwp-fm input[type="url"].nedwp-input,
div#nedwp-fm input[type="number"].nedwp-input,
div#nedwp-fm input[type="search"].nedwp-input,
div#nedwp-fm input[type="password"].nedwp-input,
div#nedwp-fm textarea.nedwp-input
{
    border-color: <?php echo esc_attr( $input_border_color ); ?>;
}
/* Notices color */
div#nedwp-fm div.nedwp-fm-modal-field-notice {
    color: <?php echo esc_attr( nedwp_fm_opt( 'notices_color' ) ); ?>;
}
/* Z-index order */
div#nedwp-fm div#nedwp-fm-shadow.nedwp-active {
    z-index: <?php echo esc_attr( substr( nedwp_fm_opt( 'modal_zindex' ), 0, -2 ) ); ?>;
}
div#nedwp-fm div#nedwp-fm-toggle {
    z-index: <?php echo esc_attr( substr( nedwp_fm_opt( 'modal_zindex' ), 0, -1 ) ); ?>;
}
div#nedwp-fm div#nedwp-fm-modal.nedwp-active {
    z-index: <?php echo esc_attr( nedwp_fm_opt( 'modal_zindex' ) ); ?>;
}<?php $custom_styles = ob_get_clean();