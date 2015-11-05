<?php

    $login_data = array (
        array (
            'username' => 'user1',
            'password' => 'password1',
            'allowtree' => array ('/tmp'), //allow browsing only /tmp and it's subfolders
            'preferences' => false, //means default preferences
        ),
        array (
            'username' => 'user2',
            'password' => 'password2',
            'allowtree' => array ('/'), //allow browsing the entire filesystem
            'preferences' => array ( //for more details see preferences.inc
                'DEFAULT_FILE_UMASK' => DEFAULT_FILE_UMASK,
                'DEFAULT_DIR_UMASK'  => DEFAULT_DIR_UMASK,
                
                'SORT_ORDER'         => DEFAULT_SORT_ORDER,   //possible values 'name', 'type', 'size', 'nametype', 'namesize'
                'FOLDER_SIZES'       => DEFAULT_FOLDER_SIZES, //folder sizes (possible values: 'enabled', 'disabled')
                'SIZE_UNIT'          => DEFAULT_SIZE_UNIT,    //size measurement unit (possible values: 'b', 'kb', 'mb')
                
                'FILE_READ_SIZE'     => FILE_READ_SIZE,       //do not attempt to read more than 128KB at a time from a file in order to conserve memory
                'EDITOR_MAX_SIZE'    => EDITOR_MAX_SIZE,      //do not attempt to edit files larger than 500KB in the editor
    
                'ARCHIVE_FORMAT'     => DEFAULT_ARCHIVE_FORMAT, //possible values: zip tar tar.gz tar.bz2
                'MULTI_UPLOAD'       => DEFAULT_UPLOAD_SLOTS,
                
                'HOME_FOLDER'        => '/', //access to the entire filesystem
            ),
        ),
    );
    
    /**
     * This very basic authentication routine will use the global $login_data to
     * authenticate users.
     * Feel free to implement your own fm_login routine to fit your needs (e.g. read authentication data from a database)
     * This routine should do the following if authentication succeeds:
     * 1) set either the $_SESSION['fileman_config'] or $_SESSION['fileman_configfile'] variables
     * This variable stores the preferences for the newly logged in user, see preferences.inc
     *
     * 2) set $_SESSION['fileman_user'] to the username that has authenticated successfully.
     *
     * 3) set $_SESSION['allowtree'] = array('folder1', 'folder2', 'folderN');
     * This specifies which folders the user is allowed to browse with the file manager.
     * An attempt to access a folder which is not in allowtree or is not a subfolder of a folder
     * in allowtree will be denied
     */
    function fm_login($username, $password)
    {
        global $login_data;
        
        foreach ($login_data as $account)
        {
            if ($account['username'] == $username && $account['password'] == $password)
            {
                if (empty($account['preferences'])) $account['preferences'] = default_preferences();
                
                $_SESSION['fileman_allowtree'] = $account['allowtree'];
                $_SESSION['fileman_config'] = $account['preferences'];
                $_SESSION['fileman_user']   = $username;
                return true;
            }
        }
        
        return false;
    }
?>