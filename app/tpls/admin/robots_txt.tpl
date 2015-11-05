<center>
<div class="acontent">
<h3><i class="fa fa-gears"></i> Файл robots.txt</h3>
<hr class="ahr"/>


<?php if(isset($Robots_txt)):?>
<br/>
<form action="" method="POST">
<h4><span class="glyphicon glyphicon-pencil"></span> Редактирование: <i>Robots.txt</i></h4>
<hr class="ahr"/>

<textarea id="code_robots" style="height:700px!important" class="form-control" name="code_robots">
<?=$Robots_txt;?>
</textarea>
<br/>
<input name="save_robots" type="submit" class="btn btn-primary btn-lg addpage" value="Сохранить"/>
</form>
<script>
  var editor = CodeMirror.fromTextArea(document.getElementById("code_robots"), {
        lineNumbers: true,
        styleActiveLine: true,
        matchBrackets: true
  });
</script>
    
<?php endif;?>

</div>
</center>