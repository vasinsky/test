<?php
    function screen_copy_move()
    {
        global $fileman_url, $logmessage, $folder, $folder_contents, $action_label;
        global $footer_message, $fm_config;
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "DTD/xhtml1-transitional.dtd"--><title>File Manager : <?= ucfirst($action_label) ?> Files</title>
    
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['fileman_css'] ?>"></head>

  <body>

    <table class="client" border="0" cellspacing="0">

      <tbody>

      <tr class="header1">
        <td valign="middle">File Manager - <?= ucfirst($action_label) ?> Files</td>
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
  
  <!-- File List and associated commands -->
  <form name="file-list-form" method="<?= FM_FORM_METHOD ?>" action="?">
          <input type="hidden" name="folder" value="<?= htmlesc($folder) ?>" />
          <?php
          $selecteds = get_selected($_REQUEST);
          $unique=0;
          foreach ($selecteds as $selected) 
          {
              $unique++;
              ?>
              <input type="hidden" name="selected_<?= $unique ?>" value="<?= htmlesc($selected) ?>" />
              <?php
          }
          ?>

      <tr>
        <td colspan="2" style="padding-top: 10px; padding-bottom: 10px;" align="center">
          
          <table class="file-list" border="0" cellpadding="0" cellspacing="0">
            <tbody>
              <tr class="file-list-header"> 
                <th colspan="11" height="25" align="left">&nbsp;<?= htmlnav($folder) ?></th>
              </tr>
              <tr class="file-list-heading">
                <th colspan="3" height="20">File</th>
                <th align="left" style="width: 8em;">Permissions</th>
                <th align="left" style="width: 8em;">User</th>
                <th align="left" style="width: 8em;">Group</th>
                <th align="left" style="width: 8em;">Date</th>
                <th align="left" style="width: 5em;">Size</th>
              </tr>
              <tr><td height="1" bgcolor="#7C98C2" colspan="11"></td></tr>
              
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
                <td><?= $entry['ls_l'] ?></td>
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
              
            </tbody>
          </table></td>
</tr>

<!-- Create File/Dir and Change Dir Commands -->
<tr>
  <td style="padding-bottom: 10px;" colspan="2"><table class="client-section" border="0" cellpadding="0" cellspacing="0">

      <tbody><tr class="client-section-header">
        <td height="25">&nbsp; Target folder
          <?php
            $allow_tree = $_SESSION['fileman_allowtree']; //array of folders we are allowed to access
            if (count($allow_tree)==1)
            {
                $relative_homefolder = str_replace($allow_tree[0], '', $fm_config['HOME_FOLDER']);
                if (trim($relative_homefolder)!='' && $relative_homefolder[0]=='/') $relative_homefolder=substr($relative_homefolder, 1); //remove / from the beginning of relative path!
                $allow_tree_0 = $allow_tree[0];
                if (!preg_match('/\/$/', $allow_tree_0)) $allow_tree_0 = $allow_tree_0.'/'; //add trailing slash if missing!
                print ("<input type=\"hidden\" name=\"target_folder_prepend\" value=\"{$allow_tree_0}\" />{$allow_tree_0}");
            }
            else
            {
                $allow_tree_0='';
                $relative_homefolder = $fm_config['HOME_FOLDER'];
                print ("<select name=\"target_folder_prepend\">\n");
                foreach ($allow_tree as $tree)
                {
                    print ("<option value=\"{$tree}\">{$tree}</option>");
                }
                print ("</select>&nbsp;".htmlesc('/'));
            }
          ?>
          
          <input type="text" class="text" name="target_folder" /> <?php /* always a relative path */ ?>

          <input type="submit" class="submit" name="action" value="<?= strtoupper('confirm '.$action_label) ?>" />
          <input type="submit" class="submit" name="action" value="CANCEL" />
        </td>
      </tr>

    </tbody></table>

  </td>
</tr>
  
  </form>
      
      <tr class="footer2">
        <td colspan="2" height="25"><?= $footer_message ?></td>
      </tr>
      
    </tbody></table>

  </body>
</html>
    <?php
    }
?>
