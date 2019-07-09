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

use Phossa2\Shared\Extension\ExtensionInterface;
use Phossa2\Shared\Extension\ExtensionAwareInterface;

/**
 * EventableExtensionAwareInterface
 *
 * @package Phossa2\Event
 * @version 2.1.6
 * @since   2.1.6 added
 */
interface EventableExtensionAwareInterface extends ExtensionAwareInterface, EventCapableInterface, ListenerInterface
{
    /**
     * Register a callable or ExtensionInterface with event
     *
     * @param  callable|ExtensionInterface $extension
     */
    public function addExt($extension, string $eventName = '*', int $priority = 0): self;
}
