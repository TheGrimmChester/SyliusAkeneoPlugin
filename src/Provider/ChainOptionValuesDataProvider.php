<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Provider;

use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Synolia\SyliusAkeneoPlugin\Exceptions\Processor\MissingProductOptionValuesProcessorException;
use Synolia\SyliusAkeneoPlugin\Processor\ProductOptionValue\OptionValuesProcessorInterface;

class ChainOptionValuesDataProvider implements OptionValuesProcessorProviderInterface
{
    /** @var array<OptionValuesProcessorInterface> */
    private $optionValuesProcessors;

    public function __construct(\Traversable $handlers)
    {
        $this->optionValuesProcessors = iterator_to_array($handlers);
    }

    public function getProcessor(AttributeInterface $attribute, ProductOptionInterface $productOption, array $context = []): OptionValuesProcessorInterface
    {
        if (null === $this->optionValuesProcessors) {
            $this->optionValuesProcessors = [];
        }

        /** @var OptionValuesProcessorInterface $optionValuesProcessor */
        foreach ($this->optionValuesProcessors as $optionValuesProcessor) {
            if ($optionValuesProcessor->support($attribute, $productOption, $context)) {
                return $optionValuesProcessor;
            }
        }

        throw new MissingProductOptionValuesProcessorException(\sprintf('Could not find an OptionValuesProcessor for option %s', $productOption->getCode()));
    }
}
