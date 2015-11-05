<?php
  class FaqController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Faq');
      }
  }
  
  $controller = new FaqController;
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/faq.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>