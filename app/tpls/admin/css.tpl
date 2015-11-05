<center>
<div class="acontent">
<h3><i class="fa fa-gears"></i> Файлы стилей CSS</h3>
<hr class="ahr"/>

<?php foreach($listCss as $path=>$file) :?>

  <a href="<?=Route::getUrl('?mode=admin&route=css&file='.$file)?>"><span class="glyphicon glyphicon-pencil"></span> <?=$file;?></a><br/>

<?php endforeach; ?>

<?php if(isset($_GET['file'])):?>
<br/>
<form action="" method="POST">
<h4><span class="glyphicon glyphicon-pencil"></span> Редактирование: <i><?=$_GET['file'];?></i></h4>
<hr class="ahr"/>

<textarea id="code_css" style="height:700px!important" class="form-control" name="code_css">
<?=$fileData;?>
</textarea>
<br/>
<input name="save_css" type="submit" class="btn btn-primary btn-lg addpage" value="Сохранить"/>
</form>
<script>
  var editor = CodeMirror.fromTextArea(document.getElementById("code_css"), {
        lineNumbers: true,
        styleActiveLine: true,
        matchBrackets: true
  });
</script>
    
<?php endif;?>

</div>
</center>