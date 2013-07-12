<?php

namespace Symfony\Component\Finder\Filter;

use Symfony\Component\Finder\Expression\Expression;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class PathFilter implements FilterInterface
{
    /**
     * @var string[]
     */
    private $patterns;

    /**
     * @param string[] $paths
     */
    public function __construct(array $paths)
    {
        $this->patterns = array_map(function ($path) {
            return Expression::create($path)->isRegex() ? $path : '/'.preg_quote($path, '/').'/';
        }, $paths);
    }

    /**
     * {@inheritdoc}
     */
    public function reject(SplFileInfo $file)
    {
        $filename = $file->getRelativePathname();

        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $filename)) {
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
