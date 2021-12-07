<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Provider;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Synolia\SyliusAkeneoPlugin\Entity\ProductConfiguration;

final class ExcludedAttributesProvider implements ExcludedAttributesProviderInterface
{
    private RepositoryInterface $productConfigurationRepository;

    public function __construct(RepositoryInterface $productConfigurationRepository)
    {
        $this->productConfigurationRepository = $productConfigurationRepository;
    }

    public function getExcludedAttributes(): array
    {
        $excludedAttributeCodes = [];
        /** @var \Synolia\SyliusAkeneoPlugin\Entity\ProductConfiguration|null $productConfiguration */
        $productConfiguration = $this->productConfigurationRepository->findOneBy([]);

        if (!$productConfiguration instanceof ProductConfiguration) {
            return [];
        }

        if (null !== $productConfiguration->getAkeneoPriceAttribute()) {
            $excludedAttributeCodes[] = $productConfiguration->getAkeneoPriceAttribute();
        }

        if (null !== $productConfiguration->getAkeneoEnabledChannelsAttribute()) {
            $excludedAttributeCodes[] = $productConfiguration->getAkeneoEnabledChannelsAttribute();
        }

        if ($productConfiguration->getAkeneoImageAttributes() instanceof Collection &&
            $productConfiguration->getAkeneoImageAttributes()->count() > 0) {
            foreach ($productConfiguration->getAkeneoImageAttributes() as $akeneoImageAttribute) {
                $excludedAttributeCodes[] = $akeneoImageAttribute->getAkeneoAttributes();
            }
        }

        return $excludedAttributeCodes;
    }
}
