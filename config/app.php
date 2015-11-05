<?php 
    /**
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */
     
    /**
     * Корневая директория домена
     */ 
    define('DIR',$_SERVER['DOCUMENT_ROOT']);
    
    /**
     * Директория сайта
     * ------------------------
     * слеш в конце обязателен
     */ 
    define('PATH','');
    
    /**
     * Директория конфигурационных файлов
     */ 
    define('CONF',DIR.'/'.PATH.'config');              
    
    /**
     * Индекс(ключ) для определения администратора
     */ 
    define('INDEX_SESSION_ADMIN', 'isadmin');
    
    /**
     * Режим отладки
     */ 
    define('DEBUG',true);
    
    /**
     * Логирование ошибок
     */ 
    define('LOGING',true);
    
    /**
     * Использование красивых ссылок (ЧПУ)
     */ 
    define('REWRITE',true);     
     
     /**
      *  Соль для хеша
      */ 
     define('SALT', 'fw-php'); 
     
     /**
      * ошибки запросов mysql
      */ 
     define('LOG_MYSQLI',DIR.'/'.PATH.'logs/mysqli.txt');
     /**
      * ошибки 404
      */  
     define('LOG_404',DIR.'/'.PATH.'logs/404.txt');
     /**
      * попытка доступа в закрытую часть сайта
      */ 
     define('LOG_ACCESS',DIR.'/'.PATH.'logs/access.txt');
     
     /**
      * Файл не найден
      */
     define('NOT_FOUND',DIR.'/'.PATH.'logs/notfound.txt');
           
     /**
      * Пути к исполняемым файлам открытых разделов
      */ 
     define('MODELS_DIR', DIR.'/'.PATH.'app/models');
     define('CONTROLLERS_DIR', DIR.'/'.PATH.'app/controllers');
     define('TPLS_DIR', DIR.'/'.PATH.'app/tpls');
     /**
      * Пути к исполняемым файлам закрытых разделов (административная часть)
      */ 
     define('ADMIN_MODELS_DIR', DIR.'/'.PATH.'app/models/admin');
     define('ADMIN_CONTROLLERS_DIR', DIR.'/'.PATH.'app/controllers/admin');
     define('ADMIN_TPLS_DIR', DIR.'/'.PATH.'app/tpls/admin'); 
    

 
?>
