<?php
  class EditsectionController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Editsection');
      }

     public function renderForm(){
        $model = $this->model;
        
        $sid = isset($_GET['sid']) ? (int)$_GET['sid'] : 1;
        
        $sectionData = $model->getSectionData($sid);
        
        $form = new HTMLForm;
        $form->open('editsection','','POST');
        $form->setInput('sname')
             ->setAttr('type|text')
             ->setAttr('onkeyup|document.getElementById(\'child\').value=translit(this.value)')
             ->setAttr('value|'.$sectionData['sname'])
             ->setRules('notempty')
             ->setErrorText('Введите наименование раздела')
             ->setAttr('class|form-control input-lg')
             ->addInput();
        $form->setInput('sindex')
             ->setAttr('type|text')
             ->setAttr('id|child')
             ->setAttr('value|'.$sectionData['sindex'])
             ->setRules('notempty|regexp[#([a-z]){3,}#i]')
             ->setErrorText('Введите псевдоним раздела (от 3х букв)')
             ->setAttr('class|form-control input-lg')
             ->addInput(); 
        $form->setTextarea('sdescription')
             ->setAttr('class|form-control')
             ->setRules('notempty')
             ->setText($sectionData['sdescription'])
             ->setErrorText('Введите описание раздела')
             ->addTextarea();                 
        $form->setInput('savesection')
             ->setAttr('type|submit')
             ->setAttr('class|btn btn-primary btn-lg addpage')
             ->setAttr('value|Сохранить')
             ->addInput();   
 
        $formEditsection = $form->close();     
        
       
      
        if(!$form->sendForm('savesection')){
          $errors = $form->getErrors();  
          Bufer::set(array(
                         'errors'=>$errors,
                         'formEditsection'=>$formEditsection
                         )
                    );
        }
        else{
          $data = $form->getData();  
          $data['sid'] = $sid;
  
          $data = array(
                    'sid'=>$data['sid'],
                    'sname'=>$this->model->escape($data['sname']),
                    'sdescription'=>$this->model->escape($data['sdescription']),
                    'sindex'=>$this->model->escape($data['sindex']),
          );            
          
          $checkIndexSection = $this->model->checkIndexSection($data);
          
          
          if(!isset($checkIndexSection['errors'])){
                    
                    
                    $result = $this->Savesection($data, $formEditsection);  
          
                    if($result['errors']){
                        Bufer::set(array(
                                         'errors'=>$result['errors'],
                                         'formEditsection'=>$formEditsection
                                         )
                                   );
                    }
          }          
          else{
              Bufer::set(array(
                             'errors'=>$checkIndexSection['errors'],
                             'formEditsection'=>$formEditsection
                             )
                        );            
          }           
          
        } 
     }
     
     public function chekIndexSection($data){
        return $this->model->chekIndexSection($data);
     }
     
     public function Savesection($data){
        return $this->model->Savesection($data);
     }
           
  }
  
  $controller = new EditsectionController;
  $controller->renderForm();
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/editsection.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>