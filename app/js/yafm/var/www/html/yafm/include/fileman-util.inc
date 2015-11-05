<?php
    //fileman helper routines

    /**
     * makes sure that the folder exists and is inside the $_SESSION['fileman_allowtree'] 
     * (if no configuration is performed ALLOW_TREE be default is /tmp which means the user only can access files in /tmp)
     */
    function validate_folder($folder)
    {
        global $fm_config;
     
        $folder = realpath($folder);
        if (empty($folder)) return false;
        if (!preg_match('/\/$/', $folder)) $folder = $folder.'/';
        return in_allow_tree($folder, empty($_SESSION['fileman_allowtree']) ? false : $_SESSION['fileman_allowtree']);
    }
    
    function in_allow_tree($folder, $allow_tree)
    {
        if (empty($allow_tree) || !is_array($allow_tree)) 
        {
            error_file ('in_allow_tree: unable to determine the folder this user is allowed to access', 'error');
            return false;
        }
        foreach ($allow_tree as $allow_folder)
        {
            if (trim($allow_folder)=='') continue;
            if (!preg_match('/\/$/', $allow_folder)) $allow_folder = $allow_folder.'/'; //append a slash if necessary
            //error_file ("comparing folder: $folder and $allow_folder", 'debug');   
            if (substr($folder, 0, strlen($allow_folder)) == $allow_folder) return $folder; //make sure the folder is under ALLOW_TREE
        }
        return false;
    }
    
    function validate_path($path) //path to a file
    {
        $file = basename($path);
        $folder = dirname($path);
        if (empty($folder)||empty($file)) return false;
        $folder = validate_folder($folder);
        if (empty($folder)) return false;
        return $folder.$file;
    }
    
    function reload_folder ($folder)
    {
        global $fm_config;
        
        global $vfs_id;
        if (!empty($vfs_id)) 
        {
            //print ('<pre>');
            //print ("reload_folder: maybe a virtual folder: $folder");
            if (!empty($_SESSION['vfs_cache'][$vfs_id]))
            {
                //print_r($_SESSION['vfs_cache'][$vfs_id]);
                //print ('</pre>');
                return $_SESSION['vfs_cache'][$vfs_id];
            }
            //print ('</pre>');
        }
        else
        {
            //print ("NORMAL RELOAD");
        }
        
        $contents = my_dir($folder);
        
        if (empty($contents)) return $contents;
        
        $m_unit = empty($fm_config['SIZE_UNIT']) ? 'b' : $fm_config['SIZE_UNIT'];
        
        foreach ($contents as $name=>$entry)
        {
            if (!empty($fm_config['FOLDER_SIZES']) && $fm_config['FOLDER_SIZES']=='enabled' &&
                    $entry['type']=='directory' && $name!='..')
            {
                $entry['size'] = unix_du ($folder.$name);
                //error_file ('reload_folder: entry size: '.$entry['size'], 'debug');
            }

            if ($entry['size'] === false) $entry['size_formatted'] = 'N/A';
            else switch ($m_unit)
            {
                case 'b' :
                    $entry['size_formatted'] = $entry['size'];
                    break;
                case 'kb' :
                    $entry['size_formatted'] = number_format($entry['size'] / 1024, 2, '.', ',') . ' kb';
                    break;
                case 'mb' :
                    $entry['size_formatted'] = number_format($entry['size'] / 1048576, 2, '.', ',') . ' mb';
                    break;
                default :
                    error_file ('reload_folder: invalid value for fm_config[FOLDER_SIZES]: '.$fm_config['FOLDER_SIZES'], 'warn');
                    $entry['size_formatted'] = $entry['size'].'?';
                    break;
            }
            $contents[$name] = $entry;
        }
        //print ('<pre>');
        //print_r($contents);
        //print ('</pre>');
        $sort_order = empty($fm_config['SORT_ORDER']) ? 'nametype' : $fm_config['SORT_ORDER'];
        return sort_folder($contents, $sort_order);
    }
    
    /*
    function htmlesc ($string)
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }
    */
    
    /**
     * returns an array of selected entries, e.g. array(
     *     17 => '17th file',
     *     21 => 'some folder',
     * )
     */
    function get_selected ($req)
    {
        $result = array();
        foreach ($req as $name=>$value)
        {
            if (preg_match('/^selected_(\d+)$/', $name, $matches))
            {
                $result[$matches[1]]=$value;
            }
        }
        return $result;
    }
    
    /**
     * returns an array (
     *     file_name => '775', //permissions are in DECIMAL (suitable for direct feed to PHP's chmod function)
     * );
     * if inside a value corresponding to a $_REQUEST['permissions_*'] key, if inside that value 
     * a filename is found that is not in the $selected_files array, it's silently ignored.
     */
    function get_selected_permissions($selected_files, $req)
    {
        $result = array();
        
        foreach ($selected_files as $selected) $result[$selected]=0;
        
        //print ('<pre>');
        //print ("get_selected_permissions: SELECTED_FILES:\n");
        //print_r($selected_files);
        //print ('</pre>');
        
        foreach ($req as $name=>$value)
        {
            if (substr($name, 0, 12)!='permissions_') continue;
            
            $permtype = substr($value, 0, 2); //permtype is now something like ur (user read) or ox (others execute)
            $filename = substr($value, 3);
            if (!in_array($filename, $selected_files)) 
            {
                //print ("$filename not in selected_files :-(\n");
                continue; //silently ignore info about filenames that are not in $selected_files
            }
            
            switch ($permtype)
            {
                case 'ur' :
                    $result[$filename] |= S_IRUSR;
                    break;
                case 'uw' :
                    $result[$filename] |= S_IWUSR;
                    break;
                case 'ux' :
                    $result[$filename] |= S_IXUSR;
                    break;
                case 'gr' :
                    $result[$filename] |= S_IRGRP;
                    break;
                case 'gw' :
                    $result[$filename] |= S_IWGRP;
                    break;
                case 'gx' :
                    $result[$filename] |= S_IXGRP;
                    break;
                case 'or' :
                    $result[$filename] |= S_IROTH;
                    break;
                case 'ow' :
                    $result[$filename] |= S_IWOTH;
                    break;
                case 'ox' :
                    $result[$filename] |= S_IXOTH;
                    break;
                default :
                    break;
            }
        }
        //print_r($result);
        return $result;
    }
    
    function get_upload_files($files)
    {
        if (!is_array($files) || empty($files))
        {
            //!!!issue a warning here!
            return false;
        }
        //print ("<pre>");
        //print_r($files);
        //print ("</pre>");
        
        $result = array();
        foreach ($files as $name=>$entry)
        {
            if (substr($name, 0, 12) != 'upload_file_') continue;
            if (empty($entry['name']) || empty($entry['tmp_name'])) continue;
            $result[]=$entry;
        }
        return $result;
    }
    
    function get_rename_data($selected_files, $req)
    {
        $result = array();
        foreach ($req as $name=>$value)
        {
            if (preg_match('/^new_name_(\d+)$/', $name, $matches))
            {
                if (empty($selected_files[$matches[1]])) continue; //silently ignore
                $result[$selected_files[$matches[1]]]=$value;
            }
        }
        return $result;
    }
    
    /**
     * Generates the required html that allows us to navigate to the parent folders
     * we have permission to
     */
    function htmlnav($folder) 
    {
        $items = split ('/', $folder);
        //print ('<pre>');
        //print_r ($items);
        $try = '/';
        $html = "/";
        foreach ($items as $item)
        {
            if ($item=='') continue;
            $item_e = htmlesc($item);
            $try .= $item;
            //print ("yo $item ho $try\n");
            if (validate_folder($try) && is_dir($try))
            {
                if ($item == "") $item_e = 'root';
                $try_e = urlencode($try);
                $html .= "<a href=\"?folder=$try_e\">$item_e</a>/";
            }
            else
            {
                $html .= $item_e;
                if (is_dir($try)) $html .= '/';
            }
            $try .= '/';
        }
        //print ('</pre>');
        return $html;
    }
    
?>
