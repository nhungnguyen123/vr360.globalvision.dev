<?php if (isset($_SESSION['messages'])): ?>
	<?php foreach ($_SESSION['messages'] as $key => $messagesGroup): ?>
		<?php foreach ($messagesGroup as $index => $message): ?>
			<span class="label label-<?php echo $key; ?>"><?php echo $message; ?></span>
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php endif; ?>
