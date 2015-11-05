<?php
 /**
  * @author Vasinsky Igor
  * @email igor.vasinsky@gmail.com
  * @copyright 2013
  *  
  * Класс разбора url, работа с ЧПУ
  */
  class Route{
      const REWRITE = REWRITE;
      const POSTFIX = POSTFIX;
      const MODE = MODE;
      
      public function __construct(){
        
      } 
      
      static public function logout(){
          unset($_SESSION['fw']);
          session_destroy();
          Route::go('/');        
      }
      
      static function postfix(){
         //return (self::POSTFIX === true) ? '.html' : '';
         return '';
      }
      
      static public function getUrl($url,$scheme = 'http'){
          if(self::REWRITE === true){
             $this_url = $url;
             $this_url = explode("?", $this_url);
              
             if(!isset($this_url[1]))
                 return $this_url[0];
             else{
                $this_url = explode("&", $this_url[1]);
                $rewrite_url = array();
                
                foreach($this_url as $k=>$v){                    
                    $temp_this_url = explode("=",$v);
                    
                    if(!in_array($k, array(0,1)))
                        $rewrite_url[] = $temp_this_url[0];
                    
                    $rewrite_url[] = $temp_this_url[1];
                }
                
                if(preg_match("#admin/#ius",implode("/",$rewrite_url))){
                   return $scheme.'://'.$_SERVER['HTTP_HOST'].'/'.implode("/",$rewrite_url);
                }
                else
                   return $scheme.'://'.$_SERVER['HTTP_HOST'].'/'.implode("/",$rewrite_url).Route::postfix();
             }
             
             return $this_url[1];
          } 
          else
            return '/'.$url;
      }  
      
      static public function status404(){
          header("HTTP/1.0 404 Not Found");
          header("HTTP/1.1 404 Not Found");
          header("Status: 404 Not Found");
          exit;        
      }   
      
      static public function go($url){
        echo '<script type="text/javascript">location.href="'.Route::getUrl($url).'"</script>';
      }
      
      static public function isAdmin(){
         return isset($_SESSION['fw'][INDEX_SESSION_ADMIN]) ? $_SESSION['fw'][INDEX_SESSION_ADMIN] : false;
      }  
      
      static public function noAnchor($url){
          $temp_url = explode('#', $url);
          
          return $temp_url[0];  
      }
      
      static public function getUrlnoAnchor($url){
         self::noAnchor($url);
         return self::getUrl($url);
      }
      
      static function getPageContent($name){
         $model = new BaseModel;
         $result = $model->returnPageData($name);

         if($result === false)
             return 'ERROR DATA CONTENT';
         else
             return $result[0]['content']; 
      }
      
      static function getPageMeta($name){
         $model = new BaseModel;
         $result = $model->returnPageData($name);

         if($result === false)
             return 'ERROR DATA META';
         else{
             $meta = array(
                            'title'=>$result[0]['title'],
                            'keywords'=>$result[0]['keywords'],
                            'description'=>$result[0]['description']
             );
             return $meta; 
         }
      }      
  }
?>