<?php if(!defined('BASEPATH')) { exit('No direct script access allowed'); }
/**
 * Helper for connecting to bug tracking system.
 *
 * @version 0.7.5
 * @author sourcemap@media.mit.edu
 * @package sourcemap
 * @subpackage helpers
 */

function file_bug($title, $description) {
// Account and user settings
$account = "sourcemap";
$project_id = "7562";
$user = "hock@media.mit.edu";
$password = "snapdragon";
$token = "1e6456ac3b56ea5c4806b96bacf5ed4df4086fde";

// Assemble the account url
$url = "http://" . $account . ".lighthouseapp.com/projects/" . $project_id . "/tickets.xml";

// Setup the cURL object
$curl = curl_init();
curl_setopt( $curl, CURLOPT_POST, 1);
curl_setopt( $curl, CURLOPT_URL, $url );
curl_setopt( $curl, CURLOPT_USERPWD, ("source" . ":" . "i16abyp82yakkmnprxwjff59ggxrypv16fr3dpa6") );

// Create the XML to post

$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
                    "<ticket>" .
                        "<title>" . $title . "</title>" .
                        "<body>" . $description . "</body>" .
                        "<tag>userticket</tag>" .

                    "</ticket>";

// Setup the right headers for content-type etc.
$header = "X-LighthouseToken: " . $token . "\r\n";
$header .= "Content-type: application/xml\r\n";
$header .= "Content-length: " . strlen( $xml ) . "\r\n\n";  // Important! Two linebreaks.
$header .= $xml;

curl_setopt( $curl, CURLOPT_HTTPHEADER, array( $header ) );

// Execute the request and get the result
$result = curl_exec( $curl );
curl_close($curl);
}
?>