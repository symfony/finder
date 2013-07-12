<?php

namespace Symfony\Component\Finder\Filter;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface FilterInterface
{
    const TYPE_TREE = 1;
    const TYPE_LIST = 2;

    /**
     * Tests if file must be rejected from the result list.
     *
     * @param SplFileInfo $file
     *
     * @return boolean
     */
    public function reject(SplFileInfo $file);

    /**
     * Tests if filter is empty.
     *
     * @return boolean
     */
    public function isEmpty();

    /**
     * Returns filter type, self::TYPE_LIST or self::TYPE_TREE.
     *
     * @return int
     */
    public function getType();

    /**
     * Returns filter priority, the highest is applied first.
     *
     * @return int
     */
    public function getPriority();
}
