<?php 
 class IndexController extends BaseController{
    public function __construct(){
        parent::__construct();
      
        RulesUrl::addRules('public',array('sections','page'));        
        
    }
    
    public function getPageData($route){
        return $this->model->getModel(ROUTE);
    }    
 }
 
 $controller = new IndexController;
 $controller->setModel('Index');
 $model = $controller->model;
 
 $index = isset($_GET['page']) ? $_GET['page'] : 'index';
 $pagesData = $model->getPageData($index);
 
 Bufer::set(array('pagesData'=>$pagesData));
 
 if(!Access::validate()){
    exit(MESSAGE_ACCESS_DENIED);
 }
 
 $controller->view(TPLS_DIR.'/header.tpl');
 $controller->view(TPLS_DIR.'/index.tpl');
 $controller->view(TPLS_DIR.'/footer.tpl');
?>