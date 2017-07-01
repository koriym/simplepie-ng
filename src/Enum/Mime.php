<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Enum;

/**
 * Provides a set of known, allowable media types (née mime types).
 */
class Mime extends AbstractEnum
{
    public const APPLICATION_ATOM_XML  = 'application/atom+xml';
    public const APPLICATION_RSS_XML   = 'application/rss+xml';
    public const APPLICATION_XHTML_XML = 'application/xhtml+xml';
    public const APPLICATION_JSON      = 'application/json';
    public const APPLICATION_XML       = 'application/xml';
    public const TEXT_XML              = 'text/xml';
    public const TEXT_HTML             = 'text/html';
}