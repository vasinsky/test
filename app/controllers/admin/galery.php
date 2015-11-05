<?php
  class GaleryController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Galery');
      }
      
      public function getListGalery(){
         $listGalery = $this->model->getListGelery();

         if($listGalery['success'] === false){
            Bufer::set(array('errors'=>array($listGalery['error'])));
         }
         else{
            Bufer::set(
                    array(
                        'galeryList'=>$listGalery['data'],
                        'paginate'=>!empty($listGalery['paginate']) ? $listGalery['paginate'] : false
                    )
            );
         }
         
         //echo '<pre>' . print_r(bufer::getData(), 1) . '<pre>';
      }
      
      public function deleteAllFoldersGalery($glid){
            $pics = glob('uploads/images/galery/'.$glid.'/pic/*.*');
             
             if(!empty($pics)){
                foreach($pics as $pic){
                    if(is_file($pic)){
                        @unlink($pic);
                    }
                }
             }
             
             $thumbs = glob('uploads/images/galery/'.$glid.'/thumb/*.*');
             
             if(!empty($thumbs)){
                foreach($thumbs as $thumb){
                    if(is_file($thumb)){
                        @unlink($thumb);
                    }
                }
             }         
             
             @unlink('uploads/images/galery/'.$glid.'/index.html');
             
             if(!@rmdir('uploads/images/galery/'.$glid.'/pic')){
                $errors[] = 'Ошибка удаления папки с картинками галереи';
             }
    
             if(!@rmdir('uploads/images/galery/'.$glid.'/thumb')){
                $errors[] = 'Ошибка удаления папки с превью изображений галереи';
             }
             
             if(!@rmdir('uploads/images/galery/'.$glid)){
                $errors[] = 'Ошибка удаления папки галереи';
             }  
             
             if(isset($errors)){
                return array('success'=>false, 'error'=>array($errors));
             }      
             else{
                return array('success'=>true);
             }
      }
      
      public function deleteGalery($glid){
          return $this->model->deleteGalery($glid);
      }
  }
  
  $controller = new GaleryController;
  $controller->getListGalery();
  
  if(isset($_GET['deletegalery'])){
     $glid = (int)$_GET['deletegalery'];
     
     $deleteFoldersGalery = $controller->deleteAllFoldersGalery($glid);
     
     $result = $controller->deleteGalery($glid);
     
     
     
     if($result['success'] === false){
        Bufer::add(array('errors'=>$result['error']));
     }
     else{
        header('location: '.Route::getUrl('?mode=admin&route=galery'));
     }
  }
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/galery.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>