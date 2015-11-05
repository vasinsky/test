<?php
  class AddsectionController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Addsection');
      }

     public function renderForm(){
        $model = $this->model;

        $form = new HTMLForm;
        $form->open('addsection','','POST');
        $form->setInput('sname')
             ->setAttr('type|text')
             ->setAttr('onkeyup|document.getElementById(\'child\').value=translit(this.value)')
             ->setAttr(isset($_POST['sname']) ? 'value|'.$_POST['sname'] : '')
             ->setRules('notempty')
             ->setErrorText('Введите наименование раздела')
             ->setAttr('class|form-control input-lg')
             ->addInput();
        $form->setInput('sindex')
             ->setAttr('type|text')
             ->setAttr('id|child')
             ->setAttr(isset($_POST['sindex']) ? 'value|'.$_POST['sindex'] : '')
             ->setRules('notempty|regexp[#([a-z]){3,}#i]')
             ->setErrorText('Введите псевдоним раздела (от 3х букв)')
             ->setAttr('class|form-control input-lg')
             ->addInput(); 
        $form->setTextarea('sdescription')
             ->setAttr('class|form-control')
             ->setRules('notempty')
             ->setText(isset($_POST['sdescription']) ? $_POST['sdescription'] : '')
             ->setErrorText('Введите описание раздела')
             ->addTextarea();                 
        $form->setInput('createsection')
             ->setAttr('type|submit')
             ->setAttr('class|btn btn-primary btn-lg addpage')
             ->setAttr('value|Создать')
             ->addInput();   
 
        $formAddsection = $form->close();     
        
        Bufer::set(array('formAddsection'=>$formAddsection));
      
        if(!$form->sendForm('createsection')){
            $errors = $form->getErrors();  
            Bufer::add(array('errors'=>$errors));
        }
        else{
            $data = $form->getData();  
            
            $data = array(
                    'sname'=>$this->model->escape($data['sname']),
                    'sdescription'=>$this->model->escape($data['sdescription']),
                    'sindex'=>$this->model->escape($data['sindex']),
            );            
          
            $checkIndexSection = $this->model->checkIndexSection($data);
          
          
            if(!isset($checkIndexSection['errors'])){    
                $result = $this->addsection($data, $formAddsection);  
      
                if($result['errors']){
                    Bufer::add(array('errors'=>$result['errors']));
                }
          }          
          else{
              Bufer::add(array('errors'=>$checkIndexSection['errors']));            
          }           
          
        } 
     }
     
     public function chekIndexSection($data){
        return $this->model->chekIndexSection($data);
     }
     
     public function addsection($data){
        return $this->model->addsection($data);
     }
           
  }
  
  $controller = new AddsectionController;
  $controller->renderForm();
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/addsection.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>