<?php
/**
 * Phossa Project
 *
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event;

use Phossa2\Event\Interfaces\EventCapableInterface;
use Phossa2\Event\Interfaces\ListenerInterface;
use Phossa2\Event\Traits\EventCapableTrait;
use Phossa2\Event\Traits\ListenerTrait;

/**
 * Base class for event capable
 *
 * @since   2.0.0 added
 * @since   2.1.0 modified with new interfaces
 * @since   2.1.3 added ListenerTrait
 */
abstract class EventCapableAbstract implements EventCapableInterface, ListenerInterface
{
    use EventCapableTrait;
    use ListenerTrait;
}
