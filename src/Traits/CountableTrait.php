<?php
/**
 * Phossa Project
 *
 * @category  Library
 * @package   Phossa2\Event
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Traits;

use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\CountableInterface;

/**
 * CountableTrait
 *
 * Implmentation of CountableInterface
 *
 * @package Phossa2\Event
 * @see     CountableInterface
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 updated
 */
trait CountableTrait
{
    /**
     * callable mapping
     *
     * @var    callable[];
     */
    protected $callable_map = [];

    /**
     * {@inheritDoc}
     */
    public function many(int $times, string $eventName, callable $callable, int $priority = 0): bool
    {
        // wrap the callable
        $wrapper = function (EventInterface $event) use ($callable, $times) {
            static $cnt = 0;
            if ($cnt++ < $times) {
                call_user_func($callable, $event);
            }
        };

        // mapping callable
        $oid = $this->hashCallable($callable);
        $this->callable_map[$eventName][$oid] = $wrapper;

        // bind wrapper instead of the $callable
        $this->attach($eventName, $wrapper, $priority);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function one(string $eventName, callable $callable, int $priority = 0): bool
    {
        return $this->many(1, $eventName, $callable, $priority);
    }

    /**
     * Override `detach()` in EventManager
     *
     * Added support for countable callable
     *
     * {@inheritDoc}
     */
    public function detach(string $event, callable $callback = null): bool
    {
        if (null !== $callback) {
            $oid = $this->hashCallable($callback);
            if (isset($this->callable_map[$event][$oid])) {
                $callback = $this->callable_map[$event][$oid];
                unset($this->callable_map[$event][$oid]);
            }
        } else {
            unset($this->callable_map[$event]);
        }

        return parent::detach($event, $callback);
    }

    /**
     * Returns a unique id for $callable with $eventName
     */
    protected function hashCallable(callable $callable): string
    {
        return spl_object_hash((object) $callable);
    }

    // from EventManagerInterface
    abstract public function attach(string $event, callable $callback, int $priority = 0): bool;
}
