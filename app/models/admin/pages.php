<?php
    class PagesModel extends BaseModel{
        public function __construct(){
            parent::__construct();
        }
        
        public function deletePage($pid){
            $sql = "update pages set display=3 where pid=".(int)$pid;
            $result = $this->deleteData($sql);
            
            return $result;
        }

        public function getAccessList(){
            $sql = "select * from access";
            $result =$this->returnData($sql);
            return $result;
        }
         
      public function changeAccess($pid,$acid){
        $sql = "update pages set acid = ".$acid." where pid=".$pid;
        return $this->updateData($sql); 
      }           
              
        public function getListPages(){
            return $this->getPaginateData("select * 
                                    from pages p
                                    left join section s on p.sid = s.sid where p.display < 3 order by p.sid , pid DESC
                                    ", 25,CUR_PAGE);
        }
        
        public function getListSections(){
            $result = $this->sqlQuery("select * from section");
            
             if($result->num_rows>0){
                while($row = $result->fetch_assoc()){
                    $listSections[] = $row;
                }
             }
             else{
                $listSections = array(
                                  array(
                                        'sid'=>1,
                                        'index'=>'main',
                                        'name'=>'основной',
                                        'description'=>'Основной раздел'
                                        )  
                );
             }
             
             return $listSections;                 
        }    
    }
?>