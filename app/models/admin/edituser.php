<?php
  class EdituserModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }

      public function getAccessList(){
        $sql = "select * from access";
        $result =$this->returnData($sql);
        return $result;
      }
      
      public function getUserData($uid){
         $sql = "select * from users where uid=".$uid;
         $result = $this->returnData($sql);
         
         return $result;
      }
      
      public function saveUser($data){
        $sql = "update users set 
                login = '".$data['login']."',
                password='".$data['password']."',
                email = '".$data['email']."',
                isadmin = ".$data['access']." 
                where uid = ".$data['uid']."
               ";
               
        $result = $this->updateData($sql);
        
        if($result === false){
            return array(
                         'success'=>false, 
                         'errors'=>array('Не могу обновить данные пользователя')
                         );
        }       
        else{
            return array('success'=>true);    
        }
      }
  }