<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

    /**
     * Class to interoperability with Microsoft related technologies
     *
     * @link http://www.impedro.com
     * @since 1.0
     * @version $Revision$
     * @author Pedro Fernandes
     *
     * Usage
     * <code>
     *   $stack = Interop.getNet ("mscorlib", "System.Collections.Stack");
     *   $stack->Push(".Net");
     *   $stack->Push("Hello ");
     *   echo $stack->Pop() . $stack->Pop();
     * </code>
     */
    final class Interop
    {
        /**
         * Get class instance of .net class inserted in php code
         *
         * @param $asm
         * @param $class
         * @return DOTNET
         */
        public static function getNet($asm, $class)
        {
            return new DOTNET($asm, $class);
        }

        /**
         * Get class instance of COM class inserted in php code
         *
         * @param $namespace
         * @param $class
         * @return COM
         */
        public static function getCOM($namespace, $class)
        {
            return new COM($namespace . '.' . $class);
        }
    }
}
