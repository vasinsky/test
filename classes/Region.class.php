<?php
  /**
   * Класс получения списка регионов
   */
   
  class Region extends BaseModel{
      public function __construct(){
         parent::__construct();  
      }
           
      public function getRegions(){
         $sql = "select * from region";
         
         $result = $this->returnData($sql);
         
         if($result === false){
             return false;
         }
         else{
            return $result;
         }
      } 
      
      
    /**
    * Установка региона
    */       
    public function setRegion(){    
        $_SESSION['reg'] = isset($_SESSION['reg']) ? $_SESSION['reg'] : 'All';
        
        if(isset($_GET['set_reg'])){
        
         $_SESSION['reg'] = $_GET['set_reg'];
         Route::go('?mode=public&route='.ROUTE);
        }         
    }
  } 
  
?>