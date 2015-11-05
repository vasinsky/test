<?php
    
    //unix file and directory manipulation functions
    
    include_once ("{$absolute_path}/lib/tool/unix-process.inc"); //for my_exec
    
    //stat(2) constants taken from the UNIX man page
    define ('S_IFMT',   0170000);   //bitmask for the file type bitfields
    define ('S_IFSOCK', 0140000);   //socket
    define ('S_IFLNK',  0120000);   //symbolic link
    define ('S_IFREG',  0100000);   //regular file
    define ('S_IFBLK',  0060000);   //block device
    define ('S_IFDIR',  0040000);   //directory
    define ('S_IFCHR',  0020000);   //character device
    define ('S_IFIFO',  0010000);   //fifo
    define ('S_ISUID',  0004000);   //set UID bit
    define ('S_ISGID',  0002000);   //set GID bit (see below)
    define ('S_ISVTX',  0001000);   //sticky bit (see below)
    define ('S_IPERM',  00777);     //mask for all permissions
    //the following are already defined by PHP's standard extensions:
    //define ('S_IRWXU',  00700);     //mask for file owner permissions
    //define ('S_IRUSR',  00400);     //owner has read permission
    //define ('S_IWUSR',  00200);     //owner has write permission
    //define ('S_IXUSR',  00100);     //owner has execute permission
    //define ('S_IRWXG',  00070);     //mask for group permissions
    //define ('S_IRGRP',  00040);     //group has read permission
    //define ('S_IWGRP',  00020);     //group has write permission
    //define ('S_IXGRP',  00010);     //group has execute permission
    //define ('S_IRWXO',  00007);     //mask for permissions for others (not in group)
    //define ('S_IROTH',  00004);     //others have read permission
    //define ('S_IWOTH',  00002);     //others have write permisson
    //define ('S_IXOTH',  00001);     //others have execute permission
    
    $unix_file_types = array (
        'int2str' => array (
            S_IFSOCK => 'socket',
            S_IFLNK  => 'symlink',
            S_IFREG  => 'regular',
            S_IFBLK  => 'blockdev',
            S_IFDIR  => 'directory', 
            S_IFCHR  => 'chardev',
            S_IFIFO  => 'fifo',
        ),
        'str2int' => array (
            'socket'    => S_IFSOCK,
            'symlink'   => S_IFLNK,
            'regular'   => S_IFREG,
            'blockdev'  => S_IFBLK,
            'directory' => S_IFDIR, 
            'chardev'   => S_IFCHR,
            'fifo'      => S_IFIFO,
        ),
    );
    
    $unix_file_sort_order_by_type = array (
        'directory' => 3, 
        'regular'   => 4,
        'symlink'   => 4,
        'socket'    => 6,
        'blockdev'  => 6,
        'chardev'   => 6,
        'fifo'      => 6,
    );
    
    function my_dir($folder)
    {
        //var_dump($unix_file_types);
        
        if (!preg_match('/\/$/', $folder)) $folder = $folder.'/';
        
        $d = @dir($folder);
        if (empty($d)) 
        {
            //!!!issue a warning
            return false;
        }
        
        $userinfo = load_etc_passwd('/etc/passwd', '/etc/group'); //load password and group information
        if (empty($userinfo))
        {
            //!!!issue a warning only
        }
     
        $result = array();   
        while (false !== ($entry = $d->read())) 
        {
            $stat = my_stat($folder.$entry, $userinfo);
            if (empty($stat)) continue;
            $result[$entry]=$stat;
            //print_r($stat);
            
        }
        $d->close();
        
        return $result;
    }
    
    function my_stat($file, $userinfo = false)
    {
        global $unix_file_types;
        
        $stat = @lstat($file);
        if (empty($stat)) return false;
        
        if (!is_array($userinfo)) $userinfo = load_etc_passwd('/etc/passwd', '/etc/group'); //load password and group information
        
        $stat['name'] = $file; //data duplication but it allows for easier access later
        $stat['basename'] = basename ($file);
        
        $stat['username'] = empty($userinfo['uid'][$stat['uid']]) ?
                $stat['uid'] : $userinfo['uid'][$stat['uid']];
        
        $stat['groupname'] = empty($userinfo['gid'][$stat['gid']]) ?
            $stat['gid'] : $userinfo['gid'][$stat['gid']];
        
        if (($stat['mode'] & S_IFLNK) == S_IFLNK)
        {
            $pointsto = @readlink($folder.$entry);
            if (!empty($pointsto)) $stat['pointsto']=$pointsto;
        }
        
        $stat['type_octal'] = sprintf("%o", ($stat['mode'] & S_IFMT));
        $stat['type'] = $unix_file_types['int2str'][($stat['mode'] & S_IFMT)];
        $stat['permissions_octal'] = sprintf("%o", ($stat['mode'] & S_IPERM));
        $stat['permissions']['owner']['r'] = (($stat['mode'] & S_IRUSR) == S_IRUSR);
        $stat['permissions']['owner']['w'] = (($stat['mode'] & S_IWUSR) == S_IWUSR);
        $stat['permissions']['owner']['x'] = (($stat['mode'] & S_IXUSR) == S_IXUSR);
        $stat['permissions']['group']['r'] = (($stat['mode'] & S_IRGRP) == S_IRGRP);
        $stat['permissions']['group']['w'] = (($stat['mode'] & S_IWGRP) == S_IWGRP);
        $stat['permissions']['group']['x'] = (($stat['mode'] & S_IXGRP) == S_IXGRP);
        $stat['permissions']['other']['r'] = (($stat['mode'] & S_IROTH) == S_IROTH);
        $stat['permissions']['other']['w'] = (($stat['mode'] & S_IWOTH) == S_IWOTH);
        $stat['permissions']['other']['x'] = (($stat['mode'] & S_IXOTH) == S_IXOTH);
        
        $stat['ls_l'] = mode2string ($stat);
        
        return $stat;
    }
    
    function my_find ($folder, $filepat, $recursive = true)
    {
        $folder_e  = escapeshellarg($folder);
        $filepat_e = escapeshellarg($filepat);
        $recursive_e = empty($recursive) ? '-maxdepth 1' : '';
        $out = my_exec ("find $folder_e $recursive_e -name $filepat_e 2>&1");
        //return $out;
        if ($out['ret_val'] > 1) 
        {
            //!!! issue a warning!
            return false;
        }
        
        $ret = array();
        foreach ($out['output'] as $line)
        {
            if (preg_match('/^find\:/', $line))
            {
                //hl_log (false, 'my_find', 'warn', 'unable2grep', $line);
                error_file ('my_find: find failed: '.$line, 'warn');
            }
            else
            {
                $ret[]=$line;
            }
        }
        if (!empty($ret))
        {
            sort ($ret);
            return $ret;
        }
        else return false;
    }
    
    /**
     * grep -l -r [-i] PATTERN `find FOLDER -type f -name FILEPATTERN`
     * (does not my_stat() the matches)
     */
    function my_grep ($subject, $folder, $filepat, $recursive = true, $ignorecase = false) 
    {
        $subject_e = escapeshellarg($subject);
        $folder_e  = escapeshellarg($folder);
        $filepat_e = escapeshellarg($filepat);
        $recursive_e = empty($recursive) ? '-maxdepth 1' : '';
        $ignorecase_e = empty($ignorecase) ? '' : '-i';
        
        $out = my_exec ("grep -l $ignorecase_e $subject_e '.' `find $folder_e $recursive_e -type f -name $filepat_e` 2>&1");
        if ($out['ret_val'] > 2) 
        {
            //!!! issue a warning!
            return false;
        }
        
        $ret = array();
        foreach ($out['output'] as $line)
        {
            if (preg_match('/^grep\:/', $line))
            {
                //hl_log (false, 'my_grep', 'warn', 'unable2grep', $line);
                error_file ('my_grep: grep failed: '.$line, 'warn');
            }
            else
            {
                $ret[]=$line;
            }
        }
        if (!empty($ret))
        {
            sort ($ret);
            return $ret;
        }
        else return false;
    }
    
    function load_etc_passwd($passwd_file, $group_file)
    {
        $passwd = @file($passwd_file);
        if (empty($passwd)) return error_file('unable to load file: '.$passwd_file, 'error'); //Unable to load file: %s
        $group = @file ($group_file);
        if (empty($group)) return error_file('unable to load file: '.$group_file, 'error'); //Unable to load file: %s

        $result = array();
        
        foreach ($passwd as $line)
        {
            if (trim($line)=='') continue;
            $spl = split(':', $line);
            if (empty($spl[0]) || !is_numeric($spl[2])) continue;
            $result['username'][$spl[0]] = $spl[2];
            $result['uid'][$spl[2]] = $spl[0];
        }
        
        foreach ($group as $line)
        {
            if (trim($line)=='') continue;
            $spl = split(':', $line);
            if (empty($spl[0]) || !is_numeric($spl[2])) continue;
            $result['groupname'][$spl[0]] = $spl[2];
            $result['gid'][$spl[2]] = $spl[0];
        }
        
        return $result;
    }

    /**
     * entry_type is an array
     */
    function mode2string($entry)
    {
        $permissions = '';
        switch ($entry['type'])
        {
            case 'directory' :
                $permissions.='d';
                break;
            case 'symlink' :
                $permissions.='l';
                break;
            case 'blockdev' :
                $permissions.='b';
                break;
            case 'chardev' :
                $permissions.='c';
                break;
            case 'socket' :
                $permissions.='s';
                break;
            case 'fifo' :
                $permissions.='f';
                break;
            case 'regular':
                $permissions.='-';
                break;
            case 'default':
                $permissions.='?';
                break;
        }
        $permissions .= $entry['permissions']['owner']['r'] ? 'r' : '-';
        $permissions .= $entry['permissions']['owner']['w'] ? 'w' : '-';
        $permissions .= $entry['permissions']['owner']['x'] ? 'x' : '-';
        $permissions .= $entry['permissions']['group']['r'] ? 'r' : '-';
        $permissions .= $entry['permissions']['group']['w'] ? 'w' : '-';
        $permissions .= $entry['permissions']['group']['x'] ? 'x' : '-';
        $permissions .= $entry['permissions']['other']['r'] ? 'r' : '-';
        $permissions .= $entry['permissions']['other']['w'] ? 'w' : '-';
        $permissions .= $entry['permissions']['other']['x'] ? 'x' : '-';
        
        return $permissions;
    }
    
    function sort_folder($mydir_out, $by_what)
    {
        if (empty($mydir_out)) return $mydir_out;
        $result = $mydir_out;
        
        switch ($by_what)
        {
            case 'name':
                ksort($result);
                break;
            case 'type':
                uasort($result, 'usort_bytype');
                break;
            case 'size':
                uasort($result, 'usort_bysize');
                break;
            case 'nametype':
                uasort($result, 'usort_bynametype');
                break;
            case 'sizetype':
                uasort($result, 'usort_bysizetype');
                break;
            default:
                error_file ('solt_folder: don\'t know how to sort by '.$by_what, 'warn'); //the array is returned unmodified!
                break;
        }
        
        //a beautiful hack to move . and .. always to the top of the list
        $result2 = array();
        if (!empty($result['.'])) 
        {
            $result2['.']=$result['.']; // the '.' folder is 1st in the new array!
            unset ($result['.']);
        }
        if (!empty($result['..'])) 
        {
            $result2['..']=$result['..']; // the '.' folder is 1st in the new array!
            unset ($result['..']);
        }
        return array_merge($result2, $result);
    }

    /**
     * sort is always like this:
     * first is the . folder (if present)
     * second is the .. folder (if present)
     * the sort order then depends on the $unix_file_sort_order_by_type array
     */
    function usort_bytype($e1, $e2)
    {
        global $unix_file_sort_order_by_type;
        
        $k1 = $unix_file_sort_order_by_type[$e1['type']];
        $k2 = $unix_file_sort_order_by_type[$e2['type']];
        
        //error_file ("keys $k1 $k2 [{$e1['type']} {$e2['type']}] ({$e1['name']} {$e2['name']})", 'debug');
        
        if ($k1 == $k2) return 0; //equal
        return ($k1 < $k2) ? -1 : 1;
    }
    
    function usort_bysizetype($e1, $e2)
    {
        global $unix_file_sort_order_by_type;
        
        $k1 = $unix_file_sort_order_by_type[$e1['type']];
        $k2 = $unix_file_sort_order_by_type[$e2['type']];
        
        //error_file ("keys $k1 $k2 [{$e1['type']} {$e2['type']}] ({$e1['name']} {$e2['name']})", 'debug');
        
        if ($k1 == $k2) 
        {
            $k1 = $e1['size'];
            $k2 = $e2['size'];
            if ($k1 == $k2) return 0; //equal
            return ($k1 < $k2) ? -1 : 1;
        }
        return ($k1 < $k2) ? -1 : 1;
    }
    
    function usort_bynametype($e1, $e2)
    {
        global $unix_file_sort_order_by_type;
        
        $k1 = $unix_file_sort_order_by_type[$e1['type']];
        $k2 = $unix_file_sort_order_by_type[$e2['type']];
        
        //error_file ("keys $k1 $k2 [{$e1['type']} {$e2['type']}] ({$e1['name']} {$e2['name']})", 'debug');
        
        if ($k1 == $k2) 
        {
            return (strcmp($e1['name'], $e2['name']));
        }
        return ($k1 < $k2) ? -1 : 1;
    }
    
    function usort_bysize($e1, $e2)
    {
        $k1 = $e1['size'];
        $k2 = $e2['size'];
        if ($k1 == $k2) return 0; //equal
        return ($k1 < $k2) ? -1 : 1;
    }
    
    /**
     * works for a single folder (du --summary)
     * returns the size of the folder in bytes (integer)
     */
    function unix_du ($path)
    {
        //error_file ('unix_du: checking path: '.$path, 'debug');
        $du_out = my_du($path, array('--exclude=_private', '-b', '-s')); //in bytes, summary
        return empty($du_out) ? false : array_pop($du_out);
    }
    
    /**
     * Invokes and parses the output of the du(1) command.
     * returns a hash, keys are folder names, values are sizes
     * the $duopts array contains options to be passed to du(1)
     * example usage: my_du('/my/path', array('-b')); //returns the size of /my/path/ and the size of each subfolder in bytes
     */
    function my_du($path, $duopts=array())
    {
        global $hl_errors;
        
        $path = escapeshellarg($path);
        $opts_e = array();
        foreach ($duopts as $opt) $opts_e[]=escapeshellarg($opt);
        $opts = join (' ', $opts_e);
        //print ("OPTS $opts\n");
        $du = `du $opts $path 2>&1`;
        
        $result = array();
        
        $du_lines = split ("\n", $du);
        foreach ($du_lines as $line)
        {
            if (trim($line)=='') continue;
            if (preg_match('/^(\d+)\s+(.+)$/', $line, $matches))
            {
                $result[$matches[2]]=$matches[1];
            }
            else
            {
                $msg = $line;
                $hl_errors[]="WARNING: $msg";
                error_file ("unix_du: $msg", "error");
            }
        }
        
        return empty($result) ? false : $result;
    }
    
    /**
     * Recursive file/folder removal
     * $path is a string or an array of strings
     */
    function my_rm_rf($path)
    {
        $result = '';
        if (empty($path)) return false;
        if (!is_array($path)) $path=array($path);
        
        foreach ($path as $entry)
        {
            if (is_dir($entry)) 
            {
                if ($hnd = @opendir($entry)) 
                {
                    while (false !== ($file1 = @readdir($hnd))) 
                    {
                        //print ("\n\n$file1\n\n");
                        if ($file1 == "." || $file1 == "..") continue;
                        $result .= my_rm_rf($entry.'/'.$file1);
                    }
                    closedir($hnd);
                } else {
                    $result .= "Unable to list directory: $entry\n";
                }
                
                //print ("removing dir $entry\n");
                @chmod($entry, 0755);
                if (!@rmdir($entry))
                {
                    $result .= "Unable to remove: $entry\n";
                }
            }
            else
            {
                //print ("removing file $entry\n");
                @chmod($entry, 0755);
                if (!@unlink($entry))
                {
                    $result .= "Unable to remove: $entry\n";
                }
            }
        }
        return $result;
    }
    
    /**
     * both $srcpath and $dstpath must be existing absolute paths!
     * $move - true means move the files
     *         false means copy the files
     */
    function my_copy_move ($files, $srcpath, $dstpath, $move=false)
    {
        $result = '';
        $srcpath = realpath('/'.$srcpath);
        $dstpath = realpath('/'.$dstpath);
        if (empty($files)||empty($srcpath)||empty($dstpath)) return false;
        if (!is_array($files)) $files=array($files);
        
        foreach ($files as $file)
        {
            $entry = $srcpath.'/'.$file;
            if (is_dir($entry)) 
            {
                //print ("making & copying dir $entry to $dstpath/$file\n");
                if (!@is_dir("$dstpath/$file") && !@mkdir("$dstpath/$file", 0755))
                {
                    $result .= "Unable to create directory: $dstpath/$file\n";
                }
                elseif ($hnd = @opendir($entry)) 
                {
                    //preserve permissions for folder
                    $result .= my_preserve_permissions ($entry, "$dstpath/$file");
                    
                    //preserve permissions for target folder!
                    while (false !== ($file1 = @readdir($hnd))) 
                    {
                        if ($file1 == "." || $file1 == "..") continue;
                        $result .= my_copy_move($file.'/'.$file1, $srcpath, $dstpath, $move);
                    }
                    closedir($hnd);
                    if ($move && !@rmdir($entry))
                    {
                        $result .= "Unable to remove old folder: $entry while moving\n";
                    }
                } else {
                    $result .= "Unable to list directory: $entry\n";
                }
            }
            else
            {
                $target_file = "$dstpath/$file";
                //print ("copying file $entry to $target_file\n");
                if (!@copy($entry, $target_file))
                {
                    $result .= "Unable to copy (or move) file: $entry to $target_file\n";
                }
                else
                {
                    //preserve permissions for file
                    $result .= my_preserve_permissions ($entry, $target_file);
                    if ($move && !@unlink ($entry))
                    {
                        $result .= "Unable to remove old file: $entry while moving\n";
                    }
                }
            }
        }
        return $result;
    }
    
    /**
     * works on both files and folders.
     * will make $dstfile have the same permissions as $srcfile
     */
    function my_preserve_permissions ($srcfile, $dstfile)
    {
        $result = '';
        
        $statinfo = stat($srcfile);
        if (!empty($statinfo['mode']))
        {
            if (!@chmod($dstfile, ($statinfo['mode'] & S_IPERM)))
            {
                $result .= "Unable to preserve permissions for: $dstfile\n";
            }
        }
        else
        {
            $result .= "Unable to retrieve permissions of: $srcfile\n";
        }
        
        return $result;
    }
    
?>
