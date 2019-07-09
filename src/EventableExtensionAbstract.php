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

use Phossa2\Shared\Extension\ExtensionAbstract;
use Phossa2\Event\Interfaces\ListenerInterface;

/**
 * EventableExtensionAbstract
 *
 * Extension with support for handling server's events.
 *
 * @property ListenerInterface $server
 *
 * @package Phossa2\Event
 * @see     ExtensionAbstract
 * @version 2.1.5
 * @since   2.1.5 added
 */
abstract class EventableExtensionAbstract extends ExtensionAbstract
{
    /**
     * Constructor
     *
     * @param  array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->setProperties($properties);
    }

    /**
     * register handlers in the extension with $this->server's evt manager
     *
     * {@inheritDoc}
     */
    protected function bootExtension(): void
    {
        foreach ($this->extensionHandles() as $evt) {
            $this->server->registerEvent($evt['event'], $evt['handler']);
        }
    }

    /**
     * Return event handlers of this extension handling
     *
     * ```php
     * protected function extensionHandles()
     * {
     *     return [
     *         ['event' => 'cache.*', 'handler' => ['byPassCache', 100]],
     *     ];
     * }
     * ```
     */
    abstract protected function extensionHandles(): array;
}
