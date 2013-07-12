<?php

namespace Symfony\Component\Finder\Adapter;

use Symfony\Component\Finder\Expression\Expression;
use Symfony\Component\Finder\Filter;
use Symfony\Component\Finder\Iterator\IteratorBuilder;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class RecursivePhpAdapter extends AbstractAdapter
{
    /**
     * {@inheritdoc}
     */
    public function searchInDirectory($dir)
    {
        return IteratorBuilder::create(array(
            new Filter\TypeFilter($this->mode),
            new Filter\MinDepthFilter($this->minDepth),
            new Filter\MaxDepthFilter($this->maxDepth),
            new Filter\ExcludeFilter($this->exclude),
            new Filter\NameFilter($this->names),
            new Filter\NotNameFilter( $this->notNames),
            new Filter\PathFilter($this->paths),
            new Filter\NotPathFilter( $this->notPaths),
            new Filter\ContentFilter($this->contains),
            new Filter\NotContentFilter($this->notContains),
            new Filter\SizeFilter($this->sizes),
            new Filter\DateFilter($this->dates),
        ), $this->filters)
            ->sort($this->sort)
            ->ignoreUnreadableDirs($this->ignoreUnreadableDirs)
            ->build($dir, $this->followLinks)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function canBeUsed()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rec_php';
    }
}
