<?php

declare(strict_types=1);

namespace Synolia\SyliusAkeneoPlugin\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method ProductAttributeInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductAttributeInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductAttributeInterface[]    findAll()
 * @method ProductAttributeInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<ProductAttributeInterface>
 */
final class ProductAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, ParameterBagInterface $parameterBag)
    {
        /** @var class-string<ProductAttributeInterface> $entityClass */
        $entityClass = $parameterBag->get('sylius.model.product_attribute.class');

        parent::__construct($registry, $entityClass);
    }

    /**
     * @param array<string> $codes
     *
     * @return array<int, int>
     */
    public function getMissingAttributesIds(array $codes): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.id')
            ->where('a.code NOT IN (:codes)')
            ->setParameter('codes', $codes)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param array<string> $codes
     *
     * @return array<int, int>
     */
    public function findByCodes(array $codes): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.code IN (:codes)')
            ->setParameter('codes', $codes)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAllAttributeCodes(): array
    {
        $attributeCodesResult = $this->createQueryBuilder('a')
            ->select('a.code')
            ->getQuery()
            ->getResult()
        ;

        if (0 === \count($attributeCodesResult)) {
            return [];
        }

        return \array_map(function (array $data) {
            return $data['code'];
        }, $attributeCodesResult);
    }
}
