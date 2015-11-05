<?php
  class EditpageModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function getPage($pid){
         $sql = "select * from pages where pid=".$pid;
        
         $result = $this->returnData($sql);
         
         if($result === false){
            $pageData = array(
                          'success'=>false,
                          'errors'=>array(
                                         'error'=>'Не возможно получить данные страницы'
                                         )  
                         );
         } 
         else{
            $pageData = array(
                        'success'=>true,
                        'pageData'=>$result
            );
         }
         
         return $pageData;
      }
      
     public function checkIndexPage($data){
            
          $sql_for_search_index_page = "select * from pages where `name` = '".$data['index']."' and pid !=".$data['pid'];
          
          $result = $this->sqlQuery($sql_for_search_index_page);
          
          if($result->num_rows>0){
              $errors = array('name'=>'Страница с данным псевдонимом уже существует!');  
              Bufer::set(array(
                             'errors'=>$errors,
                             )
                        );    
              
                return array(
                             'success'=>false, 
                             'error'=>$errors
                            );                     
           }     
           else 
                return array('success'=>true);       
     }      
      
      public function savePage($data){
         extract($data);

         $sql = "update pages 
                 set name= '".$index."',
                     title='".$title."',
                     description = '".$description."',
                     keywords = '".$keywords."',
                     created = '".date('Y-m-d H:i:s')."',
                     preview = '".$preview."',
                     content = '".$content."',
                     display= ".$display.",
                     sid = ".$sid."
                     where pid = ".$pid."
                 ";
                 
          $result = $this->updateData($sql);
          
          if(!$result){
             $result = array(
                             'success'=>false,
                             'errors'=>array('error'=>'Произошла ошибка при обновлении данных страницы')
             
                            );
          }
          else{
            $result = array('success'=>true); 
          }
          
          return $result;      
      }
  }
?>