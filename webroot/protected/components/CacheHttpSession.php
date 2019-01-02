<?php
/**
 * CCacheHttpSession class
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * CCacheHttpSession implements a session component using cache as storage medium.
 *
 * The cache being used can be any cache application component implementing {@link ICache} interface.
 * The ID of the cache application component is specified via {@link cacheID}, which defaults to 'cache'.
 *
 * Beware, by definition cache storage are volatile, which means the data stored on them
 * may be swapped out and get lost. Therefore, you must make sure the cache used by this component
 * is NOT volatile. If you want to use {@link CDbCache} as storage medium, use {@link CDbHttpSession}
 * is a better choice.
 *
 * @property boolean $useCustomStorage Whether to use custom storage.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web
 * @since 1.0
 */
class CacheHttpSession extends CCacheHttpSession
{

    /**
     * Generates a unique key used for storing session data in cache.
     * @param string $id session variable name
     * @return string a safe cache key associated with the session variable name
     */
    protected function calculateKey($id)
    {
        return $id;
    }
    /**
     * Session write handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @param string $data session data
     * @return boolean whether session write is successful
     */
    public function writeSession($id,$data)
    {
        return $this->_cache->set($this->calculateKey($id),$data,$this->getTimeout());
    }
}
