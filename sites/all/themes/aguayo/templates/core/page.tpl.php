<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 */
?>

<?php if ($messages): ?>
<div id="messages-wrapper">
  <div id="messages-content">
    <?php print $messages; ?>
  </div>
  <a href="#" id="messages-toggle"><?php print t('Close');?></a>
</div>
<?php endif; ?>

<div id="page">

  <?php if (aguayo_print_group('header', $page)): ?>
    <header id="header" role="banner">
      <?php if ($page['header']): ?>
        <div id="header-first" class="section-wrapper"><div class="section"><div class="section-inner clearfix">
          <?php print render($page['header']); ?>
        </div></div></div> <!-- /.section-inner, /.section, /#header-first -->
      <?php endif; ?>

      <?php if ($page['header_second'] || $page['header_second_b']): ?>
        <div id="header-second" class="section-wrapper"><div class="section"><div class="section-inner clearfix">
          <?php print render($page['header_second']); ?>
          <?php print render($page['header_second_b']); ?>
        </div></div></div> <!-- /.section-inner, /.section, /#header-second -->
      <?php endif; ?>

      <?php if ($page['header_third']): ?>
        <div id="header-third" class="section-wrapper"><div class="section"><div class="section-inner clearfix">
          <?php print render($page['header_third']); ?>
        </div></div></div> <!-- /.section-inner, /.section, /#header-third -->
      <?php endif; ?>
    </header> <!-- /#header -->
  <?php endif; ?>

  <?php if (aguayo_print_group('preface', $page)): ?>
    <div id="preface" role="complementary">
      <?php if ($page['preface_first']): ?>
        <div id="preface-first" class="section-wrapper"><div class="section"><div class="section-inner clearfix">
          <?php print render($page['preface_first']); ?>
        </div></div></div> <!-- /.section-inner, /.section, /#preface-first -->
      <?php endif; ?>

      <?php if ($page['preface_second']): ?>
        <div id="preface-second" class="section-wrapper"><div class="section"><div class="section-inner clearfix">
          <?php print render($page['preface_second']); ?>
        </div></div></div> <!-- /.section-inner, /.section, /#preface-second -->
      <?php endif; ?>
    </div> <!-- /#preface -->
  <?php endif; ?>

  <div id="main">
    <div id="main-inner" class="section-wrapper"><div class="section"><div class="section-inner clearfix">

      <div id="content" class="column" role="main"><div class="content-inner clearfix">

        <?php print render($page['pre_content']); ?>

        <?php if ($tabs['#primary']): ?>
          <div class="tabs clearfix"><?php print render($tabs); ?></div>
        <?php endif; ?>

        <?php print render($page['help']); ?>

        <?php if ($action_links): ?>
          <ul class="action-links"><?php print render($action_links); ?></ul>
        <?php endif; ?>

        <?php print render($page['content']); ?>
        <?php print $feed_icons; ?>

        <?php print render($page['post_content']); ?>
      </div></div> <!-- /.content-inner, /#content -->

      <?php if ($page['sidebar_first']): ?>
        <aside id="sidebar-first" class="column sidebar" role="complementary">
          <div class="sidebar-inner clearfix">
            <?php print render($page['sidebar_first']); ?>
          </div>
        </aside> <!-- /#sidebar-first -->
      <?php endif; ?>

      <?php if ($page['sidebar_second']): ?>
        <aside id="sidebar-second" class="column sidebar" role="complementary">
          <div class="sidebar-inner clearfix">
            <?php print render($page['sidebar_second']); ?>
          </div>
        </aside> <!--/#sidebar-second -->
      <?php endif; ?>

    </div></div></div> <!-- /.section-inner, /.section, /#main-inner -->
  </div> <!-- /#main -->

  <?php if (aguayo_print_group('postscript', $page)): ?>
    <div id="postscript" role="complementary">
      <?php if ($page['postscript_first']): ?>
        <div id="postscript-first" class="section-wrapper"><div class="section"><div class="section-inner clearfix">
          <?php print render($page['postscript_first']); ?>
        </div></div></div> <!-- /.section-inner, /.section, /#postscript-first -->
      <?php endif; ?>

      <?php if ($page['postscript_second']): ?>
        <div id="postscript-second" class="section-wrapper"><div class="section"><div class="section-inner clearfix">
          <?php print render($page['postscript_second']); ?>
        </div></div></div> <!-- /.section-inner, /.section, /#postscript-second -->
      <?php endif; ?>
    </div> <!-- /#postscript -->
  <?php endif; ?>

  <?php if (aguayo_print_group('footer', $page)): ?>
    <footer id="footer" role="contentinfo">
      <?php if ($page['footer']): ?>
        <div id="footer-first" class="section-wrapper"><div class="section"><div class="section-inner clearfix">
          <?php print render($page['footer']); ?>
        </div></div></div> <!-- /.section-inner, /.section, /#footer-first -->
      <?php endif; ?>

      <?php if ($page['footer_second'] || $page['footer_second_b']): ?>
        <div id="footer-second" class="section-wrapper"><div class="section"><div class="section-inner clearfix">
          <?php print render($page['footer_second']); ?>
          <?php print render($page['footer_second_b']); ?>
        </div></div></div> <!-- /.section-inner, /.section, /#footer-second -->
      <?php endif; ?>

      <?php if ($page['footer_third']): ?>
        <div id="footer-third" class="section-wrapper"><div class="section"><div class="section-inner clearfix">
          <?php print render($page['footer_third']); ?>
        </div></div></div> <!-- /.section-inner, /.section, /#footer-third -->
      <?php endif; ?>
    </footer> <!-- /#footer -->
  <?php endif; ?>

</div> <!-- /#page -->
