<?php

declare(strict_types=1);

namespace App\PhyloTree\Repository;

use App\PhyloTree\Model\TreeNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;
use App\PhyloTree\Contract\TreeNodeInterface;
use App\PhyloTree\Contract\TreeRepositoryInterface;

/**
 * Doctrine implementation of the Tree repository.
 */
final class DoctrineTreeRepository extends ServiceEntityRepository implements TreeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TreeNode::class);
    }

    /**
     * {@inheritDoc}
     * @param int|string $id
     * @param int|LockMode|null $lockMode
     * @param int|null $lockVersion
     */

    public function load(object $Root): void
{
    // TODO: Implement load() method.
}

    public function find(mixed $id, int|null|\Doctrine\DBAL\LockMode $lockMode = null, int|null $lockVersion = null): ?TreeNodeInterface
    {
        $node = parent::find(
            $id,
            $lockMode,
            $lockVersion
        );
        return $node instanceof TreeNodeInterface ? $node : null;
    }

    /**
     * {@inheritDoc}
     */
    public function findRoot(): ?TreeNodeInterface
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.parent IS NULL')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * {@inheritDoc}
     */
    public function exists(TreeNodeInterface $node): bool
    {
        return $this->find($node->getId()) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren(TreeNodeInterface $node): iterable
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.parent = :parent')
            ->setParameter('parent', $node)
            ->orderBy('n.lft', 'ASC')
            ->getQuery()
            ->toIterable();
    }

    /**
     * {@inheritDoc}
     */
    public function getDescendants(TreeNodeInterface $node): iterable
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.lft > :left')
            ->andWhere('n.rgt < :right')
            ->setParameter('left', $node->getLft())
            ->setParameter('right', $node->getRgt())
            ->orderBy('n.lft', 'ASC')
            ->getQuery()
            ->toIterable();
    }

    /**
     * {@inheritDoc}
     */
    public function getAncestors(TreeNodeInterface $node): iterable
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.lft < :left')
            ->andWhere('n.rgt > :right')
            ->setParameter('left', $node->getLft())
            ->setParameter('right', $node->getRgt())
            ->orderBy('n.lft', 'ASC')
            ->getQuery()
            ->toIterable();
    }

    /**
     * {@inheritDoc}
     */
    public function getLeaves(TreeNodeInterface $node): iterable
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.lft > :left')
            ->andWhere('n.rgt < :right')
            ->andWhere('n.rgt = n.lft + 1')
            ->setParameter('left', $node->getLft())
            ->setParameter('right', $node->getRgt())
            ->orderBy('n.lft', 'ASC')
            ->getQuery()
            ->toIterable();
    }

    /**
     * {@inheritDoc}
     */
    public function getSubtree(TreeNodeInterface $node): iterable
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.lft >= :left')
            ->andWhere('n.rgt <= :right')
            ->setParameter('left', $node->getLft())
            ->setParameter('right', $node->getRgt())
            ->orderBy('n.lft', 'ASC')
            ->getQuery()
            ->toIterable();
    }
}
