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

use InvalidArgumentException;
use Phossa2\Event\Message\Message;
use Phossa2\Event\Interfaces\EventInterface;

/**
 * Event
 *
 * Basic implementation of EventInterface
 *
 * ```php
 * // create event
 * $evt = new Event(
 *     'login.attempt',         // event name
 *     $this,                   // event target
 *     ['username' => 'phossa'] // event parameters
 * );
 *
 * // stop event
 * $evt->stopPropagation();
 * ```
 *
 * @package Phossa2\Event
 * @see     ObjectAbstract
 * @see     EventInterface
 * @version 2.0.0
 * @since   2.0.0 added
 * @since   2.1.0 using psr EventInterface now
 * @since   2.1.1 removed ArrayAccess
 */
class Event implements EventInterface
{
    /**
     * event name
     *
     * @var    string
     */
    protected $name;

    /**
     * event target/context
     *
     * an object OR static class name (string)
     *
     * @var    object|string|null
     */
    protected $target;

    /**
     * event parameters
     *
     * @var    array
     */
    protected $parameters;

    /**
     * stop propagation
     *
     * @var    bool
     */
    protected $stopped = false;

    /**
     * Constructor
     *
     * @param  string $eventName event name
     * @param  string|object|null $target event context, object or classname
     * @param  array $parameters (optional) event parameters
     * @throws InvalidArgumentException if event name is invalid
     * @api
     */
    public function __construct(string $eventName, $target = null, array $parameters = [])
    {
        $this->setName($eventName);
        $this->setTarget($target);
        $this->setParams($parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * {@inheritDoc}
     */
    public function getParams(): array
    {
        return $this->parameters;
    }

    /**
     * {@inheritDoc}
     */
    public function getParam(string $name)
    {
        return $this->parameters[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name): void
    {
        if ($name === '') {
            throw new InvalidArgumentException(
                Message::get(Message::EVT_NAME_INVALID, $name),
                Message::EVT_NAME_INVALID
            );
        }

        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function setTarget($target): void
    {
        $this->target = $target;
    }

    /**
     * {@inheritDoc}
     */
    public function setParams(array $params): void
    {
        $this->parameters = $params;
    }

    /**
     * {@inheritDoc}
     */
    public function stopPropagation(bool $flag): void
    {
        $this->stopped = $flag;
    }

    /**
     * {@inheritDoc}
     */
    public function isPropagationStopped(): bool
    {
        return $this->stopped;
    }
}
