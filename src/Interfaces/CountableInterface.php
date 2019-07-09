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
 * CountableInterface
 *
 * Able to execute event handler for limited times
 *
 * @package Phossa2\Event
 * @version 2.1.0
 * @since   2.0.0 added
 * @sicne   2.1.0 changed default priority to 0
 */
interface CountableInterface
{
    /**
     * Bind a callable to event name and execute no more than that many times.
     *
     * @param  int $times execute how many times
     * @param  string $eventName event name
     * @param  callable $callable the callable
     * @return bool true on success false on failure
     */
    public function many(int $times, string $eventName, callable $callable, int $priority = 0): bool;

    /**
     * Bind a callable to event name and execute at most one time
     *
     * Alias of `many(1, $eventName, $callable, $priority)`
     *
     * @param  string $eventName event name
     * @param  callable $callable the callable
     * @return bool true on success false on failure
     */
    public function one(string $eventName, callable $callable, int $priority = 0): bool;
}
