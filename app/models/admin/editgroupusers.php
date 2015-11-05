<?php
  class EditgroupusersModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function getGroupUsersData($acid){
          $sql = "select * from access where acid=".$acid;
          
          $result = $this->returnData($sql);
          
         if($result === false){
             return array(
                         'success'=>false,   
                         'error'=>array('Не могу получить данные группы пользователей'),
                         'groupUsersData'=>array('acid'=>'','aname'=>'')
             );
         }
         else{
            return array(
                         'success'=>true,
                         'groupUsersData'=>$result[0]
            
            );
         }
      }
      
      public function checkName($data){
          $sql = "select * from access where aname='".$data['aname']."' and acid != ".$data['acid'];

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
      
      public function saveGroupUsers($data){
          $sql = "update access set aname='".$data['aname']."' where acid=".$data['acid'];  
          
          $result = $this->updateData($sql);
          
          if($result === false){
              return array(
                           'success'=>false,
                           'error'=>'Произошла ошибка при обновлении группы' 
              );
          }  
          else{
             return array('success'=>true);
          }
      }
  }