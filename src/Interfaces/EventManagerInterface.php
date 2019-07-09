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
 * EventManagerInterface
 *
 * @package Phossa2\Event
 * @version 2.1.0
 * @since   2.1.0 added
 */
interface EventManagerInterface
{
    /**
     * Attaches a listener to an event
     *
     * @param string $event the event to attach too
     * @param callable $callback a callable function
     * @param int $priority the priority at which the $callback executed
     * @return bool true on success false on failure
     */
    public function attach(string $event, callable $callback, int $priority = 0): bool;

    /**
     * Detaches a listener from an event
     *
     * @param string $event the event to detach too
     * @param callable|null $callback a callable function
     * @return bool true on success false on failure
     */
    public function detach(string $event, callable $callback = null): bool;

    /**
     * Clear all listeners for a given event
     */
    public function clearListeners(string $event): void;

    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     *
     * @param  string|EventInterface $event
     * @param  object|string $target
     * @return mixed
     */
    public function trigger($event, $target = null, array $argv = []);
}
