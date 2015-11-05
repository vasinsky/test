<?php
 class IndexModel extends BaseModel{
    public function __construct(){
        parent::__construct();
    }
    
    public function getPageData($route){
      $sql = "select * from pages where name='".$this->mysqli->escape_string($route)."' limit 1";
      $result = $this->mysqli->query($sql);

      if(!$result){
        Files::addtolog(LOG_MYSQLI, $sql.'--->>>'.$this->mysqli->error);
            
        throw new Exception($this->mysqli->error);
      }
      else{
        if($result->num_rows>0){
            $pageData = $result->fetch_assoc();
        }
        else{
           ROUTE::status404();
        }
      }      
      
      return $pageData;      
    }        
 }
?>