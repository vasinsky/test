<?php
    /**
     * Класс для работы с файлами, подключение, чтение, запись и т.д.
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */
     
    class Files{
        const DEBUG = DEBUG;
        
        public function __construct(){
            
        }
        /**
         *  Обёртка для Include() с проверкой на существование файла
         *  @param string - путь до файла
         *  @param bool - создаватьили нет  файл в случае его отсутствия
         */ 
        static function load($pathtofile, $create = false){
            if(file_exists($pathtofile)){
    
                $data = Bufer::getData();
                
                extract($data);
                
                include $pathtofile;
            }    
            else{
                if($create === true and self::DEBUG === true)
                    file_put_contents($pathtofile, 'FILE: '.$pathtofile.' created but his empty!<br/>');
                else{  
                    throw new Exception('Ошибка подключения '.$pathtofile);          
                }
            }
        }
        /**
         * Логирование ошибок
         * @param string - имя и путь к файлу - логу
         * @param string - текст логируемой ошибки
         * Предопределённые константы файлов логов - папка logs/
         * LOG_MYSQLI - ошибки запросов mysqli
         * LOG_404 - 404 ошибки
         * LOG_ACCESS - попытка доступа в закрытую часть сайта
         *  
         */    
        static public function addtolog($filelog,$error){
            if(LOGING === true){
                $datetime = date('d/m/Y H:i:s');
                $url = $_SERVER['REQUEST_URI'];
                $useragent = $_SERVER['HTTP_USER_AGENT'];
                $ip = $_SERVER['REMOTE_ADDR'];
                $refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'no';
                
                $text = $datetime.PHP_EOL
                       .$url.PHP_EOL
                       .'->'.$refer.PHP_EOL
                       .$useragent.PHP_EOL
                       .$ip.PHP_EOL
                       .$error;
               
                file_put_contents($filelog,$text);
            }
        }
        
        static function getData($pathtofile){
            if(!file_exists($pathtofile)){
                self::addtolog(NOT_FOUND, 'Не найден файл');
                
                $fileData = 'Not found file';
            }
            else{
                $fileData = file_get_contents($pathtofile);
            }
            
            return $fileData;
        }

    } 
?>