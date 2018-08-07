    jQuery(document).ready(function ($) {
      "use strict";

      // Add Event Listner on the Plush button 
      $('.plus').click(function () {

        if (parseInt($(this).prev().val()) < parseInt($(this).prev().attr('max_value'))) {
          $(this).prev().val(+$(this).prev().val() + 1);

          var currentqty = parseInt($(this).prev().attr('data-in-cart-qty')) + 1;

          var id = $(this).prev().attr('data-product-id');

          var data = {
            product_id: id,
            quantity: 1
          };
          $(this).prev().attr('data-in-cart-qty', currentqty);
          $(this).parent().addClass('loading');
          $.post(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), data, function (response) {

            if (!response) {
              return;
            }

            if (response) {

              var url = woocommerce_params.wc_ajax_url;
              url = url.replace("%%endpoint%%", "get_refreshed_fragments");
              $.post(url, function (data, status) {
                $(".woocommerce.widget_shopping_cart").html(data.fragments["div.widget_shopping_cart_content"]);
                if (data.fragments) {
                  jQuery.each(data.fragments, function (key, value) {

                    jQuery(key).replaceWith(value);
                  });
                }
                jQuery("body").trigger("wc_fragments_refreshed");
              });
              $('.plus').parent().removeClass('loading');

            }

          });


        }




      });



      $('.minus').click(function () {

        $(this).next().val(+$(this).next().val() - 1);


        var currentqty = parseInt($(this).next().val());

        var id = $(this).next().attr('data-product-id');

        var data = {
          product_id: id,
          quantity: currentqty
        };
        $(this).parent().addClass('loading');
        $.post(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'update_qty'), data, function (response) {

          if (!response) {
            return;
          }

          if (response) {
            var url = woocommerce_params.wc_ajax_url;
            url = url.replace("%%endpoint%%", "get_refreshed_fragments");
            $.post(url, function (data, status) {
              $(".woocommerce.widget_shopping_cart").html(data.fragments["div.widget_shopping_cart_content"]);
              if (data.fragments) {
                jQuery.each(data.fragments, function (key, value) {

                  jQuery(key).replaceWith(value);
                });
              }
              jQuery("body").trigger("wc_fragments_refreshed");
            });
            $('.plus').parent().removeClass('loading');
          }

        });




      });



    });