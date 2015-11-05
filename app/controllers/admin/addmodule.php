<?php
  class AddmoduleController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('addmodule');
      }
      
      public function renderForm(){
            $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
        
            $form = new HTMLForm;
            $form->open('addmodule','','POST');
            $form->setInput('name')
                 ->setAttr('type|text')
                 ->setAttr('value|'.$name)
                 ->setRules('regexp[#^([a-z]{3,15})#iu]')
                 ->setErrorText('Наименование модуля должно быть выполнено по шаблону [a-z0-9]{3,15}')
                 ->setAttr('class|form-control input-lg')
                 ->addInput();   
            $form->setSelect('mode')
                 ->setAttr('class|form-control')    
                 ->setRules('notempty')
                 ->setErrorText('Укажите режим модуля')
                 ->addSelect(); 
            
            $display = array( 
                            '2'=>'Для административной части (mode=admin)',
                            '1'=>'Публичный (mode=public)'
                            );
            
            foreach($display as $k=>$v){
                $form->setOption($v)
                     ->setAttr('value|'.$k)
                     ->addOption('mode');
            } 

            $form->setInput('createmodule')
                 ->setAttr('type|submit')
                 ->setAttr('class|btn btn-primary btn-lg addpage')
                 ->setAttr('value|Создать')
                 ->addInput();   
     
            $formAddModule = $form->close();                
            
            Bufer::set(
                      array(
                             'formAddModule'=>$formAddModule 
                            )  
            );      
            
            
            if(!$form->sendForm('createmodule')){
              $errors = $form->getErrors();  
              Bufer::add(array('errors'=>$errors));
            }
           
            else{         
              $data = $form->getData();          
                        
              $createmodule = $this->createModule(array('name'=>$data['name'],'mode'=>$data['mode']), $formAddModule);   
              
              return $createmodule;                    
            } 
      }
      
      public function createModule($module, $form){
          
          $file_tpl_controller = DIR.'/'.PATH.'app/createmodule/controller.tpl';
          $file_tpl_model = DIR.'/'.PATH.'app/createmodule/model.tpl';
          $file_tpl_templte = DIR.'/'.PATH.'app/createmodule/template.tpl';
          
          $file_public_tpl_controller = DIR.'/'.PATH.'app/createmodule/public/controller.tpl';
          $file_public_tpl_model = DIR.'/'.PATH.'app/createmodule/public/model.tpl';
          $file_public_tpl_templte = DIR.'/'.PATH.'app/createmodule/public/template.tpl';
          
          if(!file_exists($file_tpl_templte) or !is_readable($file_tpl_templte)){
               $errors[] = 'Не могу найти или прочитать файл шаблона *.tpl нового модуля';
          }
          
          if(!file_exists($file_tpl_controller) or !is_readable($file_tpl_controller)){
               $errors[] = 'Не могу найти или прочитать файл шаблона контроллера нового модуля';
          }
          
          if(!file_exists($file_tpl_model) or !is_readable($file_tpl_model)){
               $errors[] = 'Не могу найти или прочитать файл шаблона модели нового модуля';
          }
                              
          if(isset($errors)){
               Bufer::add(array('errors'=>$errors));
          }
          else{
             $mode = $module['mode'];
             $namefilemodule = strtolower($module['name']);
             $nameclassmodule = ucfirst(strtolower($module['name']));
    
              switch ($module['mode']){
                case 1:  //public module
                    $dir_tpls = TPLS_DIR;
                    $dir_controllers = CONTROLLERS_DIR;
                    $dir_models = MODELS_DIR;
                    $tpl = file_get_contents($file_public_tpl_templte);
                    $controller = file_get_contents($file_public_tpl_controller);
                    $model = file_get_contents($file_public_tpl_model);                      
                    break;
                case 2:  //admin module
                    $dir_tpls =  ADMIN_TPLS_DIR; 
                    $dir_controllers =  ADMIN_CONTROLLERS_DIR;
                    $dir_models = ADMIN_MODELS_DIR;
                    $tpl = file_get_contents($file_tpl_templte);
                    $controller = file_get_contents($file_tpl_controller);
                    $model = file_get_contents($file_tpl_model);                    
                    break;                                  
             }   
    
             $tpl = strtr($tpl,array('{namemodule}'=>$nameclassmodule));
             $controller = strtr($controller,array('{namemodule}'=>$nameclassmodule,'{tpl}'=>$namefilemodule));
             $model = strtr($model,array('{namemodule}'=>$nameclassmodule));
 

             
            if(file_exists($dir_tpls.'/'.$namefilemodule.'.tpl')) 
                $errors[] = 'Шаблон с таким именем уже найден';
            if(file_exists($dir_controllers.'/'.$namefilemodule.'.php')) 
                $errors[] = 'Контроллер с таким именем уже найден';
            if(file_exists($dir_models.'/'.$namefilemodule.'.php')) 
                $errors[] = 'Модель с таким именем уже найдена';             
             
            if(isset($errors)){
                Bufer::add(array('errors'=>$errors));
            }            
            
            if(!file_put_contents($dir_tpls.'/'.$namefilemodule.'.tpl',$tpl)){
             $errors[] = 'Не могу сохранить файл шаблона нового модуля';
            }
            
            if(!file_put_contents($dir_controllers.'/'.$namefilemodule.'.php',$controller)){
             $errors[] = 'Не могу сохранить файл контроллера нового модуля';
            }             
            
            if(!file_put_contents($dir_models.'/'.$namefilemodule.'.php',$model)){
             $errors[] = 'Не могу сохранить файл модели нового модуля';
            }
            
            
            
            if(isset($errors)){
              return Bufer::set(
                                 array(
                                       'errors'=>$errors,
                                       'formAddModule'=>$form
                                 )   
                     );
            }
            else{
                Route::go('?mode=admin&route=addmodule');
            }
        }
          
      }
  }
  
  $controller = new AddmoduleController;
  $controller->renderForm();
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/addmodule.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');    
?>