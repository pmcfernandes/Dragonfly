<?php
/*
 Example 1:
 event::bind('blog.post.create', function($args = array())
 {
    mail('myself@me.com', 'Blog Post Published', $args['name'] . ' has been published');
});

 Example 2:
 event::trigger('blog.post.create', $postInfo);
*/
/**
 * Class Event
 *
 * <code>
 * Event::bind('blog.post.create', function($args = array()) {
 *  mail('myself@me.com', 'Blog Post Published', $args['name'] . ' has been published');
 * }
 *
 * Or:
 * Event::trigger('blog.post.create', $postInfo);
 * </code>
 */
class Event
{
    public static $events = array();

    /**
     * Create a trigger for custom user function
     *
     * @param $event
     * @param array $args
     */
    public static function trigger($event, $args = array())
    {
        if (!isset(self::$events[$event])) {
            return;
        }

        foreach (self::$events[$event] as $func) {
            call_user_func($func, $args);
        }
    }

    /**
     * Create a bind for event
     *
     * @param $event
     * @param callable $func
     */
    public static function bind($event, Closure $func)
    {
        self::$events[$event][] = $func;
    }
}