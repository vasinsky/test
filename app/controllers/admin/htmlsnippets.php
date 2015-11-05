<?php
  class HtmlsnippetsController extends BaseController{
      public function __construct(){
          parent::__construct();
          $this->setModel('Htmlsnippets');
      }
      
      public function getHtmlSnippets(){
         $snippets = $this->model->getHtmlSnippets();
         
         if($snippets === false){
             return false; 
         }
         else{
             return array('data'=>$snippets, 'paginate'=>$this->paginate());
         }
         
      }
      
      public function deleteHtmlSnippet($hsid){
         $hsid = isset($_GET['delete']) ? (int)$_GET['delete'] : 0;
         $result = $this->model->deleteHtmlSnippet($hsid);
         
         if($result['success'] === false){
            Bufer::add(array('errors'=>$result['error']));
         }
         else{
            header("location:".Route::getUrl('?mode=admin&route=htmlsnippets'));
         }
      }
  }
  
  $controller = new HtmlsnippetsController;
  
  Bufer::set(array('HtmlSnippetsList'=>$controller->getHtmlSnippets()));
  
  if(isset($_GET['delete'])){
    $hsid = isset($_GET['delete']) ? (int)$_GET['delete'] : 0;
    $controller->deleteHtmlSnippet($hsid);
  }
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/htmlsnippets.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>