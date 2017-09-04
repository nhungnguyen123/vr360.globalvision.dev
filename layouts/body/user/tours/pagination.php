<?php defined('_VR360') or die; ?>

<nav aria-label="...">
	<ul class="pagination">
		<?php for( $index = 1; $index <= $pagination['total']; $index++ ): ?>
			<li class="<?php echo $index == $pagination['current'] ? 'active': ''; ?>">
				<a href="index.php?page=<?php echo $index; ?>"><?php echo $index; ?></a>
			</li>
		<?php endfor; ?>
	</ul>
</nav>

