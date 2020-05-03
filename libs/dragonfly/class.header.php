<?php
class Header
{
    /**
     * Sends a 202 header
     *
     * @return void
     */
    public static function accepted()
    {
        http_response_code(202);
        exit();
    }

    /**
     * Sends a content type header
     *
     * @param string $mime
     * @param string $charset
     * @return void
     */
    public static function contentType(string $mime, string $charset = 'UTF-8')
    {
        header("Content-Type: $mime; charset=$charset");
    }

    /**
     * Creates headers by key and value
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function create(string $key, string $value = null)
    {
        header($key . ':' . $value);
    }

    /**
     * Sends a 201 header
     *
     * @return void
     */
    public static function created()
    {
        http_response_code(201);
        exit();
    }

    /**
     * Sends download headers for anything that is downloadable
     *
     * @return void
     */
    public static function download($params)
    {
        $defaults = [
            'name'     => 'download',
            'size'     => false,
            'mime'     => 'application/force-download',
            'modified' => time()
        ];

        $options = array_merge($defaults, $params);
        header('Pragma: public');
        header('Expires: 0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $options['modified']) . ' GMT');
        header('Content-Disposition: attachment; filename="' . $options['name'] . '"');
        header('Content-Transfer-Encoding: binary');

        Header::contentType($options['mime']);

        if ($options['size']) {
            header('Content-Length: ' . $options['size']);
        }

        header('Connection: close');
    }

    /**
     * Sends a 400 header
     *
     * @return void
     */
    public static function error()
    {
        http_response_code(400);
        exit();
    }

    /**
     * Sends a 403 header
     *
     * @return void
     */
    public static function forbidden()
    {
        http_response_code(403);
        exit();
    }

    /**
     * Sends a 410 header
     *
     * @return void
     */
    public static function gone()
    {
        http_response_code(410);
        exit();
    }

    /**
     * Sends a 404 header
     *
     * @return void
     */
    public static function missing()
    {
        http_response_code(404);
        exit();
    }

    /**
     * Sends a 404 header
     *
     * @return void
     */
    public static function notfound()
    {
        http_response_code(404);
        exit();
    }

    /**
     * Sends a 500 header
     *
     * @return void
     */
    public static function panic()
    {
        http_response_code(500);
        exit();
    }

    /**
     * Sends a redirect header
     *
     * @param string $url
     * @param integer $code
     * @return void
     */
    public static function redirect(string $url, int $code = 302)
    {
        http_response_code(302);
        header('Location: ' . $url);
        exit();
    }

    /**
     * Sends a status header
     *
     * @return void
     */
    public static function status(int $code)
    {
        http_response_code($code);
        exit();
    }

    /**
     * Sends a 200 header
     *
     * @return void
     */
    public static function success()
    {
        http_response_code(200);
        exit();
    }

    /**
     * Shortcut for static::contentType()
     *
     * @return void
     */
    public static function type(string $mime, string $charset = 'UTF-8')
    {
        Header::contentType($mime, $charset);
    }

    /**
     * Sends a 503 header
     *
     * @return void
     */
    public static function unavailable()
    {
        http_response_code(503);
        exit();
    }
}
