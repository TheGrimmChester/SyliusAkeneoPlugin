<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Processor\Product;

use Sylius\Component\Core\Model\ProductInterface;
use Synolia\SyliusAkeneoPlugin\Logger\Messages;
use Synolia\SyliusAkeneoPlugin\Processor\AbstractImageProcessor;

final class ImagesProcessor extends AbstractImageProcessor implements ImagesProcessorInterface
{
    public function process(ProductInterface $product, array $resource): void
    {
        try {
            $this->cleanImages($product);

            $imageAttributes = $this->productConfiguration->getAkeneoImageAttributes();
            if (null === $imageAttributes) {
                $this->logger->warning(Messages::noConfigurationSet('at least one Akeneo image attribute', 'Import image'));

                return;
            }

            $this->addImage($product, $resource['values'], $imageAttributes);
        } catch (\Throwable $throwable) {
            $this->logger->warning($throwable->getMessage());
        }
    }
}
