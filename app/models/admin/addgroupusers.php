<?php
  class AddgroupusersModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function addGroupUsers($data){
          $sql = "insert into access (aname) VALUES ('".$data['aname']."')";  
          $result = $this->sqlQuery($sql);
          
          if($result === false){
              return array(
                           'success'=>false,
                           'error'=>'Произошла ошибка при создании группы пользователей'
              );
          }
          else{
             return array('success'=>true);
          }
      }
      
      public function checkName($aname){
          $sql = "select * from access where aname='".$aname."'";

          $result = $this->returnData($sql);
          
          if($result === false){
              return array(
                            'success'=>true
              ); 
          }
          else{
              return array(
                           'success'=>false,
                           'error'=>array('Граппа с таким наименованием уже существует') 
              );
          }
      }
  }