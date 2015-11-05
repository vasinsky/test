<?php
  class HtmlsnippetsModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function getHtmlSnippets(){
          $sql = "select * from htmlsnippets order by hsid desc";
          
          $result = $this->getPaginateData($sql, 25, CUR_PAGE);
          
          return $result;
      }
      
      public function deleteHtmlSnippet($hsid){
         $sql = "delete from htmlsnippets where hsid=".$hsid;
         
         $result= $this->deleteData($sql);
         
         if(!$result){
            return array(
                         'success'=>false,
                         'error'=>'Произошла ошибка при удалении сниппета'
            );
         }
         else{
            return array('success'=>true);
         }
      }
  }