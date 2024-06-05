<?php 
if ($_GET && $_GET['url']) {
  $url = $_GET['url'];
  //$ch = curl_init();

  //curl_setopt($ch,CURLOPT_URL, $url);

  //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
/*
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-SPF-Referer: https://www.youtube.com/',
    'Referer: https://www.youtube.com/',
    'X-SPF-Previous: https://www.youtube.com/',
    'User-Agent: Mozilla/6.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36'
  ));
*/

$user_agent='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
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
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );
        
        echo $content;
       // echo $err;
     //   echo $errmsg;
   //     echo $header;
 // echo $url;
} else {

//var_dump(getallheaders());
}
?>
