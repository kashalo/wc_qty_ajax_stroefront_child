<?php

add_action('wp_enqueue_scripts', 'wp_enqueue_child_files'); // Register Our Child Theme JS & CSS File

function wp_enqueue_child_files()
{
    wp_enqueue_script('child_mainJS', get_stylesheet_directory_uri() . '/assets/js/child_main.js', array('jquery'), false, true);
    wp_enqueue_style('child_mainCSS', get_stylesheet_directory_uri() . '/assets/css/child_main.css', array(), false, 'all');
}

// Remove Add To cart Button
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

// Add our Quanity Input
add_action('woocommerce_after_shop_loop_item', 'QTY');
function QTY()
{
    global $product;
    ?>
        <div class="shopAddToCart">
        <button  value="-" class="minus"  >-</button>
        <input type="text"
        disabled="disabled"
        size="2"
        value="<?php echo (Check_if_product_in_cart($product->get_id())) ? Check_if_product_in_cart($product->get_id())['QTY'] : 0;
    ?>"
        id="count"
        data-product-id= "<?php echo $product->get_id() ?>"
        data-in-cart="<?php echo (Check_if_product_in_cart($product->get_id())) ? Check_if_product_in_cart($product->get_id())['in_cart'] : 0;
    ?>"
        data-in-cart-qty="<?php echo (Check_if_product_in_cart($product->get_id())) ? Check_if_product_in_cart($product->get_id())['QTY'] : 0;
    ?>"
        class="quantity  qty"
        max_value = <?php echo $product->get_max_purchase_quantity(); ?>
        min_value = <?php echo $product->get_min_purchase_quantity(); ?>
        >

        <button type="button" value="+" class="plus"  >+</button>

        </div>
                              <?php
}
//Check if Product in Cart Already
function Check_if_product_in_cart($product_ids)
{

    foreach (WC()->cart->get_cart() as $cart_item):

        $items_id = $cart_item['product_id'];
        $QTY = $cart_item['quantity'];

        // for a unique product ID (integer or string value)
        if ($product_ids == $items_id):
            return ['in_cart' => true, 'QTY' => $QTY];

        endif;

    endforeach;
}
//Add Event Handler To update QTY
add_action('wc_ajax_update_qty', 'update_qty');

function update_qty()
{
    ob_start();
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    $quantity = $_POST['quantity'];

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item):

        if ($cart_item['product_id'] == $product_id) {
            WC()->cart->set_quantity($cart_item_key, $quantity, true);
        }

    endforeach;

    wp_send_json('done');
}
