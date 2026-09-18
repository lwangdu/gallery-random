# Gallery Random

Displays a random hero from published `gallery_rendom_item` posts. Use the Gallery Random block or `[gallery_random]`. Legacy block and shortcode names remain supported.

Set shared text, buttons, focal position, and colors under **Settings > Gallery Random**. Blank item fields inherit the matching defaults. Captions come from the featured image's Media Library caption.

## Development

`gallery-rendom.php` contains registration, settings, metadata, caching, and shared server rendering. Files in `assets/` are directly served source files; this plugin has no package manager or generated build step. The block editor script registers both block names, exposes the existing heading-level attribute, and saves no static markup.

Each rendered hero has unique accessibility IDs, including when multiple instances select the same item. The item-ID transient is invalidated before permanent deletion, while WordPress can still identify the post type. The title stylesheet uses the configured title color.

Run the standalone checks:

```sh
php tests/content-defaults.php
node tests/block-registration.js
php -l gallery-rendom.php
node --check assets/gallery-random-block.js
git diff --check
```

These isolated tests do not replace WordPress or browser testing. Use WordPress Coding Standards when available. Package the root runtime PHP files, `readme.txt`, and `assets/`; exclude tests, agent guidance, Git metadata, and development files.

Settings use prefixed WordPress options; the published item list uses the `gallery_rendom_item_ids` transient. Color-reset notices use short-lived per-user transients. A one-day `gallery_rendom_last_item` cookie avoids the previous selection when headers permit it. No external service is used. Uninstall removes settings and the item-ID transient while retaining editorial posts and Media Library captions.
