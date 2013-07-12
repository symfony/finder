<?php

namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Filter\FilterInterface;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class TreeFilterIterator extends \RecursiveFilterIterator
{
    /**
     * @var FilterInterface[]
     */
    private $filters;

    /**
     * @param \RecursiveIterator $iterator
     * @param FilterIterator[]   $filters
     */
    public function __construct(\RecursiveIterator $iterator, array $filters = array())
    {
        parent::__construct($iterator);
        $this->filters = $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        $file = $this->current();

        foreach ($this->filters as $filter) {
            if ($filter->reject($file)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        /** @var TreeFilterIterator $children */
        $children = parent::getChildren();
        $children->filters = $this->filters;

        return $children;
    }
}
