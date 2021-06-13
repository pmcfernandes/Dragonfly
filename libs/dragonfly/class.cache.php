<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/* ============== How to Use ==========================

require('class.cache.php');
$cache = new SimpleCache();

if($cache->is_cached('some_api')) {
echo = get_cache('some_api');
}
else {
$data = $cache->get_data('some_api', 'http://some.api.url/?id=username');
$cache->set_cache('some_api', $data);
echo = $data;
}

*/

/**
 * Class to implement Cache helper operations
 *
 * @since 1.0
 * @version $Revision$
 * @author Pedro Fernandes
 */
class SimpleCache
{
    var $cache_path = '../../cache/'; // Path to cache folder (with trailing /)    
    var $cache_time = 3600; // Length of time to cache a file in seconds

    /**
     * Get data
     *
     * @param mixed $label
     * @param mixed $url
     * @return object
     */
    public function getData($label, $url)
    {
        $data = $this->getCache($label);

        if ($data) {
            return $data;
        } else {
            $data = $this->do_curl($url);
            $this->setCache($label, $data);
            return $data;
        }
    }

    /**
     * Set cache
     *
     * @param $label
     * @param $data
     */
    public function setCache($label, $data)
    {
        file_put_contents($this->cache_path . $this->safe_filename($label) . '.cache', $data);
    }

    /**
     * Get cache
     *
     * @param mixed $label
     * @return Object
     */
    public function getCache($label)
    {
        $filename = $this->cache_path . $this->safe_filename($label) . '.cache';

        if (file_exists($filename) && (filemtime($filename) + $this->cache_time >= time())) {
            return file_get_contents($filename);
        }

        return false;
    }

    /**
     * Is cached
     *
     * @param mixed $label
     * @return Boolean
     */
    public function isCached($label)
    {
        $filename = $this->cache_path . $this->safe_filename($label) . '.cache';

        if (file_exists($filename) && (filemtime($filename) + $this->cache_time >= time())) {
            return true;
        }

        return false;
    }

    /**
     * Helper function for retrieving data from url
     *
     * @param $url
     * @return mixed
     */
    private function do_curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }

    /**
     * Helper function to validate filename
     *
     * @param $filename
     * @return mixed
     */
    private function safe_filename($filename)
    {
        return preg_replace('/[^0-9a-z\.\_\-]/i', '', strtolower($filename));
    }
}
