<?php
  class EditgaleryModel extends BaseModel{
      public function __construct(){
          parent::__construct();
      }
      
      public function getGaleryData($glid){
          $sql = "select * from galery g left join gimages gi on gi.glid = g.glid and g.glid = ".$glid." order by giid desc";
          
          $result = $this->returnData($sql);
          
          if($result === false){
              return array('success'=>false, 'error'=>'Произошла ошибка во время получения данных галереи!');
          }
          else{
                
                foreach($result as $k=>$v){
                    if(!isset($data['meta'])){
                        $data['meta'] = array(
                                               'glid'=>$v['glid'],
                                               'gname'=>$v['gname'],
                                               'gindex'=>$v['gindex'],
                                               'gdescription'=>$v['gdescription'],
                                               'image'=>$v['image']
                                             ); 
                    }
                    
                    $data['gimages'][] = array(
                                                'giid'=>$v['giid'],
                                                'pic'=>$v['pic'],
                                                'thumb'=>$v['thumb'],
                                                'giname'=>$v['giname'],
                                                'gidescription'=>$v['gidescription']                 
                                              );
                }
                
                return array('success'=>true, 'data'=>$data);
          }
      }
      
      public function EditGaleryFromDb($data){
          $sql = "update galery set 
                 gname = '".$data['gname']."',
                 gindex = '".$data['gindex']."',
                 gdescription = '".$data['gdescription']."'
                 where glid = ".$data['glid']."
          ";
          
          if(isset($_POST['cover'])){
             $sql = strtr($sql, array('where'=>', image = (select pic 
                                                   from gimages
                                                   where giid = '.$_POST['cover'].') where'));
          }
                $sql_meta_images[] = $sql;
          
          if(isset($data['meta_images'])){

             foreach($data['meta_images'] as $giid=>$v){
                $sql_meta_images[] = "update gimages set 
                                      giname = '".$v['giname']."', 
                                      gidescription = '".$v['gidescription']."' 
                                      where giid = ".$giid;
             }
             
          }
          

          $multiSql = implode(";", $sql_meta_images);
        
          $result = $this->multiQuery($multiSql);
                    
          if($result === false){
              return array('success'=>false, 'error'=>'Ошибка при обновлении данных галереи');
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
              return array('success'=>false, 'error'=>'Ошибка при записи информации о изображениях в бд');
          }
          else{
              return array('success'=>true);
          }

      }  
      
      public function deleteImage($giid){
         $sqlData = "select pic, thumb from gimages where giid=".$giid;
         $sqlDelete = "delete from gimages where giid = ".$giid;
         
         $idata = $this->returnData($sqlData);
      
         
         //удаление картинки + превью
         if(isset($idata[0]['pic'])){
            if(!unlink($idata[0]['pic'])) $errors[] = 'Ошибка удаления картинки из папки';
            if(!unlink($idata[0]['thumb'])) $errors[] = 'Ошибка удаления превью картинки из папки';      
         }

         $result = $this->sqlQuery($sqlDelete);
         
         if($result !== false){
            if(isset($errrors))
               return array('success'=>false, 'error'=>$errors);
            else{
               return array('success'=>true); 
            }   
         }
         else{
            return array('success'=>false, 'error'=>array('Произошла ошибка при удалении картинки из БД'));
         }
      }
         
  }
?>                 