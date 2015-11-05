<?php
    define ('DEFAULT_FILE_UMASK', 0664);
    define ('DEFAULT_DIR_UMASK', 0775);
        
    define ('FILE_READ_SIZE', 131072); //do not attempt to read more than 128KB at a time from a file in order to conserve memory
    define ('EDITOR_MAX_SIZE', 512000); //do not attempt to edit files larger than 500KB in the editor
    
    define ('FM_FORM_METHOD', 'get'); //setting this to get is useful for debugging purposes.
    
    define ('DEFAULT_SORT_ORDER', 'nametype');
    define ('DEFAULT_SIZE_UNIT', 'kb');
    define ('DEFAULT_FOLDER_SIZES', 'disabled');
    define ('DEFAULT_ARCHIVE_FORMAT', 'zip');
    define ('DEFAULT_UPLOAD_SLOTS', 8);
    
    function load_preferences()
    {
        $fm_config = false;
        if (!empty($_SESSION['fileman_config']))
        {
            $fm_config = $_SESSION['fileman_config'];
        }
        else if (!empty($_SESSION['fileman_configfile']))
        {
            error_file ('trying to load preferences from file: '.$_SESSION['fileman_configfile'], 'debug');
            $fm_config = @unserialize(@file_get_contents($_SESSION['fileman_configfile']));
            if (empty($fm_config))
            {
                error_file ('unable to load preferences from session or file!', 'info');
            }
        }
        else
        {
            error_file ('Using default preferences!', 'info');
        }
        
        return empty($fm_config) ? default_preferences() : $fm_config;
    }
    
    function default_preferences()
    {
        if (empty($_SESSION['fileman_allowtree'])) $_SESSION['fileman_allowtree'] = array('/tmp'); //, '/home'
        return array (
            'DEFAULT_FILE_UMASK' => DEFAULT_FILE_UMASK,
            'DEFAULT_DIR_UMASK'  => DEFAULT_DIR_UMASK,
            
            'SORT_ORDER'         => DEFAULT_SORT_ORDER,   //possible values 'name', 'type', 'size', 'nametype', 'namesize'
            'FOLDER_SIZES'       => DEFAULT_FOLDER_SIZES, //folder sizes (possible values: 'enabled', 'disabled')
            'SIZE_UNIT'          => DEFAULT_SIZE_UNIT,    //size measurement unit (possible values: 'b', 'kb', 'mb')
            
            'FILE_READ_SIZE'     => FILE_READ_SIZE,       //do not attempt to read more than 128KB at a time from a file in order to conserve memory
            'EDITOR_MAX_SIZE'    => EDITOR_MAX_SIZE,      //do not attempt to edit files larger than 500KB in the editor

            'ARCHIVE_FORMAT'     => DEFAULT_ARCHIVE_FORMAT, //possible values: zip tar tar.gz tar.bz2
            'MULTI_UPLOAD'       => DEFAULT_UPLOAD_SLOTS,
            
            'HOME_FOLDER'        => $_SESSION['fileman_allowtree'][0],
        );
    }
    
    /**
     * Retrieve preferences from a HTTP request
     */
    function update_preferences ($req)
    {
        global $fm_config, $logmessage;
        
        $home_folder = '';
        if (!empty($req['preferences_homefolder_prepend'])) $home_folder .= $req['preferences_homefolder_prepend'];
        if (!empty($req['preferences_homefolder'])) $home_folder .= '/'.$req['preferences_homefolder'];
        $home_folder = validate_folder($home_folder);
        if (empty($home_folder)) $home_folder = $_SESSION['fileman_allowtree'][0];
        $fm_config['HOME_FOLDER']=$home_folder;
        
        if (!empty($req['preferences_sortorder']) && in_array($req['preferences_sortorder'], array('nametype', 'name', 'sizetype', 'size', 'type')))
        {
            $preferences_sortorder = $req['preferences_sortorder'];
        }
        else
        {
            $preferences_sortorder = DEFAULT_SORT_ORDER;
        }
        $fm_config['SORT_ORDER']=$preferences_sortorder;

        //SIZE_UINT
        if (!empty($req['preferences_sizeunit']) && in_array($req['preferences_sizeunit'], array('b', 'kb', 'mb')))
        {
            $preferences_sizeunit = $req['preferences_sizeunit'];
        }
        else
        {
            $preferences_sizeunit = DEFAULT_SIZE_UNIT;
        }
        $fm_config['SIZE_UNIT']=$preferences_sizeunit;
        
        //FOLDER_SIZES
        if (!empty($req['preferences_dirdu']) && in_array($req['preferences_dirdu'], array('enabled', 'disabled')))
        {
            $preferences_dirdu = $req['preferences_dirdu'];
        }
        else
        {
            $preferences_dirdu = DEFAULT_FOLDER_SIZES;
        }
        $fm_config['FOLDER_SIZES']=$preferences_dirdu;
        
        //FILE_MASK
        if (is_numeric($req['preferences_fileumask']))
        {
            $preferences_fileumask = $req['preferences_fileumask'];
        }
        elseif (is_numeric($req['preferences_fileumask_custom']))
        {
            $preferences_fileumask = $req['preferences_fileumask_custom'];
        }
        else
        {
            $preferences_fileumask = 'n/a';
        }
        while (strlen($preferences_fileumask)<3) $preferences_fileumask='0'.$preferences_fileumask;
        //print ($preferences_fileumask);
        
        if (preg_match('/^\d\d\d$/', $preferences_fileumask))
        {
            $preferences_fileumask = $preferences_fileumask[0]*64 + $preferences_fileumask[1]*8 + $preferences_fileumask[2];
        }
        else
        {
            $preferences_fileumask = DEFAULT_FILE_UMASK;
        }
        $fm_config['DEFAULT_FILE_UMASK']=$preferences_fileumask;
        
        //DIR_MASK
        //preferences_dirumask
        if (is_numeric($req['preferences_dirumask']))
        {
            $preferences_dirumask = $req['preferences_dirumask'];
        }
        elseif (is_numeric($req['preferences_dirumask_custom']))
        {
            $preferences_dirumask = $req['preferences_dirumask_custom'];
        }
        else
        {
            $preferences_dirumask = 'n/a';
        }
        while (strlen($preferences_dirumask)<3) $preferences_dirumask='0'.$preferences_dirumask;
        
        if (preg_match('/^\d\d\d$/', $preferences_dirumask))
        {
            $preferences_dirumask = $preferences_dirumask[0]*64 + $preferences_dirumask[1]*8 + $preferences_dirumask[2];
        }
        else
        {
            $preferences_dirumask = DEFAULT_DIR_UMASK;
        }
        //print ($preferences_dirumask);
        $fm_config['DEFAULT_DIR_UMASK']=$preferences_dirumask;
        
        //EDITOR_MAX_SIZE
        if (!empty($req['preferences_editormaxsize']) && is_numeric($req['preferences_editormaxsize']))
        {
            $preferences_editormaxsize = $req['preferences_editormaxsize'];
            if ($preferences_editormaxsize<5000) $preferences_editormaxsize = 5000;
            if ($preferences_editormaxsize>655360) $preferences_editormaxsize = 655360; //640K
        }
        else
        {
            $preferences_editormaxsize = EDITOR_MAX_SIZE;
        }
        $fm_config['EDITOR_MAX_SIZE']=$preferences_editormaxsize;
        
        //ARCHIVE_FORMAT
        if (!empty($req['preferences_downloadformat']) && in_array($req['preferences_downloadformat'], array('zip', 'tar', 'tar.gz', 'tar.bz2')))
        {
            $preferences_downloadformat = $req['preferences_downloadformat'];
        }
        else
        {
            $preferences_downloadformat = DEFAULT_ARCHIVE_FORMAT;
        }
        $fm_config['ARCHIVE_FORMAT']=$preferences_downloadformat;
        
        //MULTI_UPLOAD
        if (!empty($req['preferences_uploadslots']) && is_numeric($req['preferences_uploadslots']))
        {
            $preferences_uploadslots = $req['preferences_uploadslots'];
            if ($preferences_uploadslots<3) $preferences_uploadslots = 3;
            if ($preferences_uploadslots>30) $preferences_uploadslots = 30; //640K
        }
        else
        {
            $preferences_uploadslots = DEFAULT_UPLOAD_SLOTS;
        }
        $fm_config['MULTI_UPLOAD']=$preferences_uploadslots;
        
        $_SESSION['fileman_config']=$fm_config;
        
        return true;
    }
    
    function save_preferences()
    {
        global $fm_config;
        
        //return true;
        if (empty($_SESSION['fileman_configfile']) || !validate_path($_SESSION['fileman_configfile']) || !$fp=@fopen($_SESSION['fileman_configfile'], 'w'))
        {
            error_file('save_preferences: Unable to save preferences: $_SESSION[\'fileman_configfile\'] variable does not contain an absolute path to a writable file.', 'error');
            return false;
        }
        else
        {
            $fm_config_ser = serialize($fm_config);
            $bytes = @fwrite ($fp, $fm_config_ser, strlen($fm_config_ser));
            @fclose($fp);
            return $bytes;
        }
    }
    
    function preferences_init ()
    {
        global $fm_config;
        $fm_config = load_preferences();
        $_SESSION['fileman_config']=$fm_config;
    }
?>
