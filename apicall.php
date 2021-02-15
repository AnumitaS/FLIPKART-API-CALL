<?php


//error_reporting(0);
function callAPI($method, $url, $data)
{
   $curl = curl_init();
   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'APIKEY: your API key[your flipkart affiliate token is you api key]',
      'Content-Type: application/json',
      'Fk-Affiliate-Id:affiliate id',
      'Fk-Affiliate-Token:api token'
	  
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}

// calling my affiliate page to get list avaalable category

$url1 = "https://affiliate-api.flipkart.net/affiliate/api/<affiliate id>.json";// if your aff id is 'jaduram' then it is 'jaduram.json'
$x = callAPI("GET", $url1, $data= FALSE);
$j = json_decode($x, true);
//print_r( $j);

//echo $j['title'];


if(!empty($_POST['cat']))
{
	
$categ=$_POST['cat'];
$product_url=$j['apiGroups']['affiliate']['apiListings'][''.$categ.'']['availableVariants']['v1.1.0']['get'];
//echo $product_url;
$x2 = callAPI("GET",$product_url.'&inStock=true', $data = FALSE);
$j2 = json_decode($x2, true);
//print_r($j2);
$products = $j2['products'];
$nextUrl = $j2['nextUrl'];

//anumita change writing present links in previous_link.txt file.
$prev_link = $product_url.'&inStock=true';
$fp = fopen('previous_links.txt','w');
fwrite($fp, $prev_link.PHP_EOL);

//change
echo '<div id="nxt" style="visibility:hidden;">'.$nextUrl.'</div>';


foreach($products as $product){
	$image = $product['productBaseInfoV1']['imageUrls']['200x200'];
	$title = $product['productBaseInfoV1']['title'];
	$price = $product['productBaseInfoV1']['flipkartSellingPrice']['amount'];
	$curr = $product['productBaseInfoV1']['flipkartSellingPrice']['currency'];
	$p_link = $product['productBaseInfoV1']['productUrl'];
	echo '<div style="float:left; width:300px; height:350px;margin:15px; border:1px solid grey; border-radius:6px; text-align: center; padding-top:10px;">';
	echo '<img src="'.$image.'" />';
	echo '<br>'.$title;
	
	echo '<br><br><br><div style="margin:0px auto; width:150px; background-color:#a0a0a0; font-wieht:600;">'.$price.'&nbsp;&nbsp;'.$curr.'</div>';
	echo '<a href="'.$p_link.'" target="_blank">Buy</a>';
	echo '</div>';
	
}

}
elseif(!empty($_POST['nextUrl']))
{

$next = $_POST['nextUrl'];

// reading file
$file = file_get_contents('previous_links.txt');
if(strpos($file, $next)===false)
{

//writing the next url
$fp = fopen('previous_links.txt','a');
fwrite($fp, $next.PHP_EOL);

}

$x2 = callAPI("GET",''.$next.'', $data = FALSE);
$j2 = json_decode($x2, true);
//print_r($j2);
$products = $j2['products'];
$nextUrl = $j2['nextUrl'];
echo '<div id="nxt" style="visibility:hidden;">'.$nextUrl.'</div>';




foreach($products as $product){
	$image = $product['productBaseInfoV1']['imageUrls']['200x200'];
	$title = $product['productBaseInfoV1']['title'];
	$price = $product['productBaseInfoV1']['flipkartSellingPrice']['amount'];
	$curr = $product['productBaseInfoV1']['flipkartSellingPrice']['currency'];
	$p_link = $product['productBaseInfoV1']['productUrl'];
	echo '<div style="float:left; width:300px; height:350px;margin:15px; border:1px solid grey; border-radius:6px; text-align: center; padding-top:10px;">';
	echo '<img src="'.$image.'" />';
	echo '<br>'.$title;
	
	echo '<br><br><br><div style="margin:0px auto; width:150px; background-color:#a0a0a0; font-wieht:600;">'.$price.'&nbsp;&nbsp;'.$curr.'</div>';
	echo '<a href="'.$p_link.'" target="_blank">Buy</a>';
	echo '</div>';
	

}



}
elseif(!empty($_POST['prevUrl']))
{
$v = $_POST['prevUrl'];
$file = 'previous_links.txt';
$lines = file($file);
$prevUrl = $lines[$v-1];
$prevurl = preg_replace("/\s+/", "", $prevUrl);
$nextUrl = $lines[$v];
$nextUrl = preg_replace("/\s+/", "", $nextUrl);

//calling api
$x3 = callAPI("GET",''.$prevurl.'', $data = FALSE);
$j3 = json_decode($x3, true);
//print_r($j2);
$products = $j3['products'];

echo '<div id="nxt" style="visibility:hidden;">'.$nextUrl.'</div>';




foreach($products as $product){
	$image = $product['productBaseInfoV1']['imageUrls']['200x200'];
	$title = $product['productBaseInfoV1']['title'];
	$price = $product['productBaseInfoV1']['flipkartSellingPrice']['amount'];
	$curr = $product['productBaseInfoV1']['flipkartSellingPrice']['currency'];
	$p_link = $product['productBaseInfoV1']['productUrl'];
	echo '<div style="float:left; width:300px; height:350px;margin:15px; border:1px solid grey; border-radius:6px; text-align: center; padding-top:10px;">';
	echo '<img src="'.$image.'" />';
	echo '<br>'.$title;
	
	echo '<br><br><br><div style="margin:0px auto; width:150px; background-color:#a0a0a0; font-wieht:600;">'.$price.'&nbsp;&nbsp;'.$curr.'</div>';
	echo '<a href="'.$p_link.'" target="_blank">Buy</a>';
	echo '</div>';
	

}

}

else
{
// getting the list of category from my affiliate page call at the beginning

$list = $j['apiGroups']['affiliate']['apiListings'];

    echo "<option value=''>Select</option>";
foreach($list as $k=>$v){

	echo '<option value="'.$k.'">'.str_replace('_', ' ', $k).'</option>';
	
}	
	
}

?>

