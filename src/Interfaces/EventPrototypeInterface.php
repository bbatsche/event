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
 * EventPrototypeInterface
 *
 * Dealing with event prototype stuff
 *
 * @package Phossa2\Event
 * @version 2.1.1
 * @since   2.1.1 added
 */
interface EventPrototypeInterface
{
    /**
     * Setup event prototype
     *
     * ```php
     * $this->setEventPrototype(
     *     new MyEvent('prototype')
     * );
     * ```
     */
    public function setEventPrototype(EventInterface $eventPrototype = null): self;
}
