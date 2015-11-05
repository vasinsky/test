<?php
  class CssController extends BaseController{
    
      public function __construct(){
          parent::__construct();
          $this->setModel('Css');
      }
      
      public function getListCss(){
           $files = glob(DIR.'/'.PATH.'app/css/*.css');
           
           foreach($files as $file){
                $t = explode('/', $file);
                $listCss[$file] = $t[count($t)-1];
           }
           
           return $listCss;
      }
      
      public function getFileCSS($fileCss){
          $path = DIR.'/'.PATH.'app/css/';

          return Files::getData($path.$fileCss);
      }
      
      public function saveCss($css, $fileCss){
          $path = DIR.'/'.PATH.'app/css/';
          
          if(!file_put_contents($path.$fileCss, $css)){
             Bufer::add(array('errors'=>'Ошибка сохранения данных'));
          }
          else{
             header("location:".Route::getUrl('?mode=admin&route=css&file='.$fileCss));
          } 
      }
  }
  
  $controller = new CssController;
  
  
  $path = DIR.'/'.PATH.'app/css/';
  
  Bufer::set(array('listCss'=>$controller->getListCss())); 
  
  if(isset($_GET['file'])){
     Bufer::add(array('fileData'=>$controller->getFileCSS($_GET['file'])));
  }
  
  if(isset($_POST['save_css']) && isset($_GET['file'])){
    $controller->saveCss($_POST['code_css'], $_GET['file']);
  }
  
  $controller->view(ADMIN_TPLS_DIR.'/header.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/css.tpl');
  $controller->view(ADMIN_TPLS_DIR.'/footer.tpl');   
?>