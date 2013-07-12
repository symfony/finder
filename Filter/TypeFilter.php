<?php

namespace Symfony\Component\Finder\Filter;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class TypeFilter implements FilterInterface
{
    const TYPE_ANY = 0;
    const TYPE_FILE = 1;
    const TYPE_DIRECTORY = 2;

    /**
     * @var int
     */
    private $type;

    /**
     * @param int $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function reject(SplFileInfo $file)
    {
        if (self::TYPE_FILE === $this->type) {
            return !$file->isFile();
        }

        if (self::TYPE_DIRECTORY === $this->type) {
            return !$file->isDir();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return !(self::TYPE_FILE === $this->type || self::TYPE_DIRECTORY === $this->type);
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
