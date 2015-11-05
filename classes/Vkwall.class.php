<?php
  class Vkwall{
      private $access_token;
      private $url = "https://api.vk.com/method/";
      private $client_id = 5134949;
      private $client_secret = "kAEZgiWVd3nI8feUoICr";

      public function __construct() {
          $url = "https://oauth.vk.com/access_token?client_id={$this->client_id}&client_secret={$this->client_secret}&grant_type=client_credentials";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL,$url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          $output = curl_exec($ch);
          curl_close($ch);
          $obj = json_decode($output);
          $this->access_token = $obj['access_token'];            
      }  
      
      
      
      public function method($method, $params = null) {
          $p = "";
          if( $params && is_array($params) ) {
              foreach($params as $key => $param) {
                  $p .= ($p == "" ? "" : "&") . $key . "=" . urlencode($param);
              }
          }
          $response = file_get_contents($this->url . $method . "?" . ($p ? $p . "&" : "") . "access_token=" . $this->access_token);

          if( $response ) {
              return json_decode($response);
          }
          return false;
      }  
  } 

?>