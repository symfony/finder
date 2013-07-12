<?php

namespace Symfony\Component\Finder\Filter;

use Symfony\Component\Finder\Comparator\DateComparator;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class DateFilter implements FilterInterface
{
    /**
     * @var DateComparator[]
     */
    private $comparators;

    /**
     * @param DateComparator[] $comparators
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
            if (!$comparator->test($file->getMTime())) {
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
        return 40;
    }
}
