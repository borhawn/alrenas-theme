=== Alrenas ===
Contributors: alrenas
Requires at least: 6.6
Tested up to: 6.6
Requires PHP: 7.4
Version: 0.1.0
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: custom-background, custom-menu, featured-images, threaded-comments, translation-ready, e-commerce

== Description ==

Alrenas is a custom WordPress theme built for a rehabilitation technology
company that sells physiotherapy and balance-assessment equipment. It ships
bespoke templates for the home, about, products, contact, and blog pages,
plus WooCommerce integration for the product catalog and single product
pages.

The theme follows a documented design system (typography, color palette,
spacing scale) tailored to clinical/medical device marketing, and was
converted from a static HTML/CSS/JS reference build into WordPress template
parts organized by section (hero, disciplines, patient story, products,
latest posts, contact, etc.).

== Requirements ==

* WordPress 6.6+
* PHP 7.4+
* WooCommerce (for the product catalog and single product pages)

== Installation ==

1. Upload the `alrenas` folder to `/wp-content/themes/`.
2. Activate the theme through the 'Themes' screen in WordPress.
3. Install and activate WooCommerce if the product catalog is needed.
4. Assign the page templates (page-about.php, page-contact.php,
   page-products.php) to their respective pages, and set a static front
   page using front-page.php.
5. Configure the registered widget areas (contact-page-form,
   product-inquiry-form) with a form plugin, as this theme does not ship
   its own form-submission handler.

== Changelog ==

= 0.1.0 =
* Initial conversion from the static HTML/CSS/JS reference build.
* Home, about, contact, products, blog archive, single post, and 404
  templates.
* WooCommerce integration via hooks and wrapper templates (no core
  template overrides).

== Credits ==

Design system and reference markup adapted from the Alrenas static site.
