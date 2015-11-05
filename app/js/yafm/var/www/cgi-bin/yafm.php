#!/usr/bin/php
<?php
    
    error_reporting (E_ALL); //only enabled in development releases
    
    session_start();
    
    $fileman_home = $_SERVER['DOCUMENT_ROOT'].'/test/fw/app/js/yafm/var/www/html/yafm';
    $absolute_path = $fileman_home.'/hl'; //required by hl/*

    include_once ("{$absolute_path}/include/stdlib.inc"); //a trimmed down version of hostlib/include/stdlib.inc
    include_once ("{$absolute_path}/lib/tool/unix-file.inc");
    include_once ("{$absolute_path}/lib/input-validate.inc"); //for validate_unix_username
    
    $footer_message = '<a href="http://yafm.sourceforge.net/">YaFM</a> 1.0.5 &copy; 2003-2004 YaFM Team';
    
    include_once ("{$fileman_home}/include/img-constants.inc");
    include_once ("{$fileman_home}/include/preferences.inc");
    include_once ("{$fileman_home}/include/login.inc");
    include_once ("{$fileman_home}/include/fileman-util.inc");
    include_once ("{$fileman_home}/include/process-request.inc");
    include_once ("{$fileman_home}/screens/login.inc");
    include_once ("{$fileman_home}/screens/message.inc");
    include_once ("{$fileman_home}/screens/list-folder.inc");
    include_once ("{$fileman_home}/screens/confirm-delete.inc");
    include_once ("{$fileman_home}/screens/edit-permissions.inc");
    include_once ("{$fileman_home}/screens/edit-file.inc");
    include_once ("{$fileman_home}/screens/upload-multi.inc");
    include_once ("{$fileman_home}/screens/rename.inc");
    include_once ("{$fileman_home}/screens/copy-move.inc");
    include_once ("{$fileman_home}/screens/preferences.inc");
    include_once ("{$fileman_home}/screens/search-form.inc");
    include_once ("{$fileman_home}/screens/error.inc");
    
    $fileman_url  = "$http_protocol://$hostname/test/fw/app/js/yafm/var/www/html/yafm";
    
    
    $_SESSION['fileman_css'] = $fileman_url.'css/style.css';
    
    preferences_init();

    if (empty($_SESSION['fileman_user']))
    {   
        //the user has not authenticated yet
        if (!empty($_REQUEST['action']) && 'login'==strtolower($_REQUEST['action'])) 
        {
            $screen = process_login ($_REQUEST);
        } 
        else 
        {
            $screen = 'screen_login';
        }
        
        if ($screen=='screen_login')
        {
            $screen();
            exit;
        }
    }
    
    //if vfs_id is set we're not browsing a regular folder but a virtual filesystem, e.g.
    //a search result set or the contents of an archive
    $vfs_id = empty($_REQUEST['vfs_id']) ? false 
    : $_REQUEST['vfs_id'];
    
    //try the value in $_REQUEST then $fm_config['HOME_FOLDER'] then default value '/'
    $folder = empty($_REQUEST['folder']) ? (empty($fm_config['HOME_FOLDER']) ? '/' : $fm_config['HOME_FOLDER']) : $_REQUEST['folder'];
    $folder = validate_folder($folder); //check if it's a valid folder and if it's within the tree we're allowed to access
    if (empty($folder))
    {
        $logmessage = 'Folder does not exist or you do not have permissions to access it';
    }
    else
    {
        $folder_contents = reload_folder($folder);
        if (empty($folder_contents))
        {
            $logmessage = 'Folder does not exist or you do not have permissions to access it';
            $screen='';
        }
        else
        {
            $logmessage = '';
            $screen = process_request ($_REQUEST, $folder);
        }
    }

    if (!empty($screen) && function_exists($screen)) $screen();
    else 
    {
        if ((!empty($screen)) && (!function_exists($screen))) 
        {
            $logmessage = "Internal error: no such screen: $screen";
        }
        screen_error();
    }
?>
