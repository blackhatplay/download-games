
<?php
 header("Access-Control-Allow-Origin: *");
include("simplehtmldom/simple_html_dom.php");

if(($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['link'])) || ($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['link']))) {
	if(isset($_POST['link'])) {
		$link = (string) @$_POST['link'];
	}
	if(isset($_GET['link'])) {
		$link = (string) @$_GET['link'];
	}

	if (!$link == '') {
		$details = file_get_contents($link);
	
		$details = str_get_html($details);

		// $form = $details->find('form');
		$form = $details->find('form[action=http://solvetube.site/file-will-start-automatically-on-this-page-after-few-seconds-installation-video-guide-is-in-progress/]');

		// $mainForm = $form[0]->children();
		$mainForm = $form[0]->find("input");

		foreach($mainForm as $element){
			if($element->name == 'id') {
				$tempId = $element->value;
			}
			if($element->name == 'filename') {
				$tempFilename = $element->value;
			}
		}

		getLink($tempFilename,$tempId);

	}

}

if($_SERVER['REQUEST_METHOD'] == "POST"  and isset($_POST['filename']))
    
    {
		$filename = (string) @$_POST['filename'];
		$id = (string) @$_POST['id'];

		getLink($filename,$id);

// echo $nlen;

}

function getLink($a,$b) {

	$filename = $a;
	$id = $b;
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
$response = file_get_contents('http://solvetube.site/file-will-start-automatically-on-this-page-after-few-seconds-installation-video-guide-is-in-progress/',false,$context);

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

$return = array(
	"link"=>$temp
);

echo json_encode($return);

}


?>