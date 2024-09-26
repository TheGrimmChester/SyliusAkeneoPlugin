<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Factory;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Synolia\SyliusAkeneoPlugin\Provider\TaskProviderInterface;

abstract class AbstractPipelineFactory implements PipelineFactoryInterface
{
    protected TaskProviderInterface $taskProvider;

    protected EventDispatcherInterface $dispatcher;

    public function __construct(
        TaskProviderInterface $taskProvider,
        EventDispatcherInterface $dispatcher
    ) {
        $this->taskProvider = $taskProvider;
        $this->dispatcher = $dispatcher;
    }
}
