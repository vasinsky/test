<?php
    /**
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */
    header("Content-Type: text/html;charset=utf-8");
    session_start();
    set_time_limit(0);

    /**
     *  Основной файл конфигурации
     */ 
    include 'config/app.php';
       
    if(DEBUG === true){  
        error_reporting(-1);
        ini_set('display_errors', 1);
    }
    else{
        error_reporting(0);
        ini_set('display_errors', 0);        
    }

    /**
     * ЧПУ url, восстановление GET параметров
     */ 


    $furl = parse_url($_SERVER['REQUEST_URI']);
	$url =  explode("/",$furl['path']);

    foreach($url as $k=>$v){
        if(($k>0 || $k == 1) && $k%2 > 0){
            $key = $v;
        }
        else{
            if(isset($key)){
                $_GET[$key] = $v;
            }    
        }
    }

    /**
     * Определение посетителя - администратор/посетитель
     */ 
    $_SESSION['fw'][INDEX_SESSION_ADMIN] = isset($_SESSION['fw'][INDEX_SESSION_ADMIN]) 
                                           ? $_SESSION['fw'][INDEX_SESSION_ADMIN] 
                                           : false;                                    
                                                                        
    /**
     * Автозагрузка классов из папки classes
     * Остальные классы подгружаются в файле config/route.php
     */
     spl_autoload_register(function ($class) {
        if(!preg_match("#Model#i", $class) && !preg_match("#Controller#", $class)){
            if(file_exists('classes/' . $class . '.class.php'))
                include 'classes/' . $class . '.class.php';
        }  
     });
     
    /**
     * Выход из админки 
     */ 
    if(isset($_GET['logout'])){
        Route::logout();
    }     
    
     /**
      * Классы, содержащие базовый функционал
      */ 
     try{ 
     Files::load(DIR.'/'.PATH.'classes/BaseController.class.php');
     Files::load(DIR.'/'.PATH.'classes/BaseModel.class.php');
     }
     catch(Exception $e){
        echo $e->getMessage().'<br/>';
     }
     /**
      * Подключения файлов конфигураций
      */  
     try{ 
     Files::load(CONF.'/mysqli.php');  
     Files::load(CONF.'/route.php');  
     }
     catch(Exception $e){
         echo $e->getMessage().'<br/>'; 
     }
     /**
      * Очистка хранилища данных
      */ 
      Bufer::clear();
?>    