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

use Phossa2\Shared\Shareable\ShareableInterface;

/**
 * SharedManagerInterface
 *
 * Support for shared event manager for different 'scope'. Default scope
 * is global which is ''. Common usage is to use class name or interface
 * name as scope.
 *
 * @package Phossa2\Event
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 changed priority default value
 */
interface SharedManagerInterface extends ShareableInterface, EventManagerInterface
{
    /**
     * Bind a callable to event name in scope $scope
     *
     * ```php
     * eventDispatcher::onEvent(
     *     'Phossa2\\Mvc\\MvcInterface',
     *     'mvc.onRoute',
     *     function ($evt) {
     *         // ...
     *     }
     * );
     * ```
     *
     * @param  string|string[] $scope
     * @return bool true on success false on failure
     */
    public static function onEvent($scope, string $eventName, callable $callable, int $priority = 0): bool;

    /**
     * Unbind a callable from a specific eventName in $scope
     *
     * @param  string|string[] $scope
     * @return bool true on success false on failure
     */
    public static function offEvent($scope, string $eventName = '', callable $callable = null): bool;

    /**
     * Bind a callable to event name in the global scope
     *
     * Alias of `::onEvent('', $eventName, $callable, $priority)`
     *
     * @return bool true on success false on failure
     */
    public static function onGlobalEvent(string $eventName, callable $callable, int $priority = 0): bool;

    /**
     * Unbind a callable from a specific eventName in the global scope
     *
     * Alias of `::offEvent('', $eventName, $callable)`
     *
     * @return bool true on success false on failure
     */
    public static function offGlobalEvent(string $eventName = '', callable $callable = null): bool;
}
