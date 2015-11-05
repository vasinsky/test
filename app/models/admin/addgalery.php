<?php
  class AddgaleryModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function addGalery($data){
         $sql = "insert into galery (gname, gindex, gdescription, image) values
                ('".$data['gname']."',
                 '".$data['gindex']."',
                 '".$data['gdescription']."',
                 '".$data['image']."'
                )
                ";
         $result = $this->sqlQuery($sql);  
         
         if($result === false){
            return array('success'=>false, 'error'=>'Ошибка запроса при создании галереи');
         }      
         else{
            return array('success'=>true, 'gid'=>$this->last_id());
         }
                
      }
      
      public function deleteGalery($gid){
          $sql = "delete from galery where gid =".$gid;
          
          $result = $this->deleteData($sql);
          
          if($result === false){
              return array('success'=>false, 'error'=>'Ошибка при удалении галереи');
          }
          else{
              return array('success'=>true);
          }
      }
      
      public function addImagesOnDb($gid, $images){
          $sql = "insert into gimages (glid, pic, thumb, giname,gidescription) values ";
          
          foreach($images as $k=>$image){
             $pic = $image['pic'];
             $thumb = $image['thumb'];
             
             $inserts[] = "('".$gid."', '".$pic."', '".$thumb."', ' ', ' ')";
          }
          
          $sql = $sql.implode(",", $inserts);
          
          $result = $this->sqlQuery($sql);
          
          if($result === false){
              return array('success'=>false, 'error'=>'Ошибка при записи инвормации о изображениях в бд');
          }
          else{
              return array('success'=>true);
          }

      }
  }
/**  
      CREATE TABLE `galery` (
      `glid` int(11) NOT NULL AUTO_INCREMENT,
      `gname` varchar(255) DEFAULT NULL,
      `gindex` varchar(255) DEFAULT NULL,
      `gdescription` text,
      `image` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`glid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
    */  