<?php

namespace App\PhyloTree\Model\Tree;

use App\PhyloTree\Contract\TreeNodeInterface;

class Tree
{
    /**
     * Strukturális lekérdezések
     */

    private TreeNodeInterface $root;

    public function __construct(
        TreeNodeInterface $root
    )
    {
        $this->root = $root;
        $this->rebuildNestedSetValues();
    }
    public function getRoot(): TreeNodeInterface
    {
        return $this->root;
    }

    public function addNode(
        TreeNodeInterface $parent,
        TreeNodeInterface $node ): void
    {
        $parent->addChild($node);
    }

    public function removeNode(
        TreeNodeInterface $node
    ): void
    {
        $parent = $node->getParent();

        if ($parent !== null) {

            $parent->removeChild($node);
        }
    }

    public function findById(int $id): ?TreeNodeInterface
    {
        return $this->searchNode(
            $this->root,
            $id
        );
    }
    private function searchNode(
        TreeNodeInterface $node,
        int $id
    ): ?TreeNodeInterface
    {
        if ($node->getId() === $id) {
            return $node;
        }


        foreach ($node as $child) {

            $result = $this->searchNode(
                $child,
                $id
            );

            if ($result !== null) {
                return $result;
            }
        }
        return null;
    }
    public function toNewick(): string
    {
        return $this->nodeToNewick($this->root) . ";";
    }
    private function nodeToNewick(
        TreeNodeInterface $node
    ): string
    {
        if ($node->isLeaf()) {
            return (string)$node->getId();
        }

        $children = [];

        foreach ($node as $child) {
            $children[] =
                $this->nodeToNewick($child);
        }

        return "(" .
            implode(",", $children)
            .
            ")" . $node->getId();
        }

    public function insertNode(
        TreeNodeInterface $parent,
        TreeNodeInterface $node
    ): void
    {
        $parent->addChild($node);

        $this->rebuildNestedSetValues();
    }

    public function insertSubtree(
        TreeNodeInterface $parent,
        TreeNodeInterface $subtreeRoot
    ): void
    {
        $parent->addChild($subtreeRoot);

        $this->rebuildNestedSetValues();
    }

    public function moveNode(
        TreeNodeInterface $node,
        TreeNodeInterface $newParent
    ): void
    {
        /*
         * Root áthelyezése tiltott
         */
        if ($node === $this->root) {
            throw new \RuntimeException(
                'Root node cannot be moved'
            );
        }

        $oldParent = $node->getParent();

        if ($oldParent !== null) {
            $oldParent->removeChild($node);
        }

        $newParent->addChild($node);
        $this->rebuildNestedSetValues();
    }

    public function removeSubtree(
        TreeNodeInterface $node
    ): void
    {
        /*
         * Root törlése tiltott
         */
        if ($node === $this->root) {

            throw new \RuntimeException(
                'Root node cannot be removed'
            );
        }
        $parent = $node->getParent();

        if ($parent !== null) {
            $parent->removeChild($node);
        }

        $this->rebuildNestedSetValues();
    }

    public function rebuildNestedSetValues(): void
    {

        $counter = 1;
        $this->calculateBounds(
            $this->root,
            $counter
        );
    }

    private function calculateBounds(
        TreeNodeInterface $node,
        int &$counter
    ): void
    {
        /*
         * belépés
         */
        $node->setLeft($counter);
        $counter++;

        foreach ($node as $child) {
            $this->calculateBounds(
                $child,
                $counter
            );
        }
        /*
         * kilépés
         */
        $node->setRight($counter);
        $counter++;
    }
}
