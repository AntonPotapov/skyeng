<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

/**
 * Class DecoratorManager
 * @package src\Decorator
 */
class DecoratorManager extends DataProvider
{
    /**
     * @var CacheItemPoolInterface
     */
    public $cache;

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @param string                 $host
     * @param string                 $user
     * @param string                 $password
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface        $logger
     */
    public function __construct(string $host, string $user, string $password, CacheItemPoolInterface $cache, LoggerInterface $logger)
    {
        parent::__construct($host, $user, $password);
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @param array $input
     * @return array
     */
    public function getResponse(array $input): array
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = parent::get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            return $result;
        } catch (Exception $e) {
            $this->logger->critical('Error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * @param array $input
     * @return string
     */
    public function getCacheKey(array $input): string
    {
        return json_encode($input);
    }
}
