<?php
  class SectionsController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Sections');
      }
      
      public function getSectionsList(){
         return $this->model->getSectionsList();
      }
      
      
      
      public function deleteSection($sid){
         $result = $this->model->deleteSection($sid);
         
         if($result['success'] === false){
             Bufer::set(
                        array(
                               'errors'=>$result['errors'] 
                        )
                );
         }
      }
  }
  
  $controller = new SectionsController;
  
  Bufer::set(
            array(
                'sectionsList'=>array(
                                     'data'=>$controller->model->getSectionsList(),
                                     'paginate'=>$controller->paginate())
            )
  );
  
  if(isset($_GET['deletesection'])){
      $sid = (int)$_GET['deletesection'];
      $result = $controller->deleteSection($sid);
      
      if($result['success'] === false){
          Bufer::set(
                     array(
                           'errors'=>$errors,
                           'sectionsList'=>array(
                                     'data'=>$controller->model->getSectionsList(),
                                     'paginate'=>$controller->paginate())
                           )   
          );
      }
      else{
          header("location: ".Route::getUrl('?mode=admin&route=sections'));
      }
  }
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/sections.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>