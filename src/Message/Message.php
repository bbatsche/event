<?php
/**
 * Phossa Project
 *
 * @category  Library
 * @package   Phossa2\Event
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Message;

use Phossa2\Shared\Message\Message as BaseMessage;

/**
 * Mesage class for Phossa2\Event
 *
 * @package Phossa2\Event
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 updated
 */
class Message extends BaseMessage
{
    /*
     * Event name "%s" is not valid
     */
    public const EVT_NAME_INVALID = 1606291014;

    /**
     * {@inheritDoc}
     */
    protected static $messages = [
        self::EVT_NAME_INVALID => 'Event name "%s" is not valid',
    ];
}
