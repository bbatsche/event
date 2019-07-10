<?php
/**
 * Phossa Project
 *
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Traits;

use Phossa2\Event\Interfaces\EventManagerInterface;
use Phossa2\Event\Interfaces\ListenerAwareInterface;
use Phossa2\Event\Interfaces\ListenerInterface;

/**
 * ListenerAwareTrait
 *
 * Implementation of ListenerAwareInterface with scope (shared manager)
 * support.
 *
 * @see     ListenerAwareInterface
 * @see     EventManagerInterface
 *
 * @since   2.0.0 added
 * @since   2.1.0 updated
 * @since   2.1.4 added hasPriority()
 */
trait ListenerAwareTrait
{
    public function attachListener(ListenerInterface $listener): bool
    {
        // get the standardized handlers of the $listener
        $events = $this->listenerEvents($listener);

        // add to manager's event pool
        foreach ($events as $handler) {
            /** @var EventManagerInterface */
            $em = $this;

            if (isset($handler[3])) { // found scope
                $em = static::getShareable($handler[3]);
            }

            $em->attach($handler[0], $handler[1], $handler[2]);
        }

        return true;
    }

    public function detachListener(ListenerInterface $listener, string $eventName = ''): bool
    {
        // get the standardized handlers of the $listener
        $events = $this->listenerEvents($listener);

        // try find the match
        foreach ($events as $handler) {
            if ($eventName === '' || $eventName === $handler[0]) {
                $this->offListenerEvent($handler);
            }
        }

        return true;
    }

    /**
     * standardize events definition
     *
     * @return array<array<string|int|callable>>
     */
    protected function listenerEvents(ListenerInterface $listener): array
    {
        $result = [];

        foreach ($listener->eventsListening() as $eventName => $data) {
            $newData = $this->expandToHandler($data);

            foreach ($newData as $handler) {
                $result[] = $this->expandWithPriority($listener, $eventName, $handler);
            }
        }

        return $result;
    }

    /**
     * standardize to array of 'method1' or ['method1', 20]
     *
     * @param string|callable|array<string|int> $data
     *
     * @return array<string|int|callable>
     */
    protected function expandToHandler($data): array
    {
        if (is_callable($data)) {
            $result = [$data];
        } elseif (is_string($data)) {
            $result = [$data];
        } elseif ($this->hasPriority($data)) {
            $result = [$data];
        } else {
            $result = $data;
        }

        return (array) $result;
    }

    /**
     * standardize one 'method1' or ['method1', 20, $scope]
     * to [eventName, callable, priority, $scopeIfAny]
     *
     * @param string|callable|array<string|int> $data
     *
     * @return array<string|int|callable>
     */
    protected function expandWithPriority(ListenerInterface $listener, string $eventName, $data): array
    {
        if (is_array($data) && is_int($data[1])) {
            $callable = $this->expandCallable($listener, $data[0]);
            $priority = $data[1];
            $scope = $data[2] ?? null;
        } else {
            $callable = $this->expandCallable($listener, $data);
            $priority = 0; // default
            $scope = null;
        }

        return [$eventName, $callable, $priority, $scope];
    }

    /**
     * standardize 'method' or callable to callable
     *
     * @param string|callable $callable
     */
    protected function expandCallable(ListenerInterface $listener, $callable): callable
    {
        return is_callable($callable) ? $callable : [$listener, $callable];
    }

    /**
     * off listener event [$eventName, $handler, $priority, $scope]
     *
     * @param array<string|int|callable> $data
     */
    protected function offListenerEvent(array $data): void
    {
        /** @var EventManagerInterface */
        $em = $this;

        if (isset($data[3])) { // scope found
            $em = static::getShareable($data[3]);
        }

        $em->detach($data[0], $data[1]);
    }

    /**
     * the second value is the priority value
     *
     * @param array<string|int|callable> $data
     */
    protected function hasPriority(array $data): bool
    {
        return isset($data[1]) && is_int($data[1]);
    }
}
