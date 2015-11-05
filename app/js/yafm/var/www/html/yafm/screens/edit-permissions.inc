<?php
    function screen_edit_permissions()
    {
        global $fileman_url, $logmessage, $folder, $folder_contents;
        global $footer_message;
        
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "DTD/xhtml1-transitional.dtd"--><title>File Manager : Manage File Permissions</title>
    
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['fileman_css'] ?>"></head>

  <body>

    <table class="client" border="0" cellspacing="0">

      <tbody>

      <tr class="header1">
        <td valign="middle">File Manager - Permissions</td>
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
                <?= htmlesc($logmessage); ?></td>
            </tr>
  
          </tbody></table>
        </td>
      </tr>
      
      <tr>
        <td colspan="2" style="padding-top: 10px; padding-bottom: 10px;" align="center">
          
  <!-- File List and associated commands -->
  <form name="file-list-form" method="<?= FM_FORM_METHOD ?>" action="?">
          <input type="hidden" name="folder" value="<?= htmlesc($folder) ?>" />
          <?php
          $selecteds = get_selected($_REQUEST);
          //print ('<pre>');
          //print_r($selecteds);
          //print ("folder contents:\n");
          //print_r($folder_contents);
          //print ('</pre>');
          $unique=0;
          foreach ($selecteds as $selected) 
          {
              $unique++;
              ?>
              <input type="hidden" name="selected_<?= $unique ?>" value="<?= htmlesc($selected) ?>" />
              <?php
          }
          ?>

          <table class="file-list" border="0" cellpadding="0" cellspacing="0">
            <>
              <tr class="file-list-header"> 
                <th colspan="16" height="25" align="left">&nbsp;<?= htmlnav($folder) ?></th>
              </tr>
              <tr class="file-list-heading"> 
                <th colspan="3" rowspan="2" height="20">File</th>
                <th style="width: 8em;" colspan="3">User</th>
                <th style="width: 8em;" colspan="3">Group</th>
                <th style="width: 8em;" colspan="3">Others</th>
                <th align="left" style="width: 8em;" rowspan="2">User</th>
                <th align="left" style="width: 8em;" rowspan="2">Group</th>
                <th align="left" style="width: 8em;" rowspan="2">Date</th>
                <th align="left" style="width: 5em;" rowspan="2">Size</th>
              </tr>
              <tr class="file-list-heading"><th>r</th><th>w</th><th>x</th><th>r</th><th>w</th><th>x</th><th>r</th><th>w</th><th>x</th></tr>
              <tr><td height="1" bgcolor="#7C98C2" colspan="16"></td></tr>
              <?php
              $unique=0;
              $file_list_class = 'file-list-even';
              if (!empty($folder_contents)) foreach ($folder_contents as $name=>$entry)
              {
                  $unique++;
                  $name_url = htmlesc($name);
                  switch ($entry['type'])
                  {
                      case 'directory' :
                          if ($name=='.') continue 2; //next entry
                          $icon = ($name=='..' ? FM_IMG_BACK : FM_IMG_DIR);
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
                  
                  $thename = empty($entry['virtualname']) ? $entry['basename'] : $entry['virtualname'];
                  if (!empty($entry['virtualname']))
                  {
                      $virtual_folder = dirname($entry['virtualname']) . '/';
                  }
                  else
                  {
                      $virtual_folder = '';
                  }
              ?>
              <tr class="<?=$file_list_class ?>"> 
                <td style="width: 25px; text-align: center;"> <img src="<?= $fileman_url ?>images/<?= $icon ?>" alt="<?= $entry['type'] ?>"> 
                </td>
                <td colspan="2" height="20"><?= htmlesc($virtual_folder) ?><?= $name_url ?></td>
                <td align="center"><input type="checkbox" class="checkbox" name="permissions_<?= get_uniq(); ?>" <?= $entry['permissions']['owner']['r'] ? 'CHECKED' : '' ?> value="ur_<?= htmlesc($thename) ?>"></td>
                <td align="center"><input type="checkbox" class="checkbox" name="permissions_<?= get_uniq(); ?>" <?= $entry['permissions']['owner']['w'] ? 'CHECKED' : '' ?> value="uw_<?= htmlesc($thename) ?>"></td>
                <td align="center"><input type="checkbox" class="checkbox" name="permissions_<?= get_uniq(); ?>" <?= $entry['permissions']['owner']['x'] ? 'CHECKED' : '' ?> value="ux_<?= htmlesc($thename) ?>"></td>
                <td align="center"><input type="checkbox" class="checkbox" name="permissions_<?= get_uniq(); ?>" <?= $entry['permissions']['group']['r'] ? 'CHECKED' : '' ?> value="gr_<?= htmlesc($thename) ?>"></td>
                <td align="center"><input type="checkbox" class="checkbox" name="permissions_<?= get_uniq(); ?>" <?= $entry['permissions']['group']['w'] ? 'CHECKED' : '' ?> value="gw_<?= htmlesc($thename) ?>"></td>
                <td align="center"><input type="checkbox" class="checkbox" name="permissions_<?= get_uniq(); ?>" <?= $entry['permissions']['group']['x'] ? 'CHECKED' : '' ?> value="gx_<?= htmlesc($thename) ?>"></td>
                <td align="center"><input type="checkbox" class="checkbox" name="permissions_<?= get_uniq(); ?>" <?= $entry['permissions']['other']['r'] ? 'CHECKED' : '' ?> value="or_<?= htmlesc($thename) ?>"></td>
                <td align="center"><input type="checkbox" class="checkbox" name="permissions_<?= get_uniq(); ?>" <?= $entry['permissions']['other']['w'] ? 'CHECKED' : '' ?> value="ow_<?= htmlesc($thename) ?>"></td>
                <td align="center"><input type="checkbox" class="checkbox" name="permissions_<?= get_uniq(); ?>" <?= $entry['permissions']['other']['x'] ? 'CHECKED' : '' ?> value="ox_<?= htmlesc($thename) ?>"></td>
                <td><?= $entry['username'] ?></td>
                <td><?= $entry['groupname'] ?></td>
                <td><?= date('m-d-Y', $entry['mtime']) ?></td>
                <td><?= $entry['size'] ?></td>
              </tr>
              <?php
                  // select css for the next row
                  if ($file_list_class == 'file-list-even') $file_list_class = 'file-list-odd';
                  else $file_list_class = 'file-list-even';
              } //foreach folder entry
              ?>
              
              <tr class="file-list-footer"> 
                <td colspan="16" align="center" height="25"> 
                  <input type="submit" class="submit" name="action" value="SET PERMISSIONS"> 
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
    
    function get_uniq()
    {
        static $a = 0;
        $a++;
        return $a;
    }
?>
