<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace support\view;

use think\Template;
use Webman\View;

/**
 * Class Blade
 * @package support\view
 */
class ThinkPHP implements View
{
    /**
     * @var array
     */
    protected static $_vars = [];

    /**
     * @param $name
     * @param null $value
     */
    public static function assign($name, $value = null)
    {
        static::$_vars += \is_array($name) ? $name : [$name => $value];
    }

    /**
     * @param $template
     * @param $vars
     * @param string $app
     * @return mixed
     */
    public static function render($template, $vars, $app = null)
    {
        static $views = [];
        $app = $app == null ? request()->app : $app;
        if (!isset($views[$app])) {
            $view_path = $app === '' ? app_path(). '/view/' : app_path(). "/$app/view/";
            $views[$app] = new Template([
                'view_path'   => $view_path,
                'cache_path'  => runtime_path() . '/views/',
                'view_suffix' => config('view.view_suffix', 'html')
            ]);
        }
        \ob_start();
        $vars += static::$_vars;
        $views[$app]->fetch($template, $vars);
        $content = \ob_get_clean();
        static::$_vars = [];
        return $content;
    }
}