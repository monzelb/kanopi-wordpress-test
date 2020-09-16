<?php
/**
*Plugin Name: They Said So Quotes
*Description: Plugin for inserting quotes into posts 
**/

// Exit if accessed directly
if(!defined('ABSPATH')){
    exit;
}



function call_api($method, $url, $data = false, $api_key=null)
{
    $curl = curl_init();

    switch ($method)
    {
	case "POST":
	    curl_setopt($curl, CURLOPT_POST, 1);

	    if ($data)
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    break;
	case "PUT":
	    curl_setopt($curl, CURLOPT_PUT, 1);
	    break;
	default:
	    if ($data)
		$url = sprintf("%s?%s", $url, http_build_query($data));
    }

    $headers = [
	'Content-Type: application/json'
	];
    if ( !empty($api_key))
	$headers[] = 'X-TheySaidSo-Api-Secret: '. $api_key;

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}






add_filter( 'the_content', 'filter_the_content_in_the_main_loop', 1 );
 
function filter_the_content_in_the_main_loop( $content ) {



    $qod_result = call_api("GET","https://quotes.rest/qod?category=funny",false,null);
    
        $quote = json_decode($qod_result);
    
        $cleanedquote = ($quote->{'contents'}->{'quotes'}[0]->{'quote'});
    
    
    // Check if we're inside the main loop in a single Post.
    if ( is_singular() && in_the_loop() && is_main_query() ) {
        return $content . $cleanedquote;
    }

    return $content;
}



// }


?>

