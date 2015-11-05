<?php
  class EditsectionModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function getSectionData($sid){
         $sql = "select * from section where sid=".$sid;
         
         $result = $this->sqlQuery($sql)->fetch_assoc();
         
         return $result;
      }
      
      public function checkIndexSection($data){
         $sindex = $data['sindex'];
         $sid = (int)$data['sid'];
         
         $sql = "select count(*) as count from section where sindex='".$this->escape($sindex)."' and sid != ".$sid;
         
         $result = $this->returnData($sql);
         
         if($result[0]['count'] == 0){
            return array('success'=>true);
         }
         else{
            return array('success'=>false, 'errors'=>array('Раздел с таким псевдонимом уже существует'));
         }
    }
    
    
    public function Savesection($data){
        $sid = (int)$data['sid'];
        $sindex = $this->escape($data['sindex']);
        $sname = $this->escape($data['sname']);
        $sdescription = $this->escape($data['sdescription']);
        
        $sql = "insert into section (sindex,sname,sdescription) values ('".$sindex."', '".$sname."', '".$sdescription."')";
        
        $sql = "update section set 
                       sindex = '".$sindex."',
                       sname = '".$sname."',
                       sdescription = '".$sdescription."'
                       where sid = ".$sid;
        
        $result = $this->updateData($sql);
        
        if($result !== false){
            header("location: ".Route::getUrl('?mode=admin&route=editsection&sid='.$sid));
        }
        else{
            return array('success'=>false, 'errors'=>array('Произошла ошибка при редактировании раздела'));
        }
    }
  }