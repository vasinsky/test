<?php
  
  class AutorizationController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Autorization');
      }
      /**
       * Создание и  валидация формы
       */       
      public function renderForm(){
          $form = new HTMLForm;
          $form->open('access','','POST');
          $form->setInput('login')
               ->setAttr('type|text')
               ->setAttr('value| ')
               ->setAttr('class|form-control input-lg')
               ->setRules('trim|notempty')
               ->setErrorText('Введите логин')
               ->addInput();
          $form->setInput('password')
               ->setAttr('type|password')
               ->setAttr('class|form-control input-lg')
               ->setRules('trim|notempty')
               ->setErrorText('Введите пароль')
               ->addInput(); 
               
          $form->setInput('capcha')
               ->setAttr('type|text')
               ->setAttr('class|form-control input-lg')
               ->setAttr('id|captcha-form')
               ->setRules('code[captcha]')  
               ->setErrorText('Не верно введён защитный код')
               ->addInput(); 
                              
                
          $form->setInput('enter')
               ->setAttr('type|submit')
               ->setAttr('class|btn btn-primary btn-lg')
               ->setAttr('value|Войти')
               ->addInput();               
          
          $formAutorize = $form->close();       
          
          /**
           * Провервка отправленной формы
           */   
          if(!$form->sendForm('enter')){
              $errors = $form->getErrors();  
              Bufer::set(array(
                             'errors'=>$errors,
                             'formAutorize'=>$formAutorize
                             )
                        );
          }
          else{
              $data = $form->getData();
              $this->access($data,$formAutorize);  
          }              
      }
      /**
       *  Реакция на правильно заполненную форму
       *  @param array - ассоц. массив значений полей формы
       *  @param array - массив с элементами формы из renderForm()
       */ 
      public function access($data, $form){
          $login = $this->model->escape($data['login']);
          $password = sha1(SALT.$data['password']);
          $result = $this->model->sqlQuery("select 
                                            *
                                           from 
                                             users 
                                           where 
                                             isadmin=1 and login='".$login."' and password='".$password."'"
                                           );                               
          if($result->num_rows>0){
              $_SESSION['fw'][INDEX_SESSION_ADMIN] = true;
              Route::go('?mode=admin&route=pages');  
          }      
          else{
              Bufer::set(array(
                                'errors'=>array('Не верный логин или пароль!'),
                                'formAutorize'=>$form
                              )
                        );
          }                       
                     
      }
  }
  
  $controller = new AutorizationController;
  $controller->renderForm();
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/autorization.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');
   
  
?>