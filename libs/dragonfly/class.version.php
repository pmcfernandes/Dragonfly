<?php
namespace Impedro\Dragonfly;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Class to compare versions
 *
 * @since 1.0
 * @version 1.0.1
 * @author Pedro Fernandes
 */
final class Version
{
    const VERSION = '1.1.0';

    /**
     * Compare versions with current version
     */
    public static function compare($version)
    {
        $currentVersion = str_replace(' ', '', strtolower(self::VERSION));
        $version = str_replace(' ', '', $version);
        return version_compare($version, $currentVersion);
    }
}
