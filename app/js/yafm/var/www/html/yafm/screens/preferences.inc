<?php
    function screen_preferences()
    {
        global $fileman_url, $logmessage, $folder, $folder_contents, $fm_config;
        global $footer_message;
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "DTD/xhtml1-transitional.dtd"--><title>File Manager : Preferences</title>
    
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['fileman_css'] ?>"></head>

  <body>

    <table class="client" border="0" cellspacing="0">

      <tbody>

      <tr class="header1">
        <td valign="middle">File Manager - Preferences</td>
        <td class="header2" valign="top">
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?folder=<?= htmlesc($fm_config['HOME_FOLDER']) ?>">Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?folder=<?= htmlesc($folder) ?>&action=SEARCHFORM">Search</a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?folder=<?= htmlesc($folder) ?>&action=PREFERENCES">Preferences</a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?action=LOGOUT">Logout</a>
        </td>
      </tr>

            
      <tr>
        <td colspan="2" style="padding-top: 10px;">
          <table class="message-list" border="0" cellspacing="1">
      
            <tbody><tr class="message-list-even">
              <td class="message-list-info">
                 INFO              </td>
              <td class="message-list-entry">
                <?= htmlesc($logmessage) ?></td>
            </tr>
  
          </tbody></table>
        </td>
      </tr>
      
      <tr>
        <td colspan="2" style="padding-top: 10px; padding-bottom: 10px;" align="center">
          
  <!-- File List and associated commands -->
  <form name="file-list-form" method="<?= FM_FORM_METHOD ?>" action="?">
          <input type="hidden" name="folder" value="<?= htmlesc($folder) ?>" />
          <table class="file-list" border="0" cellpadding="0" cellspacing="0">
            <tbody>
              <!--tr class="file-list-header"> 
                <th colspan="11"><?= htmlesc($folder) ?></th>
              </tr-->
              <tr class="file-list-heading">
                <th colspan="2"  height="20">Folder Browsing</th>
              </tr>
              <tr><td height="1" bgcolor="#7C98C2" colspan="2"></td></tr>
              
              <tr class="file-list-even"> 
                <td height="25">Default folder</td><td>
                  <?php
                    $allow_tree = $_SESSION['fileman_allowtree']; //array of folders we are allowed to access
                    if (count($allow_tree)==1)
                    {
                            $relative_homefolder = str_replace($allow_tree[0], '', $fm_config['HOME_FOLDER']);
                            if (trim($relative_homefolder)!='' && $relative_homefolder[0]=='/') $relative_homefolder=substr($relative_homefolder, 1); //remove / from the beginning of relative path!
                            $allow_tree_0 = $allow_tree[0];
                            if (!preg_match('/\/$/', $allow_tree_0)) $allow_tree_0 = $allow_tree_0.'/';
                            print ("<input type=\"hidden\" name=\"preferences_homefolder_prepend\" value=\"{$allow_tree_0}\"/>{$allow_tree_0}");
                    }
                    else
                    {
                            $allow_tree_0='';
                            $relative_homefolder = $fm_config['HOME_FOLDER'];
                            print ("<select name=\"preferences_homefolder_prepend\" class=\"text_even\">\n");
                            foreach ($allow_tree as $tree)
                            {
                                print ("<option value=\"{$tree}\">{$tree}</option>");
                            }
                            print ("</select>&nbsp;".htmlesc('/'));
                    }
                  ?>
                  <input name="preferences_homefolder" value=""  class="text_even"/>
                </td>
              </tr>
              <tr class="file-list-even"> 
                <?php
                    $sel = which_selected($fm_config['SORT_ORDER'], array('name', 'sizetype', 'size', 'type'), 'nametype');
                ?>
                <td height="25">Sort Order</td><td><select name="preferences_sortorder" class="text_even">
                  <option value="nametype" <?= $sel['nametype'] ?>>Name and Type</option>
                  <option value="sizetype" <?= $sel['sizetype'] ?>>Size and Type</option>
                  <option value="name" <?= $sel['name'] ?>>Name</option>
                  <option value="size" <?= $sel['size'] ?>>Size</option>
                  <option value="type" <?= $sel['type'] ?>>Type</option>
                </select></td>
              </tr>
              <tr class="file-list-even"> 
                <?php
                    $sel = which_selected($fm_config['SIZE_UNIT'], array('kb', 'mb'), 'b');
                ?>
                <td height="25">File size is measured in</td><td><select name="preferences_sizeunit" class="text_even">
                  <option value="b" <?= $sel['b'] ?>>Bytes</option>
                  <option value="kb" <?= $sel['kb'] ?>>Kilobytes</option>
                  <option value="mb" <?= $sel['mb'] ?>>Megabytes</option>
                </select></td>
              </tr>
              <tr class="file-list-even"> 
                <?php
                    $sel = which_selected($fm_config['FOLDER_SIZES'], array('disabled'), 'enabled');
                ?>
                <td height="25">Show real directory sizes</td><td><select name="preferences_dirdu" class="text_even">
                  <option value="disabled" <?= $sel['disabled'] ?>>Disabled</option>
                  <option value="enabled" <?= $sel['enabled'] ?>>Enabled</option>
                </select></td>
              </tr>
              <tr class="file-list-even"> 
                <?php
                    $cur_fileumask = sprintf('%03o', $fm_config['DEFAULT_FILE_UMASK']);
                    $sel = which_selected($cur_fileumask, array('664', '775', '444', '555', '777'), 'custom');
                ?>
                <td height="25">Permissions for new files</td><td><select name="preferences_fileumask" class="text_even">
                    <option value="664" <?= $sel['664'] ?>>Regular File (rw-rw-r--/664)</option>
                    <option value="775" <?= $sel['775'] ?>>Executable (rwxrwxr-x/775)</option>
                    <option value="444" <?= $sel['444'] ?>>Read-only (r--r--r--/444)</option>
                    <option value="555" <?= $sel['555'] ?>>Executable Read-only (r-xr-xr-x/555)</option>
                    <option value="777" <?= $sel['777'] ?>>Full access for everyone (rwxrwxrwx/777)</option>
                    <option value="custom" <?= $sel['custom'] ?>>Custom</option>
                  </select>
                  or custom permissions: <input name="preferences_fileumask_custom" value="<?= empty($sel['custom']) ? '' : $cur_fileumask ?>" class="text_even"/>
                </td>
              </tr>
              <tr class="file-list-even"> 
                <?php
                    $cur_dirumask = sprintf('%03o', $fm_config['DEFAULT_DIR_UMASK']);
                    $sel = which_selected($cur_dirumask, array('775', '700', '555', '777'), 'custom');
                ?>
                <td height="25">Permissions for new folders</td><td><select name="preferences_dirumask" class="text_even">
                    <option value="775" <?= $sel['775'] ?>>Regular Folder (rwxrwxr-x/755)</option>
                    <option value="700" <?= $sel['700'] ?>>Private (rwx------/700)</option>
                    <option value="555" <?= $sel['555'] ?>>Read-only (r-xr-xr-x/555)</option>
                    <option value="777" <?= $sel['777'] ?>>Full access for everyone (rwxrwxrwx/777)</option>
                    <option value="custom" <?= $sel['custom'] ?>>Custom</option>
                  </select>
                  or custom permissions: <input name="preferences_dirumask_custom" value="<?= empty($sel['custom']) ? '' : $cur_dirumask ?>"  class="text_even"/>
                </td>
              </tr>
              
              <tr class="file-list-heading">
                <th colspan="2" height="20">File Editor</th>
              </tr>
              <tr><td height="1" bgcolor="#7C98C2" colspan="2"></td></tr>
              <tr class="file-list-even"> 
                <td height="25">Edited file maximum size</td><td><input name="preferences_editormaxsize" value="<?= $fm_config['EDITOR_MAX_SIZE'] ?>"  class="text_even"/> bytes</td>
              </tr>
              
              <tr class="file-list-heading">
                <th colspan="2" height="20">Download/Upload</th>
              </tr>
              <tr><td height="1" bgcolor="#7C98C2" colspan="2"></td></tr>
              <tr class="file-list-even"> 
                <td height="25">Preferred archive format for downloading</td><td>
                  <?php
                    $sel = which_selected($fm_config['ARCHIVE_FORMAT'], array('tar', 'tar.gz', 'tar.bz2'), 'zip');
                  ?>
                  <select name="preferences_downloadformat" class="text_even">
                    <option value="zip" <?= $sel['zip'] ?>>.ZIP</option>
                    <option value="tar" <?= $sel['tar'] ?>>.tar</option>
                    <option value="tar.gz" <?= $sel['tar.gz'] ?>>.tar.gz</option>
                    <option value="tar.bz2" <?= $sel['tar.bz2'] ?>>.tar.bz2</option>
                  </select>
                </td>
              </tr>
              <tr class="file-list-even"> 
                <td height="25">Number of slots on multiple upload page</td><td>
                  <input name="preferences_uploadslots" value="<?= $fm_config['MULTI_UPLOAD'] ?>"  class="text_even"/>
                </td>
              </tr>
              
              <tr class="file-list-footer"> 
                <td colspan="11" align="center" height="25"> 
                  <input type="submit" class="submit" name="action" value="SET PREFERENCES"> 
                  <input type="submit" class="submit" name="action" value="CANCEL"> 
                </td>
              </tr>
            </tbody>
          </table>

  </form>
      
  </td>
</tr>

      <tr class="footer2">
        <td colspan="2" height="25"><?= $footer_message ?></td>
      </tr>
      
    </tbody></table>

  </body>
</html>
    <?php
    }
    
    function which_selected($value, $options, $default=false)
    {
        $result = array ();
        if (!empty($default)) $result[$default] = 'SELECTED';
        foreach ($options as $option)
        {
            if ($option==$value)
            {
                $result[$option] = 'SELECTED';
                if (!empty($default)) $result[$default] = '';
            }
            else
            {
                $result[$option] = '';
            }
        }
        //print_r($result);
        return $result;
    }
?>
