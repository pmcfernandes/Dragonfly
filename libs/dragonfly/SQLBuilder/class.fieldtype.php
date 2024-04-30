<?php
namespace Impedro\Dragonfly\SQLBuilder;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

class FieldType
{
    const Numeric = 0;
    const Text = 1;
    const Date = 2;
    const Boolean = 3;
    const Currency = 4;
    const Empty_ = 5;
    const Image = 6;
    const Memo = 7;
    const Decimal4 = 8;
    const Decimal18 = 9;
}
