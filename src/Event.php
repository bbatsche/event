<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event;

use InvalidArgumentException;
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
 * @see   ObjectAbstract
 * @see   EventInterface
 *
 * @since 2.0.0 added
 * @since 2.1.0 using psr EventInterface now
 * @since 2.1.1 removed ArrayAccess
 */
class Event implements EventInterface
{
    /**
     * Event name
     *
     * @var string
     */
    protected $name;

    /**
     * Event target or context
     *
     * An object OR static class name (string)
     *
     * @var object|string|null
     */
    protected $target;

    /**
     * Event parameters
     *
     * @var mixed[]
     */
    protected $parameters;

    /**
     * Stop propagation
     *
     * @var bool
     */
    protected $stopped = false;

    /**
     * @param string|object|null $target     Event context, object or classname
     * @param mixed[]            $parameters
     *
     * @throws InvalidArgumentException if event name is invalid.
     */
    public function __construct(string $eventName, $target = null, array $parameters = [])
    {
        $this->setName($eventName);
        $this->setTarget($target);
        $this->setParams($parameters);
    }

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
     *
     * @throws InvalidArgumentException if name is empty.
     */
    public function setName(string $name): void
    {
        if ($name === '') {
            throw new InvalidArgumentException(sprintf('Event name "%s" is not valid', $name));
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

    public function stopPropagation(bool $flag): void
    {
        $this->stopped = $flag;
    }

    public function isPropagationStopped(): bool
    {
        return $this->stopped;
    }
}
