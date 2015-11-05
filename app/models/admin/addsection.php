<?php
  class AddsectionModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function checkIndexSection($data){
         $sindex = $data['sindex'];
         
         $sql = "select count(*) as count from section where sindex='".$this->escape($sindex)."'";
         
         $result = $this->returnData($sql);
         
         if($result[0]['count'] == 0){
            return array('success'=>true);
         }
         else{
            return array('success'=>false, 'errors'=>array('Раздел с таким псевдонимом уже существует'));
         }
    }
    
    public function addsection($data){
        $sindex = $this->escape($data['sindex']);
        $sname = $this->escape($data['sname']);
        $sdescription = $this->escape($data['sdescription']);
        
        $sql = "insert into section (sindex,sname,sdescription) values ('".$sindex."', '".$sname."', '".$sdescription."')";
        
        $result = $this->sqlQuery($sql);
        
        if($result !== false){
            header("location: ".Route::getUrl('?mode=admin&route=sections'));
        }
        else{
            return array('success'=>false, 'errors'=>array('Произошла ошибка при добавлении нового раздела'));
        }
    }
  }