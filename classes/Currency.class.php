<?php
  /**
   * Класс получения списка валют и коэфициента
   */
   
  class Currency extends BaseModel{
      public function __construct(){
         parent::__construct();  
      }
      
      public function getCurrency(){
         $sql = "select * from currency";
         
         $result = $this->returnData($sql);
         
         if($result === false){
             return false;
         }
         else{
            return $result;
         }
      }
      
    /**
    * Установка валюты
    */       
    public function setCurr(){    
        $_SESSION['curr'] = isset($_SESSION['curr']) ? $_SESSION['curr'] : 'USD';

        if(isset($_GET['set_curr'])){
        
         $_SESSION['curr'] = $_GET['set_curr'];

         Route::go('?mode=public&route='.ROUTE);
         

        }         
    }       
  } 
  
?>