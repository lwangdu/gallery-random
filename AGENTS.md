# AGENTS.md

General repository guidance for AI coding agents working on a WordPress plugin.

## Project Triage

Before editing, inspect the plugin bootstrap file, `readme.txt`, `README.md` if present, `uninstall.php`, `composer.json`, `package.json`, lockfiles, source directories, generated assets, and tests. Verify:

- plugin slug, main file, author, text domain, current version, and declared WordPress/PHP requirements;
- whether the plugin uses classic PHP, blocks, the Interactivity API, REST/AJAX, scheduled events, WP-CLI, custom tables, or third-party services;
- which files are source files and which are generated or packaged files.

Do not infer compatibility from version strings alone. Check the actual runtime and tooling available in the checkout.

## Workflow

1. Read applicable repository and skill instructions before changing code.
2. Inspect `git status --short`; preserve unrelated developer changes.
3. Trace the complete feature path: input, validation, storage, transport, rendering, front-end behavior, and cleanup.
4. Make the smallest safe change that satisfies the request.
5. Update all affected layers together, including source, generated assets, defaults, documentation, and tests.
6. Verify the changed behavior and report what was and was not tested.

Do not remove code believed to be unused without asking the developer first.

## WordPress Coding and Security

- Follow WordPress coding standards for PHP, JavaScript, CSS, HTML, documentation, and accessibility.
- Prefix global functions, classes, constants, options, transients, script handles, REST namespaces, and database identifiers.
- Use WordPress APIs and hooks rather than reimplementing core behavior.
- Guard executable PHP files with `defined( 'ABSPATH' ) || exit;`. Use `defined( 'WP_UNINSTALL_PLUGIN' ) || exit;` in `uninstall.php`.
- Internationalize user-facing strings with the plugin’s actual text domain.
- Sanitize and validate input at the boundary, authorize privileged actions with capabilities, and use action-specific nonces for browser-originated state changes. Nonces do not replace authorization.
- Escape output at render time using the context-appropriate escaping function.
- Give public REST routes explicit permission callbacks and schemas. Secure AJAX actions in the same way.
- Use `$wpdb->prepare()` for dynamic SQL and bound limits for remote/API requests, pagination, and polling.
- Never expose secrets in markup, JavaScript, REST responses, scheduled-event arguments, logs, or errors.
- Do not introduce third-party dependencies unless the benefit and package impact are documented.

## Blocks and Generated Assets

- Treat `block.json` and source files as authoritative; do not edit generated `build/` files manually.
- Rebuild after source or metadata changes and review the generated diff.
- Preserve existing block names, attribute types, defaults, and saved-content behavior unless a migration/deprecation plan is included.
- Validate block attributes again in PHP; editor metadata is not a security boundary.
- For dynamic blocks, keep server rendering and `save: null` behavior consistent unless an explicit compatibility plan changes it.
- Use WordPress block wrappers and supports correctly, including generated support styles.
- Register assets through the project’s build and metadata pipeline; do not bundle duplicate WordPress runtime packages.

## Accessibility and Front End

- Use semantic HTML, logical headings, accessible names, visible keyboard focus, sufficient contrast, and responsive layouts.
- Ensure controls work by keyboard and do not rely only on hover, pointer events, or color.
- Handle loading, empty, error, and retry states. Prevent stale asynchronous responses from overwriting newer state.
- Keep server-rendered content useful when JavaScript fails where practical.
- Avoid duplicate IDs, unexpected focus changes, excessive live-region announcements, and unnecessary motion. Respect reduced-motion preferences.
- Scope plugin CSS to the plugin wrapper and avoid assumptions about the active theme.

## Data, Lifecycle, and Uninstall

- Prefer posts, taxonomies, metadata, options, and transients over custom tables when they fit the data model.
- Document plugin-owned options, transients, tables, scheduled events, and external data.
- Activation and migration routines must be safe to rerun and must not destroy existing data.
- Deactivation should normally disable behavior without deleting persistent data.
- `uninstall.php` may remove only plugin-owned data and must leave user-created editorial content unless deletion was explicitly requested.
- Update administrator documentation when storage, retention, migration, or uninstall behavior changes.

## Verification

Run checks proportionate to the change, using actual repository tooling:

- PHP changes: `php -l` on changed files and available WordPress Coding Standards/Plugin Check checks.
- JavaScript/CSS changes: project lint and build commands.
- Metadata changes: parse JSON, verify asset references, rebuild, and inspect generated output.
- REST/AJAX/security changes: test valid requests plus invalid input, missing/invalid nonces, and insufficient permissions.
- Lifecycle changes: test activation, deactivation, migration, and uninstall boundaries safely.
- Front-end changes: test keyboard use, responsive layout, loading, empty, error, and no-JavaScript behavior where relevant.
- Always run `git diff --check` and inspect the final diff and package contents.

Distinguish static checks, local runtime checks, browser checks, and unperformed checks. If required WordPress versions, services, data, or tools are unavailable, state the specific limitation and remaining uncertainty.

## Documentation and Release

- Update `README.md` for developer guidance and `readme.txt` for administrator/WordPress.org-facing usage, requirements, settings, and changelog information.
- Keep plugin header, package metadata, readmes, and release versions consistent when a version bump is requested.
- Update `Tested up to` only after compatibility testing against that WordPress version.
- Exclude development-only files, secrets, `node_modules`, and unnecessary tooling from release packages.
- Do not commit, tag, publish, or push unless explicitly requested.
- Before committing, confirm the exact working-tree scope. After an authorized push, report the commit, branch, remote, hash alignment, and clean status.

## Ask Before

Ask for explicit approval before:

- changing minimum WordPress/PHP requirements;
- changing public REST endpoints, response formats, block attributes, or stored-content formats;
- adding external services or dependencies;
- removing features or backward compatibility;
- weakening security, permission, sanitization, or escaping checks;
- deleting user-created WordPress content;
- publishing, tagging, or deploying a release.

Routine tests, builds, and package-manager operations within the requested implementation scope may proceed, subject to the environment’s permission requirements.

## References

Use official WordPress documentation for API behavior and compatibility questions:

- Agent skills: <https://make.wordpress.org/ai/handbook/projects/agent-skills/>
- Block metadata: <https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/>
- Interactivity API: <https://developer.wordpress.org/block-editor/reference-guides/packages/packages-interactivity/>
- Nonces and authorization: <https://developer.wordpress.org/apis/security/nonces/>
