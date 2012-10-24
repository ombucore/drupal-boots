<?php
/**
 * @file
 * @see modules/system/page.tpl.php
 */
?>

<?php if ($page['site_top']): ?>
  <?php print render($page['site_top']); ?>
<?php endif ?>

<div id="container" class="container-fluid <?php print $classes ?>">

  <?php if ($page['header']): ?> <?php print render($page['header']); ?> <?php endif ?>

  <?php if ($messages): ?>
	<div class="row-fluid">
	  <div id="console" class="span12 clearfix"><?php print $messages; ?></div>
	</div>
  <?php endif; ?>

  <div class="row-fluid">
	<?php if ($page['sidebar_first']): ?> <?php print render($page['sidebar_first']); ?> <?php endif ?>
	<div class="span<?php print $content_col_span ?>">
	  <?php if ($page['content_top']): ?> <?php print render($page['content_top']); ?> <?php endif ?>
	  <?php if ($page['content']): ?> <?php print render($page['content']); ?> <?php endif ?>
	</div>
	<?php if ($page['sidebar_second']): ?> <?php print render($page['sidebar_second']); ?> <?php endif ?>
  </div>


<?php if ($page['footer']): ?> <?php print render($page['footer']); ?> <?php endif ?>

</div> <!--/#container -->

<?php if ($page['site_bottom']): ?>
  <?php print render($page['site_bottom']); ?>
<?php endif ?>
