<?php

namespace Symfony\Component\Finder\Filter;

use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class MinDepthFilter implements FilterInterface
{
    /**
     * @var int
     */
    private $value = 0;

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
        return $file->getRelativeDepth() < $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return 0 === $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return self::TYPE_LIST;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 50;
    }
}
