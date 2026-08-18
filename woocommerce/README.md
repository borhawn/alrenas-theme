# WooCommerce template overrides

This directory is intentionally empty of PHP overrides.

The theme currently integrates through WooCommerce theme support, core
templates, standard hooks, and narrow wrapper functions. Add an override here
only when the existing design cannot be achieved with hooks or CSS. Any future
override must retain WooCommerce hooks and record the matching core template
version in its file header.

WooCommerce currently owns the product gallery, summary, price, stock,
variations, add-to-cart controls, attributes/specifications, tabs, related
products, cart, checkout, account screens and structured data. The theme adds
only its outer wrapper and the plugin-controlled product inquiry section via
standard hooks.
