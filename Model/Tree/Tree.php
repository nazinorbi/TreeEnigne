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
}
