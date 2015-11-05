<?php
  class UsersController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Users');
      }
      
      public function getUserList(){
         return $this->model->getUserList();
      }
      
      public function getAccessList(){
        return $this->model->getAccessList();
      }
      
      public function changeAccess($uid,$access){
        return $this->model->changeAccess($uid,$access);
      }
      
      public function deleteUser($uid){
         $result = $this->model->deleteUser($uid);
         if($result['success'] === true){
             header("location:".Route::getUrl('?mode=admin&route=users'));
         }
         else{
            Bufer::add(array('errors'=>$result['error']));
         }
      }
  }
  
  $controller = new UsersController;
  
   Bufer::set(
            array(
                'usersList'=>array(
                                     'data'=>$controller->model->getUsersList(),
                                     'paginate'=>$controller->paginate()
                ),
                'accessList'=>$controller->getAccessList()
            )
  ); 
  
  if(isset($_GET['updateAccess'])){
     
     $temp = explode(',', $_GET['updateAccess']);
    
     $uid = (int)$temp[0];
     $access = (int)$temp[1];
     $result = $controller->changeAccess($uid,$access);
     
     if($result === false){
        Bufer::set(
                array(
                    'errors'=>array('Произошла ошибка при смене прав пользователя'),
                    'usersList'=>array(
                                         'data'=>$controller->model->getUsersList(),
                                         'paginate'=>$controller->paginate()
                    ),
                    'accessList'=>$controller->getAccessList()
                )
        );         
     }
     else{
        header("location: ".Route::getUrl('?mode=admin&route=users'));
     }
  }
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/users.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   

  if(isset($_GET['deleteuser'])){
     $uid = (int)$_GET['deleteuser'];
     $controller->deleteUser($uid);
  }
?>