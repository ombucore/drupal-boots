<?php
/**
 * @file
 * @see modules/system/page.tpl.php
 */
?>

<?php if (!empty($page['site_top'])): ?>
<?php print render($page['site_top']); ?>
<?php endif ?>

<div id="container" class="container <?php print $classes ?>">

    <?php if (!empty($page['header'])): ?> <?php print render($page['header']); ?> <?php endif ?>

    <?php if ($messages) { print $messages; } ?>

    <div class="row">

        <!-- Sidebar First-->
        <?php if (!empty($page['sidebar_first'])): ?>
        <?php print render($page['sidebar_first']); ?>
        <?php endif ?>

        <!-- Main Content -->
        <div class="col-xs-<?php print $content_col_width_xs; ?> col-sm-<?php print $content_col_width_sm; ?> col-md-<?php print $content_col_width_md; ?> col-lg-<?php print $content_col_width_lg; ?>">

            <?php if (!empty($page['content_top'])): ?>
            <?php print render($page['content_top']); ?>
            <?php endif ?>
            <?php if (!empty($page['content'])): ?>
            <?php print render($page['content']); ?>
            <?php endif ?>
        </div>

        <!-- Sidebar Second -->
        <?php if (!empty($page['sidebar_second'])): ?>
        <?php print render($page['sidebar_second']); ?>
        <?php endif ?>
    </div><!-- /.row -->


    <?php if (!empty($page['footer'])): ?> <?php print render($page['footer']); ?> <?php endif ?>

</div> <!--/#container -->

<?php if (!empty($page['site_bottom'])): ?>
<?php print render($page['site_bottom']); ?>
<?php endif ?>
