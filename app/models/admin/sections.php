<?php
  class SectionsModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function getSectionsList(){
          $sql = "select count(p.pid) as countpages, s.* from section s left join pages p on p.sid = s.sid group by s.sid";
          $result = $this->getPaginateData($sql, 25, CUR_PAGE);
          
          return $result;
      }
      public function deleteSectionPages($sid){
          $sql = "update pages set display=3 where sid=".$sid;
          return $this->updateData($sql);
      }
      
      public function deleteSection($sid){
          $sql = "delete from section where sid=".$sid;
          
          $result = $this->deleteData($sql);
        
          if($result !==false){
              $deleteSectionPages = $this->deleteSectionPages($sid);
              
              if($deleteSectionPages === false){
                  return array(
                             'success'=>false, 
                             'errors'=>array('Раздел удалён, при попытке удаления страниц раздела - произошла ошибка'));
              }
              else{
                  return array('success'=>true);
              }
          }
          else{
             return array(
                          'success'=>false, 
                          'errors'=>array('Не могу удалить раздел, страницы не будут удалены'));
         }
      }
  }