<?php
  class {namemodule}Controller extends BaseController{
      public function __construct(){
          parent::__construct();
      }
  }
  
  $controller = new {namemodule}Controller;
  $controller->setModel('{namemodule}');
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/{tpl}.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>