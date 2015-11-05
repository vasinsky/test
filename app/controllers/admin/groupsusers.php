<?php
  class GroupsusersController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Groupsusers');
      }
      
      public function getGroupsUsersList(){
          return $this->model->getGroupsUsersList();
      }
      
      public function deleteGroupUsers($acid){
          $result = $this->model->deleteGroupUsers($acid);
          
          if(isset($result['error']))
              Bufer::add(array('errors'=>$result['error']));
      }
  }
  
  $controller = new GroupsusersController;
  
  $acid = isset($_GET['deletegroupusers']) ? (int)$_GET['deletegroupusers'] : false;
  
  if($acid !== false){
      $result = $controller->deleteGroupUsers($acid);
      
      if($result['success'] === false){
          Bufer::add(array('errors'=>$result['error']));
      }
      else{
          header("location:".Route::getUrl('?mode=admin&route=groupsusers'));
      }
  }
  
  Bufer::set(array('groupsUsersList'=>$controller->getGroupsUsersList()));
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/groupsusers.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   

 
?>