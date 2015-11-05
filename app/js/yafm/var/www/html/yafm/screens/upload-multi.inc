<?php
    function screen_multi_file()
    {
        global $fileman_url, $logmessage, $folder, $folder_contents;
        global $footer_message;
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "DTD/xhtml1-transitional.dtd"--><title>File Manager : Multiple Upload</title>
    
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['fileman_css'] ?>"></head>

  <body>

    <table class="client" border="0" cellspacing="0">

      <tbody>

      <tr class="header1">
        <td valign="middle">File Manager - Multiple Upload</td>
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
  <form name="multi-file-upload" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
          <input type="hidden" name="folder" value="<?= htmlesc($folder) ?>" />

          <table class="file-list" border="0" cellpadding="0" cellspacing="0">
            <tbody>
              <tr class="file-list-header"> 
                <th colspan="11" height="25" align="left">&nbsp;<?= htmlnav($folder) ?></th>
              </tr>
              <?php
              global $fm_config;
              $multi_upload = empty($fm_config['MULTI_UPLOAD']) ? MULTI_UPLOAD : $fm_config['MULTI_UPLOAD'];
              $file_list_class = 'file-list-even';
              for ($i=1; $i<=$multi_upload; $i++)
              {
              ?>
              <tr class="<?= $file_list_class ?>"> 
                <td style="width: 60px" height="25">File <?= $i ?></td>
                <td><input type="file" name="upload_file_<?= $i ?>" class="text_even"></td>
              </tr>
              <?php
                  // select css for the next row
                  if ($file_list_class == 'file-list-even') $file_list_class = 'file-list-odd';
                  else $file_list_class = 'file-list-even';
              }
              ?>
              
              <tr class="file-list-footer"> 
                <td colspan="2" height="25" align="middle"> 
                  <input type="submit" class="submit" name="action" value="UPLOAD" /> 
                  <input type="button" class="submit" name="action" value="CANCEL" onclick="document.location.href='<?= $_SERVER['PHP_SELF'] ?>?action=cancel&folder=<?= $folder ?>'" />
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
