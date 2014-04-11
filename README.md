Boots
=====
Boots was developed by [OMBU](http://ombuweb.com) to provide a hierarchical set of starter themes for Drupal 7.  These themes include:

1. #### **Boots Core**
    Boots Core is our root theme.  Its primary purpose is to provide a foundational layer of lean, optimized Drupal markup using [Bootstrap](http://getbootstrap.com/) naming conventions (e.g., .btn, .alert).  We’ve purposefully excluded CSS from Boots Core so that it can be defined by other themes without the need for style overrides.

1. #### **Boots Grid**
    Boots Grid takes Boots Core one step further by incorporating [Bootstrap's grid system](http://getbootstrap.com/css/#grid).  The grid system provides a convention foundation for creating responsive sites and multi-column layouts.  Visual design treatments are still largely excluded from Boots Grid so that inheriting themes can define their own styles without collision.  Most of our client work begins with Boots Grid.
    
1. #### **Bootstrap**
    Our Bootstrap theme adds a thin layer of visual treatments and front-end behaviors to Boots Grid.  These additions come directly from [Bootstrap](http://getbootstrap.com/) and contain a handful of our own targeted overrides.  Internally, we use the Bootstrap theme primarily to demonstrate the power and flexibility of the Boots system.
    
1. #### **Boots Admin**
    Boots Admin is our Drupal admin theme.  It inherits from Boots Core and provides a starting point for building more sophisticated admin themes when necessary.  Boots Admin is still under development and is not in active use by any of our projects.

Dependencies
------------
* [LESS CSS](http://lesscss.org)
    Our styles are written in LESS and compiled into CSS.

* [Bootstrap](http://getbootstrap.com/)
    Boostrap is already included in the repository, but contains LESS files of its own that may be customized and recompiled.

* [Modernizr](http://modernizr.com/)
    Modernizr is also included in the repository and is enabled in Boots Core to allow the use of HTML5 elements in older versions of IE.

Usage
-----

* Choose an appropriate Boots theme and apply it in your Drupal environment as a site theme or parent theme.

* To customize Bootstrap styles, substitute or include your own variables.less file and recompile the theme's primary LESS file.
