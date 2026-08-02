<?php

declare(strict_types=1);

namespace App\PhyloTre\Doctrine;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use App\PhyloTree\Contract\TreeNodeInterface;
use App\PhyloTree\Contract\TreeRepositoryInterface;
use App\PhyloTree\Contract\TreeStorageInterface;

final class DoctrineTreeStorage implements TreeStorageInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TreeRepositoryInterface $repository,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction(): void
    {
        $this->entityManager
            ->getConnection()
            ->beginTransaction();
    }

    /**
     * {@inheritDoc}
     */
    public function commit(): void
    {
        $this->entityManager
            ->getConnection()
            ->commit();
    }

    /**
     * {@inheritDoc}
     */
    public function rollback(): void
    {
        $this->entityManager
            ->getConnection()
            ->rollBack();
    }

    /**
     * {@inheritDoc}
     */
    public function persist(TreeNodeInterface $node): void
    {
        $this->entityManager->persist($node);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(TreeNodeInterface $node): void
    {
        $this->entityManager->remove($node);
    }

    /**
     * {@inheritDoc}
     */
    public function flush(): void
    {
        $this->entityManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function refresh(TreeNodeInterface $node): void
    {
        $this->entityManager->refresh($node);
    }

    /**
     * {@inheritDoc}
     */
    public function lock(TreeNodeInterface $node): void
    {
        $this->entityManager->lock($node, LockMode::PESSIMISTIC_WRITE);
    }

    /**
     * {@inheritDoc}
     */
    public function lockSubtree(TreeNodeInterface $node): void
    {
        /*
         * Az alap implementáció ugyanazt a zárolást végzi.
         * A repository vagy egy külön LockManager később
         * felülírhatja ezt részfa-zárolásra.
         */
        $this->lock($node);
    }

    /**
     * {@inheritDoc}
     */
    public function getRepository(): TreeRepositoryInterface
    {
        return $this->repository;
    }
}
