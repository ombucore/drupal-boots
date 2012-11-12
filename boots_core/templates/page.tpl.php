<?php

/**
 * @file
 * @see modules/system/page.tpl.php
 */
?>
<div id="container">
  <header>
    <?php if ($logo): ?>
    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"
      rel="home" id="logo">
      <?php echo theme('image', array(
        'path' => $logo,
        'alt' => $site_name,
      )); ?>
    </a>
    <?php endif; ?>

    <?php if ($site_name || $site_slogan): ?>
    <div id="name-and-slogan">
      <?php if ($site_name): ?>
      <?php if ($title): ?>
      <div id="site-name">
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"
          rel="home"><span><?php print $site_name; ?></span></a>
      </div>
      <?php else: /* Use h1 when the content title is empty */ ?>
      <h1 id="site-name">
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"
          rel="home"><span><?php print $site_name; ?></span></a>
      </h1>
      <?php endif; ?>
      <?php endif; ?>

      <?php if ($site_slogan): ?>
      <div id="site-slogan"><?php print $site_slogan; ?></div>
      <?php endif; ?>
    </div> <!-- /#name-and-slogan -->
    <?php endif; ?>

    <?php print render($page['header']); ?>
  </header>

  <div id="page">
    <?php if ($page['sidebar_first']): ?>
    <div id="sidebar-first" class="sidebar">
      <?php print render($page['sidebar_first']); ?>
    </div>
    <?php endif; ?>

    <div id="main">
      <?php if ($breadcrumb): ?>
        <?php print $breadcrumb; ?>
      <?php endif; ?>

      <?php print $messages; ?>

      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
      <h1 class="title" id="page-title"><?php print $title; ?></h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php print render($page['help']); ?>

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

  <footer>
    <?php print render($page['footer']); ?>
  </footer>
</div> <!--/#container -->
