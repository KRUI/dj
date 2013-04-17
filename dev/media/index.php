<?php 
/** 
 * Google short links API demo 
 * 
 * @license    Public Domain 
 * @author     Kathrin <kath...@vollick-tech.com> 
*/ 
/* 
 * To the maximum extent permissible under law, the author of this 
code has waived all copyright 
 * and  related or neighboring rights to this short links demo, and 
hereby places it into the 
 * public domain. This work is published from the United States. 
 */ 
define ('SHORTLINK_SERVER', '[your server]'); 
define ('SHORTLINK_SECRET', '[your secret]'); 
define ('SHORTLINK_EMAIL', '[your email]'); 
define ('SHORTLINK_DEBUG', false); 
$url = 'http://www.vollick-tech.com'; 
// If we're viewing this on the web, send it as plain text 
if (isset ($_SERVER['HTTP_HOST'])) { 
        header ('Content-Type: text/plain'); 
} 

// Random keys 
// $key1 = substr (sha1 (uniqid (rand(), true)), 0, 8); 
// $key2 = substr (sha1 (uniqid (rand(), true)), 0, 8); 
echo "Testing short link generation:\n"; 
// echo sprintf ("Public Hashed:    %s\n", short_link ($url, true, 
true)); 
echo sprintf ("Private Hashed:   %s\n", short_link ($url, true, 
false)); 
// echo sprintf ("Public Shortcut:  %s\n", short_link ($url, true, 
true, $key1)); 
// echo sprintf ("Private Shortcut: %s\n", short_link ($url, true, 
false, $key2)); 
function short_link ($url, $hashed = true, $public = false, $shortcut 
= '') { 
        // Base URL for the API call 
    $base_url = sprintf ('http://%s/js/%s', SHORTLINK_SERVER, 
$hashed ? 'get_or_create_hash': 'get_or_create_shortlink'); 
    // API call parameters 
    $parameters = array ( 
        'is_public' => $public ? 'true' : 'false', 
        'oauth_signature_method' => 'HMAC-SHA1', 
        'shortcut' => $shortcut, 
        'timestamp' => sprintf ('%0.1f', time()), 
        'url' => $url, 
        'user' => SHORTLINK_EMAIL, 
    ); 
    // URL encode the parameters 
    $params_encoded = http_build_query ($parameters); 
    // Build the base URL for the signature 
    $signature_base = sprintf ('GET&%s&%s', urlencode ($base_url), 
urlencode ($params_encoded)); 
    $signature = urlencode (base64_encode (hash_hmac ('sha1', 
$signature_base, SHORTLINK_SECRET, true))); 
    // Make the API call 
    $request_url = sprintf ('%s?%s&oauth_signature=%s', $base_url, 
$params_encoded, $signature); 
    $response = file_get_contents ($request_url); 
    if (defined ('SHORTLINK_DEBUG') && SHORTLINK_DEBUG) { 
        echo sprintf ("Signature base: %s\n", $signature_base); 
        echo sprintf ("Signature: %s\n", $signature); 
        echo sprintf ("Request URL: %s\n", $request_uri); 
    } 
    if (!$response) { 
        return false; 
    } 
    $decoded = json_decode ($response, true); 
    if (defined ('SHORTLINK_DEBUG') && SHORTLINK_DEBUG) { 
        echo "Response: \n"; 
        var_dump ($decoded); 
    } 
    return sprintf ('http://%s/%s', SHORTLINK_SERVER, $decoded 
['shortcut']); 
}
?>