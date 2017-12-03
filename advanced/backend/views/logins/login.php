<form action="index.php?r=logins/login" method="post">
	<input type="text" name="username"><br>
	<input type="text" name="password">
	<input type="hidden" name="token" value="<?php echo $token ?>">
	<input type="submit" value="提交">
</form>