<?php
  class GroupsusersModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function getGroupsUsersList(){
          $sql = "select * from access";
          
          $result = $this->returnData($sql);
          
          return $result;
      }
      
      public function deleteGroupUsers($acid){
         $sql = "delete from access where acid=".$acid;
         
         $result = $this->deleteData($sql);
         
         if($result === false){
              return array('success'=>false, 'error'=>'Произошла ошибка удаления группы');
         }
         else{
              return array('success'=>true);
         }
      }
  }