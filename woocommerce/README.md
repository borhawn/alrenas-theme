# WooCommerce template overrides

This directory holds the theme's WooCommerce email overrides, added for
the quote system (see `inc/quotes/`). Everything else -- product gallery,
summary, price, stock, variations, add-to-cart, attributes/specs, tabs,
related products, cart, checkout, account screens and structured data --
remains fully owned by WooCommerce's own templates and hooks; only the
outer wrapper (`inc/woocommerce.php`) and the product inquiry sidebar are
theme-controlled outside of what's here.

- `emails/email-styles.php` -- full override. Reads the site's brand
  colors from WooCommerce -> Settings -> Emails (base/background/text
  colors) but hardcodes the theme's fonts and a few custom classes
  (`.email-button`, `.email-note-box`, etc.) used by the templates below.
  Deliberately smaller than WooCommerce's own version of this file, which
  also styles a couple of newer, feature-flagged email-editor layouts
  this theme doesn't use.
- `emails/{customer,admin}-*.php` (+ `plain/` counterparts) -- the 5
  quote-lifecycle emails registered in `inc/quotes/emails.php`
  (`Alrenas_Email_*` classes). These are new templates specific to the
  quote flow, not copies of a core WooCommerce email at a pinned
  version -- there's nothing to keep in sync with core here.

Add a further override only when the existing design genuinely can't be
achieved with hooks or CSS. If you ever do override a stock WooCommerce
template verbatim (rather than writing a new one, as above), record the
matching core template version in its file header so drift is easy to
spot on a WooCommerce update.
