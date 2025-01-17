<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Synolia\SyliusAkeneoPlugin\Client\ClientFactoryInterface;
use Synolia\SyliusAkeneoPlugin\Payload\Product\ProductPayload;
use Synolia\SyliusAkeneoPlugin\Task\Product\BatchProductsTask;

final class BatchImportProductsCommand extends AbstractBatchCommand
{
    protected static $defaultDescription = 'Import batch product ids from Akeneo PIM.';

    /** @var string */
    protected static $defaultName = 'akeneo:batch:products';

    /** @var ClientFactoryInterface */
    private $clientFactory;

    /** @var LoggerInterface */
    private $logger;

    /** @var \Synolia\SyliusAkeneoPlugin\Task\Product\BatchProductsTask */
    private $batchProductGroupsTask;

    public function __construct(
        ClientFactoryInterface $clientFactory,
        LoggerInterface $akeneoLogger,
        BatchProductsTask $batchProductsTask,
        string $name = null
    ) {
        parent::__construct($name);
        $this->clientFactory = $clientFactory;
        $this->logger = $akeneoLogger;
        $this->batchProductGroupsTask = $batchProductsTask;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $ids = explode(',', $input->getArgument('ids'));

        $this->logger->notice('Processing batch', ['from_id' => $ids[0], 'to_id' => $ids[\count($ids) - 1]]);
        $this->logger->debug(self::$defaultName, ['batched_ids' => $ids]);

        $productModelPayload = new ProductPayload($this->clientFactory->createFromApiCredentials());
        $productModelPayload->setIds($ids);

        $this->batchProductGroupsTask->__invoke($productModelPayload);

        return 0;
    }
}
