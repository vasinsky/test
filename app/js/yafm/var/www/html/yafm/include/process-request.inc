<?php

    include_once ("{$fileman_home}/include/random.inc");
    include_once ("{$fileman_home}/screens/message.inc");

    function process_request($req, $folder)
    {
        global $logmessage, $folder_contents, $fm_config;
        global $fileman_url;
        
        $screen='screen_folder_list';
        
        //print ("<pre>");
        //print_r($req);
        //print ("</pre>");
        
        if (!empty($req['action']))
        {
            $action = strtolower($req['action']);
            $selecteds = get_selected($req);
            $selecteds_e = array();
            foreach ($selecteds as $selected) $selecteds_e[] = escapeshellarg($selected);
            
            switch ($action)
            {
                case 'create' :
                    if (empty($req['create_what']) || empty($req['newname']))
                    {
                        $logmessage = "Unable to process your request: required parameter(s) omitted.";
                    }
                    else
                    {
                        switch ($req['create_what'])
                        {
                            case 'file' :
                                $path = $folder.$req['newname'];
                                $path = validate_path($path);
                                if (empty($path))
                                {
                                    $logmessage = "You don't have permissions to create file: {$folder}{$req['newname']}";
                                }
                                else if (@touch ($path))
                                {
                                    $logmessage = "File {$req['newname']} created successfully";
                                    @chmod($path, $fm_config['DEFAULT_FILE_UMASK']);
                                    $folder_contents = reload_folder ($folder);
                                }
                                else
                                {
                                    $logmessage = "Unable to create file: {$req['newname']}";
                                }
                                break;
                            case 'directory' :
                                $path = $folder.$req['newname'];
                                $path = validate_path($path);
                                if (empty($path))
                                {
                                    $logmessage = "You don't have permissions to create file: {$folder}{$req['newname']}";
                                }
                                else if (@mkdir ($path, empty($fm_config['DEFAULT_DIR_UMASK']) ? DEFAULT_DIR_UMASK : $fm_config['DEFAULT_DIR_UMASK']))
                                {
                                    $logmessage = "Folder {$req['newname']} created successfully.";
                                    @chmod($path, $fm_config['DEFAULT_DIR_UMASK']);
                                    $folder_contents = reload_folder ($folder);
                                }
                                else
                                {
                                    $logmessage = "Unable to create folder: {$req['newname']}";
                                }
                                break;
                            default :
                                $logmessage = "Unable to process your request: invalid parameters received.";
                                break;
                        }
                    }
                    break;
                case 'delete' :
                    //print ("<pre>");
                    //print_r($selecteds);
                    //print ("</pre>");
                    if (empty($selecteds)) $logmessage = "You must select at least one file or folder for this operation.";
                    else //foreach ($selecteds as $selected)
                    {
                        //if (empty($folder_contents)) break;
                        
                        $screen = 'screen_confirm_delete';
                        $logmessage = "About to delete the following entries";
                        $folder_contents_all = $folder_contents;
                        $folder_contents = array();
                        foreach ($folder_contents_all as $name=>$entry)
                        {
                            if (in_array($name, $selecteds)) $folder_contents[$name]=$entry; //filter only the selected entries!
                        }

                    }
                    break;
                case 'confirm deletion' :
                    if (empty($selecteds)) $logmessage = "You must select at least one file or folder for this operation.";
                    else 
                    {
                        $successful = array();
                        $failed = array();
                        foreach ($selecteds as $selected)
                        {
                            $path = $folder.$selected;
                            $path = validate_path($path);
                            if (empty($path))
                            {
                                $failed[]=$selected;
                            }
                            else
                            {
                                if (@ini_get('safe_mode'))
                                {
                                    $rmout = my_rm_rf($path);
                                    if (empty($rmout))
                                    {
                                        $successful[]=$selected;
                                    }
                                    else
                                    {
                                        $failed[]=$selected;
                                    }
                                }
                                else
                                {
                                    $rmpath = escapeshellarg($path);
                                    $rmout = `rm -rf $rmpath 2>&1`;
                                    if (empty($rmout))
                                    {
                                        $successful[]=$selected;
                                    }
                                    else
                                    {
                                        $failed[]=$selected;
                                    }
                                }
                            }
                        }
                        $logmessage='';
                        if (!empty($successful)) $logmessage.='Deleted: '.join (', ', $successful);
                        if (!empty($failed)) $logmessage.=' Unable to delete: '.join (', ', $failed);
                        $folder_contents = reload_folder ($folder);
                        //print_r($selecteds);
                    }
                    break;
                case 'download' :
                    if (empty($selecteds)) $logmessage = "You must select at least one file or folder for this operation.";
                    else 
                    {
                        $args = join (' ', $selecteds_e);
                        $dir_e = escapeshellarg($folder);
                        
                        $preferred_format = empty($fm_config['ARCHIVE_FORMAT']) ? DEFAULT_ARCHIVE_FORMAT : $fm_config['ARCHIVE_FORMAT'];
                        session_write_close(); //allow the user to keep using yafm while the download is progressing
                        set_time_limit(3600); //one hour
                        switch ($preferred_format)
                        {
                            case 'zip' :
                                header("Content-Type: application/zip");
                                header("Content-disposition: attachment; filename=yafm-download.zip");
                                //zip SUCKS SUCKS SUCKS
                                //it doesn't work if output is redirected to a pipe. It *needs* to be redirected to a file!
                                //how *stupid* is that?
                                //hint: use .tar* instead ;-)
                                $tmpfile="/tmp/fileman-".time().random_string(15).".zip";
                                error_file ("cd $dir_e && zip -y -r - $args > $tmpfile", 'debug');
                                @passthru("cd $dir_e && zip -y -r - $args > $tmpfile");
                                
                                /*
                                //bandwidth throttling
                                $slow = fopen ($tmpfile, 'r');
                                while (!feof($slow)) {
                                    print(fread($slow, 16384)); //aprox 16 kbytes/sec
                                    sleep (1);
                                }
                                fclose($slow);
                                */                                
                                
                                @readfile ($tmpfile);
                                @unlink($tmpfile);
                                break;
                            case 'tar' :
                                header("Content-Type: application/x-tar");
                                header("Content-disposition: attachment; filename=yafm-download.tar");
                                passthru("tar --directory=$dir_e -c $args 2>&1");
                                break;
                            case 'tar.gz' :
                                header("Content-Type: application/x-gzip");
                                header("Content-Encoding: x-gzip");
                                header("Content-disposition: attachment; filename=yafm-download.tar.gz");
                                passthru("tar --directory=$dir_e -cz $args 2>&1");
                                break;
                            case 'tar.bz2' :
                                header("Content-Type: Content-Type: application/x-bzip2");
                                header("Content-disposition: attachment; filename=yafm-download.tar.bz2");
                                error_file ("tar --directory=$dir_e -c $args | bzip2 2>&1", 'debug');
                                passthru("tar --directory=$dir_e -c $args | bzip2 2>&1");
                                break;
                            default :
                                //this should never happen ;-)
                                error_file ('unrecognized archive format '.$preferred_format, 'error');
                                header("Content-Type: text/plain");
                                print ("FATAL: Unrecognized archive format: $preferred_format\n");
                                break;
                        }
                        exit;
                    }
                    break;
                case 'move' :
                case 'copy' :
                    if (empty($selecteds)) $logmessage = "You must select at least one file or folder for this operation.";
                    else foreach ($selecteds as $selected)
                    {
                        global $action_label;
                        $action_label = $action;
                        $screen = 'screen_copy_move';
                        $logmessage = "Select destination folder for the following files";
                        $folder_contents_all = $folder_contents;
                        $folder_contents = array();
                        
                        /*
                        print('<pre>');
                        print_r($folder_contents_all);
                        print('</pre>');
                        */
                        foreach ($folder_contents_all as $name=>$entry)
                        {
                            $thename = empty($entry['virtualname']) ? $entry['basename'] : $entry['virtualname'];
                            if (in_array($thename, $selecteds)) $folder_contents[$name]=$entry; //filter only the selected entries!
                        }
                    }
                    break;
                case 'confirm move' :
                case 'confirm copy' :
                    if (empty($selecteds) || empty($req['target_folder']) || empty($req['target_folder_prepend']) )
                    {
                        $logmessage = "Unable to process your request: required parameter(s) omitted.";
                    }
                    else
                    {
                        global $action_label;
                        $action = str_replace('confirm ', '', $action);
                        $action_label = $action;
                        $targetdir = validate_folder('/'.$req['target_folder_prepend'].'/'.$req['target_folder']);
                        if (empty($targetdir))
                        {
                            $logmessage = "Unable to $action files because you don't have write permissions to the target folder or it does not exist";
                        }
                        else
                        {
                            if (@ini_get('safe_mode'))
                            {
                                $copyout = my_copy_move($selecteds, $folder, $targetdir, $action=='move');
                            }
                            else
                            {
                                $args = join (' ', $selecteds_e);
                                //print ("$targetdir\n<br>"); break;
                                $curdir_e = escapeshellarg($folder);
                                $dir_e = escapeshellarg($targetdir);
                                $cmd = ($action=='move') ? 'mv' : 'cp -R';
                                //print ("cd $curdir_e && $cmd -R $args $dir_e 2>&1");
                                $copyout = `cd $curdir_e && $cmd $args $dir_e 2>&1`;
                                //print ($copyout);
                            }
                            
                            if (!empty($copyout))
                            {
                                $logmessage='Operation failed, please check your permissions over the source files and the target folder';
                            }
                            else
                            {
                                $logmessage='Operation succeeded.';
                                if ($action=='move') $folder_contents = reload_folder ($folder);
                            }
                        }
                    }
                    break;
                case 'searchform' : 
                    if (empty($req['folder']))
                    {
                        $logmessage = "Unable to process your request: required parameter(s) omitted.";
                    }
                    else
                    {
                        $screen = 'screen_search_form';
                    }
                    break;
                case 'search' : 
                    if (empty($req['folder'])) 
                    {
                        $logmessage = "Unable to process your request: required parameter(s) omitted.";
                        break;
                    }
                    
                    $filepat = empty($req['search_filemask']) ? '*' : $req['search_filemask'];
                    $recursive = !empty($req['search_recursive']);
                    
                    $logmessage = "Searhing for $filepat in $folder";
                    
                    session_write_close(); //release session lock while we're searching...
                    set_time_limit(600); //search must complete in 10 minutes
                    
                    if (empty($req['search_text']))
                    {
                        $filelist = my_find ($folder, $filepat, $recursive);
                    }
                    else
                    {
                        $logmessage .= " containing {$req['search_text']}";
                        $ignorecase = empty($req['search_sensitive']);
                        $filelist = my_grep ($req['search_text'], $folder, $filepat, $recursive, $ignorecase);
                    }
                    
                    global $vfs_id;
                    $vfs_id = "VFS_SEARCH";//: '$folder' '$filepat' '$recursive'";
                    
                    $folder_contents = array();
                    if (empty($filelist))
                    {
                        //nothing found!
                    }
                    else foreach ($filelist as $file)
                    {
                        $stat = my_stat ($file);
                        $basename = $stat['basename'];
                        //print ("<pre>name:{$stat['name']}</pre>");
                        if ($stat['name'] == $folder) {
                            $stat['virtualname'] = '.';
                        }
                        else
                        {
                            $stat['virtualname'] = str_replace ($folder, '', $stat['name']);
                        }
                        //print ("<pre>virtual: {$stat['virtualname']}</pre>");
                        $stat['vfs'] = $vfs_id;
                        $folder_contents[$basename] = $stat;
                    }
                    session_start(); //reopen/relock session so we can write the vfs_cache thing
                    $_SESSION['vfs_cache'] = array($vfs_id => $folder_contents);
                    
                    //print ('<pre>');
                    //print ("$folder\n");
                    //print_r($filelist);
                    //print_r($folder_contents);
                    //print ('</pre>');
                    
                    global $search_mode;
                    $search_mode = true;
                    
                    break;
                case 'permissions' :
                    //print ("<pre>");
                    //print_r($selecteds);
                    //print ("</pre>");
                    if (empty($selecteds)) $logmessage = "You must select at least one file or folder for this operation.";
                    else foreach ($selecteds as $selected)
                    {
                        $screen = 'screen_edit_permissions';
                        $logmessage = "Adjust the permissions of the following entries";
                        //print ('<pre>');
                        //print ("FILTERING SELECTEDS IN ProcessRequest->'permissions'\n");
                        //print_r ($folder_contents);
                        //print ('</pre>');
                        $folder_contents_all = $folder_contents;
                        $folder_contents = array();
                        if (empty($selecteds)) $logmessage = "You must select at least one file or folder for this operation.";
                        else foreach ($folder_contents_all as $name=>$entry)
                        {
                            $thename = empty($entry['virtualname']) ? $entry['basename'] : $entry['virtualname'];
                            if (in_array($thename, $selecteds)) $folder_contents[$name]=$entry; //filter only the selected entries!
                        }

                    }
                    break;
                case 'set permissions' :
                    $selectedpermissions = get_selected_permissions($selecteds, $req);
                    if (empty($selecteds) || empty($selectedpermissions)) $logmessage = "You must select at least one file or folder for this operation.";
                    else 
                    {
                        //print ("<pre>ProcessRequest->'set permissions'");
                        //print_r($selectedpermissions);
                        //print ("</pre>");
                        $successful = array();
                        $failed = array();
                        foreach ($selecteds as $selected)
                        {
                            $path = $folder.$selected;
                            //print ("VALIDATING PATH:$path\n");
                            $path = validate_path($path);
                            //print ("VALIDATED PATH:$path\n");
                            if (empty($path))
                            {
                                $failed[]=$selected;
                            }
                            else
                            {
                                $chmodout = @chmod($path, $selectedpermissions[$selected]);
                                //print ("CHMODOUT: '$chmodout' to '{$selectedpermissions[$selected]}'\n");
                                if (!empty($chmodout))
                                {
                                    $successful[]=$selected;
                                }
                                else
                                {
                                    $failed[]=$selected;
                                }
                            }
                        }
                        $logmessage='';
                        if (!empty($successful)) $logmessage.='Modified: '.join (', ', $successful);
                        if (!empty($failed)) $logmessage.=' Unable to set permissions for: '.join (', ', $failed);
                        $folder_contents = reload_folder ($folder);
                        //print_r($selecteds);
                    }
                    break;
                case 'editfile' :
                    if (empty($req['folder']) || empty($req['filename']))
                    {
                        $logmessage = "Unable to process your request: required parameter(s) omitted.";
                    }
                    else
                    {
                        $screen = 'screen_edit_file';
                        $logmessage = "Loading file {$req['filename']}";
                    }
                    break;
                case 'save changes' :
                    if (empty($req['folder']) || empty($req['filename']) || empty($req['edit_contents']))
                    {
                        $logmessage = "Unable to process your request: required parameter(s) omitted.";
                    }
                    else
                    {
                        $filename = $req['filename'];
                        $path = $folder.$filename;
                        $path = validate_path($path);
                        if (empty($path))
                        {
                            $logmessage = "Permission denied for {$req['filename']}";
                        }
                        else
                        {
                            $fd=@fopen ($path, "w");
                            if (!$fd)
                            {
                                error_file ("process-request: could not open file for writing: {$filename}", "error");
                                $logmessage = "Unable to write to {$req['filename']}";
                            }
                            else
                            {
                                if (get_magic_quotes_gpc())
                                {
                                    $req['edit_contents'] = stripslashes($req['edit_contents']);
                                }
                                $bytes = fwrite ($fd, $req['edit_contents']);
                                if ($bytes <= 0)
                                {
                                    error_file ("process-request: zero bytes written to file: {$filename}", "warn");
                                }

                                $fd = @fclose ($fd);
                                if (!$fd)
                                {
                                    error_file ("process-request: could not close file: $filename", "warn");
                                }
                                $logmessage = "$bytes bytes written to file {$req['filename']}";
                                $folder_contents = reload_folder ($folder);
                            }
                        }
                    }
                    break;
                case 'multi' :
                    $screen = 'screen_multi_file';
                    $logmessage='Select one or more files for upload and click the UPLOAD button';
                    break;
                case 'upload' :
                    //print ("<pre>");
                    //print_r($_REQUEST);
                    //print_r($_FILES);
                    //print ("</pre>");
                    $files = get_upload_files($_FILES);
                    if (empty($req['folder']) || empty($files))
                    {
                        $logmessage = "Unable to process your request: required parameter(s) omitted.";
                    }
                    else
                    {
                        $successful = array();
                        $failed = array();
                        foreach ($files as $file)
                        {
                            $filename = $file['name'];
                            $path = $folder.$filename;
                            $path = validate_path($path);
                            if (empty($path))
                            {
                                //$logmessage = "Permission denied for {$filename}";
                                $failed[]=$filename;
                            }
                            else
                            {
                                if (@move_uploaded_file($file['tmp_name'], $folder.$file['name']))
                                {
                                    if (!@chmod($folder.$file['name'], empty($fm_config['DEFAULT_FILE_UMASK']) ? DEFAULT_FILE_UMASK : $fm_config['DEFAULT_FILE_UMASK']))
                                    {
                                        //!!!just issue a warning!
                                    }
                                    $successful[]=$file['name'];
                                }
                                else
                                {
                                    $failed[]=$file['name'];
                                }
                            }
                        }
                        $logmessage='';
                        if (!empty($successful)) $logmessage.='Uploaded: '.join (', ', $successful);
                        if (!empty($failed)) $logmessage.=' Unable to upload: '.join (', ', $failed);
                        $folder_contents = reload_folder ($folder);
                    }
                    break;
                case 'showrename' :
                    if (empty($req['folder']) || empty($selecteds))
                    {
                        if (empty($selecteds)) $logmessage = "You must select at least one file or folder for this operation.";
                        else $logmessage = "Unable to process your request: required parameter(s) omitted.";
                    }
                    else
                    {
                        $screen = 'screen_rename';
                        $logmessage = "Enter a new name for each of the following files";
                        //print ('<pre>');
                        //print ("FILTERING SELECTEDS IN ProcessRequest->'showrename'\n");
                        //print_r ($folder_contents);
                        //print ('</pre>');
                        $folder_contents_all = $folder_contents;
                        $folder_contents = array();
                        //here
                        foreach ($folder_contents_all as $name=>$entry)
                        {
                            $thename = empty($entry['virtualname']) ? $entry['basename'] : $entry['virtualname'];
                            if (in_array($thename, $selecteds)) $folder_contents[$name]=$entry; //filter only the selected entries!
                        }
                    }
                    break;
                case 'rename' :
                    //print_r(get_selected($req)); break;
                    $rename_data = get_rename_data ($selecteds, $req);
                    //print ("<pre>");
                    //print_r ($rename_data);
                    //print ("</pre>");
                    if (empty($rename_data)) $logmessage = "You must select at least one file or folder for this operation.";
                    else 
                    {
                        $successful = array();
                        $failed = array();
                        foreach ($rename_data as $oldname=>$newname)
                        {
                            $oldpath = $folder.$oldname;
                            $oldpath = validate_path($oldpath);
                            $newpath = $folder.'/'.dirname($oldname).'/'.$newname;
                            $newpath = validate_path($newpath);
                            //print ("<pre>RENAMING $oldpath to $newpath\n</pre>");
                            if (empty($oldpath)||empty($newpath))
                            {
                                $failed[]=$oldname;
                            }
                            else
                            {
                                if (@rename($oldpath, $newpath))
                                {
                                    $successful[]=$oldname;
                                }
                                else
                                {
                                    $failed[]=$oldname;
                                }
                            }
                        }
                        $logmessage='';
                        if (!empty($successful)) $logmessage.='Renamed: '.join (', ', $successful);
                        if (!empty($failed)) $logmessage.=' Unable to rename: '.join (', ', $failed);
                        $folder_contents = reload_folder ($folder);
                    }
                    break;
                case 'preferences' :
                    $logmessage = "Customize your File Manager.";
                    $screen='screen_preferences';
                    break;
                case 'set preferences' :
                    if (update_preferences($req))
                    {
                        if (save_preferences())
                        {
                            $logmessage='Preferences updated successfully.';
                        }
                        else
                        {
                            $logmessage='Unable to save preferences to persistent storage. Preferences will be lost once you logout.';
                        }
                    }
                    else
                    {
                        $logmessage='Unable to set preferences.';
                    }
                    $folder_contents = reload_folder ($folder);
                    break;
                case 'logout' :
                    //$logmessage = "No mess.";
                    unset($_SESSION['fileman_config']);
                    unset($_SESSION['fileman_user']);
                    unset($_SESSION['fileman_configfile']);
                    unset($_SESSION['fileman_allowtree']);
                    unset($_SESSION['fileman_css']);
                    if (!empty($_SESSION['fileman_returnurl']))
                    {
                        header("Location: ".$_SESSION['fileman_returnurl']);
                        unset($_SESSION['fileman_returnurl']);
                        session_destroy();
                        exit;
                    }
                    else
                    {
                        $screen = 'screen_login';
                        $_SESSION['fileman_css'] = $fileman_url.'css/style.css';
                        session_destroy();
                        //print ('SCREEN');
                    }
                    break;
                case 'login' :
                    $screen = process_login ($req);
                    break;
                case 'cancel' :
                    $logmessage = "Action cancelled.";
                    break;
                default :
                    $logmessage = "Unable to process your request: invalid parameters received.";
                    break;
            }
        }
        
        return $screen;
    }
    
    function process_login($req)
    {
        global $logmessage;
        
        if (empty($req['username']) || empty($req['password']))
        {
            $logmessage='You must enter username and password';
            $screen = 'screen_login';
        }
        else
        {
            if (fm_login($req['username'], $req['password']))
            {
                $logmessage="Welcome to File Manager, {$_SESSION['fileman_user']}!";
                $screen = 'screen_folder_list';
                preferences_init();
            }
            else
            {
                $logmessage='Login failed, please check your username and password and try again';
                $screen = 'screen_login';
            }
        }
        
        return $screen;
    }
?>
