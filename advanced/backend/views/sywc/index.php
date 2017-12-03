<form action="index.php?r=sywc/file" method="post" enctype="multipart/form-data">
<input type="file" name="file">
<input type="hidden" value="<?php echo $token ?>" name="token">
<input type="submit" value="上传">
</form>