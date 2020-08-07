<?php

// header("Access-Control-Allow-Origin: *");
include("simplehtmldom/simple_html_dom.php");

if($_SERVER['REQUEST_METHOD'] == "GET") { 

    $catData = file_get_contents('http://oceanofgames.com/');
    $catData = str_get_html($catData);
    $result = $catData->find('ul[id=menu-navigation]')[0]->children();
    $catNav = $catData->find('div[class=page-navi]');
    
    foreach($result as $element) {
            if($element->plaintext !== 'Home '){
                if($element->plaintext !== 'Hacks '){
                    $cat[] = $element->plaintext;
                }
            }
    }

    echo json_encode($cat);

}


?>