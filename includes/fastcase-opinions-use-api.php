<?php
/**
 * Working with the Fastcase API
 *
 * End point
 * https://services.fastcase.com/REST/ResearchServices.svc/GetPublicLink
 
 * Use JSON like:
 * {
 *  "Context": { 
 *      "ServiceAccountContext": "AdIS5ea9khiRQFGR8PAl" //the api key
 *  }, 
 *  "Request":{ 
 *      "Citations":[ 
 *      { 
 *      "Volume": "1", 
 *      "Reporter": "US", 
 *      "Page": "1" 
 *      }
 *  ] 
 *  }
 * }
 * First we POST the JSON and that returns a public URL encoded in JSON.
 * Once we get the URL we'll GET the URL to return the actual case.
 * Then we format it as a post for Wordpress.
 */
function prefix_admin_use_api() {
extract($_POST);
// data, hardcoded for now. Note the weird array(array()) construction for dealing with JSON list of citations.

$data = array(
              "Context"=>array(
                               "ServiceAccountContext"=>$api_key_field
                               ),
              "Request"=>array(
                               "Citations"=>array(array(
                                                  "Volume"=>$fco_volume_field,
                                                  "Reporter"=>$fco_reporter_field,
                                                  "Page"=>$fco_page_field))
                               )
              );
// make it JSON
$data_json = json_encode($data);
//echo "<code>".$data_json."</code>";
//die;
// init endpoint
$ch = curl_init("https://services.fastcase.com/REST/ResearchServices.svc/GetPublicLink");

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_json))                                                                       
);                                                                                                                   
 
$result = curl_exec($ch);
curl_close($ch);
//echo "<code>".$result."</code><hr>";
$fcresult = (json_decode($result,true));
extract($fcresult);
//catch error from bad cite
if (!$GetPublicLinkResult['Result'][0]['FullCitation']){
  echo 'Bad citation, try again.';
} else {

$title = $GetPublicLinkResult['Result'][0]['FullCitation'];
// Using the URL, get the case
$fcurl = $GetPublicLinkResult['Result'][0]['Url'];

$ch = curl_init();    
curl_setopt($ch, CURLOPT_URL, $fcurl); // set url
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$html = curl_exec($ch);
curl_close($ch);

// parse returned page to get just the case
$dom = new DOMDocument();
$dom->validateOnParse = true;
# The @ before the method call suppresses any warnings that
# loadHTML might throw because of invalid HTML in the page.
@$dom->loadHTML($html);
//strip out style attributes
$domx = new DOMXPath($dom);
$items = $domx->query("//div[@style]");

foreach($items as $item) {
  $item->removeAttribute("style");
}
$opinion = $dom->getElementById('theDocument');
//echo "<code>".var_dump($opinion)."</code>";
//echo $opinion->nodeValue;
mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");
ob_start("mb_output_handler");
htmlspecialchars($opinion);
//echo $dom->saveHTML($opinion);
// build a post
$fc_opinion = array(
    'post_title'            => $title,
    'post_content'          => $dom->saveHTML($opinion),
    'post_author'           => 1,
    'post_type'             => 'opinions',
    'post_status'           => 'draft',
    );
//print_r ($fc_opinion);
$post_id = wp_insert_post( $fc_opinion, $wp_error );
//echo $post_id;
// this is a hack to open the new psot in an edit window
// Is there a WordPress way to do this?
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = '/post.php?post='.$post_id.'&action=edit';
header("Location: http://$host$uri/$extra");
}
}
?>