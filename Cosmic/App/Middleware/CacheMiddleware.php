<?php
namespace App\Middleware;

use Core\Cache;
use Core\View;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class CacheMiddleware implements IMiddleware
{
    public static $template = null;

    public function handle(Request $request): void
    {
        $page = self::exists(url()->getOriginalUrl());
      
        if ($page) {
            if (request()->isAjax()) {
                View::getResponse(static::$template['template'], self::$template);
                exit;
            }
            
            View::renderTemplate(static::$template['template'], self::$template);
            exit;
        }
    }

    public static function exists($template)
    {
        $cache = new Cache();

        if ($cache->get($template, $param)) {
            View::$cache = true;
            return self::$template = $param;
        }
    }

    public static function get()
    {
        return self::$template;
    }
  
    public static function set($template, $args, $time)
    {
        $cache = new Cache();
      
        if(!empty($args)) {
            $cachePath = url()->getOriginalUrl();
            if(!$cache->get($cachePath, $param) && $time != null) {
                $args['template'] = $template;
                $cache->setAutoSave(true);
                $cache->set($cachePath, $args, $time);
            } else if(!$cache->get($cachePath, $param)){
                $cache->delete($cachePath);
            }
        }
    }
}