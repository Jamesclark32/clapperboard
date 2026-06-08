# clapperbrd/codegen

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
        "clapperbrd/codegen": "*"
    }
}
```

Then run:

```bash
composer update clapperbrd/codegen
```

Laravel package discovery will register `Clapperbrd\Codegen\CodegenServiceProvider` automatically.

## Usage

Preview changes without writing files:

```bash
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
- Generated output assumes the host app follows the architecture used by this codebase.
