<?php
/**
 * Copyright (c) 2017–2018 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017–2018 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Configuration;

use Psr\Log\LoggerInterface;

interface SetLoggerInterface
{
    public function setLogger(LoggerInterface $logger);
}
