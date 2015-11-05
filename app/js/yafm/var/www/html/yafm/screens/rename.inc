<?php
    function screen_rename()
    {
        global $fileman_url, $logmessage, $folder, $folder_contents;
        global $footer_message;
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "DTD/xhtml1-transitional.dtd"--><title>File Manager : Rename</title>
    
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['fileman_css'] ?>"></head>

  <body>

    <table class="client" border="0" cellspacing="0">

      <tbody>

      <tr class="header1">
        <td valign="middle">File Manager - Rename</td>
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
            <tbody>
              <tr class="file-list-header"> 
                <th colspan="11" height="25" align="left">&nbsp;<?= htmlnav($folder) ?></th>
              </tr>
              <tr class="file-list-heading">
                <th height="20"></th>
                <th align="left">Current name</th>
                <th align="left">New name</th>
                <th align="left" style="width: 8em;">Permissions</th>
                <th align="left" style="width: 8em;">User</th>
                <th align="left" style="width: 8em;">Group</th>
                <th align="left" style="width: 8em;">Date</th>
                <th align="left" style="width: 5em;">Size</th>
              </tr>
              <tr><td height="1" bgcolor="#7C98C2" colspan="11"></td></tr>
              
              <?php
              $unique=0;
              if (!empty($folder_contents)) foreach ($folder_contents as $name=>$entry)
              {
                  $unique++;
                  $name_url = $name;
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
              <tr class="file-list-even"> 
                <td style="width: 25px; text-align: center;" height="25"> <img src="<?= $fileman_url ?>images/<?= $icon ?>" alt="<?= $entry['type'] ?>"> 
                </td>
                <td><?= htmlesc($virtual_folder) ?><?= htmlesc($name) ?></td>
                <td><?= htmlesc($virtual_folder) ?><input name="new_name_<?= $unique ?>" value="<?= htmlesc($name) ?>" class="text_even"/></td>
                <td><?= $entry['ls_l'] ?></td>
                <td><?= $entry['username'] ?></td>
                <td><?= $entry['groupname'] ?></td>
                <td><?= date('m-d-Y', $entry['mtime']) ?></td>
                <td><?= $entry['size'] ?></td>
              </tr>
              <?php
              } //foreach folder entry
              ?>
              
              <tr class="file-list-footer"> 
                <td colspan="11" align="center" height="25"> 
                  <input type="submit" class="submit" name="action" value="RENAME"> 
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
?>
