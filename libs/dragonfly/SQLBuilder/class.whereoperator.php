<?php
namespace Impedro\Dragonfly\SQLBuilder;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use Impedro\Dragonfly\Utils\Str;


class WhereOperator
{
    const Equal = 0;
    const IsNull = 1;
    const IsNotNull = 2;
    const Like = 3;
    const In = 4;
    const NotLike = 5;
    const NotEqual = 6;
    const Greater = 7;
    const Less = 8;
    const Greater_OR_Equal = 9;
    const Less_OR_Equal = 10;
    const NotIn = 11;
    const Contains = 12;
    const BeginsWith = 13;
    const EndWith = 14;
    const NotContains = 15;
    const NotBeginsWith = 16;
    const NotEndsWith = 17;
}
