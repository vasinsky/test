<?php
    function screen_search_form()
    {
        global $fileman_url, $logmessage, $folder, $folder_contents, $fm_config;
        global $footer_message;
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "DTD/xhtml1-transitional.dtd"--><title>File Manager : File Search</title>
    
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['fileman_css'] ?>"></head>

  <body>

    <table class="client" border="0" cellspacing="0">

      <tbody>

      <tr class="header1">
        <td valign="middle">File Manager - File Search</td>
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
              <!--tr class="file-list-heading">
                <th colspan="2"  height="20">File Name</th>
              </tr>
              <tr><td height="1" bgcolor="#7C98C2" colspan="2"></td></tr-->
              
              <tr class="file-list-even"> 
                <td height="25">Search in Folder</td>
                <td>
                    <input type="text" name="folder" value="<?= $folder ?>" class="text_even" />
                </td>
                <td>
                    <input type="checkbox" name="search_recursive" checked="true" />Recurse Subfolders
                </td>
              </tr>
              
              <tr class="file-list-even"> 
                <td height="25">Filename</td>
                <td>
                    <input type="text" name="search_filemask" value="*" class="text_even" />
                </td>
              </tr>
              
              <!--tr class="file-list-heading">
                <th colspan="2" height="20">File Contents</th>
              </tr>
              <tr><td height="1" bgcolor="#7C98C2" colspan="2"></td></tr-->
              <tr class="file-list-even"> 
                <td height="25">Containing text</td>
                <td><input name="search_text" value="" class="text_even" /></td>
                <td><input type="checkbox" name="search_sensitive" checked="true" />Case Sensitive</td>
              </tr>
              
              <tr class="file-list-footer"> 
                <td colspan="11" align="center" height="25"> 
                  <input type="submit" class="submit" name="action" value="SEARCH"> 
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
