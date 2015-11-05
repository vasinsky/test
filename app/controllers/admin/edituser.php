<?php
  class EdituserController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Edituser');
      }

      public function getAccessList(){
        return $this->model->getAccessList();
      }
      
      public function getUserData($uid){
          $result = $this->model->getUserData($uid);

          if(!is_array($result)){
             return array('userData'=>array(
                                        'uid'=>0,
                                        'login'=>'',
                                        'password'=>'',
                                        'email'=>'',
                                        'isadmin'=>3
                                       ),
                                       
                           'errors'=>array('Не могу прочитать данные пользователя')            
                        );                 

          }
          else{
              return array('userData'=>$result[0], 'errors'=>array());
          }
          
      }
      
      public function renderForm(){
          $uid = isset($_GET['uid']) ? (int)$_GET['uid'] : 1;   
        
          $result = $this->getUserData($uid);      
          
          $access = $this->getAccessList();
          
          $form = new HTMLForm;
          $form->open('edituser','','POST');
          $form->setInput('login')
               ->setAttr('type|text')
               ->setAttr(isset($result['userData']['login']) ? 'value|'.$result['userData']['login'] : '')
               ->setRules('notempty|trim')
               ->setErrorText('Введите логин пользователя')
               ->setAttr('class|form-control input-lg')
               ->addInput();
               
          $form->setInput('uid')
               ->setAttr('type|hidden')    
               ->setAttr('value|'.$result['userData']['uid'])
               ->addInput();    
                
               
          $form->setInput('password')
               ->setAttr('type|text')
               ->setAttr(isset($result['userData']['password']) ? 'value|'.$result['userData']['password'] : '')
               ->setRules('notempty|trim|length[40|40]')
               ->setErrorText('Введите hash пароля пользователя (можно получить в генераторе под формой)')
               ->setAttr('class|form-control input-lg')
               ->addInput();         
               
          $form->setInput('email')
               ->setAttr('type|text')
               ->setAttr(isset($result['userData']['email']) ? 'value|'.$result['userData']['email'] : '')
               ->setRules('email')
               ->setErrorText('Введён не корректный email пользователя')
               ->setAttr('class|form-control input-lg')
               ->addInput();    
         $form->setSelect('access')
              ->setAttr('class|form-control')  
              ->setAttr('style|width:300px')  
              ->setRules('notempty')   
              ->addSelect(); 
              
         $form->setInput('edituser')
              ->setAttr('type|submit')
              ->setAttr('class|btn btn-primary btn-lg addpage')
              ->setAttr('value|Сохранить')
              ->addInput();

         foreach($access as $k=>$v){
             
             if(!in_array($v['acid'],array(2))){
                 $form->setOption($v['aname'])
                      ->setAttr('value|'.$v['acid'])
                      ->setAttr($result['userData']['isadmin'] == $v['acid']  ? 'selected|selected' : '')
                      ->addOption('access');
             }     
        }

        $formUserData = $form->close();   
     
        Bufer::set( 
                     array(
                           'errors'=>isset($result['errors']) ? $result['errors'] : array(),
                           'userData'=>$result['userData'],
                           'formUserData'=>$formUserData
                     )
                    );                  

        if(!$form->sendForm('edituser')){
          $errors = $form->getErrors();  
          Bufer::set(array(
                         'errors'=>$errors,
                         'formUserData'=>$formUserData,
                         )
                    );
        }
        else{
          $data = $form->getData();  
 
          $data = array(
                    'uid'=>$uid,
                    'login'=>$this->model->escape($data['login']),
                    'password'=>$this->model->escape($data['password']),
                    'email'=>$this->model->escape($data['email']),
                    'access'=>$this->model->escape($data['access'])
          );            
          
          $saveUser = $this->saveUser($data);

            
          if(!isset($saveUser['error'])){
                  Route::go('?mode=admin&route=edituser&uid='.$uid);     
          }       
          else{
              Bufer::set(array(
                             'errors'=>$saveUser['error'],
                             '$formUserData'=>$formUserData,
                             )
                        );          
          }               

        }          
            
      }
      
      public function saveUser($data){
         return $this->model->saveUser($data);
      }
      
      
      
  }
  
  $controller = new EdituserController;
  $controller->renderForm(); 
  
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/edituser.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>