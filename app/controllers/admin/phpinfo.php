<?php
  class PhpinfoController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Phpinfo');
      }
      
      public function getPHPinfo(){
         return (file_exists(DIR.'/'.PATH.'/phpinfo.php')) ? true : false;
      }
  }
  
  $controller = new PhpinfoController;

  Bufer::set(array('data'=>$controller->getPHPinfo()));
  
  if(!$controller->getPHPinfo()){
      Bufer::add(array('errors'=>array('Отсутствует файл phpinfo.php в корне сайта')));
  }

  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/phpinfo.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>