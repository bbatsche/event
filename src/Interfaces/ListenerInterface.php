<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Interfaces;

/**
 * Classes implementing this interface will be able to listen to events
 *
 * @since 2.0.0 added
 * @since 2.1.3 added registerEvent()
 * @since 2.1.7 updated doc
 */
interface ListenerInterface
{
    /**
     * Get the list of events $this is listening
     *
     * E.g.
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
     *
     * @return array<string, string>|array<string, callable>|array<string, array<string|int>>
     */
    public function eventsListening(): array;

    /**
     * Add to events listening
     *
     * Handler may be in the following formats
     *
     * - 'method1'  // method of $this
     * - ['method2', 20] // method of $this with priority
     * - callable1 // a callable
     * - ['method3', 20, 'mvc'] // with scope also
     *
     * @param string|string[]                   $eventName Event name(s)
     * @param string|array<string|int>|callable $handler
     *
     * @since 2.1.3 added
     */
    public function registerEvent($eventName, $handler): self;
}
