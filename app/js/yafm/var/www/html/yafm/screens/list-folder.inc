<?php
    function screen_folder_list()
    {
        global $fileman_url, $logmessage, $folder, $folder_contents, $fm_config;
        global $footer_message;
        global $search_mode;
        global $vfs_id;
        
        $title = (!empty($search_mode)) ? 'Search Results' : 'Folder List';
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "DTD/xhtml1-transitional.dtd"--><title>File Manager : <?= $title ?></title>
    
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['fileman_css'] ?>"></head>

  <body>

    <table class="client" border="0" cellspacing="0">

      <tbody>

      <tr class="header1">
        <td valign="middle">File Manager - <?= $title ?></td>
        <td class="header2" valign="top">
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?folder=<?= htmlesc($fm_config['HOME_FOLDER']) ?>">Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?folder=<?= htmlesc($folder) ?>&action=SEARCHFORM">Search</a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?folder=<?= htmlesc($folder) ?>&action=PREFERENCES">Preferences</a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?action=LOGOUT">Logout</a>&nbsp;
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

          <table class="file-list" border="0" cellspacing="0" cellpadding="0">
            <tbody>
              <tr class="file-list-header"> 
                <th colspan="8" height="25" align="left">&nbsp;<?= htmlnav($folder) ?></th>
                <th colspan="3" align="right">Total <?= count($folder_contents) ?></th>
              </tr>
              <tr class="file-list-heading"> 
                <th colspan="3" height="20">File</th>
                <th style="width: 8em;">Permissions</th>
                <th align="left" style="width: 8em;">User</th>
                <th align="left" style="width: 8em;">Group</th>
                <th align="left" style="width: 8em;">Date</th>
                <th align="left" style="width: 5em;">Size</th>
                <th style="width: 90px;" colspan="3">Operations</th>
              </tr>
              <tr><td height="1" bgcolor="#7C98C2" colspan="11"></td></tr>
              
              <?php
              $unique=0;
              $oddline=0;
              if (empty($folder_contents))
              {
                  ?>
              <tr class="file-list-even"> 
                <td style="width: 25px; text-align: left;" colspan="7" >
                   (No files or folders found.)
                </td>
              </tr>
                  <?php
              }
              else foreach ($folder_contents as $name=>$entry)
              {
                  $oddline = 1 - $oddline;
                  $unique++;
                  $name_url = htmlesc(empty ($entry['virtualname']) ? $entry['basename'] : $entry['virtualname']);
                  $virtual_name = empty($entry['virtualname']) ? $entry['basename'] : $entry['virtualname'];
                  
                  $vfs_html = empty ($vfs_id) ? '' : '&vfs_id='.$vfs_id;
                  
                  //error_file ("escaped name: $name_url", 'debug');
                  switch ($entry['type'])
                  {
                      case 'directory' :
                          //$oddline = 1 - $oddline;
                          //continue 2; //next entry
                          switch ($name) 
                          {
                              case '.' :
                                  $icon = FM_IMG_REFRESH;
                                  //$name_url = '<a href="'.$_SERVER['PHP_SELF'].'?folder='.urlencode($folder.$name).'">'.htmlesc($name).' (refresh current folder)</a>';
                                  break;
                              case '..' :
                                  $icon = FM_IMG_BACK;
                                  //$name_url = '<a href="'.$_SERVER['PHP_SELF'].'?folder='.urlencode($folder.$name).'">'.htmlesc($name).' (previous folder)</a>';
                                  break;
                              default :
                                  $icon = FM_IMG_DIR;
                                  break;
                          }
                          $name_url = '<a href="'.$_SERVER['PHP_SELF'].'?folder='.urlencode($folder.$virtual_name).'">'.htmlesc($virtual_name).'</a>';
                          break;
                      case 'symlink' :
                          $icon=FM_IMG_SYMLINK;
                          break;
                      case 'blockdev' :
                          $icon=FM_IMG_BLOCKDEV;
                          break;
                      case 'chardev' :
                          $icon=FM_IMG_CHARDEV;
                         break;
                      case 'socket' :
                          $icon=FM_IMG_SOCKET;
                          break;
                      case 'fifo' :
                          $icon=FM_IMG_FIFO;
                          break;
                      case 'regular':
                          $icon=FM_IMG_TEXT;
                          break;
                      case 'default':
                          $icon=FM_IMG_BROKEN;
                          break;
                  }
              ?>
              <tr class="file-list-<?php echo empty($oddline) ? 'odd' : 'even' ?>"> 
                <td style="width: 25px; text-align: center;"> <input type="checkbox" class="checkbox" name="selected_<?= $unique ?>" value="<?= htmlesc($virtual_name) ?>"> 
                </td>
                <td style="width: 25px; text-align: center;"> <img src="<?= $fileman_url ?>images/<?= $icon ?>" alt="<?= $entry['type'] ?>"> 
                </td>
                <td><?= $name_url ?></td>
                <td><?= $entry['ls_l'] ?></td>
                <td><?= $entry['username'] ?></td>
                <td><?= $entry['groupname'] ?></td>
                <td><?= date('m-d-Y', $entry['mtime']) ?></td>
                <td><?= empty($entry['size_formatted']) ? $entry['size'] : $entry['size_formatted'] ?></td>
                <td style="width: 28px; text-align: center;"> 
                    <?php
                    if ($entry['type']=='regular') print ('<a href="'.$_SERVER['PHP_SELF'].'?action=EDITFILE&folder='.urlencode($folder).'&filename='.urlencode($virtual_name).$vfs_html.'"><img src="'.$fileman_url.'images/'.FM_IMG_EDIT.'" alt="[r]" title="Edit" border="0"> </a>');
                    ?>
                </td>
                <td style="width: 28px; text-align: center;"> <a href="<?= $_SERVER['PHP_SELF'] ?>?action=DOWNLOAD&folder=<?= urlencode($folder) ?>&selected_1=<?= urlencode($virtual_name).$vfs_html ?>"><img src="<?= $fileman_url ?>images/<?= FM_IMG_DOWNLOAD ?>" alt="[d]" title="Download" border="0"> </a>
                </td>
                <td style="width: 28px; text-align: center;"> <a href="<?= $_SERVER['PHP_SELF'] ?>?action=SHOWRENAME&folder=<?= urlencode($folder) ?>&selected_1=<?= urlencode($virtual_name).$vfs_html ?>"><img src="<?= $fileman_url ?>images/<?= FM_IMG_RENAME ?>" alt="[r]" title="Rename" border="0"> </a>
                </td>
              </tr>
              <?php
              } //foreach folder entry
              ?>
              
              <tr class="file-list-footer"> 
                <td colspan="11" height="25"> 
                    Selected Entries: 
                    <!--select name="command">
                    <option value="download">Download</option>
                    <option value="delete">Delete</option>
                    <option value="move">Move</option>
                    <option value="copy">Copy</option>
                    <option value="chmod">Change Permissions</option>
                  </select--> 
                  <input type="submit" class="submit" name="action" value="DOWNLOAD"> 
                  <input type="submit" class="submit" name="action" value="DELETE"> 
                  <input type="submit" class="submit" name="action" value="MOVE"> 
                  <input type="submit" class="submit" name="action" value="COPY"> 
                  <input type="submit" class="submit" name="action" value="PERMISSIONS"> 
                  <?php
                      if (!empty($vfs_id)) print ("<input type=\"hidden\" name=\"vfs_id\" value=\"$vfs_id\" />");
                  ?>
                </td>
              </tr>
            </tbody>
          </table>

  </form>
      
  </td>
</tr><tr>

</tr><tr>
  <td colspan="2">
        
    <!-- Create File/Dir and Change Dir Commands -->
    <form name="phpftp-command-form" method="<?= FM_FORM_METHOD ?>" action="<?= $_SERVER['PHP_SELF'] ?>">
    
    <input type="hidden" name="folder" value="<?= htmlesc($folder) ?>" />
      
    <table class="client-section">

      <tbody><tr class="client-section-header">
        <td height="25">
          New &nbsp;<select name="create_what" class="text"><option value="file">File</option><option value="directory">Directory</option></select>
          <input type="text" class="text" name="newname">
          <input type="submit" class="submit" name="action" value="CREATE">
        </td>
      </tr>

    </tbody></table>
        
    </form>

  </td>
</tr>

<tr>
  <td style="padding-top: 10px; padding-bottom: 10px;" colspan="2">

    <!-- Upload a file -->
    <form name="file-upload-form" enctype="multipart/form-data" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
    
    <input type="hidden" name="folder" value="<?= htmlesc($folder) ?>" />
      
    <table class="client-section" cellspacing="0" border="0">

      <tbody><tr class="client-section-header">
        <td height="25">
          Upload File :
          <input type="file" class="file" name="upload_file_1">
          <input type="submit" class="submit" name="action" value="UPLOAD">
          <a href="<?= $_SERVER['PHP_SELF'] ?>?action=MULTI&folder=<?= htmlesc($folder) ?>">multiple files</a>
        </td>
      </tr>

    </tbody></table>

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
    
?>
