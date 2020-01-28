<li class="list_elt single-people">
	<img src="src/img/avatar/avatar<?php echo $user['picture'];?>.png" alt="">
	<div class="inner-singlePeople">
		<h3><?php echo $user['name'];?></h3>

		<form action="user.php" method="post">
			<input type="hidden" name="userID" value="<?php print $user['ID'];?>">
			
			<?php bt('button', 'white-bt', 'Me connecter'); ?>
			
		</form>

	</div>
</li>