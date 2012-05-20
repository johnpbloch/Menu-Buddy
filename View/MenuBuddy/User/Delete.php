<p>Are you sure you want to delete "<?php echo $user->username; ?>"?</p>
	<a href="/user/list">No, cancel</a> | 
<form method="post" style="display:inline;">
	<input type="hidden" name="_mbtoken" value="<?php echo $token; ?>" />
	<input type="submit" value="Yes, delete this sucker!" />
</form>