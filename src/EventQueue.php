<?php
/**
 * Phossa Project
 *
 * @category  Library
 * @package   Phossa2\Event
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event;

use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Shared\Queue\PriorityQueueTrait;
use Phossa2\Event\Interfaces\EventQueueInterface;

/**
 * EventQueue
 *
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
 * @package Phossa2\Event
 * @see     ObjectAbstract
 * @see     EventQueueInterface
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 used updated interface
 * @since   2.1.2 using PriorityQueueTrait now
 */
class EventQueue extends ObjectAbstract implements EventQueueInterface
{
    use PriorityQueueTrait;
}
