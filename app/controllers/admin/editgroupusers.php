<?php
  class EditgroupusersController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Editgroupusers');
      }
      
      public function renderForm(){
          $acid = isset($_GET['acid']) ? (int)$_GET['acid'] : 0;
        
          $groupUsersData = $this->model->getGroupUsersData($acid);
        
          //echo '<pre>' . print_r($groupUsersData, 1) . '</pre>';
        
          $form = new HTMLForm;
          $form->open('editgroupusers', '', 'POST');
          
          $form->setInput('aname')
               ->setAttr('type|text')
               ->setAttr('value|'.$groupUsersData['groupUsersData']['aname'])
               ->setRules('notempty|trim')
               ->setErrorText('Введите наименование группы')
               ->setAttr('class|form-control input-lg')
               ->addInput();

          $form->setInput('editgroupusers')
               ->setAttr('type|submit')
               ->setAttr('class|btn btn-primary btn-lg addpage')
               ->setAttr('value|Сохранить')
               ->addInput();   
                

           $formEditGroupUsers = $form->close();
           
           Bufer::set(array('formEditGroupUsers'=>$formEditGroupUsers));
           
           if($groupUsersData['success']===false){
                Bufer::add(array('errors'=>$groupUsersData['error']));
           }
           

           if(!$form->sendForm('editgroupusers')){
               $errors = $form->getErrors();  
               Bufer::add(array('errors'=>$errors ));
           }
           else{
               $data = $form->getData();  
                
               $data = array(
                        'acid'=>$acid,
                        'aname'=>$this->model->escape($data['aname'])
               );            
              
              $checkName = $this->checkName($data);
              
              if($checkName['success'] === true){
                  $result = $this->saveGroupUsers($data);
                  
                  if($result['success'] === false){
                     Bufer::add(array('errors'=>$result['error']));
                  }
                  else{
                     Route::go('?mode=admin&route=editgroupusers&acid='.$acid);
                  }
              }
              else{
                   Bufer::add(array('errors'=>$checkName['error']));
              }
           }              
      }
      
      public function checkName($data){
          return $this->model->checkName($data);
      }
      
      public function saveGroupUsers($data){
         return $this->model->saveGroupUsers($data);
      }
  }
  
  $controller = new EditgroupusersController; 
  $controller->renderForm();
  
  $bufer = Bufer::getData();
  
  //echo '<pre>' . print_r($bufer, 1) . '</pre>';
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/editgroupusers.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl'); 
  

?>