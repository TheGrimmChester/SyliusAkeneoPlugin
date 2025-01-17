<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Provider;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;

final class AkeneoFamilyPropertiesProvider implements AkeneoFamilyPropertiesProviderInterface
{
    /** @var bool */
    private $loadsAllFamiliesAtOnce = false;

    /** @var array */
    private $families = [];

    /** @var \Akeneo\Pim\ApiClient\AkeneoPimClientInterface */
    private $client;

    public function __construct(AkeneoPimEnterpriseClientInterface $akeneoPimClient)
    {
        $this->client = $akeneoPimClient;
    }

    public function getProperties(string $familyCode): array
    {
        if (isset($this->families[$familyCode])) {
            return $this->families[$familyCode];
        }

        if ($this->loadsAllFamiliesAtOnce) {
            foreach ($this->client->getFamilyApi()->all() as $familyResource) {
                $this->families[$familyResource['code']] = $familyResource;
            }
        }

        if (!isset($this->families[$familyCode]) && !$this->loadsAllFamiliesAtOnce) {
            $this->families[$familyCode] = $this->client->getFamilyApi()->get($familyCode);
        }

        return $this->families[$familyCode];
    }
}
