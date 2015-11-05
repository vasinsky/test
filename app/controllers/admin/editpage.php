<?php
  class EditpageController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Editpage');
      }
     
     public function getPage($pid){
         $result = $this->model->getPage($pid);
         return $result;
     }

     public function renderForm(){
        
        $model = $this->model;
        
        $pid = isset($_GET['pid']) ? (int)$_GET['pid'] : 0;
        
        $pageData = $this->getPage($pid);
        
        if(isset($pageData['errors'])){
            Bufer::set(array(
                             'errors'=>$pageData['errors']
                             )
            );
        }
        else{
            Bufer::set(array('pageData'=>$pageData['pageData']));
        }

        $errors = isset($pageData['errors']) ? $pageData['errors'] : false;
        
        $dataPage = isset($pageData['pageData'][0]) ? $pageData['pageData'][0] : false;
        
        $getSections = $model->returnData("select * from section");
        
        $form = new HTMLForm;
        $form->open('addpage','','POST');
        $form->setInput('title')
             ->setAttr('type|text')
             ->setAttr('onkeyup|document.getElementById(\'child\').value=translit(this.value)')
             ->setAttr(isset($dataPage['title']) ? 'value|'.$dataPage['title'] : '')
             ->setRules('notempty|trim')
             ->setErrorText('Введите Title страницы')
             ->setAttr('class|form-control input-lg')
             ->addInput();
        $form->setInput('pid')
             ->setAttr('type|hidden')
             ->setAttr('value|'.$pid)
             ->setRules('notempty')   
             ->addInput();     
        $form->setInput('description')
             ->setAttr('type|text')
             ->setAttr(isset($dataPage['description']) ? 'value|'.$dataPage['description'] : '')
             ->setRules('notempty')
             ->setErrorText('Введите описание для страницы')
             ->setAttr('class|form-control input-lg')
             ->addInput(); 
        $form->setInput('keywords')
             ->setAttr('type|text')
             ->setAttr(isset($dataPage['keywords']) ? 'value|'.$dataPage['keywords'] : '')
             ->setRules('notempty')
             ->setErrorText('Введите ключевые слова для  страницы')
             ->setAttr('class|form-control input-lg')
             ->addInput();                         
        $form->setInput('index')
             ->setAttr('type|text')
             ->setAttr('id|child')
             ->setAttr(isset($dataPage['name']) ? 'value|'.$dataPage['name'] : '')
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
                 ->setAttr(isset($dataPage['sid']) && ($dataPage['sid'] == $s['sid']) ? 'selected|selected' : '')
                 ->addOption('section');
        }
 
        $form->setSelect('display')
             ->setAttr('class|form-control')  
             ->setAttr('style|width:300px')  
             ->setRules('notempty')   
             ->addSelect(); 
        
        $display = array( 
                        1=>'да',
                        2=>'нет',
                        3=>'только для администрации'
                        );
        
        foreach($display as $k=>$v){
            $form->setOption($v)
                 ->setAttr('value|'.$k)
                 ->setAttr(isset($dataPage['display']) && ($dataPage['display'] == $k) ? 'selected|selected' : '')
                 ->addOption('display');
        }

        $form->setTextarea('preview')
             ->setAttr('id|preview')
             ->setText(isset($dataPage['preview']) ? $dataPage['preview'] : '')
             ->setAttr('class|preview form-control')
             ->setRules('trim')
             ->addTextarea();   
                 
        $form->setTextarea('content')
             ->setAttr('id|content')
             ->setText(isset($dataPage['content']) ? $dataPage['content'] : '')
             ->setAttr('class|addpage form-control content')
             ->setRules('notempty')   
             ->addTextarea();         
                 
        $form->setInput('editpage')
             ->setAttr('type|submit')
             ->setAttr('class|btn btn-primary btn-lg addpage')
             ->setAttr('value|Сохранить')
             ->addInput();   
 
        $formEditpage = $form->close();     
       
        Bufer::set(array(
                     'errors'=>$errors,
                     'formEditpage'=>$formEditpage,
                     'titlePage'=>isset($dataPage['title']) ? $dataPage['title'] : ''
                     )
                ); 
        
        /**
        * Провервка отправленной формы
        */   

        if(!$form->sendForm('editpage')){
          $errors = $form->getErrors();  
          Bufer::set(array(
                         'errors'=>$errors,
                         'formEditpage'=>$formEditpage,
                         'titlePage'=>isset($dataPage['title']) ? $dataPage['title'] : ''
                         )
                    );
        }
        else{
          $data = $form->getData();  
 
          $data = array(
                    'pid'=>(int)$data['pid'],
                    'title'=>$this->model->escape($data['title']),
                    'description'=>$this->model->escape($data['description']),
                    'keywords'=>$this->model->escape($data['keywords']),
                    'index'=>$this->model->escape($data['index']),
                    'sid'=>(int)$data['section'],
                    'display'=>(int)$data['display'],
                    'preview'=>$this->model->escape($data['preview']),
                    'content'=>$this->model->escape($data['content'])
          );            
          
          $savePage = $this->savePage($data);
          $checkIndexPage = $this->model->checkIndexPage($data);
            
          if(!isset($checkIndexPage['error'])){
              $result =  $this->savePage($data, $formEditpage);
              
              if(!isset($savePage['errors'])){
                  Route::go('?mode=admin&route=editpage&pid='.$data['pid']);     
              }
              else{
                  Bufer::set(array(
                                 'errors'=>$savePage['error'],
                                 'formEditpage'=>$formEditpage,
                                 'titlePage'=>isset($data['title']) ? $data['title'] : ''
                                 )
                            );            
              }               
          }            
          else{
              Bufer::set(array(
                             'errors'=>$checkIndexPage['error'],
                             'formEditpage'=>$formEditpage,
                             'titlePage'=>isset($data['title']) ? $data['title'] : ''
                             )
                        );            
          }               

        }   
       
     }  

     public function savePage($data){
        $result = $this->model->savePage($data);
        
        return $result;
     }  
     
            
  }
  
  $controller = new EditpageController;
  $controller->renderForm();
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/editpage.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');  
?>