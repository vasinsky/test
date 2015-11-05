<?php
  class AddgroupusersController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Addgroupusers');
      }
      
      public function renderForm(){
          $form = new HTMLForm;
          $form->open('addgroupusers', '', 'POST');
          
          $form->setInput('aname')
               ->setAttr('type|text')
               ->setRules('notempty|trim')
               ->setErrorText('Введите наименование группы')
               ->setAttr('class|form-control input-lg')
               ->addInput();

          $form->setInput('addgroupusers')
               ->setAttr('type|submit')
               ->setAttr('class|btn btn-primary btn-lg addpage')
               ->setAttr('value|Создать')
               ->addInput();   
                

           $formAddGroupUsers = $form->close();
           
           Bufer::set(array('formAddGroupUsers'=>$formAddGroupUsers));    
           
           if(!$form->sendForm('addgroupusers')){
               $errors = $form->getErrors();  
               Bufer::add(array('errors'=>$errors ));
           }
           else{
               $data = $form->getData();
               
               $checkName = $this->checkName($data['aname']);

               if($checkName['success']=== false){
                    Bufer::add(array('errors'=>$checkName['error']));
               }
               else{                
                   $data = array(
                                 'aname'=>$this->model->escape($data['aname'])
                   ); 
                   
                   $result = $this->addGroupUsers($data);
                   
                   if($result['success']===false){
                       Bufer::add(array('errors'=>$result['error']));
                   }
                   else{
                       Route::go('?mode=admin&route=groupsusers');
                   }
               }

           }          
      }
      
      public function checkName($aname){
          return $this->model->checkName($aname);
      }
      
      public function addGroupUsers($data){
          return $this->model->addGroupUsers($data);
      }
  }
  
  $controller = new AddgroupusersController;
  $model = $controller->model;
  
  $controller->renderForm();
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/addgroupusers.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>