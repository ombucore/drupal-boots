<?php
/**
 * @file
 * @see modules/system/page.tpl.php
 */
?>

<!-- Site Top -->
<?php if (!empty($page['site_top'])): ?>
<?php print render($page['site_top']); ?>
<?php endif ?>

<div id="page" class="<?php print $classes ?>">

  <!-- Header -->
  <?php if (!empty($page['header'])): ?> <?php print render($page['header']); ?> <?php endif ?>

  <!-- Console Messages -->
  <?php if ($messages) { print $messages; } ?>

  <!-- Content top -->
  <?php if (!empty($page['content_top'])): ?>
  <?php print render($page['content_top']); ?>
  <?php endif ?>

  <div data-type="region-container" data-name="core">
    <div class="container">
      <div class="row">

        <!-- Sidebar First-->
        <?php if (!empty($page['sidebar_first'])): ?>
        <?php print render($page['sidebar_first']); ?>
        <?php endif ?>

        <!-- Main Content -->
        <div id="main" class="col-xs-<?php print $content_col_width_xs; ?> col-sm-<?php print $content_col_width_sm; ?> col-md-<?php print $content_col_width_md; ?> col-lg-<?php print $content_col_width_lg; ?>">
        <?php if (!empty($page['content'])): ?>
        <?php print render($page['content']); ?>
        <?php endif ?>
        </div>

        <!-- Sidebar Second -->
        <?php if (!empty($page['sidebar_second'])): ?>
        <?php print render($page['sidebar_second']); ?>
        <?php endif ?>

      </div>
    </div>
  </div>

  <!-- Content Bottom -->
  <?php if (!empty($page['content_bottom'])): ?>
  <?php print render($page['content_bottom']); ?>
  <?php endif ?>

  <!-- Sections -->
  <?php if (!empty($page['sections'])): ?>
    <div data-type="region" data-name="sections">
        <?php print render($page['sections']) ?>
    </div>
  <?php endif ?>

  <!-- Footer -->
  <?php if (!empty($page['footer'])): ?> <?php print render($page['footer']); ?> <?php endif ?>

</div>

<!-- Site Bottom -->
<?php if (!empty($page['site_bottom'])): ?>
<?php print render($page['site_bottom']); ?>
<?php endif ?>
