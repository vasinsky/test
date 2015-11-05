<?php
    function screen_edit_file()
    {
        global $fileman_url, $logmessage, $folder, $folder_contents;
        global $footer_message;
        global $fm_config;

        $filename = $_REQUEST['filename'];        
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "DTD/xhtml1-transitional.dtd"--><title>File Manager : Edit File</title>
    
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['fileman_css'] ?>"></head>

  <body>

    <table class="client" border="0" cellspacing="0">

      <tbody>

      <tr class="header1">
        <td valign="middle">File Manager - Edit File</td>
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
        <td colspan="2" style='padding-top:10px;padding-bottom:10px' align='center'>
          
    <form name='phpftp_edit_form' method='post' action='<?= $_SERVER['PHP_SELF'] ?>'>

    <input type="hidden" name="folder" value="<?= htmlesc($folder) ?>" />
    <input type="hidden" name="filename" value="<?= htmlesc($filename) ?>" />
  
    <table class='file-list'>

      <tr class='file-list-header'>

        <td height="25" align="left">&nbsp;<?= htmlnav($folder.$filename) ?></td>
      </tr>

      <tr class='files-list-even'>
        <td>
          <textarea name='edit_contents'><?php 
            $fp = @fopen($folder.$filename, 'r');
            if (!$fp)
            {
                print ("Unable to read file. Check your permissions over this file.");
                $canread=-1;
            }
            else
            {
                global $fm_config;
                $editor_max_size = empty($fm_config['EDITOR_MAX_SIZE']) ? EDITOR_MAX_SIZE : $fm_config['EDITOR_MAX_SIZE'];
                $file_read_size = empty($fm_config['FILE_READ_SIZE']) ? FILE_READ_SIZE : $fm_config['FILE_READ_SIZE'];
                $filepos=0;
                while (!feof($fp))
                {
                    $canread = $editor_max_size-$filepos;
                    if ($canread==0) 
                    {
                        print ("...\n(FILE TRUNCATED)");
                        break;
                    }
                    if ($canread>$file_read_size) $canread=$file_read_size;
                    error_file("about to read $canread bytes.", 'debug');
                    $contents = fread($fp, $canread);
                    print (htmlesc($contents));
                    $filepos = @ftell ($fp);
                }
            }
            @fclose ($fp);
            //readfile($folder.$filename) 
          ?></textarea>
        </td>
      </tr>
      
      <?php
      if ($canread==0)
      {
          ?>
            <tr>
                <td style="padding-top: 10px;">
                <table class="message-list">

                    <tbody><tr class="message-list-even">
                    <td class="message-list-info">WARNING</td>
                    <td class="message-list-entry">File is too large, only <?= $editor_max_size ?> bytes were loaded into editor.</td>
                    </tr>

                </tbody></table>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
          <?php
      }
      ?>

      <tr class='file-list-footer'>
        <td height="25">
          <input type='reset'  class='reset'  value='UNDO CHANGES'/>
          <input type='submit' class='submit' name='action' value='CANCEL' />
          <input type='submit' class='submit' name='action' value='SAVE CHANGES'/>

        </td>
      </tr>

    </table>
    
    </form>

            </td>
      </tr>

      <tr class='footer2'>
        <td colspan="2" height="25"><?= $footer_message ?></td>
      </tr>

    </table>

  </body>
</html>
    <?php
    }
?>
