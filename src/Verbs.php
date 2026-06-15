<?php

namespace JamesClark32\Clapperboard;

enum Verbs: string
{
    case Index = 'index';
    case Show = 'show';
    case Create = 'create';
    case Store = 'store';
    case Edit = 'edit';
    case Update = 'update';
    case Destroy = 'destroy';

    public static function fromInput(string $verb): ?self
    {
        $verb = str($verb)->lower()->singular()->toString();

        if ($verb === 'delete') {
            $verb = self::Destroy->value;
        }

        return self::tryFrom($verb);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function sortOrder(): int
    {
        return match ($this) {
            self::Index => 1,
            self::Create => 2,
            self::Store => 3,
            self::Show => 4,
            self::Edit => 5,
            self::Update => 6,
            self::Destroy => 7,
        };
    }

    public static function httpVerb(string|self $verb): string
    {
        $case = is_string($verb) ? self::tryFrom($verb) : $verb;

        return match ($case) {
            self::Index, self::Show, self::Create, self::Edit => 'get',
            self::Store => 'post',
            self::Update => 'put',
            self::Destroy => 'delete',
        };
    }

    public static function isCommand(string|self $verb): bool
    {
        $case = is_string($verb) ? self::tryFrom($verb) : $verb;

        return match ($case) {
            self::Store, self::Update, self::Destroy => true,
            default => false,
        };
    }

    public static function isQuery(string|self $verb): bool
    {
        $case = is_string($verb) ? self::tryFrom($verb) : $verb;

        return match ($case) {
            self::Index, self::Show, self::Create, self::Edit => true,
            default => false,
        };
    }
}
