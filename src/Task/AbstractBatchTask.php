<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Task;

use Doctrine\DBAL\Exception\ConnectionLost;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Synolia\SyliusAkeneoPlugin\Payload\PipelinePayloadInterface;

abstract class AbstractBatchTask implements AkeneoTaskInterface, BatchTaskInterface
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getSelectStatement(PipelinePayloadInterface $payload): \Doctrine\DBAL\Statement
    {
        return $this->entityManager->getConnection()->prepare(\sprintf(
            'SELECT id, `values`
             FROM `%s`
             WHERE id IN (%s)
             ORDER BY id ASC',
            $payload->getTmpTableName(),
            implode(',', $payload->getIds())
        ));
    }

    protected function removeEntry(PipelinePayloadInterface $payload, int $id): void
    {
        $query = $this->entityManager->getConnection()->prepare(\sprintf(
            'DELETE FROM `%s` WHERE id = :id',
            $payload->getTmpTableName(),
        ));
        $query->bindValue('id', $id, ParameterType::INTEGER);

        try {
            $query->execute();
        } catch (ConnectionLost $connectionLost) {
            $query->execute();
        }
    }
}
