<center>
<div class="acontent">
<h3><i class="fa fa-clipboard"></i> Информация о PHP</h3>
<hr class="ahr"/>

    <?php if(isset($errors) && !empty($errors)) :?>
    
     <div class="alert alert-danger fade in"> 
     <ul class="text-danger">
     
     <?php foreach($errors as $error):?>
    
    <li><?=$error?></li>
    
    
     <?php endforeach;?>
     
     </ul>
     </div>
    <?php endif;?>

    <?php if($data === true):?>

    <iframe width="100%" frameBorder="0"  height="600" src="/phpinfo.php"></iframe>
    
    <?php endif;?>
</div>
</center>