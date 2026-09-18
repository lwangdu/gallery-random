---
name: wordpress-plugin-builder
description: Develop, debug, review, and prepare releases for general-purpose WordPress plugins, including PHP, blocks, REST/AJAX, frontend behavior, persistence, lifecycle, testing, and packaging.
license: GPL-2.0-or-later
metadata:
  project-type: wordpress-plugin
---

# WordPress Plugin Builder

Use this skill for WordPress plugin creation and maintenance. Preserve user data, public interfaces, stored content, compatibility, security, accessibility, and release quality.

## Start Here

1. Locate the repository root and read its AGENTS.md.
2. Inspect git status --short and preserve unrelated changes.
3. Read the plugin bootstrap, readmes, uninstall.php, tooling manifests, relevant source, generated assets, and tests.
4. Verify the actual slug, text domain, supported WordPress/PHP versions, stable identifiers, commands, and build pipeline.
5. Trace the affected behavior before editing; use relevant installed WordPress skills when available.

Treat this as reusable guidance, not a source of plugin-specific facts. Verify details against the current checkout.

## Development Workflow

Trace changes through input/editor controls, boundary validation and authorization, storage and migrations, rendering and responses, frontend state, generated assets, documentation, and backward compatibility. Preserve existing names, response shapes, block attributes, stored formats, and user-created data unless a change is explicitly authorized.

Prefer WordPress-native APIs: hooks, Settings API, Options API, metadata, post types, REST API, block registration, enqueue APIs, cron, and WP-CLI. Use namespaced or consistently prefixed identifiers. Edit source files and regenerate build output; do not hand-edit generated files.

## Security and Data

- Guard executable PHP files with defined( 'ABSPATH' ) || exit; and uninstall.php with defined( 'WP_UNINSTALL_PLUGIN' ) || exit;.
- Sanitize and validate external input; escape output in context.
- Check capabilities before privileged actions. Nonces supplement, but do not replace, authentication and authorization.
- Give REST routes explicit permission callbacks and argument schemas. Secure AJAX actions similarly.
- Use $wpdb->prepare() for dynamic SQL and bound limits for API requests and pagination.
- Never expose credentials or sensitive data in markup, JavaScript, responses, scheduled arguments, logs, or errors.
- Prefer WordPress content APIs before custom tables. Make activation and migrations rerunnable; deactivation should normally preserve data.
- Uninstall only plugin-owned data and never delete editorial content without explicit authorization.

## Blocks, Frontend, and Accessibility

Treat block.json and source files as authoritative. Preserve block names, attribute types, defaults, and saved-content behavior. Validate attributes again in PHP. For dynamic blocks, keep server rendering and save: null behavior coherent; use migrations or deprecations for authorized format changes.

Use semantic HTML, logical headings, accessible names, visible focus, keyboard operation, adequate contrast, responsive layouts, and reduced-motion support. Handle loading, empty, error, retry, and completed states. Prevent duplicate IDs, stale asynchronous responses, excessive announcements, and unexpected focus moves. Scope plugin CSS to its wrapper and avoid theme-specific assumptions.

## Validation

Run actual repository commands relevant to the change:

- PHP syntax checks on changed files.
- JavaScript/CSS lint and build commands when assets change.
- JSON parsing and referenced-asset checks for metadata changes.
- WordPress Coding Standards or Plugin Check when available.
- Browser and accessibility checks for UI changes.
- Authorized and rejected REST/AJAX requests for endpoint changes.
- Activation, migration, deactivation, and uninstall boundary checks for lifecycle changes.
- git diff --check, final diff review, and release-package inspection.

Use focused fixtures or mocks when static checks cannot establish behavior. Do not invent commands such as npm test. Do not update Tested up to or claim compatibility until the relevant WordPress version has been tested. Report unavailable tools and remaining uncertainty.

## Documentation and Release

Update README.md and readme.txt when developer- or administrator-facing behavior changes. Keep release metadata consistent when a version bump is requested. Exclude secrets and development-only files from packages. Do not commit, tag, publish, deploy, or push unless explicitly requested. Before authorized commits or pushes, verify exact scope and report the commit, branch, remote, and status.

## Ask Before

Ask before changing minimum WordPress/PHP requirements, public REST responses, block attributes or stored formats, external dependencies/services, security checks, features or backward compatibility, user-created content, or publication/deployment state.

## References

- Agent skills: <https://make.wordpress.org/ai/handbook/projects/agent-skills/>
- Block metadata: <https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/>
- Interactivity API: <https://developer.wordpress.org/block-editor/reference-guides/packages/packages-interactivity/>
- Nonces and authorization: <https://developer.wordpress.org/apis/security/nonces/>
