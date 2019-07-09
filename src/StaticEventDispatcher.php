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

use BadMethodCallException;
use Phossa2\Event\Message\Message;
use Phossa2\Shared\Base\StaticAbstract;
use Phossa2\Event\Interfaces\EventManagerInterface;

/**
 * StaticEventDispatcher
 *
 * A static wrapper of EventDispatcher
 *
 * ```php
 * // attach
 * StaticEventDispatcher::attach('test', function() { echo 'test'; });
 *
 * // trigger
 * StaticEventDispatcher::trigger('test');
 * ```
 *
 * @package Phossa2\Event
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 updated
 */
class StaticEventDispatcher extends StaticAbstract
{
    /**
     * slave event manager
     *
     * @var    EventManagerInterface[]
     */
    protected static $event_manager = [];

    /**
     * default static scope
     *
     * @var    string
     */
    protected static $static_scope = '__STATIC__';

    /**
     * Provides a static interface for event dispatcher's dynamic methods
     *
     * @param  string $name method name
     * @param  array $arguments arguments
     * @return mixed
     * @throws BadMethodCallException if method not found
     * @internal
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $mgr = static::getEventManager();

        if (method_exists($mgr, $name)) {
            return call_user_func_array([$mgr, $name], $arguments);
        }

        throw new BadMethodCallException(
            Message::get(
                Message::MSG_METHOD_NOTFOUND,
                $name,
                get_called_class()
            ),
            Message::MSG_METHOD_NOTFOUND
        );
    }

    /**
     * Set the inner event manager
     *
     * @param  EventManagerInterface $eventManager
     * @api
     */
    public static function setEventManager(EventManagerInterface $eventManager): void
    {
        self::$event_manager[get_called_class()] = $eventManager;
    }

    /**
     * Get the inner event manager
     *
     * @api
     */
    public static function getEventManager(): EventManagerInterface
    {
        if (!isset(self::$event_manager[get_called_class()])) {
            self::$event_manager[get_called_class()] =
                EventDispatcher::getShareable(static::$static_scope);
        }

        return self::$event_manager[get_called_class()];
    }
}
