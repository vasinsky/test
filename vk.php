<?php
ini_set("display_errors", 1);
error_reporting(-1);
//phpinfo();
class Model_Vk {

    private $access_token;
    private $url = "https://api.vk.com/method/";

    public function __construct($access_token) {

        $this->access_token = $access_token;
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

$client_id = 5134949;
$user_id = 73170478;
$group_id = 88383868;

$url = 'http://oauth.vk.com/authorize?client_id={YOUR_CLIENT_ID}&scope=wall,offline&redirect_uri=http://oauth.vk.com/blank.html&response_type=token';
$url = strtr($url, array('{YOUR_CLIENT_ID}'=>$client_id));

//echo $url;

//exit;
$access_token = '1017e27c28ad0886ea2e2fd6730954e42b5ee1d4e89830e6434baf776e92087b147a22d5e45fd37851fa1';
//echo $url;

//exit;

//Инициализируем класс
$vk = new Model_Vk($access_token);


$paramsApi = array(
   "access_token"=>$access_token,
   "user_id"=>$user_id
); 


$params = array(
    "owner_id" => -88383868,//$user_id,
    "message" => "Всем тестам мега тест!!!"
);



$post = $vk->method("wall.post", $params);

/*
$params = array(
    "owner_id" => $user_id,
    "post_id" => 3876
);
*/

//$params = array(
//    "owner_id"=>"-".$group_id,
    
//);
//$post = $vk->method("wall.get", $params);
//$post = $vk->method("wall.get", $params);

//$post = $vk->method("wall.pin", $params);

//echo '<pre>' . print_r($post,1) . '</pre>';

//https://vk.com/dev/wall.get
//https://vk.com/editapp?id=5134949&section=options

