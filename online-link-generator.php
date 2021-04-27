<?php

/*
 
 This snippet of custom code adds a link to either the email or checkout page that the customer recieves straight after purchasing an online product. It creates a linke with pre-populated url parameter code.
 
 Pre:
 - Set up list of products (IDs) and their respective online form link urls
 - Loop through all ordered items
 - If current order item matches one of the products in the list, echo link with parameters
 
 Post:
 - One link is displayed on the customer email/checkout page per online product ordered.
 
*/

$supplier_url_param = 'sup=';
$order_url_param = 'order-no=';
$priority_url_param = 'priority=';

$online_products = array(
    "1461" => "test.co.uk/form-1/?" . $supplier_url_param . "TEST&" . $priority_url_param . "3&",
    "1610" => "test.co.uk/form-2/?" . $supplier_url_param . "TEST&" . $priority_url_param . "1&",
    "1609" => "test.co.uk/form-3/?" . $supplier_url_param . "TEST&" . $priority_url_param . "2&",
    "1608" => "test.co.uk/form-4/?" . $supplier_url_param . "TEST&" . $priority_url_param . "2&");


foreach( $order->get_items() as $item ){
    
    foreach($online_products as $product_id => $link) {
        
        if( $item->get_product_id() == $product_id) {
            
            if($style_type == 'email') {
            
            echo '<p style="margin: 0px 0px 16px 0px"><strong>Here is your unique online application form link:</strong></p>';
            echo '<div style="width: 100%; padding: 10px; background-color: #ca120b; border-radius: 200px; margin-bottom: 35px">';
            echo '<p style="text-align: center; color: white; margin: 0px;"><a style="text-align:center;color:white" href="https://' . $link . $order_url_param . $order->get_order_number() . '">' . $link . $order_url_param . $order->get_order_number() . '</a></p><br />';
            echo '</div>';
                
            } else if($style_type == 'website') {
                
            echo '<div class="online-link-container">';
            echo '<p><strong>Here is your unique online application form link:</strong></p>';
            echo '<div>';
            echo '<p><a href="https://' . $link . $order_url_param . $order->get_order_number() . '">' . $link . $order_url_param . $order->get_order_number() . '</a></p><br />';
            echo '</div>';
            echo '</div>';
                
            }
            
        }
    }
}
   