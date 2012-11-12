The BetterBase theme starter
============================

Betterbase hopes to give Drupal developers a solid jumping off point for themes. The 'betterbase' theme itself has the goals of stripping out unneeded content and html elements and aims to be used in every project without modifications or the need to take anything away. The 'starter' sub-theme of betterbase aims to make a few assumptions about how sites are commonly put together, then provides a framework for whipping them together quickly and solidly. [LESS CSS](http://lesscss.org) is extensively used in the starter theme.

Setup Steps
-----------
* After cloning, run 
	* `git submodule init`
	* `git submodule update`
* After updating any subrepos, run `git submodule update` in main repo root

Features
--------
* HTML5 Ready (Using the new semantic elements and making sure IE can play along
  thanks to [Modernizr](http://modernizr.com))
* LESS CSS driven sub-theme that can generate dimensions of main content areas
* LESS CSS driven CSS3 Gradient Faux Columns
* Measure out a mockup in pixels, enter the dimensions into variables.less, have
  it calculate out a liquid layout that preserves the ratios of the sidebars &
  main content :)

Pre-launch Check List
---------------------
* **Don't forget to give CSS PIE a set absolue path**
* Modernizr is using a totally stripped down version that will basically just
  let you use new HTML5 elements in IE. So it doesn't add any classes to the
  `html` element that identifies features that browser supports (no
  `border-radius` or `css-columns` classes). Considering PIE gets most whipped
  into shape, we don't need most of those. [Regenerate your own custom
  Modernizr](http://www.modernizr.com/download) for each project if you need to
  have options for sending styles to browsers that can and can't use the more
  advanced CSS3 that PIE doesn't cover (ie css columns).
* Open variables.less and pick one of the layout setups: fixed, liquid, or fixed
  to liquid conversion. Only un-comment one. Import either
  toolkit-css-less-layout/layout-liquid.less or layout-fixed.less.

To Do
-----
**Front End To Dos**

* Make sure HTML 5 elements are used where applicable:
	* Navs
* Help SEO by reformatting page.tpl.php so source order goes #main,
  #sidebar-first, #sidebar-second. Currently, #sidebar-first comes before #main.

Future Features
---------------
* A 404 page that doesn't suck
* A contact us page that doesn't suck
* Different tab styles (View/Edit) than the default Drupal tabs 
* Contact Us form, if it has address, to be marked up using microformats
