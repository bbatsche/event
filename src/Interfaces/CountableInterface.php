<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Interfaces;

/**
 * Able to execute event handler for limited times
 *
 * @since 2.0.0 added
 * @since 2.1.0 changed default priority to 0
 */
interface CountableInterface
{
    /**
     * Bind a callable to event name and execute no more than that many times.
     *
     * @param int $times Execute how many times
     *
     * @return bool true on success false on failure
     */
    public function many(int $times, string $eventName, callable $callable, int $priority = 0): bool;

    /**
     * Bind a callable to event name and execute at most one time
     *
     * Alias of `many(1, $eventName, $callable, $priority)`
     *
     * @return bool true on success false on failure
     */
    public function one(string $eventName, callable $callable, int $priority = 0): bool;
}
