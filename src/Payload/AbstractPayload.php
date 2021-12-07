<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Payload;

use Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientInterface;
use ReflectionClass;
use Synolia\SyliusAkeneoPlugin\Command\Context\CommandContextInterface;
use Synolia\SyliusAkeneoPlugin\Configuration\ConfigurationContextTrait;

abstract class AbstractPayload implements PipelinePayloadInterface
{
    use ConfigurationContextTrait;

    protected AkeneoPimEnterpriseClientInterface $akeneoPimClient;

    protected \Synolia\SyliusAkeneoPlugin\Entity\ApiConfiguration $apiConfiguration;

    protected ?CommandContextInterface $commandContext;

    protected array $ids = [];

    protected string $tmpTableName;

    protected string $commandName;

    public function __construct(
        AkeneoPimEnterpriseClientInterface $akeneoPimClient,
        ?CommandContextInterface $commandContext = null
    ) {
        $this->akeneoPimClient = $akeneoPimClient;
        $this->commandContext = $commandContext;

        if (null !== $commandContext) {
            $this->allowParallel = $commandContext->allowParallel();
            $this->batchSize = $commandContext->getBatchSize();
            $this->batchingAllowed = $commandContext->isBatchingAllowed();
            $this->maxRunningProcessQueueSize = $commandContext->getMaxRunningProcessQueueSize();
            $this->verbosity = $commandContext->getVerbosity();
            $this->isContinue = $commandContext->isContinue();
        }
    }

    public function getAkeneoPimClient(): AkeneoPimEnterpriseClientInterface
    {
        return $this->akeneoPimClient;
    }

    public function getType(): string
    {
        try {
            return mb_substr((new ReflectionClass($this))->getShortName(), 0, -7);
        } catch (\ReflectionException $e) {
            return '';
        }
    }

    public function getApiConfiguration(): \Synolia\SyliusAkeneoPlugin\Entity\ApiConfiguration
    {
        return $this->apiConfiguration;
    }

    public function setApiConfiguration(\Synolia\SyliusAkeneoPlugin\Entity\ApiConfiguration $apiConfiguration): self
    {
        $this->apiConfiguration = $apiConfiguration;

        return $this;
    }

    public function getCommandContext(): CommandContextInterface
    {
        if (null === $this->commandContext) {
            throw new \LogicException('CommandContext is null');
        }

        return $this->commandContext;
    }

    public function hasCommandContext(): bool
    {
        return null !== $this->commandContext;
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function setIds(array $ids): BatchPayloadInterface
    {
        $this->ids = $ids;

        return $this;
    }

    public function getTmpTableName(): string
    {
        return $this->tmpTableName;
    }

    public function setTmpTableName(string $table): BatchPayloadInterface
    {
        $this->tmpTableName = $table;

        return $this;
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    public function setCommandName(string $command): BatchPayloadInterface
    {
        $this->commandName = $command;

        return $this;
    }
}
