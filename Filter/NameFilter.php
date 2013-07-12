<?php

namespace Symfony\Component\Finder\Filter;

use Symfony\Component\Finder\Expression\Expression;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class NameFilter implements FilterInterface
{
    /**
     * @var string[]
     */
    private $patterns;

    /**
     * @param string[] $names
     */
    public function __construct(array $names)
    {
        $this->patterns = array_map(function ($name) {
            return Expression::create($name)->getRegex()->render();
        }, $names);
    }

    /**
     * {@inheritdoc}
     */
    public function reject(SplFileInfo $file)
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $file->getFilename())) {
                return false;
            }
        }

        return true;
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
        return self::TYPE_LIST;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 25;
    }
}
