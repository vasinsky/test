<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$upload_handler = new UploadHandler();

/**
            'script_url' => $this->get_full_url().'/',
            'upload_dir' => PATH.'/uploads/multi/',
            'upload_url' => DIR.'/'.PATH.'/uploads/multi/',
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'param_name' => 'files',
*/            