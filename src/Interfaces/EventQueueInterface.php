<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Interfaces;

/**
 * @since 2.0.0 added
 * @since 2.1.0 changed priority
 * @since 2.1.2 now extending PriorityQueueInterface
 */
interface EventQueueInterface extends \Countable, \IteratorAggregate
{
    /**
     * Remove data from the queue if any
     *
     * @param mixed $data
     */
    public function remove($data): void;

    /**
     * Empty/flush the queue
     */
    public function flush(): void;

    /**
     * Append $queue onto this one
     */
    public function append(self $queue): void;

    /**
     * Add $data to the queue
     *
     * @param mixed $data
     */
    public function insert($data, int $priority = 0): void;
}
