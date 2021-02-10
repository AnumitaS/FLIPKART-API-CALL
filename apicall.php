<?php
# This file will fetch data from flipkart affiliate API 
#Author: Anumita Singha Roy.
#website: https://www.indoxer.com
#License: You can use , modify, distribute this code. But do not change the author name. You can apply your name at your edited lines.

// creating api calling function

error_reporting(0); // By this line we are hiding all errors, if you want to see errors, then put a # at the beginning of this line.
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
      'APIKEY: put your API key here',
      'Content-Type: application/json',
      'Fk-Affiliate-Id:write your affiliate ID here',
      'Fk-Affiliate-Token:write your affiliate token id here'
	  
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}




?>
