<?php
/**
 * Phossa Project
 *
 * @category  Library
 * @package   Phossa2\Event
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Interfaces;

/**
 * ListenerInterface
 *
 * Classes implementing this interface will be able to listen to events
 *
 * @package Phossa2\Event
 * @version 2.1.7
 * @since   2.0.0 added
 * @since   2.1.3 added registerEvent()
 * @since   2.1.7 updated doc
 */
interface ListenerInterface
{
    /**
     * Get the list of events $this is listening
     *
     * e.g.
     * ```php
     * [
     *     // one method of $this
     *     eventName1 => 'method1',
     *
     *     // 2 methods
     *     eventName2 => ['callable1', 'method2'],
     *
     *     // priority 20 and in a scope
     *     eventName2 => ['method2', 20, 'mvcScope'], // with priority 20
     *
     *     eventName3 => [
     *         'method1',
     *         ['method3', 50],
     *         ['method4', 70, 'anotherScope']
     *     ]
     * ];
     * ```
     */
    public function eventsListening(): array;

    /**
     * Add to events listening
     *
     * handler in the format of
     *
     * - 'method1'  // method of $this
     *
     * - ['method2', 20] // method of $this with priority
     *
     * - callable1 // a callable
     *
     * - ['method3', 20, 'mvc'] // with scope also
     *
     * @param  string|string[] $eventName event name[s]
     * @param  mixed $handler
     * @since  2.1.3 added
     */
    public function registerEvent($eventName, $handler): self;
}
