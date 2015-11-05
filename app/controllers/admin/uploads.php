<?php
  class UploadsController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Uploads');
      }
      public function getsubdir(){
            $dir = glob(PATH.'uploads/multi/*');
            
            foreach($dir as $folder){
                if(is_dir($folder) && $folder != PATH.'uploads/multi/thumbnail')
                    $folders[] = $folder;
            }
            
            return isset($folders) ? $folders : PATH.'uploads/multi/';
      }
  }
  
  $controller = new UploadsController;
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/uploads.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl'); 
?>