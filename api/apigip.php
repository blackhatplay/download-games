
<?php
 header("Access-Control-Allow-Origin: *");
include("simplehtmldom/simple_html_dom.php");
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if(($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['link'])) || ($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['link']))) {
	if(isset($_POST['link'])) {
		$link = (string) @$_POST['link'];
	}
	if(isset($_GET['link'])) {
		$link = (string) @$_GET['link'];
	}

	if (!$link == '') {
		// $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
		// $details = file_get_contents($link,false,$context);

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
		$return = [];
		$mainFormArr = [];

		$form = $details->find('form');
		$i = 0;
		foreach($form as $element) {
			if ($element->method == 'post') {
				$finalLink = $element->action;
				$mainFormArr[$i] = $element;
				$i++;
			}
		 }

		 $i = 0;

			foreach($mainFormArr as $mainForm) {
				$mainForm = $mainForm->find("input");

			foreach($mainForm as $element){
				if($element->name == 'id') {
					$tempId = $element->value;
				}
				if($element->name == 'filename') {
					$tempFilename = $element->value;
				}
			}

			$return[$i] = getLink($tempFilename,$tempId,$finalLink);
			$i++;
		}

		echo json_encode($return);

	}

}

if($_SERVER['REQUEST_METHOD'] == "POST"  and isset($_POST['filename']))
    
    {
		$filename = (string) @$_POST['filename'];
		$id = (string) @$_POST['id'];

		getLink($filename,$id);

// echo $nlen;

}

function getLink($a,$b,$c) {

	$filename = $a;
	$id = $b;
	$finalLink = $c;
$postdata = http_build_query(
		array(
			'id' => $id,
			'filename' => $filename
		)

);

$opts = array('http' =>
		array(

			'method' => 'POST',
			'header' => 'Content-type: application/x-www-form-urlencoded',
			'content' => $postdata
		)
);



global $response;

$context = stream_context_create($opts);
$response = file_get_contents($finalLink,false,$context);

$html = str_get_html($response);

// echo gettype($html);

$script = $html->find('script');

foreach($script as $element){
	if (strpos($element->innertext, $filename) !== false) {
		$wantedText = $element->innertext;
	break;
	}
}


// echo $wantedText;

$start = strpos($wantedText,"http");
$end = strpos($wantedText,"expires")+18;

$nlen = $end - $start;

$temp = substr($wantedText,$start,$nlen);
// echo $filename;

// $return = array(
// 	"link"=>$temp
// );

// echo json_encode($return);

return $temp;

}


?>