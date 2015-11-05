<?php
  class EdithtmlsnippetsModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function getDataHtmlSnippet($hsid){
          $sql = "select * from htmlsnippets where hsid = ".$hsid;
          
          $result = $this->sqlQuery($sql);
          
          if($result->num_rows > 0){
             $data = $result->fetch_assoc();
             
             return array(
                          'success'=>true,
                          'data'=>$data
             );
          }
          else{
             return array(
                          'success'=>false,
                          'error'=>array('Данный снипет не найден')
             );
          }
      }
      
      public function checkHsname($data){
          $sql = "select * from htmlsnippets where hsname='".$data['hsname']."' and hsid !=".$data['hsid'];
          
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
      
      public function saveSnippet($data){
          extract($data);
          
          $sql = "update htmlsnippets set 
                        hsname = '".$hsname."',
                        hsdescription = '".$hsdescription."',
                        code = '".$code."'
                        where hsid = ".$hsid."
                 ";
                 
          $result = $this->updateData($sql);
          
          if($result === false){
              return array(
                           'success'=>false,
                           'error'=>array('Произошла ошибка при обновлении сниппета') 
              );
          }       
          else{
             return array('success'=>true);
          }
      }
            
  }