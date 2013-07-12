<?php

namespace Symfony\Component\Finder\Filter;

use Symfony\Component\Finder\Expression\Expression;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ContentFilter implements FilterInterface
{
    /**
     * @var string[]
     */
    private $patterns;

    /**
     * @param string[] $contains
     */
    public function __construct(array $contains)
    {
        $this->patterns = array_map(function ($content) {
            return Expression::create($content)->isRegex() ? $content :'/'.preg_quote($content, '/').'/';
        }, $contains);
    }

    /**
     * {@inheritdoc}
     */
    public function reject(SplFileInfo $file)
    {
        if (!$file->isFile() || !$file->isReadable()) {
            return true;
        }

        if (!$content = $file->getContents()) {
            return true;
        }

        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $content)) {
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
        return -50;
    }
}
