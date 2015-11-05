<?php
  class EdithtmlsnippetsController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Edithtmlsnippets');
      }
      public function getDataHtmlSnippet($hsid){
         $hsid = isset($_GET['hsid']) ? (int)$_GET['hsid'] : 0;
         
         $result = $this->model->getDataHtmlSnippet($hsid);
         
         if($result['success'] === false){
             Bufer::set(array('errors'=>$result['error']));
         }
         else{
             return $result['data'];
         }
      }
      
      public function renderForm(){
         $form = new HTMLForm;
         
         $hsid = isset($_GET['hsid']) ? (int)$_GET['hsid'] : 0;
         $data = $this->getDataHtmlSnippet($hsid);
         
         
         $form->open('editsnippet','','POST');

         $form->setInput('hsname')
              ->setRules('regexp[#[a-z\-_0-9]{3,15}#i]')
              ->setErrorText('Имя сниппета может содержать след. символы: a-z_-0-9  от 3 до 15 символов')
              ->setAttr('class|form-control input-lg')
              ->setAttr(isset($data['hsname'])?'value|'.$data['hsname']:'')
              ->addInput();
         
         $form->setTextarea('hsdescription')
              ->setRules('trim|notempty')
              ->setErrorText('Введите описание снипета')
              ->setAttr('class|desc  form-control')
              ->setText(isset($data['hsdescription'])?$data['hsdescription']:'')
              ->addTextarea();  
              
         $form->setTextarea('code')
              ->setRules('trim|notempty')
              ->setErrorText('Введите код снипета')
              ->setAttr('class|preview_desc form-control')
              ->setAttr('id|content')
              ->setText(isset($data['code'])?$data['code']:'')
              ->addTextarea(); 
              
        $form->setInput('editsnippet')
             ->setAttr('type|submit')
             ->setAttr('class|btn btn-primary btn-lg addpage')
             ->setAttr('value|Сохранить')
             ->addInput();  
             
        $formEditSnippet = $form->close();  
        
        Bufer::add(array('formEditSnippet'=>$formEditSnippet));      
        
        /**
        * Провервка отправленной формы
        */   

        if(!$form->sendForm('editsnippet')){
          $errors = $form->getErrors();  
          Bufer::add(array('errors'=>$errors));
        }
        else{
          $data = $form->getData();  
            
          $data = array(
                    'hsid'=> $hsid = isset($_GET['hsid']) ? (int)$_GET['hsid'] : 0,
                    'hsname'=>$this->model->escape($data['hsname']),
                    'hsdescription'=>$this->model->escape($data['hsdescription']),
                    'code'=>$this->model->escape($data['code'])
          );            
          
          $checkHsname = $this->model->checkHsname($data);
          
          if(!isset($checkHsname['error'])){
              $result = $this->saveSnippet($data);
              
              if($result['success'] === false){
                  Bufer::add(array('errors'=>$result['error']));
              }
              else{
                  Route::go('?mode=admin&route=edithtmlsnippets&hsid='.$data['hsid']);
              }
          }            
          else{
              Bufer::add(array('errors'=>$checkHsname['error']));            
          }           
          
        } 
                                       
      }
      
      public function saveSnippet($data){
          return $this->model->saveSnippet($data);
      }
      
      public function checkHsname($data){
          return $this->model->checkHsname($data);
      }
            
  }
  
  $controller = new EdithtmlsnippetsController;
  $controller->renderForm();
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/edithtmlsnippets.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>