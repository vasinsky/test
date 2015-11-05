<?php
   class RulesUrl{
      static public $dataRules=array();
      
      static function addRules($mode,$route){
         self::$dataRules['mode']  = $mode;
         self::$dataRules['route'] = $route; 
         self::setRules($mode, $route);
      }
      
      static function getRules(){
          foreach($_GET as $m=>$r){
             if(in_array($m, array('public', 'admin'))){
                 self::setRules($m, $r);
                 unset($_GET[$m]);
             }
          }
      }
      
      static function setRules($mode, $route){
           $get = explode("/",mb_substr($_SERVER['REQUEST_URI'],1));  
           $rules = self::$dataRules;

           if($mode == 'admin'){
               $_GET['mode'] =  isset(self::$dataRules['mode']) ? self::$dataRules['mode'] : $mode;
               $_GET['route'] =  isset(self::$dataRules['route']) ? self::$dataRules['route'] : $route; 
               
               foreach($get as $k=>$value){
                   if($k>1){
                       if($k%2 == 0)
                           $_GET[$value] = $get[$k+1];
                   }
               }
           }
           elseif($mode == 'public'){
              $count = count($get);
              foreach($get as $k=>$v){
                    if(isset($rules['route'][$k]))
                    $_GET[$rules['route'][$k]] = ($k == ($count-1)) ? strtr($v, array('.html'=>'')) : $v;
              }
           }
      }
      
   }
   
   
?>