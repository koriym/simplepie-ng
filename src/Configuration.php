<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Skyzyx\UtilityPack\Types;

/**
 * A "master container" for configuration. Think of it as a global class that
 * you can access to read global configurations. It is not meant to be
 * sub-classed.
 */
class Configuration
{
    /**
     * A PSR-11 container.
     *
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * Bitwise libxml options to use for parsing XML.
     *
     * @var int
     */
    protected static $libxml;

    /**
     * A PSR-3 logger.
     *
     * @var LoggerInterface
     */
    protected static $logger;

    /**
     * The handler stack which contains registered middleware.
     *
     * @var HandlerStackInterface
     */
    protected static $middleware;

    /**
     * Constructs a new instance of this class.
     *
     * @param ContainerInterface|null $container A PSR-11 container. The default value is null,
     *                                           which will instantiate `SimplePie\Container`.
     */
    public static function setContainer(?ContainerInterface $container = null)
    {
        self::$container = $container ?? new Container();

        // Run validations
        self::validateLogger($container);
        self::validateLibxml($container);
        self::validateMiddlewareStack($container);

        self::getLogger()->info(sprintf('`%s` has completed instantiation.', __CLASS__));
    }

    /**
     * Gets the libxml value to use for parsing XML.
     *
     * @return int
     */
    public static function getLibxml(): int
    {
        return self::$libxml;
    }

    /**
     * Retrieves the PSR-3 logger.
     *
     * @return LoggerInterface
     */
    public static function getLogger(): LoggerInterface
    {
        return self::$logger;
    }

    /**
     * Gets the handler stack which contains registered middleware.
     *
     * @return HandlerStackInterface
     */
    public static function getMiddlewareStack(): HandlerStackInterface
    {
        return self::$middleware;
    }

    /**
     * Validates the user's configuration for the PSR-3 logger.
     *
     * A valid PSR-3 logger set by the user will be utilized. If there is no
     * logger set, the default value will be `NullLogger`. An invalid setting
     * will throw an exception.
     *
     * @param ContainerInterface $container A PSR-11 container.
     *
     * @throws ConfigurationException
     */
    protected static function validateLogger(ContainerInterface $container): void
    {
        if ($container->has('simplepie.logger')) {
            if ($container->get('simplepie.logger') instanceof LoggerInterface) {
                self::$logger = $container->get('simplepie.logger');
            } else {
                throw new ConfigurationException(
                    sprintf(
                        ErrorMessage::LOGGER_NOT_PSR3,
                        Types::getClassOrType($container->get('simplepie.logger'))
                    )
                );
            }
        } else {
            self::$logger = new NullLogger();
        }

        // What are we logging with?
        self::getLogger()->debug(sprintf(
            'Logger configured to use `%s`.',
            Types::getClassOrType(self::getLogger())
        ));
    }

    /**
     * Validates the user's configuration for `LIBXML_*` XML parsing parameters.
     * If no valid values are set, the default values will be applied.
     *
     * @param ContainerInterface $container A PSR-11 container.
     *
     * @throws ConfigurationException
     */
    protected static function validateLibxml(ContainerInterface $container): void
    {
        if ($container->has('simplepie.libxml')) {
            if (is_int($container->get('simplepie.libxml'))) {
                self::$libxml = $container->get('simplepie.libxml');
            } else {
                throw new ConfigurationException(
                    sprintf(
                        ErrorMessage::LIBXML_NOT_INTEGER,
                        Types::getClassOrType($container->get('simplepie.libxml'))
                    )
                );
            }
        } else {
            self::$libxml = 0
                | LIBXML_BIGLINES
                | LIBXML_COMPACT
                | LIBXML_HTML_NODEFDTD
                | LIBXML_HTML_NOIMPLIED // Required, or things crash.
                | LIBXML_NOBLANKS
                // | LIBXML_NOCDATA // Do not merge into text nodes.
                | LIBXML_NOENT
                | LIBXML_NOXMLDECL
                | LIBXML_NSCLEAN
                | LIBXML_PARSEHUGE;
        }

        // What are we logging with?
        self::getLogger()->debug(sprintf(
            'Libxml configuration has a bitwise value of `%s`.%s',
            self::getLibxml(),
            (self::getLibxml() === 4792582)
                ? ' (This is the default configuration.)'
                : ''
        ));
    }

    /**
     * Validates the user's configuration for the middleware handler stack.
     *
     * A valid middleware handler stack by the user will be utilized. If there
     * is no handler stack set, the default value will be `HandlerStack`. An
     * invalid setting will throw an exception.
     *
     * @param ContainerInterface $container A PSR-11 container.
     *
     * @throws ConfigurationException
     */
    protected static function validateMiddlewareStack(ContainerInterface $container): void
    {
        if ($container->has('simplepie.middleware')) {
            if ($container->get('simplepie.middleware') instanceof HandlerStackInterface) {
                self::$middleware = $container->get('simplepie.middleware');
            } else {
                throw new ConfigurationException(
                    sprintf(
                        ErrorMessage::MIDDLEWARE_NOT_HANDLERSTACK,
                        Types::getClassOrType($container->get('simplepie.middleware'))
                    )
                );
            }
        } else {
            self::$middleware = new HandlerStack($this->getLogger());
        }
    }
}