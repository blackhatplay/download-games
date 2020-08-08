
<?php

// header("Access-Control-Allow-Origin: *");
include("simplehtmldom/simple_html_dom.php");
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if($_SERVER['REQUEST_METHOD'] == "GET" and (isset($_GET['s']) || isset($_GET['cat']))) { 
    if(isset($_GET['s'])){
        if(isset($_GET['pageNo'])) {
            $pageNo = (string) @$_GET['pageNo'];
            $searchTerm = (string) @$_GET['s'];
            $stripped = trim(preg_replace('/\s+/', '+', $searchTerm));
            $searchlink = 'https://getintopc.com/page/'. $pageNo . '/?s=' . $stripped;
        } else {
            $searchTerm = (string) @$_GET['s'];
            $stripped = trim(preg_replace('/\s+/', '+', $searchTerm));
            $searchlink = 'https://getintopc.com/?s=' . $stripped;
        }
    } else if (isset($_GET['cat'])) {
        if(isset($_GET['pageNo'])) {
            $pageNo = (string) @$_GET['pageNo'];
            $catTerm = (string) @$_GET['cat'];
            if($catTerm == 'tutorials'){
                $searchlink = 'https://getintopc.com/' . $catTerm . '/page/' . $pageNo .'/';
            } else {
                $searchlink = 'https://getintopc.com/softwares/' . $catTerm . '/page/' . $pageNo .'/';
            }
        } else {
            $catTerm = (string) @$_GET['cat'];
            if($catTerm == 'tutorials'){
                $searchlink = 'https://getintopc.com/' . $catTerm;
            } else {
                $searchlink = 'https://getintopc.com/softwares/' . $catTerm;
            }
        }
    }
        
        // $searchData = file_get_contents($searchlink);

        $cookie = tempnam ("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt( $ch, CURLOPT_URL, $searchlink );
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
        $searchData = curl_exec( $ch );
        $response = curl_getinfo( $ch );
    
        curl_close ( $ch );

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

if($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['link'])) {

	$link = (string) @$_GET['link'];

	// $link = (string) @$_GET['link'];

	if (!$link == '') {
        getLink($link);

	}

}

function getLink($link){
    // $details = file_get_contents($link);

    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $link );
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
    $details = curl_exec( $ch );
    $response = curl_getinfo( $ch );

    curl_close ( $ch );
	
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