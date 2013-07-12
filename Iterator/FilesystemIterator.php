<?php

namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class FilesystemIterator extends \RecursiveDirectoryIterator
{
    /**
     * @var boolean
     */
    private $followLinks;

    /**
     * @var boolean
     */
    private $ignoreUnreadableDirs;

    /**
     * @var int
     */
    private $depth;

    /**
     * @var boolean
     */
    private $rewindable;

    /**
     * @param string  $path
     * @param boolean $followLinks
     * @param boolean $ignoreUnreadableDirs
     * @param int     $depth
     */
    public function __construct($path, $followLinks = false, $ignoreUnreadableDirs = false, $depth = 0)
    {
        $flags = \FilesystemIterator::KEY_AS_PATHNAME     // iterator keys are file pathname
               | \FilesystemIterator::SKIP_DOTS           // skip `.` and `..`
               | \FilesystemIterator::CURRENT_AS_PATHNAME // returns only pathname to let us build our own SplFileInfo
               | \FilesystemIterator::UNIX_PATHS;         // paths always use `/` as separator (even on windows)

        if ($followLinks) {
            $flags |= \RecursiveDirectoryIterator::FOLLOW_SYMLINKS;
        }

        parent::__construct($path, $flags);
        $this->followLinks = $followLinks;
        $this->depth = $depth;
        $this->ignoreUnreadableDirs = $ignoreUnreadableDirs;
    }

    /**
     * @return SplFileInfo
     */
    public function current()
    {
        return new SplFileInfo(parent::current(), $this->getSubPath(), $this->getSubPathname(), $this->depth);
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        try {
            /** @var FilesystemIterator $iterator */
            $path = parent::getChildren();
        } catch (\UnexpectedValueException $e) {
            if ($this->ignoreUnreadableDirs) {
                // If directory is unreadable and finder is set to ignore it, a fake empty content is returned.
                return new \RecursiveArrayIterator(array());
            } else {
                throw new AccessDeniedException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return new self($path, $this->followLinks, $this->ignoreUnreadableDirs, $this->depth + 1);
    }

    /**
     * Do nothing for non rewindable stream
     */
    public function rewind()
    {
        if (false === $this->isRewindable()) {
            return;
        }

        // @see https://bugs.php.net/bug.php?id=49104
        parent::next();
        parent::rewind();
    }

    /**
     * Checks if the stream is rewindable.
     *
     * @return Boolean true when the stream is rewindable, false otherwise
     */
    public function isRewindable()
    {
        if (null !== $this->rewindable) {
            return $this->rewindable;
        }

        if (false !== $stream = @opendir($this->getPath())) {
            $infos = stream_get_meta_data($stream);
            closedir($stream);

            if ($infos['seekable']) {
                return $this->rewindable = true;
            }
        }

        return $this->rewindable = false;
    }
}
