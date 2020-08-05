
<?php

// header("Access-Control-Allow-Origin: *");
include("simplehtmldom/simple_html_dom.php");

if($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['s'])) { 
    $searchTerm = (string) @$_GET['s'];
    if ($searchTerm) {
        // echo 'hello';
        $stripped = trim(preg_replace('/\s+/', '+', $searchTerm));
        $searchlink = 'http://oceanofgames.com/?s=' . $stripped;
        $searchData = file_get_contents($searchlink);
        $searchData = str_get_html($searchData);
        $result = $searchData->find('div[class=post-details]');
        $objects = [];
        $i = 0;
        foreach($result as $element){
            $cat = [];
            for ($i = 0 ; $i<count($element->find('div[class=post-info]')[0]->find('a')) ; $i++) {
                // print $element->find('div[class=post-info]')[0]->find('a')[$i]->plaintext;
                array_push($cat, $element->find('div[class=post-info]')[0]->find('a')[$i]->plaintext);
            }
            // $element->find('a[class=post-thumb]')[0]->href;
            // $forImg = file_get_contents($element->find('a[class=post-thumb]')[0]->href);
            // $forImg = str_get_html($forImg);
            // $forImg = $forImg->find('.post-content')[0]->find("img")[0];
            // print $forImg;

            $objects[] = (object) [
                'title' => $element->find('a[class=post-thumb]')[0]->title,
                "href" => $element->find('a[class=post-thumb]')[0]->href,
                "img" => $element->find('a[class=post-thumb]')[0]->find('img')[0]->src,
                'uploadDate' => $element->find('div[class=post-date]')[0]->plaintext,
                'category' => $cat
              ];
        }
        echo json_encode($objects);
        // echo $result;
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['link'])) {

	$link = (string) @$_POST['link'];

	// $link = (string) @$_GET['link'];

	if (!$link == '') {
        getLink($link);

	}

}

function getLink($link){
    $details = file_get_contents($link);
	
    $details = str_get_html($details);

    $title =  $details->find('h1[class=title]')[0]->innertext;

    $postContent = $details->find('.post-content')[0]->find("p");

    $forImg = $details->find('.post-content')[0]->find("img");

    $img = $forImg[0]->src;

    // $count = $details->find('.post-content')[0]->children();

    // echo count($count);

    $gameOverview = $details->find('.post-content')[0]->find("p")[1]->plaintext . '<br>' . $details->find('.post-content')[0]->find("p")[2]->plaintext;

    $techincalSpecs = $details->find('.post-content')[0]->find("ul")[0]->innertext;
    // $temp = '';
    // foreach($postContent as $element){
    // 	$temp =  $temp . '<br>' . $element->plaintext;
    // }
    


    // print $title;
    // echo '<br>';
    // print $postContent[1]->innertext;
    // print $techincalSpecs;
    // print $gameOverview;

    $return = array(
        "title"=>$title,
        "technicalSpecs"=>$techincalSpecs, 
        "gameOverview"=>$gameOverview,
        "img"=>$img
    );

    echo json_encode($return);
}


?>