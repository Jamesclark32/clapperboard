# jamesclark32/clapperboard

Opinionated Laravel application code generation commands for AI agents.

## Scope

This package currently provides the existing generation commands from the host app:

- `php artisan generate:model`
- `php artisan generate:route`

The package is intentionally opinionated around a Laravel + Inertia + Vue application structure.

## Install In A Laravel App

For local development via a Composer path repository:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../clapperboard"
        }
    ],
    "require-dev": {
        "jamesclark32/clapperboard": "^0.1"
    }
}
```

Then run:

```bash
composer update jamesclark32/clapperboard
```

Laravel package discovery will register `JamesClark32\Clapperboard\CodegenServiceProvider` automatically.

Install agent-facing project instructions:

```bash
php artisan clapperboard:install
```

## Local Testing

Example workflow for testing package changes against a local Laravel app:

1. Add this package to a host app with a Composer path repository.
2. Refresh the package in the host app after each package change.
3. Run generator dry-runs first, then apply writes.

In the host Laravel app:

```bash
composer update jamesclark32/clapperboard
php artisan clapperboard:install --dry-run --json
php artisan generate:model Widget "Widget catalog item" '{"attributes":[{"name":"name","fillable":true,"columnType":"string"},{"name":"description","fillable":true,"columnType":"text","nullable":true},{"name":"price","fillable":true,"columnType":"decimal","casts":"decimal"}],"relationships":[]}' --dry-run --json
php artisan generate:route Widgets index --dry-run --json
php artisan generate:route Widgets create --dry-run --json
php artisan generate:route Widgets store --dry-run --json
php artisan generate:route Widgets show --dry-run --json
php artisan generate:route Widgets edit --dry-run --json
php artisan generate:route Widgets update --dry-run --json
```

To apply the generated files in the host app:

```bash
php artisan generate:model Widget "Widget catalog item" '{"attributes":[{"name":"name","fillable":true,"columnType":"string"},{"name":"description","fillable":true,"columnType":"text","nullable":true},{"name":"price","fillable":true,"columnType":"decimal","casts":"decimal"}],"relationships":[]}'
php artisan generate:route Widgets index
php artisan generate:route Widgets create
php artisan generate:route Widgets store
php artisan generate:route Widgets show
php artisan generate:route Widgets edit
php artisan generate:route Widgets update
```

Useful follow-up checks in the host app:

```bash
php artisan test
php artisan route:list --name=widgets
```

## Usage

Preview changes without writing files:

```bash
php artisan clapperboard:install --dry-run --json
php artisan generate:route Widgets index --dry-run --json
php artisan generate:model Widget "Widget catalog item" '{"attributes":[{"name":"name","fillable":true,"columnType":"string"}],"relationships":[]}' --dry-run --json
```

Write files:

```bash
php artisan generate:route Widgets index
php artisan generate:model Widget "Widget catalog item" '{"attributes":[{"name":"name","fillable":true,"columnType":"string"}],"relationships":[]}'
```

## Notes

- `--dry-run` returns a generation plan without writing files.
- `--json` returns machine-readable output for agents.
- `php artisan clapperboard:install` writes Clapperboard instructions into `AGENTS.md` and `.github/copilot-instructions.md`.
- Generated output assumes the host app follows the architecture used by this codebase.
