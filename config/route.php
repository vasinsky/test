<?php
    /**
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */
     
    /**
     * Определение констант, часто используемых в GET url
     */ 
    RulesUrl::getRules();
     
    define('CUR_PAGE', isset($_GET['page']) ? ((int)$_GET['page']<=1 ? 1 : (int)$_GET['page']) : 1);
    define('MODE', isset($_GET['mode']) ? $_GET['mode'] : 'public');
    define('SECTION', isset($_GET['section']) ? $_GET['section'] : 'pages');

    define('MESSAGE_ACCESS_DENIED', 'У вас нет доступа к данному разделу сайта!
             Вернитесь на <a href="/'.PATH.'">главную страницу</a>');
     
    if(MODE == 'public'){
        define('ROUTE', isset($_GET['route']) ? $_GET['route'] : 'index');
    }
    else{
        define('ROUTE', isset($_GET['route']) 
                        ? $_GET['route']
                        : ((Route::isAdmin() === true) ? 'pages'  : 'autorization'));
    }
    
    if(MODE == 'public'){
        if(!file_exists(TPLS_DIR.'/'.ROUTE.'.tpl')){
            Files::addtolog(LOG_404, 'Попытка доступа к не существующему разделу.');
            Route::status404();
        }
        
        Files::load(MODELS_DIR.'/'.ROUTE.'.php');
        Files::load(CONTROLLERS_DIR.'/'.ROUTE.'.php');
    }
    elseif(MODE =='admin'){   
         
         $moduls = glob('app/controllers/admin/*.php');
         $name_modules = array();
         
         foreach($moduls as $modul){
             $name_modules[] = strtr($modul, array('app/controllers/admin/'=>'','.php'=>''));   
         }
        
         if(Route::isAdmin() === false && !in_array(ROUTE, $name_modules)){
            Files::addtolog(LOG_ACCESS, 'Попытка доступа к закрытому разделу.');
            Route::status404();            
         } 
         else{ 
            Files::load(ADMIN_MODELS_DIR.'/' .ROUTE. '.php');
            Files::load(ADMIN_CONTROLLERS_DIR.'/' .ROUTE. '.php');
        }  

    }

?>