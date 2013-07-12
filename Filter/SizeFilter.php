<?php

namespace Symfony\Component\Finder\Filter;

use Symfony\Component\Finder\Comparator\NumberComparator;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class SizeFilter implements FilterInterface
{
    /**
     * @var NumberComparator[]
     */
    private $comparators;

    /**
     * @param NumberComparator[] $comparators
     */
    public function __construct(array $comparators)
    {
        $this->comparators = $comparators;
    }

    /**
     * {@inheritdoc}
     */
    public function reject(SplFileInfo $file)
    {
        foreach ($this->comparators as $comparator) {
            if (!$comparator->test($file->getSize())) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return empty($this->comparators);
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
        return 30;
    }
}
