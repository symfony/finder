<?php

namespace Symfony\Component\Finder\Filter;

use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class MaxDepthFilter implements FilterInterface
{
    /**
     * @var int
     */
    private $value = PHP_INT_MAX;

    /**
     * @param int $value
     */
    public function __construct($value)
    {
        $this->value = max($value, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function reject(SplFileInfo $file)
    {
        return $file->getRelativeDepth() > $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return PHP_INT_MAX === $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return self::TYPE_TREE;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 50;
    }
}
