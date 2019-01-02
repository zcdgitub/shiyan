<?php
/**
 * CRedisCache class file
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CRedisCache implements a cache application component based on {@link http://redis.io/ redis}.
 *
 * CRedisCache needs to be configured with {@link hostname}, {@link port} and {@link database} of the server
 * to connect to. By default CRedisCache assumes there is a redis server running on localhost at
 * port 6379 and uses the database number 0.
 *
 * CRedisCache also supports {@link http://redis.io/commands/auth the AUTH command} of redis.
 * When the server needs authentication, you can set the {@link password} property to
 * authenticate with the server after connect.
 *
 * See {@link CCache} manual for common cache operations that are supported by CRedisCache.
 *
 * To use CRedisCache as the cache application component, configure the application as follows,
 * <pre>
 * array(
 *     'components'=>array(
 *         'cache'=>array(
 *             'class'=>'CRedisCache',
 *             'hostname'=>'localhost',
 *             'port'=>6379,
 *             'database'=>0,
 *         ),
 *     ),
 * )
 * </pre>
 *
 * The minimum required redis version is 2.0.0.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package system.caching
 * @since 1.1.14
 */
class RedisCache extends CRedisCache
{

    /**
     * @param string $key a key identifying a value to be cached
     * @return string a key generated from the provided key which ensures the uniqueness across applications
     */
    protected function generateUniqueKey($key)
    {
        //PC::debug($this->keyPrefix.$key,'keyPrefix');
        return $this->hashKey ? $this->keyPrefix . md5(json_encode(['yii\\redis\\Session', $key])) : $this->keyPrefix.$key;
    }

}
