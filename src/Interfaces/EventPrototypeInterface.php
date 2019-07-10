<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Interfaces;

/**
 * Dealing with event prototype stuff
 *
 * @since 2.1.1 added
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
