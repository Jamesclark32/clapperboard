<?php

namespace Clapperbrd\Codegen\Support;

class FakerFieldMapper
{
    /**
     * Map field names to appropriate faker methods.
     */
    protected static array $fieldNameMap = [
        // Names and titles
        'name' => 'name()',
        'title' => 'sentence(3)',
        'first_name' => 'firstName()',
        'last_name' => 'lastName()',
        'username' => 'userName()',
        'slug' => 'slug()',

        // Contact information
        'email' => 'safeEmail()',
        'phone' => 'phoneNumber()',
        'mobile' => 'phoneNumber()',
        'website' => 'url()',
        'url' => 'url()',

        // Addresses
        'address' => 'address()',
        'street' => 'streetAddress()',
        'city' => 'city()',
        'state' => 'state()',
        'country' => 'country()',
        'postal_code' => 'postcode()',
        'zip_code' => 'postcode()',

        // Content
        'description' => 'paragraph()',
        'content' => 'paragraphs(3, true)',
        'summary' => 'sentence()',
        'notes' => 'text()',
        'comment' => 'sentence()',
        'bio' => 'paragraph()',

        // Financial
        'price' => 'randomFloat(2, 10, 1000)',
        'cost' => 'randomFloat(2, 10, 1000)',
        'amount' => 'randomFloat(2, 10, 1000)',
        'salary' => 'numberBetween(30000, 150000)',
        'revenue' => 'randomFloat(2, 1000, 100000)',

        // Categories and tags
        'category' => 'word()',
        'type' => 'word()',
        'status' => 'randomElement(["active", "inactive", "pending"])',
        'priority' => 'randomElement(["low", "medium", "high"])',
        'tag' => 'word()',

        // Dates and times
        'birth_date' => 'date()',
        'start_date' => 'date()',
        'end_date' => 'date()',
        'published_at' => 'dateTime()',
        'expires_at' => 'dateTime()',

        // Images and files
        'avatar' => 'imageUrl()',
        'image' => 'imageUrl()',
        'photo' => 'imageUrl()',
        'file' => 'word() . ".pdf"',
        'path' => 'filePath()',

        // Numbers and quantities
        'quantity' => 'numberBetween(1, 100)',
        'count' => 'numberBetween(1, 50)',
        'weight' => 'randomFloat(2, 0.1, 100)',
        'height' => 'randomFloat(2, 1, 300)',
        'width' => 'randomFloat(2, 1, 300)',
        'length' => 'randomFloat(2, 1, 300)',
        'size' => 'randomElement(["small", "medium", "large"])',

        // IDs and codes
        'code' => 'regexify("[A-Z]{3}[0-9]{3}")',
        'sku' => 'regexify("[A-Z]{2}[0-9]{4}")',
        'uuid' => 'uuid()',

        // Company/Business
        'company' => 'company()',
        'department' => 'word()',
        'position' => 'jobTitle()',
        'industry' => 'word()',
    ];

    /**
     * Map cast types to appropriate faker methods.
     */
    protected static array $castTypeMap = [
        'boolean' => 'boolean()',
        'bool' => 'boolean()',
        'integer' => 'numberBetween(1, 1000)',
        'int' => 'numberBetween(1, 1000)',
        'float' => 'randomFloat(2, 1, 1000)',
        'double' => 'randomFloat(2, 1, 1000)',
        'decimal' => 'randomFloat(2, 1, 1000)',
        'date' => 'date()',
        'datetime' => 'dateTime()',
        'timestamp' => 'dateTime()',
        'json' => '["item1", "item2", "item3"]',
        'array' => '["item1", "item2", "item3"]',
    ];

    /**
     * Map field names that contain certain keywords.
     */
    protected static array $containsMap = [
        'email' => 'safeEmail()',
        'phone' => 'phoneNumber()',
        'address' => 'address()',
        'description' => 'paragraph()',
        'content' => 'paragraphs(2, true)',
        'price' => 'randomFloat(2, 10, 1000)',
        'cost' => 'randomFloat(2, 10, 1000)',
        'amount' => 'randomFloat(2, 10, 1000)',
        'date' => 'date()',
        'time' => 'dateTime()',
        'url' => 'url()',
        'image' => 'imageUrl()',
        'photo' => 'imageUrl()',
        'avatar' => 'imageUrl()',
    ];

    /**
     * Get the appropriate faker method for a field.
     */
    public static function getFakerMethod(string $fieldName, ?string $castType = null): string
    {
        $fieldName = strtolower($fieldName);

        // Check for exact field name matches first
        if (isset(static::$fieldNameMap[$fieldName])) {
            return static::$fieldNameMap[$fieldName];
        }

        // Check for boolean fields (is_*, has_*, can_*, etc.)
        if (preg_match('/^(is|has|can|should|will|was)_/', $fieldName)) {
            return 'boolean()';
        }

        // Check for ID fields
        if (str_ends_with($fieldName, '_id') && $fieldName !== 'id') {
            return 'numberBetween(1, 100)';
        }

        // Check cast type mapping
        if ($castType && isset(static::$castTypeMap[$castType])) {
            return static::$castTypeMap[$castType];
        }

        // Check if field name contains certain keywords
        foreach (static::$containsMap as $keyword => $fakerMethod) {
            if (str_contains($fieldName, $keyword)) {
                return $fakerMethod;
            }
        }

        // Default fallback based on field name patterns
        if (str_contains($fieldName, 'name') || str_contains($fieldName, 'title')) {
            return 'words(2, true)';
        }

        if (str_contains($fieldName, 'text') || str_contains($fieldName, 'body')) {
            return 'paragraph()';
        }

        // Final fallback
        return 'word()';
    }

    /**
     * Get a human-readable explanation of what the faker will generate.
     */
    public static function getExplanation(string $fieldName, ?string $castType = null): string
    {
        $method = static::getFakerMethod($fieldName, $castType);

        return match (true) {
            str_contains($method, 'name()') => 'a random person name',
            str_contains($method, 'safeEmail()') => 'a safe email address',
            str_contains($method, 'boolean()') => 'true or false',
            str_contains($method, 'paragraph()') => 'a paragraph of Lorem ipsum text',
            str_contains($method, 'sentence') => 'a sentence of random words',
            str_contains($method, 'randomFloat') => 'a random decimal number',
            str_contains($method, 'numberBetween') => 'a random integer',
            str_contains($method, 'date') => 'a random date',
            str_contains($method, 'imageUrl') => 'a placeholder image URL',
            str_contains($method, 'randomElement') => 'one of several predefined options',
            default => 'a random word',
        };
    }
}
