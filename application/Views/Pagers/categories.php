<?php
$request = \Config\Services::request();
$page_previous = (!empty($_GET['page']) ? $_GET['page'] - 1 : null);
$page_next = (!empty($_GET['page']) ? $_GET['page'] + 1 : 2);
$pager->setSurroundCount(2);
if ($pager->hasPrevious() || $pager->hasNext()) :
?>
<div class="bloc" id="share">
	<h1>Pages</h1>
		<ul class="pagination">
			<?php if ($pager->hasPrevious()) : ?>
				<li>
					<a href="<?php echo $pager->getFirst(); ?>" aria-label="<?php echo lang('Pager.first'); ?>">
						<i class="fa fa-step-backward"></i>
					</a>
				</li>
				<li>
					<a href="<?php echo base_url('categories/' . $request->uri->getSegment(2) . '/?page=' . $page_previous); ?>" aria-label="<?php echo lang('Pager.previous'); ?>">
						<i class="fa fa-chevron-left"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php foreach ($pager->links() as $link) : ?>
				<li <?php echo $link['active'] ? 'class="active"' : ''; ?>>
					<a href="<?php echo $link['uri']; ?>">
						<?php echo $link['title']; ?>
					</a>
				</li>
			<?php endforeach; ?>

			<?php if ($pager->hasNext()) : ?>
				<li>
					<a href="<?php echo base_url('categories/' . $request->uri->getSegment(2) . '/?page=' . $page_next); ?>" aria-label="<?php echo lang('Pager.next'); ?>">
						<i class="fa fa-chevron-right"></i>
					</a>
				</li>
				<li>
					<a href="<?php echo $pager->getLast(); ?>" aria-label="<?php echo lang('Pager.last'); ?>">
						<i class="fa fa-step-forward"></i>
					</a>
				</li>
			<?php endif; ?>
		</ul>
</div>
<?php
endif;
?>
