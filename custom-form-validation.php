<?php



/* 

Documentation: https://docs.gravityforms.com/gform_validation/

*/






//  add_filter lets us target a particular form or all of the forms on our site and pass any functions to them.

add_filter( 'gform_validation', 'check_passport_length' ); 





/*

This function checks the value of the Passport Number field

*/

function getFormId($form){
    
    $target_id = 0;
    
    // Loop through all fields in form to find ID of Passport Number field
    
    foreach( $form['fields'] as &$field ) {
 
        if ($field->label == "Passport Number") {
            
            $target_id = $field->id;
            
            //End loop
            break;
            
        } else {
            
            $target_id = null;
            
        }
    }
    
    return $target_id;
    
}

//If the user input from that field does not have 9 characters, set the field to failed validation and display error message
function check_passport_length($validation_result) {
    
    $form = $validation_result['form'];
    
    $target_id = getFormId($form);
    
    if(!empty($target_id) && strlen(rgpost("input_" . $target_id)) !== 9){
        
        $value = strlen(rgpost("input_" . $target_id)); //Length of users input
        

        $validation_result['is_valid'] = false;
        

        foreach( $form['fields'] as &$field ) {
 
            if ($field->id == $target_id) {
                $field->failed_validation = true;
                $field->validation_message = 'Passport Number Invalid. Passport numbers should have 9 digits. The number you have entered has ' . $value;
                break;
            }
            
		}
            
	} else {
        
        //do nothing
        
    }
    
    $validation_result['form'] = $form;
    
    return $validation_result;
    
    
}



?>