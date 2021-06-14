jQuery(document).ready(function($) {

  if ($('.esf_insta_feeds_masonary').length) {

    $('.esf_insta_load_more_btns_wrap').hide();

    $('.esf-insta-masonry-wrapper .esf_insta_feed_fancy_popup').
        imagesLoaded(function() {

          $('.esf_insta_feeds_holder .esf-insta-load-opacity').
              fadeIn('slow').
              css('display', 'inline-block');

          $('.esf_insta_load_more_btns_wrap').slideDown();

          $('.esf_insta_feeds_holder .esf-insta-load-opacity').
              removeClass('esf-insta-load-opacity');

        });
  }

  if ($('.esf_insta_feeds_grid').length) {

    $('.esf_insta_load_more_btns_wrap').hide();

    $('.esf-insta-grid-wrapper .esf_insta_feed_fancy_popup').
        imagesLoaded(function() {

          $('.esf_insta_feeds_holder .esf-insta-load-opacity').fadeIn('slow');

          $('.esf_insta_load_more_btns_wrap').slideDown();

          $('.esf_insta_feeds_holder .esf-insta-load-opacity').
              removeClass('esf-insta-load-opacity');

        });
  }

  

  jQuery(document).on('click', '.esf_insta_load_more_btn', function(event) {

    event.preventDefault();

    var outer_this = jQuery(this);

    var selected_template = jQuery(this).data('selected-template');

    var load_more_outer = jQuery(this).parent().parent().parent();

    var is_disabled = jQuery(this).hasClass('no-more');

    if (is_disabled) { return; }

    jQuery(outer_this).addClass('loading');

    var transient_name = jQuery(this).data('transient-name');

    var current_items = jQuery(this).attr('data-current_items');

    var shortcode_atts = jQuery(this).data('shortcode_atts');

    jQuery.ajax({

      url: esf_insta.ajax_url,

      type: 'post',

      dataType: 'json',

      data: {

        action: 'esf_insta_load_more_feeds',

        current_items: current_items,

        shortcode_atts: shortcode_atts,

        transient_name: transient_name,

        selected_template: selected_template,

        nonce: esf_insta.nonce,

      },

      success: function(response) {

        if (response.success) {

          var html = response.data.html;

          var total_items = response.data.total_items;

          jQuery(outer_this).attr('data-current_items', ' ');

          jQuery(outer_this).attr('data-current_items', total_items);

          if (selected_template === 'masonary') {

            var $container = jQuery('.esf-insta-masonry-wrapper a');

            var $items = jQuery(html);

            jQuery(outer_this).
                parent().
                parent().
                parent().
                parent().
                find('.esf-insta-masonry').
                append(html);
            $('.esf-insta-masonry-wrapper .esf_insta_feed_fancy_popup').
                imagesLoaded(function() {

                  $('.esf_insta_feeds_holder .esf-insta-load-opacity').
                      fadeIn('slow').
                      css('display', 'inline-block');

                  jQuery(outer_this).removeClass('loading');

                  $('.esf_insta_feeds_holder .esf-insta-load-opacity').
                      removeClass('esf-insta-load-opacity');

                });

          }
          else if (selected_template === 'grid') {

            jQuery(outer_this).
                parent().
                parent().
                parent().
                parent().
                find('.esf-insta-row').
                append(html);

            $('.esf-insta-grid-wrapper .esf_insta_feed_fancy_popup').
                imagesLoaded(function() {

                  $('.esf_insta_feeds_holder .esf-insta-load-opacity').
                      fadeIn('slow');

                  jQuery(outer_this).removeClass('loading');

                  $('.esf_insta_feeds_holder .esf-insta-load-opacity').
                      removeClass('esf-insta-load-opacity');

                });

            jQuery(outer_this).removeClass('loading');

          }
          else {

            jQuery(outer_this).
                parent().
                parent().
                parent().
                siblings('.esf_insta_feeds_holder').
                append(html);

            jQuery(outer_this).removeClass('loading');

          }

          

        }
        else {

          jQuery(outer_this).removeClass('loading');

          jQuery(outer_this).addClass('no-more');
          console.log('ESF Error: ' + response.data);

        }

      },

    });

  });

  /* </fs_premium_only> */

});