<?php

namespace Symfony\Component\Finder\Filter;

use Symfony\Component\Finder\Expression\Expression;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ExcludeFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @param array $paths
     */
    public function __construct(array $paths)
    {
        $this->pattern = '#(^|/)('.implode('|', array_map(function ($path) {
            return preg_quote($path, '#');
        }, $paths)).')(/|$)#';
    }

    /**
     * {@inheritdoc}
     */
    public function reject(SplFileInfo $file)
    {
        $relativePath = $file->isDir() ? $file->getRelativePathname() : $file->getRelativePath();

        return preg_match($this->pattern, $relativePath);
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
        return 40;
    }
}
