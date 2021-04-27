<?php


/*

This function converts the value from 'Stripe' to 'Invoice' when a certain URL sup parameter is present
This function runs before the form submission is processed but after the user has submitted

/*


/*

Pre:
- Setup a list of sup codes
- Loop through each code and check against URL sup parameter
- IF there is a match, run the code to convert 'Stripe' to 'Invoice'

Post:
- Code to convert value is executed

*/

add_action( 'gform_pre_submission', 'stripe_to_invoice_converter' );

function stripe_to_invoice_converter( $form ) {
    
    if( isset($_GET['sup']) ){
        
        init( $_GET['sup'], $form );
        
    }  
    
}

/*

Pre:
- Split the sup parameter to ensure no gravity forms url param data is interfering - e.g. get all text before '#' symbol
- Loop through list of given sup params and check against the current param
- If there is a match, run the code to convert the input value
- Else continue to next loop iteration

Post:
- The conversion code is executed

*/

function init( $sup, $form ) {
    
    //$sup_code_list = array('test', 'BNT', 'ETH', 'BLS');
    include 'sup-code-targets.php';
    
    $sup = preg_split('/[?&@#%]/', $sup, -1, PREG_SPLIT_NO_EMPTY);
    
    foreach ( $sup_code_list as $sup_code ) {
        
        if ( strtolower($sup[0]) == strtolower($sup_code) ) {
        
            convert( $form );
        
        } else { continue; }
        
    } 
    
}



/*

Pre:
- Loop through all fields in the form
- Find the Zoho Payment Method field
- Replace the value of that field with 'Invoice'

Post:
- The correct field has its value updated

*/

function convert( $form ) {
    
    foreach ( $form['fields'] as &$field ) {
        
        if ( strtolower($field->label) == "zoho payment method" ) {
            
            $_POST['input_' . $field->id] = 'Invoice To Be Sent';
            
        }
    }
}



?>