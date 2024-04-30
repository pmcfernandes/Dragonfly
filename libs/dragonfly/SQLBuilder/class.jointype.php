<?php
namespace Impedro\Dragonfly\SQLBuilder;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

class JoinType
{
    const Inner = 0;
    const Left = 1;
    const Right = 2;
    const Full = 3;
}
