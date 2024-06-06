<?php
require "list.php";

function &get_url_page($url) {
    $user_agent='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36';
    $options = array(
        CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
        CURLOPT_POST           =>false,        //set to GET
        CURLOPT_USERAGENT      => $user_agent, //set user agent
        CURLOPT_COOKIEFILE     =>"public/cookie.txt", //set cookie file
        CURLOPT_COOKIEJAR      =>"public/cookie.txt", //set cookie jar
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );
    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    curl_close( $ch );
        
    return $content;
}

function scrape_data($url, $filename) {
    $data = get_url_page($url);
    
    $location1 = strpos($data, "One-time purchase");
    $location2 = strpos($data, "₹", $location1);
    if ($location1 === false || $location2 === false) {
        die("Pattern not found");
    }
    $data = floatval(substr($data, $location2 + 3, 10));

    $fp = fopen("public/" . $filename . ".txt", "a") or die("Unable to open file!");
    fwrite($fp, date("c") . "," . $data . "\n");
    fclose($fp);
    
    echo $filename . ": " . $data . "<br />";
}

$prod_m = "";
if ($_GET && $_GET['p']) {
    $prod_m = $_GET["p"];
}
foreach ($product_list as $p) {
    if ($prod_m) {
        if ($p["name"] == $prod_m) {
            scrape_data($p["link"], $p["name"]);
        }
    } else {
        scrape_data($p["link"], $p["name"]);
    }
}

?>
