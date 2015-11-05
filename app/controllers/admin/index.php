<?php 
 class IndexController extends BaseController{
    public function __construct(){
        parent::__construct();
        $this->setModel('Index');
    }
 }

 $controller = new IndexController;

 $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
 $controller->view(ADMIN_TPLS_DIR.'/index.tpl');
 $controller->view(ADMIN_TPLS_DIR.'/footer.tpl'); 

?>

