<?php
/**
 * Plugin Name: RMC Gravity Form Webhooks
 * Plugin URI: www.rockymountaincode.com
 * Author: Rocky Mountain Code
 * Author URI: www.rockymountaincode.com
 * Description: This plugin sends Gravity form submission information to third party API
 * Version: 1.0
 * text-domain: rmcgfw
*/

@require_once( __DIR__ . '/options-page.php');

defined('ABSPATH') or die('No entry');

//the 10 is for priority and the 2 is for how many arguments are being passed into function
add_action( 'gform_after_submission', 'third_party_send', 10, 2 );

function third_party_send( $entry, $form ){

	//get API URL and token from options page
    $theme_options = get_option( 'rmc_webhooks_option_name' );
    $preURL = $theme_options['endpoint_url_0'];
    $ps_token = $theme_options['token_1'];
    rtrim($preURL, '/');
    ltrim($preURL, 'http:');
    ltrim($preURL, 'https:');

	//declare additional variables
    $info = [];
	$check_header = get_field_atts_by_label($form, $entry, 'headerFormType');
	$endpoint = '';

	//check which endpoint that will be sent with API URL
	if($check_header == "lead"){
		$endpoint = 'lead';
	};
	
	if($check_header == "FT"){
		$endpoint = 'free_trial_client';
	}

	if($check_header == "modal"){
		$endpoint = 'modal';
	}
	
	//get form object values
    foreach($form["fields"] as $field){       
        array_push($info, (object)[
            $field['label']  => $entry[$field["id"]]
        ]);
    }

	//convert form data to JSON object
    $body = wp_json_encode($info);

	//set URL with appropriate endpoint
    $url = $preURL .'/'. $endpoint;
  
	//set arguments for sending data to API
    $args = [
        'method' => 'POST',
        'body' => $body,
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'PS_Token: ' . $ps_token
          ]
    ];
  
	//make request
    $response = wp_remote_post($url, $args);  
    
	//error handling
    if(is_wp_error($response)){
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
    }
}

//this function gets the value of the headerFormType so the endpoint after submission can be determined
function get_field_atts_by_label( $form, $entry, $label ) {
    foreach( $form['fields'] as $field ) {
        if( $field['label'] == $label ) {
            return $entry[ $field->id ];
        }
    }
    return false;
}

?>