
<?php
/*
  Project: PHP Coursera web scraping
    Author: Patrick Casey
    Version: 1.0
*/
require_once( __DIR__ . '/file_scrap_api.php' );

$link=$_POST['title'];

$stock = file_get_html($link);

// Get all links loaeded in given URL

$mixlink_value = $stock->find('div.rc-CollectionItem-wrapper');
$subcategory =array();
if ($mixlink_value!=NULL || count($mixlink_value)>0){
  for ($i=0;$i<count($mixlink_value);$i++){ 
      if($mixlink_value[$i]->find('a',0)){
      	$subcategory[$i]=$mixlink_value[$i]->find('a',0)->href;
      	if(strpos($subcategory[$i],'https')!==0){
      		$subcategory[$i]='https://www.coursera.org'.$subcategory[$i];
      	}
		$subcategory[$i]=str_replace('\'', '', $subcategory[$i]);
      }
  }
}

// Fetch specific data(category, title, author, enroll, rating) for all above links

$res=array(array());
for ($i=0;$i<count($subcategory);$i++){
	$res[$i][0]=$subcategory[$i];
	$substock = file_get_html($subcategory[$i]);	
	if($substock->find('#main div[aria-label=breadcrumbs] a',2)){
		$category = $substock->find('#main div[aria-label=breadcrumbs] a',2)->text();
		$res[$i][1]=$category;
	}
	if($substock->find('#main .Banner .banner-title',0)){
		$title = $substock->find('#main .Banner .banner-title',0)->text();
		$res[$i][2]=$title;
	}else if($substock->find('#main div[data-e2e=banner] h1',0)){
		$title = $substock->find('#main div[data-e2e=banner] h1',0)->text();
		$res[$i][2]=$title;
	}	
	if($substock->find('#main .rc-InstructorListSection .Instructors .instructor-name',0)){
		$author = $substock->find('#main .rc-InstructorListSection .Instructors .instructor-name',0)->text();
		$res[$i][3]=$author;
	}
	if($substock->find('#main .Banner .rc-ProductMetrics span span',0)){
		$enroll = $substock->find('#main .Banner .rc-ProductMetrics span span',0)->text();
		$res[$i][4]=$enroll;
	}
	if($substock->find('#main .Banner .XDPRating span[data-test=ratings-count-without-asterisks] span',0)){
		$rating = $substock->find('#main .Banner .XDPRating span[data-test=ratings-count-without-asterisks] span',0)->text();
		$rating = explode(" ", $rating)[0]; //[0]
		$res[$i][5]=$rating;
	}else{
		if(count($substock->find('#main div[data-e2e=banner] span'))>0){
		 	$possiblerating=$substock->find('#main div[data-e2e=banner] span');
		 	for ($j=0;$j<count($possiblerating);$j++){
		 		$rating = $substock->find('#main div[data-e2e=banner] span',$j)->text();
		 		if (strpos($rating, 'rating') !== false){
		 			$mixstring = explode(" ", $rating);
		 			if($mixstring[2]){
						$enroll=preg_replace( "/<br>|\n|\r/", "", $mixstring[2]);
						$res[$i][4]=$enroll;
					}
					if(strpos($rating,'ratings')){
						$res[$i][5]=$mixstring[0];
					}
					break;
		 		}
		 	}
		}
	}
}

// Open a file in write mode ('w') with the request category name
$temp=explode('/',$link);
$filename=$temp[count($temp)-1].'.csv';
$fp = fopen($filename, 'w');
  
// Loop through file pointer and a line
foreach ($res as $fields) {
    fputcsv($fp, $fields);
}
fclose($fp);

echo json_encode($res); 

?>