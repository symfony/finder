<?php

namespace Symfony\Component\Finder\Filter;

use Symfony\Component\Finder\Expression\Expression;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class NotNameFilter implements FilterInterface
{
    /**
     * @var string[]
     */
    private $patterns;

    /**
     * @param string[] $notNames
     */
    public function __construct(array $notNames)
    {
        $this->patterns = array_map(function ($name) {
            return Expression::create($name)->getRegex()->render();
        }, $notNames);
    }

    /**
     * {@inheritdoc}
     */
    public function reject(SplFileInfo $file)
    {
        foreach ($this->patterns as $patterns) {
            if (preg_match($patterns, $file->getFilename())) {
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
        return empty($this->patterns);
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
        return 25;
    }
}
