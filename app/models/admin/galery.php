<?php
  class GaleryModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function getListGelery(){
            $sql = "select count(gi.giid) as count, g.*  
                    from galery g
                    left join gimages gi on gi.glid = g.glid group by g.glid order by glid desc
                    ";
                    
            $result = $this->getPaginateData($sql, 25, CUR_PAGE);
            
            if($result === false){
                return array('success'=>false, 'error'=>'Ни одной галереи не обноружено');
            }
            else{
                return array(
                            'success'=>true, 
                            'data'=>$result, 
                            'paginate'=>$this->paginate() 
                            );
            } 
             
      }
      
      public function deleteGalery($glid){
         $sql = array(
             "delete from galery where glid=".$glid,
             "delete from gimages where glid=".$glid
         );
         
         $multiSql = implode(";", $sql);
         
        
         
         
         $result = $this->multiQuery($multiSql);
         
         if($result !== false){
             return array('success'=>true);
         }
         else{
             return array('success'=>false, 'error'=>array('Ошибка при удалени галереи из БД'));
         }
         
      }
  }