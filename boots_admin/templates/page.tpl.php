<?php

/**
 * @file
 * @see modules/system/page.tpl.php
 */
?>
<div id="container">

  <?php if ($breadcrumb): ?>
  <div id="breadcrumb"><?php print $breadcrumb; ?></div>
  <?php endif; ?>

  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
  <h1 class="title" id="page-title"><?php print $title; ?></h1>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php print $messages; ?>
  <?php print render($page['help']); ?>

  <?php if (!empty($tabs['#primary'])): ?>
  <ul class="nav nav-tabs"><?php print render($tabs['#primary']); ?></ul>
  <?php endif; ?>
  <div id="page">

    <?php if ($page['sidebar_first']): ?>
    <div id="sidebar-first" class="sidebar">
      <?php print render($page['sidebar_first']); ?>
    </div>
    <?php endif; ?>

    <div id="main">

      <?php if (!empty($tabs['#secondary'])): ?>
      <ul class="nav nav-pills"><?php print render($tabs['#secondary']); ?></ul>
      <?php endif; ?>

      <?php if ($action_links): ?>
      <div class="btn-group action-links"><?php print render($action_links); ?></div>
      <?php endif; ?>

      <?php print render($page['content']); ?>

      <?php print $feed_icons; ?>
    </div><!-- /#main -->

    <?php if ($page['sidebar_second']): ?>
    <div id="sidebar-second" class="sidebar">
      <?php print render($page['sidebar_second']); ?>
    </div>
    <?php endif; ?>

  </div><!-- /#page -->

  <?php if ($page['footer']): ?>
  <footer id="footer">
    <?php print render($page['footer']); ?>
  </footer>
  <?php endif; ?>
