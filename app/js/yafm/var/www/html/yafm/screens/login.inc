<?php
    function screen_login()
    {
        global $fileman_url, $logmessage;
        global $footer_message;
    ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "DTD/xhtml1-transitional.dtd"--><title>File Manager : Login</title>
    
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['fileman_css'] ?>"></head>

  <body>

    <table class="client" border="0" cellspacing="0">

      <tbody><tr class="header1">
        <td>File Manager - Login</td>
      </tr>
      
      <?php
      if (!empty($logmessage))
      {
      ?>
      <tr>
        <td colspan="2" style='padding-top:10px'>
          <table class='message-list' border="0" cellspacing="1">
      
            <tr class='message-list-even'>
              <td style="color:red;">
                 ERROR              </td>
              <td class='message-list-entry'><?= $logmessage ?></td>
            </tr>
  
          </table>
        </td>
      </tr>
      <?php
      }
      ?>
      <tr>
        <td colspan="2" style="padding-top: 10px; padding-bottom: 10px;" align="center">
          
<form name="login_form" method="<?= FM_FORM_METHOD ?>" action="<?= $_SERVER['PHP_SELF'] ?>">

<table width="300"> 
  <tbody><tr class="login-header">
    <td colspan="2" style="padding: 5px;">Login</td>
  </tr>
  <tr class="login-even">
    <th>Username</th>
    <td><input class="text" name="username" /></td>
  </tr>
  <tr class="login-even">
    <th>Password</th>
    <td>
      <input class="text" type="password" name="password" />
    </td>
  </tr>
  <tr class="login-footer">
    <td colspan="2" style="padding: 5px;">
      <input class="reset" type="reset" name="reset" value="Clear">
      <input class="submit" type="submit" name="action" value="Login">
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

  </body></html>
    <?php
    }
?>
