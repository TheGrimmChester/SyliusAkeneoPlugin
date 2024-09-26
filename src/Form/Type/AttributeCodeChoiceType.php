<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Form\Type;

use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Synolia\SyliusAkeneoPlugin\Client\ClientFactoryInterface;
use Synolia\SyliusAkeneoPlugin\Payload\Attribute\AttributePayload;
use Synolia\SyliusAkeneoPlugin\Task\Attribute\RetrieveAttributesTask;

final class AttributeCodeChoiceType extends AbstractType
{
    private AkeneoPimEnterpriseClientInterface $akeneoPimClient;

    private LocaleContextInterface $localeContext;

    private RetrieveAttributesTask $retrieveAttributesTask;

    public function __construct(
        ClientFactoryInterface $clientFactory,
        LocaleContextInterface $localeContext,
        RetrieveAttributesTask $retrieveAttributesTask
    ) {
        $this->akeneoPimClient = $clientFactory->createFromApiCredentials();
        $this->localeContext = $localeContext;
        $this->retrieveAttributesTask = $retrieveAttributesTask;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $payload = new AttributePayload($this->akeneoPimClient);
        /** @var AttributePayload $attributePayload */
        $attributePayload = $this->retrieveAttributesTask->__invoke($payload);

        if (!$attributePayload->getResources() instanceof ResourceCursorInterface) {
            return;
        }

        $attributes = [];
        foreach ($attributePayload->getResources() as $attributeResource) {
            $attributes[($attributeResource['labels'][$this->localeContext->getLocaleCode()]) ?? current($attributeResource['labels'])] = $attributeResource['code'];
        }

        $resolver->setDefaults([
            'multiple' => false,
            'choices' => $attributes,
            'required' => false,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
