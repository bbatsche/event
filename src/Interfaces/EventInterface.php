<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Interfaces;

/**
 * Interface for event objects
 *
 * @since 2.1.0 added
 */
interface EventInterface
{
    /**
     * Get event name
     */
    public function getName(): string;

    /**
     * Get target/context from which event was triggered
     *
     * @return string|object|null
     */
    public function getTarget();

    /**
     * Get parameters passed to the event
     *
     * @return mixed[]
     */
    public function getParams(): array;

    /**
     * Get a single parameter by name
     *
     * @return mixed
     */
    public function getParam(string $name);

    /**
     * Set the event name
     */
    public function setName(string $name): void;

    /**
     * Set the event target
     *
     * @param string|object|null $target
     */
    public function setTarget($target): void;

    /**
     * Set event parameters
     *
     * @param mixed[] $params
     */
    public function setParams(array $params): void;

    /**
     * Indicate whether or not to stop propagating this event
     */
    public function stopPropagation(bool $flag): void;

    /**
     * Has this event indicated event propagation should stop?
     */
    public function isPropagationStopped(): bool;
}
