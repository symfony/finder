<?php

namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Filter\FilterInterface;
use Symfony\Component\Finder\Filter\TypeFilter;
use Symfony\Component\Finder\Iterator\SortableIterator;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class IteratorBuilder
{
    /**
     * @var FilterInterface[][]
     */
    private $filters = array();

    /**
     * @var \Closure[]
     */
    private $callbacks = array();

    /**
     * @var string|\Closure
     */
    private $sort;

    /**
     * @var bool
     */
    private $followLink = false;

    /**
     * @var bool
     */
    private $ignoreUnreadableDirs = false;

    /**
     * Creates an IteratorBuilder instance.
     *
     * @param FilterInterface[] $filters
     * @param \Closure[]        $callbacks
     *
     * @return IteratorBuilder
     */
    public static function create(array $filters = array(), array $callbacks = array())
    {
        $builder = new self();

        foreach ($filters as $filter) {
            $builder->addFilter($filter);
        }

        foreach ($callbacks as $callback) {
            $builder->addCallback($callback);
        }

        return $builder;
    }

    /**
     * Adds a filter.
     *
     * @param FilterInterface $filter
     *
     * @return IteratorBuilder
     */
    public function addFilter(FilterInterface $filter)
    {
        if ($filter->isEmpty()) {
            return $this;
        }

        if (!isset($this->filters[$filter->getType()])) {
            $this->filters[$filter->getType()] = array();
        }

        $this->filters[$filter->getType()][] = $filter;

        return $this;
    }

    /**
     * Adds a callback filter.
     *
     * @param \Closure $callback
     *
     * @return IteratorBuilder
     */
    public function addCallback(\Closure $callback)
    {
        $this->callbacks[] = $callback;

        return $this;
    }

    /**
     * Sets result sort.
     *
     * @param string|\Closure $sort
     *
     * @return IteratorBuilder
     */
    public function sort($sort)
    {
        if ($sort) {
            $this->sort = $sort;
        }

        return $this;
    }

    /**
     * Tells if symbolic links must be followed or not.
     *
     * @param boolean $followLink
     *
     * @return IteratorBuilder
     */
    public function followLink($followLink)
    {
        $this->followLink = $followLink;

        return $this;
    }

    /**
     * Tells if unreadable dirs must be ignored.
     *
     * @param boolean $ignoreUnreadableDirs
     *
     * @return IteratorBuilder
     */
    public function ignoreUnreadableDirs($ignoreUnreadableDirs)
    {
        $this->ignoreUnreadableDirs = $ignoreUnreadableDirs;

        return $this;
    }

    /**
     * Builds result iterator for given dirs.
     *
     * @param string $dir
     *
     * @return \Iterator
     */
    public function build($dir)
    {
        $iterator = new FilesystemIterator($dir, $this->followLink, $this->ignoreUnreadableDirs);
        $iterator = $this->filterTree($iterator);
        $iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
        $iterator = $this->filterList($iterator);
        $iterator = $this->filterCallbacks($iterator);
        $iterator = $this->sortList($iterator);

        return $iterator;
    }

    /**
     * Wraps iterator with tree filter if needed.
     *
     * @param \RecursiveIterator $iterator
     *
     * @return \RecursiveIterator
     */
    private function filterTree(\RecursiveIterator $iterator)
    {
        if (empty($this->filters[FilterInterface::TYPE_TREE])) {
            return $iterator;
        }

        return new TreeFilterIterator($iterator, $this->sortFilters($this->filters[FilterInterface::TYPE_TREE]));
    }

    /**
     * Wraps iterator with list filter if needed.
     *
     * @param \Iterator $iterator
     *
     * @return \Iterator
     */
    private function filterList(\Iterator $iterator)
    {
        if (empty($this->filters[FilterInterface::TYPE_LIST])) {
            return $iterator;
        }

        return new ListFilterIterator($iterator, $this->sortFilters($this->filters[FilterInterface::TYPE_LIST]));
    }

    /**
     * Wraps iterator with callbacks filter if needed.
     *
     * @param \Iterator $iterator
     *
     * @return \Iterator
     */
    private function filterCallbacks(\Iterator $iterator)
    {
        if (empty($this->callbacks)) {
            return $iterator;
        }

        return new CustomFilterIterator($iterator, $this->callbacks);
    }

    /**
     * Wraps iterator with sortable one if needed.
     *
     * @param \Iterator $iterator
     *
     * @return \Iterator
     */
    private function sortList(\Iterator $iterator)
    {
        if (null === $this->sort) {
            return $iterator;
        }

        return new SortableIterator($iterator, $this->sort);
    }

    /**
     * Sorts filters from the highest to the lowest priority.
     *
     * @param FilterInterface[] $filters
     *
     * @return FilterInterface[]
     */
    private function sortFilters(array $filters)
    {
        usort($filters, function (FilterInterface $a, FilterInterface $b) {
            return $a->getPriority() - $b->getPriority();
        });

        return $filters;
    }
}
