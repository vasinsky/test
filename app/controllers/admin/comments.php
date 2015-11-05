<?php
  class CommentsController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Comments');
      }
  }
  
  $controller = new CommentsController;
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/comments.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>