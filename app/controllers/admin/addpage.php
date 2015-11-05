<?php
  class AddpageController extends BaseController{
     public function __construct(){
        parent::__construct();
        $this->setModel('Addpage');
     }
     
     public function renderForm(){
        $model = $this->model;
        
        $getSections = $model->returnData("select * from section");

        $form = new HTMLForm;
        $form->open('addpage','','POST');
        $form->setInput('title')
             ->setAttr('type|text')
             ->setAttr('onkeyup|document.getElementById(\'child\').value=translit(this.value)')
             ->setAttr(isset($_POST['title']) ? 'value|'.$_POST['title'] : '')
             ->setRules('notempty')
             ->setErrorText('Введите Title страницы')
             ->setAttr('class|form-control input-lg')
             ->addInput();
        $form->setInput('description')
             ->setAttr('type|text')
             ->setAttr(isset($_POST['description']) ? 'value|'.$_POST['description'] : '')
             ->setRules('notempty')
             ->setErrorText('Введите описание для страницы')
             ->setAttr('class|form-control input-lg')
             ->addInput(); 
        $form->setInput('keywords')
             ->setAttr('type|text')
             ->setAttr(isset($_POST['keywords']) ? 'value|'.$_POST['keywords'] : '')
             ->setRules('notempty')
             ->setErrorText('Введите ключевые слова для  страницы')
             ->setAttr('class|form-control input-lg')
             ->addInput();                         
        $form->setInput('index')
             ->setAttr('type|text')
             ->setAttr('id|child')
             ->setAttr(isset($_POST['index']) ? 'value|'.$_POST['index'] : '')
             ->setRules('notempty')
             ->setErrorText('Введите псевдоним страницы')
             ->setAttr('class|form-control input-lg')
             ->addInput(); 
        $form->setSelect('section')
             ->setAttr('class|form-control')  
             ->setAttr('style|width:300px')   
             ->setRules('notempty') 
             ->addSelect(); 
          
        foreach($getSections as $s){
            $form->setOption($s['sname'].' ('.$s['sindex'].')')
                 ->setAttr('value|'.$s['sid'])
                 ->setAttr(isset($_POST['section']) && ($_POST['section'] == $s['sid']) ? 'selected|selected' : '')
                 ->addOption('section');
        }
 
        $form->setSelect('display')
             ->setAttr('class|form-control')  
             ->setAttr('style|width:300px') 
             ->setRules('notempty')    
             ->addSelect(); 
        
        $display = array( 
                        1=>'да',
                        0=>'нет',
                        2=>'только для администрации'
                        );
        
        foreach($display as $k=>$v){
            $form->setOption($v)
                 ->setAttr('value|'.$k)
                 ->setAttr(isset($_POST['display']) && ($_POST['display'] == $k) ? 'selected|selected' : '')
                 ->addOption('display');
        }

        $form->setTextarea('preview')
             ->setAttr('id|preview')
             ->setText(isset($_POST['preview']) ? $_POST['preview'] : '')
             ->setAttr('class|preview form-control')
             ->setRules('trim')
             ->addTextarea();   
                 
        $form->setTextarea('content')
             ->setAttr('id|content')
             ->setText(isset($_POST['content']) ? $_POST['content'] : '')
             ->setAttr('class|addpage form-control content')
             ->setRules('notempty')
             ->setErrorText('Введите контент страницы')
             ->addTextarea();         
                 
        $form->setInput('addpage')
             ->setAttr('type|submit')
             ->setAttr('class|btn btn-primary btn-lg addpage')
             ->setAttr('value|Создать')
             ->addInput();   
 
        $formAddpage = $form->close();     
        
        Bufer::set(array('formAddpage'=>$formAddpage));
        
        /**
        * Провервка отправленной формы
        */   
        if(!$form->sendForm('addpage')){
          $errors = $form->getErrors();  
          Bufer::add(array('errors'=>$errors));
        }
        else{
            
            
          $data = $form->getData();  
            
          $data = array(
                    'title'=>$this->model->escape($data['title']),
                    'description'=>$this->model->escape($data['description']),
                    'keywords'=>$this->model->escape($data['keywords']),
                    'index'=>$this->model->escape($data['index']),
                    'section'=>(int)$data['section'],
                    'display'=>(int)$data['display'],
                    'preview'=>$this->model->escape($data['preview']),
                    'content'=>$this->model->escape($data['content'])
          );            
          
          $checkIndexPage = $this->model->checkIndexPage($data);
          
          
          if(!isset($checkIndexPage['error']))
                    $this->addpage($data);  
          else{
              Bufer::add(array('errors'=>$checkIndexPage['error']));            
          }           
          
        }   
     }
     
     public function addpage($data){
           $result = $this->model->addpage($data);
           
           if(!$result['success']){
               Bufer::add(array('errors'=>array('Произошла ошибка при создании страницы')));             
           } 
           else{
               Route::go('?mode=admin&route=pages');  
           }
     }
  }
  
  $controller = new AddpageController;
  $controller->renderForm();
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/addpage.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');
?>