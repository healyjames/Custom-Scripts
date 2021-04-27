<?php


/*

This function hides the various payment field sections when a certain URL parameter has is present.


Pre:
- Check that fomr fields exist (e.g. the form has not yet been submitted)
- Search for id of field that pulls in the sup parameter
- Loop through all form fields
- Add logic to hide any form field with specific class when a URL parameter has been passed

Post:
- All fields payments fields will be hidden and deactivated, allowing the user to proceed without payment.

*/

add_filter( 'gform_pre_render', 'set_payment_conditional_logic' );

function set_payment_conditional_logic( $form ) {
    
    if( ! empty( $form['fields'] ) ){
    
        $target_id = get_field_id_from_class( $form );

        foreach ( $form['fields'] as &$field ) {
            
            if ( $field->cssClass == "dynamic-hidden-field" ) {
                
                //Get any cuurent rules that have been applied to the field on the front end
                $rules = rgar( $field->conditionalLogic, 'rules' );
                
                
                if( empty($rules) ) {  $rules = array(); } 
                
                
                    
                array_push(

                    $rules,
                    array( 'fieldId' => $target_id, 'operator' => 'is', 'value' => 'test' ),
                    array( 'fieldId' => $target_id, 'operator' => 'is', 'value' => 'a' ),
                    array( 'fieldId' => $target_id, 'operator' => 'is', 'value' => 'b' ),
                    array( 'fieldId' => $target_id, 'operator' => 'is', 'value' => 'c' ),
                    array( 'fieldId' => $target_id, 'operator' => 'is', 'value' => 'XYZ' )

                );
                                
                
                $field->conditionalLogic =
                    array(
                        'actionType' => 'hide',
                        'logicType' => 'any',
                        'rules' => $rules
                    );
                
                
            }
        }
        
        return $form;
        
    } else { return $form; }
    
}




/*

This function finds the id of the supplier field

*/
function get_field_id_from_class( $form ){
    
    $target_id = 0;
    
    foreach( $form['fields'] as &$field ) {
 
        if ( strtolower($field->label) == "supplier" ) {
            
            $target_id = $field->id;
            break;
            
        } else {
            
            $target_id = 0;
            
        }
    }
    
    return $target_id;
    
}




/*

This function disables the form validation on the payment page [when certain sup parameter is present] so that the hidden payment fields do not activate any errors on submit.

Pre:
- Check for sup code
- Loop through a set of predefined target sup codes
- Check if current sup code matches the current sup code from our predefined list
- Loop through all form fields
- Check if current field is on the current page AND if it has the correct css class (dynamic-hidden-field)
- IF it does, then set validation to true - ignore any errors on the form page and submit it regardless

Post:
- Users can proceed without payment but only when one of the defined sup codes are supplied.


*/

add_filter( 'gform_validation', 'validate_form' );

function validate_form( $validation_result ) {
    
    $form_validation = $validation_result;
    
    if( isset($_GET['sup']) ){
        
        $form_validation = setup( $_GET['sup'], $validation_result );
        
    }
    
    $validation_result = $form_validation;
    return $validation_result;
 
}


function setup( $sup, $validation_result ) {
    
    $v = $validation_result;
    
    include 'sup-code-targets.php';
    
    //Split sup code by any special characters
    $sup = preg_split('/[?&@#%]/', $sup, -1, PREG_SPLIT_NO_EMPTY);
    
    foreach ( $sup_code_list as $sup_code ) {
        
        if ( strtolower($sup[0]) == strtolower($sup_code) ) {
        
            $v = run_validation( $validation_result );
        
        } else { continue; }
        
    }
    
    $validation_result = $v;
    return $validation_result;
    
}



function run_validation( $validation_result ) {
    
    $form = $validation_result['form'];
    
    // Get the current page being validated
    $current_page = rgpost( 'gform_source_page_number_' . $form['id'] ) ? rgpost( 'gform_source_page_number_' . $form['id'] ) : 1;

    foreach( $form['fields'] as &$field ) {
        
        $field_page = $field->pageNumber;
        
        if ( $field_page == $current_page && $field->cssClass == "dynamic-hidden-field" ) {
            
            //disable form validation
            $validation_result['is_valid'] = true; 
            $field->failed_validation = false;
            continue;
            
        }
    }
 
    //Assign modified $form object back to the validation result
    $validation_result['form'] = $form;
    return $validation_result;
    
}





?>