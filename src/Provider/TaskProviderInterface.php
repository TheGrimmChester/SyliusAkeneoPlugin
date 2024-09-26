<?php

namespace Synolia\SyliusAkeneoPlugin\Provider;

use Synolia\SyliusAkeneoPlugin\Task\AkeneoTaskInterface;

interface TaskProviderInterface
{
    public function addTask(AkeneoTaskInterface $akeneoTask): void;

    public function get(string $taskClassName): AkeneoTaskInterface;
}
