<?php
/**
 * Phossa Project
 *
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event;

use Phossa2\Event\Interfaces\EventQueueInterface;
use SplPriorityQueue;

/**
 * One implementation of EventQueueInterface with priority queue
 *
 * ```php
 * // create a new queue
 * $queue = new EventQueue();
 *
 * // insert a callable
 * $queue->insert($callable, 50);
 *
 * // count handlers in the queue
 * if (count($queue) > 0) {
 *     // loop thru the queue
 *     foreach ($queue as $q) {
 *         $callable = $q['data'];
 *         $priority = $q['priority'];
 *     }
 * }
 *
 * // remove callable
 * $queue->remove($callable);
 *
 * // merge with another queue
 * $nqueue = $queue->combine($another_queue);
 *
 * // flush (empty)
 * $queue->flush();
 * ```
 *
 * @see     ObjectAbstract
 * @see     EventQueueInterface
 *
 * @since   2.0.0 added
 * @since   2.1.0 used updated interface
 * @since   2.1.2 using PriorityQueueTrait now
 */
class EventQueue implements EventQueueInterface
{
    /** @var SplPriorityQueue */
    private $queue;

    /** @var int */
    private $queueOrder = PHP_INT_MAX;

    public function __construct()
    {
        $this->queue = $this->createQueue();
    }

    public function count(): int
    {
        return $this->queue->count();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data): void
    {
        $newQueue = $this->createQueue();

        foreach ($this->queue as $item) {
            if ($item['data'] === $data) {
                continue;
            }

            $newQueue->insert($item['data'], $item['priority']);
        }

        $this->queue = $newQueue;
    }

    public function flush(): void
    {
        $this->queue      = $this->createQueue();
        $this->queueOrder = PHP_INT_MAX;
    }

    public function append(EventQueueInterface $queue): void
    {
        foreach ($queue as $item) {
            $this->insert($item['data'], $item['priority'][0]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function insert($data, int $priority = 0): void
    {
        $this->queue->insert($data, [$priority, $this->queueOrder]);
        $this->queueOrder--;
    }

    public function getIterator(): SplPriorityQueue
    {
        $queue = clone $this->queue;
        $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

        return $queue;
    }

    /**
     * Create SplPriorityQueue and set its extract mode.
     */
    private function createQueue(): SplPriorityQueue
    {
        $queue = new SplPriorityQueue();
        $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

        return $queue;
    }
}
