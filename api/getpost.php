
<?php

// header("Access-Control-Allow-Origin: *");
include("simplehtmldom/simple_html_dom.php");

if($_SERVER['REQUEST_METHOD'] == "GET" and (isset($_GET['s']) || isset($_GET['cat']))) { 
    if(isset($_GET['s'])){
        if(isset($_GET['pageNo'])) {
            $pageNo = (string) @$_GET['pageNo'];
            $searchTerm = (string) @$_GET['s'];
            $stripped = trim(preg_replace('/\s+/', '+', $searchTerm));
            $searchlink = 'http://oceanofgames.com/page/'. $pageNo . '/?s=' . $stripped;
        } else {
            $searchTerm = (string) @$_GET['s'];
            $stripped = trim(preg_replace('/\s+/', '+', $searchTerm));
            $searchlink = 'http://oceanofgames.com/?s=' . $stripped;
        }
    } else if (isset($_GET['cat'])) {
        if(isset($_GET['pageNo'])) {
            $pageNo = (string) @$_GET['pageNo'];
            $catTerm = (string) @$_GET['cat'];
            $searchlink = 'http://oceanofgames.com/category/' . $catTerm . '/page/' . $pageNo .'/';
        } else {
            $catTerm = (string) @$_GET['cat'];
            $searchlink = 'http://oceanofgames.com/category/' . $catTerm;
        }
    }

        $searchData = file_get_contents($searchlink);
        $searchData = str_get_html($searchData);
        $result = $searchData->find('div[class=post-details]');
        $objects = [];
        $i = 0;
        foreach($result as $element){
            $cat = [];
            for ($i = 0 ; $i<count($element->find('div[class=post-info]')[0]->find('a')) ; $i++) {
                array_push($cat, $element->find('div[class=post-info]')[0]->find('a')[$i]->plaintext);
            }

            $objects[] = (object) [
                'title' => $element->find('a[class=post-thumb]')[0]->title,
                "href" => $element->find('a[class=post-thumb]')[0]->href,
                "img" => $element->find('a[class=post-thumb]')[0]->find('img')[0]->src,
                'uploadDate' => $element->find('div[class=post-date]')[0]->plaintext,
                'category' => $cat
              ];
        }

        $pageNo = $searchData->find('div[class=page-navi pagination numbers  clear-block]');

        $nav[0] = false;
        $nav[1] = false;

        if($pageNo) {

            if ($pageNo) {
                $pageNo  = $pageNo[0]->children();
                for($i=0; $i< count($pageNo); $i++){
                    if($pageNo[$i]->class === 'current'){
                        if($i != 0){
                            $nav[0] = true;
                        } else  {
                            $nav[0] = false;
                        }
    
                        if ($i+1 != count($pageNo)) {
                            $nav[1] = true;
                        } else {
                            $nav[1] = false;
                        }
                    }
                }
            }
        }
        $objects[] = (object) [
            "navLeft" => $nav[0],
            "navRight" => $nav[1]
          ];
        echo json_encode($objects);
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

    $gameOverview = $details->find('.post-content')[0]->find("p")[1]->plaintext . '<br>' . $details->find('.post-content')[0]->find("p")[2]->plaintext;

    $techincalSpecs = $details->find('.post-content')[0]->find("ul")[0]->innertext;

    $return = array(
        "title"=>$title,
        "technicalSpecs"=>$techincalSpecs, 
        "gameOverview"=>$gameOverview,
        "img"=>$img
    );

    echo json_encode($return);
}


?>