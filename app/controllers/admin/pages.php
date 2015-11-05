<?php
  class PagesController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Pages'); 
          //RulesUrl::addRules('amdin','pages');
      }

      public function getListSections(){
         $model = $this->model;
         /**
          * Список разделов
          */ 
         $result = $model->getListSections();
         
         return $result;
      }      

      public function getListPages(){
         $model = $this->model;

         /**
          * Список страниц
          */ 
         $result = $model->getListPages();
         
         if(count($result)>0){
             $listPages = $result;
         }
         else{
            $listPages = null;
         }
         return $listPages;

      }
      
      public function getAccessList(){
        return $this->model->getAccessList();
      }
      
      public function changeAccess($pid,$acid){
        return $this->model->changeAccess($pid,$acid);
      }      
            
      public function deletePage($pid){
         $result = $this->model->deletePage($pid);
         
         return $result;
      }
  }
  
  $controller = new PagesController;
  
  if(isset($_GET['deletepage'])){
     $delete = $controller->deletePage((int)$_GET['deletepage']);
     
     if($delete === false){
          Bufer::set(array(
                           'errors'=>array('Ошибка при удалении страницы'), 
                           'accessList'=>$controller->getAccessList(),
                           'listPages'=>array(
                                              'data'=>$controller->getListPages(),
                                              'paginate'=>$controller->paginate()
                                             ),
                           'listSections'=>$controller->getListSections()
                           )
                     );        
     }
     else{
        header("location: ".Route::getUrl('?mode=admin&route=pages'));
     }
  }
  
  if(isset($_GET['updateAccess'])){
     
     $temp = explode(',', $_GET['updateAccess']);
    
     $pid = (int)$temp[0];
     $acid = (int)$temp[1];
     $result = $controller->changeAccess($pid,$acid);
     
     if($result === false){
        Bufer::set(
                array(
                    'errors'=>array('Произошла ошибка при смене доступа к странице'),
                    'accessList'=>$controller->getAccessList(), 
                    'listPages'=>array(
                                      'data'=>$controller->getListPages(),
                                      'paginate'=>$controller->paginate()
                                     ),
                    'listSections'=>$controller->getListSections()
                )
        );         
     }
     else{
        header("location: ".Route::getUrl('?mode=admin&route=pages'));
     }
  }
  
  Bufer::set(array(
                   'accessList'=>$controller->getAccessList(), 
                   'listPages'=>array(
                                      'data'=>$controller->getListPages(),
                                      'paginate'=>$controller->paginate()
                                     ),
                   'listSections'=>$controller->getListSections()
                   )
             );
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/pages.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');

    

?>