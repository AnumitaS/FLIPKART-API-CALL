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
      'APIKEY: 2a983119bdb241c99be08de6aa60cf92',
      'Content-Type: application/json',
	  'Fk-Affiliate-Id:samzonedis',
	  'Fk-Affiliate-Token:2a983119bdb241c99be08de6aa60cf92'
	  
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}

$url1 = "https://affiliate-api.flipkart.net/affiliate/api/samzonedis.json";
$x = callAPI("GET", $url1, $data= FALSE);
$j = json_decode($x, true);
//print_r( $j);

//echo $j['title'];


if(!empty($_POST['cat']))
{
$categ=$_POST['cat'];
$product_url=$j['apiGroups']['affiliate']['apiListings'][''.$categ.'']['availableVariants']['v1.1.0']['get'];
//echo $product_url;
$x2 = CallAPI("GET",$product_url.'&inStock=true', $data = FALSE);
$j2 = json_decode($x2, true);
//print_r($j2);
$products = $j2['products'];
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
$list = $j['apiGroups']['affiliate']['apiListings'];

    echo "<option value=''>Select</option>";
foreach($list as $k=>$v){

	echo '<option value="'.$k.'">'.str_replace('_', ' ', $k).'</option>';
	
}	
	
}

?>


