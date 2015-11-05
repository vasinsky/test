<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

//define('PATH','test/fw/');
//Корневая директория сайта

include "../../../../../../../config/app.php";

define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']);
//Директория с изображениями (относительно корневой)
define('DIR_IMAGES',	'/'.PATH.'uploads/images');
//Директория с файлами (относительно корневой)
define('DIR_FILES',		'/'.PATH.'uploads/data');

define('DIR_MULTI',		'/'.PATH.'uploads/multi');


//Высота и ширина картинки до которой будет сжато исходное изображение и создана ссылка на полную версию
define('WIDTH_TO_LINK', 500);
define('HEIGHT_TO_LINK', 500);

//Атрибуты которые будут присвоены ссылке (для скриптов типа lightbox)
define('CLASS_LINK', 'lightview');
define('REL_LINK', 'lightbox');

date_default_timezone_set('Asia/Yekaterinburg');

?>
