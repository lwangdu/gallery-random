=== Gallery Random ===
Contributors: Lobsang Wangdu
Tags: gallery, random gallery, images
Requires at least: 6.0
Tested up to: 6.8
Stable tag: 1.0.25
License: GPLv2 or later

Displays one randomized full-width hero image with title, description, buttons, and a hidden caption opened from an info icon.

== Usage ==
1. Activate Gallery Random.
2. Go to Gallery Random in wp-admin.
3. Add a Gallery Random Item for each image.
4. Set the image as the Featured Image.
5. Set shared content under Settings > Gallery Random. Leave item fields blank to inherit, or enter per-image overrides. On an item, select Use the default gallery title for this item when its WordPress post title should be ignored. Edit the featured image's Caption in the WordPress Media Library.
6. Add this shortcode to a page or post. Each page load displays one random published gallery item:

[gallery_random]

The hyphen shortcode also works:

[gallery-random]

The original misspelled shortcodes are kept as compatibility aliases for existing content:

[gallery_rendom]

[gallery-rendom]

You can also add the Gallery Random block in the block editor. The block uses the same renderer as the shortcode so front-end output stays consistent.

== Settings ==
Go to Settings > Gallery Random (also available under Gallery Random > Settings) to set the default title, description, focal position, and primary/secondary button text and URLs. Each blank item field inherits its matching default. An item's excerpt takes priority over its editor content; when both are blank, the default description is used. Descriptions retain the existing 32-word display limit. Select Use plugin default for an item's focal position; existing explicit positions remain overrides. Buttons appear only when their resolved text and URL are both present.

The same page controls the text area background, title color, description color, and button colors with six-digit hex values such as #004a89 or 004a89. Reset to Default Colors changes colors only, leaving shared content untouched.

== Captions ==
Captions use the featured image's standard WordPress Media Library Caption field and remain hidden until the info button is activated. No caption means no info button. There is no shared caption default or custom caption meta box. Old custom captions remain stored but are no longer displayed: copy any you want to keep into the appropriate image's Media Library Caption field. Shared images use the same caption wherever selected.

== Caching ==
The shortcode defines DONOTCACHEPAGE during render and stores the published Gallery Random item ID list in a transient that is cleared when items are saved, trashed, untrashed, or deleted. Some full-page cache plugins decide whether to cache before shortcode rendering, so pages that use Gallery Random may still need to be excluded manually in the active cache plugin.

== Uninstall ==
Deleting the plugin removes its color settings, content defaults, and transient cache. Gallery Random Item posts and Media Library captions are left in place because they are editorial content.
