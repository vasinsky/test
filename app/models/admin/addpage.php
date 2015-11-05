<?php
  class AddpageModel extends BaseModel{
     public function __construct(){
        parent::__construct();
     }
     
     public function checkIndexPage($data){
            
          $sql_for_search_index_page = "select * from pages where `name` = '".$data['index']."'";
          
          $result = $this->sqlQuery($sql_for_search_index_page);
          
          if($result->num_rows>0){
                $errors = array('name'=>'Страница с данным псевдонимом уже существует!');  

                return array(
                             'success'=>false, 
                             'error'=>$errors
                            );                     
           }     
           else 
                return array('success'=>true);       
     }
     
     public function addPage($data){
            extract($data);               
            
            $sql = "insert into pages (name,title,description,keywords,created,preview, content,display,sid) values 
                   ('".$index."','".$title."','".$description."', '".$keywords."',
                   '".date('Y-m-d H:i:s')."', '".$preview."', '".$content."', '".$display."','".$section."')";      
                   
            $result = $this->sqlQuery($sql);      
                   
            if(!$result){
                return array(
                             'success'=>false, 
                             'error'=>$result->errors
                            ); 
            }       
                   
            else{
                return array('success'=>true);
            }       
     }
  }
?>