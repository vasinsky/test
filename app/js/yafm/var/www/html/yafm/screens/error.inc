<?php
    function screen_error()
    {
        global $fileman_url, $logmessage, $folder, $folder_contents, $action_label;
        global $footer_message, $fm_config;
        
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "DTD/xhtml1-transitional.dtd"--><title>File Manager : Error</title>
    
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['fileman_css'] ?>"></head>

  <body>

    <table class="client" border="0" cellspacing="0">

      <tbody>

      <tr class="header1">
        <td valign="middle">File Manager - Error</td>
        <td class="header2" valign="top">
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?folder=<?= htmlesc($fm_config['HOME_FOLDER']) ?>">Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?folder=<?= htmlesc($folder) ?>&action=SEARCHFORM">Search</a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?folder=<?= htmlesc($folder) ?>&action=PREFERENCES">Preferences</a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a id="logout" href="<?= $_SERVER['PHP_SELF'] ?>?action=LOGOUT">Logout</a>
        </td>
      </tr>

            
      <tr>
        <td colspan="2" style="padding-top: 10px; padding-bottom: 10px;">
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
  
      <!--tr>
        <td style="padding-top: 10px; padding-bottom: 10px;" align="center">
        </td>
      </tr-->


      <tr class="footer2">
        <td colspan="2" height="25"><?= $footer_message ?></td>
      </tr>
      
    </tbody></table>

  </body>
</html>
    <?php
    }
?>
