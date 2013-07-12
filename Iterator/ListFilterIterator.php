<?php

namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Filter\FilterInterface;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ListFilterIterator extends \FilterIterator
{
    /**
     * @var FilterInterface[]
     */
    private $filters = array();

    /**
     * @param \Iterator         $iterator
     * @param FilterInterface[] $filters
     */
    public function __construct(\Iterator $iterator, array $filters)
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
}
