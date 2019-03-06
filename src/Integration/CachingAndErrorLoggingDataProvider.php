<?php
/**
 * Created by IntelliJ IDEA.
 * User: vitaly
 * Date: 2019-03-05
 * Time: 10:25
 */

namespace src\Integration;


use DateTime;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;

/**
 * Class CachingAndErrorLoggingDataProvider
 * @package src\Integration
 */
class CachingAndErrorLoggingDataProvider implements DataProvider
{
    /**
     * @var DataProvider
     */
    private $dataProvider;

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $cacheTtl;

    /**
     * CachingAndErrorLoggingDataProvider constructor.
     * @param DataProvider $dataProvider
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     * @param string $cacheTtl
     */
    public function __construct(
        DataProvider $dataProvider,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger,
        string $cacheTtl
    ) {
        $this->dataProvider = $dataProvider;
        $this->cache = $cache;
        $this->logger = $logger;
        $this->cacheTtl = $cacheTtl;
    }


    /**
     * @inheritdoc
     */
    public function get(array $request): array
    {
        try {
            $cacheKey = $this->getCacheKey($request);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = $this->dataProvider->get($request);

            $cacheItem
                ->set($result)
                ->expiresAt(new DateTime($this->cacheTtl));

            $this->cache->save($cacheItem);

            return $result;
        } catch (\Exception|InvalidArgumentException $e) {
            $this->logger->critical($e->getMessage(), ['exception' => $e]);
        }

        return [];
    }

    /**
     * @param array $request
     * @return string
     */
    private function getCacheKey(array $request): string
    {
        $key = json_encode($request);
        if (false === $key) {
            throw new \RuntimeException(sprintf('Failed to get cache key. Reason: %s', json_last_error_msg()));
        }

        return $key;
    }
}