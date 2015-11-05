<?php
  class AddhtmlsnippetsController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Addhtmlsnippets');
      }
      
      public function renderForm(){
         $form = new HTMLForm;
         
         $form->open('addsnippet','','POST');

         $form->setInput('hsname')
              ->setRules('regexp[#[a-z\-_0-9]{3,15}#i]')
              ->setErrorText('Имя сниппета может содержать след. символы: a-z_-0-9  от 3 до 15 символов')
              ->setAttr('class|form-control input-lg')
              ->setAttr(isset($_POST['hsname'])?'value|'.$_POST['hsname']:'')
              ->addInput();
         
         $form->setTextarea('hsdescription')
              ->setRules('trim|notempty')
              ->setErrorText('Введите описание снипета')
              ->setAttr('class|desc  form-control')
              ->setText(isset($_POST['hsdescription'])?$_POST['hsdescription']:'')
              ->addTextarea();  
              
         $form->setTextarea('hscode')
              ->setRules('trim|notempty')
              ->setErrorText('Введите код снипета')
              ->setAttr('class|preview desc form-control')
              ->setAttr('id|content')
              ->setText(isset($_POST['hscode'])?$_POST['hscode']:'')
              ->addTextarea(); 
              
        $form->setInput('addsnippet')
             ->setAttr('type|submit')
             ->setAttr('class|btn btn-primary btn-lg addpage')
             ->setAttr('value|Создать')
             ->addInput();  
             
        $formAddSnippet = $form->close();  
        
        Bufer::add(array('formAddSnippet'=>$formAddSnippet));      
        
        /**
        * Провервка отправленной формы
        */   
        if(!$form->sendForm('addsnippet')){
          $errors = $form->getErrors();  
          Bufer::add(array('errors'=>$errors));
        }
        else{
          $data = $form->getData();  
            
          $data = array(
                    'hsname'=>$this->model->escape($data['hsname']),
                    'hsdescription'=>$this->model->escape($data['hsdescription']),
                    'hscode'=>$this->model->escape($data['hscode'])
          );            
          
          $checkHsname = $this->model->checkHsname($data);
          
          if(!isset($checkHsname['error']))
                    $this->addSnippet($data);  
          else{
              Bufer::add(array('errors'=>$checkHsname['error']));            
          }           
          
        }                                          
      }
      
      public function checkHsname($data){
          return $this->model->checkHsname($data);
      }
      
      public function addSnippet($data){
          $result = $this->model->addSnippet($data);  
          
          if($result['success'] === false){
              Bufer::add(array('errors'=>$result['error']));
          }
          else{
              Route::go('?mode=admin&route=htmlsnippets');
          }
      }
  }
  
  $controller = new AddhtmlsnippetsController;
  $controller->renderForm();
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/addhtmlsnippets.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>