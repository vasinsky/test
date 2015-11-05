<?php
  class UsersModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function getUsersList(){
          $sql = "select * from users order by Field(isAdmin, 1,3,2)";
          $result = $this->getPaginateData($sql, 25, CUR_PAGE);
          return $result;
      }
      
      public function getAccessList(){
        $sql = "select * from access";
        $result =$this->returnData($sql);
        return $result;
      }
      
      public function changeAccess($uid,$access){
        $sql = "update users set isAdmin = ".$access." where uid=".$uid;
        return $this->updateData($sql); 
      } 
      
      public function deleteUser($uid){
        $sql = "delete from users where uid=".$uid;
        $result = $this->deleteData($sql);

        if($result === false){
            return array('success'=>false,'error'=>array('Ошибка удаления пользователя'));
        }
        else{
            return array('success'=>true);
        }
      } 
      
  }
