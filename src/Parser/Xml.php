<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie\Parser;

use Psr\Http\Message\StreamInterface;
use SimplePie\Mixin\RawDocumentTrait;

class Xml extends AbstractParser
{
    use RawDocumentTrait;

    /**
     * Constructs a new instance of this class.
     *
     * @param StreamInterface $stream A PSR-7 `StreamInterface` which is typically returned by the
     *                                `getBody()` method of a `ResponseInterface` class.
     */
    public function __construct(StreamInterface $stream)
    {
        $this->rawDocument = $this->readStream($stream);
    }
}
