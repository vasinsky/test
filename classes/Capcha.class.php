<?php
    /**
     * Класс капчи (используется доп. класс)
     * @author Vasinsky Igor
     * @email igor.vasinsky@gmail.com
     * @copyright 2013
     */   
     
     class Capcha{
        /**
         * Возвращает картинку с кодом
         * @return string
         */ 
        public static function getImageCapcha(){
            return '<img src="/library/coolcapcha/captcha.php" id="captcha" />';
        }
        /**
         * Возвращает ссылку для обновления картинки капчи
         * @param string
         * @return string
         * @return void
         */ 
         public static function getReloadLink($text='Обновить код'){
            return '<a href="#" onclick="
            document.getElementById(\'captcha\').src=\'/library/coolcapcha/captcha.php?\'+Math.random();
            document.getElementById(\'captcha-form\').focus(); return false;"
            id="change-image">'.$text.'</a>';
        }
     }
?>