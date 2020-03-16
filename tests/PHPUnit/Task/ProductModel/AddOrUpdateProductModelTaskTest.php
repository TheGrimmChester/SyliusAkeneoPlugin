<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusAkeneoPlugin\PHPUnit\Task\ProductModel;

use donatj\MockWebServer\MockWebServer;
use PHPUnit\Framework\Assert;
use Synolia\SyliusAkeneoPlugin\Model\PipelinePayloadInterface;
use Synolia\SyliusAkeneoPlugin\Payload\ProductModel\ProductModelPayload;
use Synolia\SyliusAkeneoPlugin\Provider\AkeneoTaskProvider;
use Synolia\SyliusAkeneoPlugin\Task\ProductModel\AddOrUpdateProductModelsTask;
use Synolia\SyliusAkeneoPlugin\Task\ProductModel\RetrieveProductModelsTask;

final class AddOrUpdateProductModelTaskTest extends AbstractTaskTest
{
    /** @var AkeneoTaskProvider */
    private $taskProvider;

    /** @var MockWebServer */
    protected $server;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskProvider = self::$container->get(AkeneoTaskProvider::class);
        self::assertInstanceOf(AkeneoTaskProvider::class, $this->taskProvider);
    }

    public function testCreateUpdateTask(): void
    {
        $productModelPayload = new ProductModelPayload($this->createClient());

        /** @var RetrieveProductModelsTask $retrieveProductModelsTask */
        $retrieveProductModelsTask = $this->taskProvider->get(RetrieveProductModelsTask::class);
        $optionsPayload = $retrieveProductModelsTask->__invoke($productModelPayload);

        /** @var AddOrUpdateProductModelsTask $addOrUpdateProductModelsTask */
        $addOrUpdateProductModelsTask = $this->taskProvider->get(AddOrUpdateProductModelsTask::class);
        $result = $addOrUpdateProductModelsTask->__invoke($optionsPayload);
        Assert::assertInstanceOf(PipelinePayloadInterface::class, $result);
    }
}
