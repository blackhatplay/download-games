<?php

// header("Access-Control-Allow-Origin: *");
include("simplehtmldom/simple_html_dom.php");
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if($_SERVER['REQUEST_METHOD'] == "GET") { 

    // $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));

    // $catData = file_get_contents('https://getintopc.com/',false,$context);

    // echo $catData;

    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, 'https://getintopc.com/' );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    global $content;
    $catData = curl_exec( $ch );
    $response = curl_getinfo( $ch );

    curl_close ( $ch );

    $catData = str_get_html($catData);
    $result = $catData->find('ul[id=menu-header-menu]')[0]->children();
    $catNav = $catData->find('div[class=page-navi]');
    
    foreach($result as $element) {
            if($element->plaintext !== 'Home '){
                if($element->plaintext !== 'Hacks '){
                    $cat[] = $element->plaintext;
                }
            }
    }

    echo json_encode($cat);
    // echo date("h:i:s") . "\n"; 

}


?>