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
