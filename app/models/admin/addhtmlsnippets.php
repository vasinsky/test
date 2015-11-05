<?php
  class AddhtmlsnippetsModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function checkHsname($data){
          $sql = "select * from htmlsnippets where hsname='".$data['hsname']."'";
          
          $result = $this->sqlQuery($sql);
            
          if($result->num_rows>0){
                return array(
                             'success'=>false, 
                             'error'=>array('Сниппет с таким названием уже существует!')
                            );                     
           }     
           else 
                return array('success'=>true);    
      }
      
      public function addSnippet($data){
         
         extract($data);
         
         $sql = "insert into htmlsnippets (hsname, hsdescription, code) 
                 values ('".$hsname."', '".$hsdescription."', '".$hscode."')";
                 
         $result = $this->sqlQuery($sql);
         
         if(!$result){
             return array(
                          'success'=>false,
                          'error'=>array('Произошла ошибка при создании сниппета')
                         );
         }        
         else{
            return array('success'=>true);
         }
      }
  }