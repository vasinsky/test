<?php   
    /**
     * Класс парсера контента
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */
     
    class Parser{ 
        /**
         * string адрес страницы донора
         */ 
        public $url = '';
        /**
         * string подстановка REFERER
         */
        public $referer = '/';
        /**
         * string Маскировка под браузер
         */
        public $useragent = 'ParserBot';
        /**
         * int Таймаут между попытками
         */
        public $timeout = 10;
        /**
         * bool Следовать за редиректом
         */
        public $followlocation = true;
        /** 
         * bool Возвратить контент после парсинга
         */
        public $returndata = true;
        /** 
         * string Файл кук
         */
        private $filecookie = 'cookie.txt';
        /** 
         * bool Использовать куки
         */
        private $cookie = false;
        /**
         * array Инфо и коннекте
         */
        private $info = null;
        /** 
         * bool Запретить проверку сертификата
         */
        public $sslpeer = false;
        /**
         * bool проверка на существования имени хоста
         */
        public $sslhost = false;
        /** 
         * int Версия SSL
         */
        public $sslversion = 2;
        /**
         * bool Используем SSL сертификат
         */
        private $setSSL = false;
        /**
         * string Имя сертификата
         */
        private $sslcert;
        /**
         * string Пароль от сертификата
         */
        private $sslpass;
        /**
         * array Хранение доп настроек для POST и GET запросов
         */
        private $options_query = array();
        /**
         * string для хранения спарсенного контента всей странички
         */
        private $content = '';
        
        /**
         * Подготовка опций для cCurl 
         * return array
         */ 
        private function setOptions(){      
            $options[CURLOPT_URL] = $this->url;
            $options[CURLOPT_REFERER] = $this->referer;
            $options[CURLOPT_CONNECTTIMEOUT] = $this->timeout;
            $options[CURLOPT_RETURNTRANSFER] = $this->returndata;
            $options[CURLOPT_USERAGENT] = $this->useragent;
            
            if(!empty($this->options_query))
                $options = array_merge($options, $this->options_query);            
            
            if($this->cookie === true){
                $options[CURLOPT_COOKIEJAR] = $this->filecookie; 
                $options[CURLOPT_COOKIEFILE] = $this->filecookie;    
            }

            $options[CURLOPT_SSL_VERIFYPEER] = $this->sslpeer;
            $options[CURLOPT_SSL_VERIFYHOST] = $this->sslhost;
            $options[CURLOPT_SSLVERSION] = $this->sslversion;
                    
            if($this->setSSL === true){
                $options[CURLOPT_SSLCERT] = $this->sslcert;
                $options[CURLOPT_SSLCERTPASSWD] = $this->sslpass;
            }
            
            $options[ CURLOPT_FOLLOWLOCATION] = $this->followlocation;  
            
            return $options;     
        } 
        /**
         * Использовать куки
         * @param string - path/name_file_cookie
         */
        public function setCookie($filecookie){
            $filecookie = $this->filecookie;
            $this->cookie = true;
        }
        /**
         * Использовать SSL сертификат
         * @param string - path/name_cert
         * @param string - cert_password 
         * return object
         */
        public function useSSL($cert,$pass){
            $this->setSSL = true;
            $this->sslcert = $cert;
            $this->sslpass = $pass;
            return $this;
        }         
        
        /**
         * Вернёт инфо подключения 
         * return array
         */ 
        public function getInfo(){
            return $this->info;
        } 
        
        /**
         * Инициализация 
         * return object
         */ 
        public function init(){

            if($this->url == '')
                throw new Exception('Не указан URL донора');
                               
            $options = $this->setOptions();
            
            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $out = curl_exec($curl);
            $info = curl_getinfo($curl);
    
            if($info['http_code'] != 200){
                $this->info = $info;
                return false;
            }
    
            curl_close($curl); 
            
            $this->content = ($this->returndata !== false) ? $out : null;   
            
            return $this;       
        }
        
        /**
         * Послать POST запрос
         * @param string - string post params 
         * return object
         */
        public function post($params){
            $this->options_query = array(
                                         CURLOPT_POST => true,
                                         CURLOPT_POSTFIELDS => $params  
                                         );      
            return $this;                               
        }        
        
        /**
         * Смена кодировки    
         * return object
         */ 
        public function changeCharset($beforeCharset, $afterCharset){
            $this->content = iconv($beforeCharset,$afterCharset, $this->content);
            return $this;
        }
        
        /**
         * Возвращает спарсенный контент
         * return string
         */
        public function getContent(){
            if($this->url == null)
                throw new Exception('Не указан URL донора');
            
            return $this->content;
        }

        /**
         * Парсит контент
         * @param string full string pattern regexp
         * @param bool preg_match_all() - default / false - preg_match()
         * return array / bool
         */ 
        public function parse($regexp, $all = true){
            if($this->url == '')
                return false;            
            
            if($all === true){
                preg_match_all($regexp, $this->content, $res);
                return $res;
            }
            elseif($all === false){
                preg_match($regexp, $this->content, $res);
                return $res;
            }
            else
                return false;
        }              
    }
    /************************************* U S E *************************************************************************
    //Простой вариант парсинга
    $parser = new Parser;
    $parser->url = 'http://phpforum.ru';
    $parser->init();
    $res = $parser->parse("#<title>(.*)</title>#i");

   
    //Смена кодировки контента сайта донора
    $parser = new Parser;
    $parser->url = 'http://phpforum.ru';
    $parser->init();
    $parser->changeCharset('windows-1251','utf-8');
    $res = $parser->parse("#<title>(.*)</title>#iu");    
    
    //Использования кук
    $parser = new Parser;
    $parser->url = 'http://phpforum.ru';
    $parser->setCookie('mycookie.txt');
    $parser->init();
    $res = $parser->parse("#<title>(.*)</title>#i");
  
    //Послать POST запрос
    $parser = new Parser;
    $parser->url = 'http://phpforum.ru';
    $parser->post('?pass=пароль&login=логин');
    $parser->init();
    $res = $parser->parse("#<title>(.*)</title>#iu"); 
    
    //Использовать SSL сертификат
    $parser = new Parser;
    $parser->url = 'http://phpforum.ru';
    $parser->useSSL('cert.crt','pass');
    $parser->init();
    $res = $parser->parse("#<title>(.*)</title>#iu");    
    
    //Получить целиком спарсенный контент
  
    $parser = new Parser;
    $parser->url = 'http://phpforum.ru';
    $parser->init();
    $res = $parser->getContent();
    
    //Дополнительные настройки параметров: USERAGENT, FOLLOWLOCATION и т.д.
    //можно установить - обращаясь к публичным свойствам класса
    //$parser->useragent = 'Opera';
    //$parser->followlocation = false;
    //$parser->timeout = 25;  
    //$parser->returndata = false;      
    //$parser->referer = 'http://google.com/bot/';     
    //и т.д.
    **************************************************************************************************************/

?>
