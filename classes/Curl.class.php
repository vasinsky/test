<?php

    /**
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */


    class Curl{
        public $options;
        public $returndata;
        
        public function _construct(){
      
        }
        /**
         * @param array - массив настроек для курла
         * @param bool - вернуть контент или нет
         */ 
        static public function getPage($options, $returndata=true){
            $curl = curl_init();
            //Таймаут на подключение 20 сек
            $options[CURLOPT_CONNECTTIMEOUT] = 20;
            
            curl_setopt_array($curl, $options);
            $out =curl_exec($curl);
    
            $info = curl_getinfo($curl);
    
            curl_close($curl); 
            
            return ($returndata !== false) ? $out : null;
            
        }
    }
/**
        $options = array(
             CURLOPT_URL=>'http://phpforum.ru/index.php?act=Login&CODE=01&CookieDate=1',
             CURLOPT_REFERER=>$this->index_page,
             CURLOPT_RETURNTRANSFER=>true,
             CURLOPT_USERAGENT=> $this->userAgent,
             CURLOPT_COOKIEJAR=>$this->cookie_file,
             CURLOPT_COOKIEFILE=>$this->cookie_file,
             CURLOPT_FOLLOWLOCATION=>true,
             CURLOPT_POST=>true ,
             CURLOPT_POSTFIELDS=>"UserName=".iconv('utf-8','windows-1251',$username)
                                            ."&PassWord=".iconv('utf-8','windows-1251',$password)  
                        );
                        
        $options = array(
             CURLOPT_URL=>$this->getUrl(),
             CURLOPT_REFERER=>$this->index_page,
             CURLOPT_RETURNTRANSFER=>true,
             CURLOPT_USERAGENT=> $this->userAgent,
             CURLOPT_COOKIEJAR=>$this->cookie_file,
             CURLOPT_COOKIEFILE=>$this->cookie_file,
             CURLOPT_FOLLOWLOCATION=>false, 
        );  
*/                                    

?>