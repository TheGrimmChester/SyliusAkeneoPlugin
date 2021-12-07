<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Transformer;

final class AttributeOptionValueDataTransformer implements AttributeOptionValueDataTransformerInterface
{
    public const AKENEO_PREFIX = 'akeneo-';

    public function transform(string $value): string
    {
        return mb_strtolower(sprintf(
            '%s%s',
            self::AKENEO_PREFIX,
            $value
        ));
    }

    public function reverseTransform(string $value): string
    {
        return str_replace(self::AKENEO_PREFIX, '', $value);
    }
}
