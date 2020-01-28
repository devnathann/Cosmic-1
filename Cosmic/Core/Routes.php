<?php
namespace Core;

use App\Config;

use App\Middleware\AdminAuthMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\CacheMiddleware;
use App\Middleware\LoggedInMiddleware;
use App\Middleware\NotLoggedInMiddleware;
use App\Middleware\ValidateMiddleWare;
use App\Middleware\PermissionMiddleware;
use App\Middleware\GuildMiddleware;

use Core\Handlers\ExceptionHandler;

use Pecee\SimpleRouter\SimpleRouter as Router;

class Routes extends Router
{
    public static function init()
    {
        Router::PartialGroup('/', function () {
         
            Router::setDefaultNamespace('\App\Controllers');

            Router::get('/assets/js/web/user_settings.js', 'Home\Index@configuration');
          
            Router::partialGroup('/api/{callback}', function ($callback) {
                Router::get('/', 'Ajax\Api@' . $callback);
            });
            
        
            Router::get('/', 'Home\Index@index')->setName('index.home');
            Router::get('/home', 'Home\Index@index');
            Router::get('/lost', 'Home\Lost@index')->setName('lost');
            Router::get('/disconnect', 'Home\Lost@index')->setName('index.home');
            Router::get('/games/ranking', 'Games\Ranking@index'); 
            Router::get('/jobs', 'Jobs\Jobs@index');
     
            Router::get('/profile', 'Home\Proficle@profile');
            Router::get('/profile/{user}', 'Home\Profile@profile', ['defaultParameterRegex' => '[a-zA-Z0-9\d\-_=\?!@:\.,]+']);
          
            Router::post('/profile/search', 'Home\Profile@search');
          
            Router::get('/assets/js/web/web.locale.js', function () {
                header('Content-Type: application/javascript');
                return 'var Locale = ' . json_encode(Locale::get('website/javascript', true), true) . '';
            });

            /**
             *  When user is not logged in
             */
          
            Router::group(['middleware' => NotLoggedInMiddleware::class, 'exceptionHandler' => ExceptionHandler::class], function () {
              
                Router::get('/registration/{name?}', 'Home\Registration@index');
                Router::get('/facebook', 'Home\Login@facebook');

                Router::get('/password/claim', 'Password\Claim@index');
                Router::get('/password/reset/{token}', 'Password\Reset@index');
              
            });
          
            /**
             *  When user is logged in
             */

            Router::group(['middleware' => LoggedInMiddleware::class, 'exceptionHandler' => ExceptionHandler::class], function () {

                Router::get('/logout', 'Home\Login@logout');
                Router::get('/hotel', 'Client\Client@hotel');
                Router::get('/client', 'Client\Client@client');

                Router::get('/settings', 'Settings\Preferences@index');
                Router::get('/settings/email', 'Settings\Email@index');
                Router::get('/settings/password', 'Settings\Password@index');
                Router::get('/settings/namechange', 'Settings\Namechange@index');
                Router::get('/settings/preferences', 'Settings\Preferences@index');
                Router::get('/settings/verification', 'Settings\Verification@index');

                Router::get('/shop', 'Shop\Shop@index');
                Router::get('/shop/club', 'Shop\Club@index');
                Router::get('/shop/history', 'Shop\History@index');
                Router::get('/shop/{lang}/lang', 'Shop\Shop@index');
                Router::get('/shop/offers/{offerid}', 'Shop\Offers@index');

                Router::get('/help/requests/view', 'Help\Requests@index');
                Router::get('/help/requests/new', 'Help\Ticket@index');
                Router::get('/help/requests/{ticket}/view', 'Help\Requests@ticket', ['defaultParameterRegex' => '[0-9]+']);

                Router::get('/guilds', 'Community\Guilds\Home@index');
                Router::get('/guilds/{slug}', 'Community\Guilds\Category@index', ['defaultParameterRegex' => '[\w\-]+'])->addMiddleware(GuildMiddleware::class);
                Router::get('/guilds/{slug}/page/{page}', 'Community\Guilds\Category@index', ['defaultParameterRegex' => '[\w\-]+'])->addMiddleware(GuildMiddleware::class);
                Router::get('/guilds/{group}/thread/{slug}', 'Community\Guilds\Topic@index', ['defaultParameterRegex' => '[\w\-]+'])->addMiddleware(GuildMiddleware::class);
                Router::get('/guilds/{group}/thread/{slug}/page/{page}', 'Community\Guilds\Topic@index', ['defaultParameterRegex' => '[\w\-]+'])->addMiddleware(GuildMiddleware::class);

                Router::get('/marketplace/my/inventory', 'Community\Value@my');
                Router::get('/marketplace/all/sell', 'Community\Value@sell');
              
                Router::partialGroup('/guilds/post/{controller}/{action}', function ($controller, $action) {
                    Router::post('/', 'Community\Guilds\\' . ucfirst($controller) . '@' . $action)->addMiddleware(GuildMiddleware::class);
                })->addMiddleware(ValidateMiddleWare::class);
             
                Router::get('/jobs/my', 'Jobs\Jobs@my');
                Router::get('/jobs/{id}/apply', 'Jobs\Apply@index');

                Router::get('/api/player/count', 'Client\Client@count');
              
            });

            /**
             *  Add if page must be cached
             */
          
            Router::group(['middleware' => CacheMiddleware::class, 'exceptionHandler' => ExceptionHandler::class], function () {

                Router::get('/articles', 'Community\Articles@index');
                Router::get('/article/{slug}', 'Community\Articles@index', ['defaultParameterRegex' => '[\w\-]+']);

                Router::get('/community/team', 'Community\Team@index');
                Router::get('/community/photos', 'Community\Photos@index');
                Router::get('/community/staff', 'Community\Staff@index');

                Router::get('/community/fansites', 'Community\Fansites@index');

                Router::get('/marketplace', 'Community\Value@index');
                Router::get('/marketplace/{value}', 'Community\Value@index', ['defaultParameterRegex' => '[\w\-]+']);

                Router::get('/help', 'Help\Help@index');
                Router::get('/help/{slug}', 'Help\Help@index', ['defaultParameterRegex' => '[\w\-]+']);
              
            });
          
            /**
             *  Handle post requests
             */

            Router::partialGroup('{directory}/{controller}/{action}', function ($directory, $controller, $action) {
                 if(request()->getMethod() == "post"){
                    Router::post('/', ucfirst($directory) . '\\' . ucfirst($controller) . '@' . $action);
                 }
            })->addMiddleware(ValidateMiddleWare::class);
          
        })->addMiddleware(AuthMiddleware::class);

        /**
         *  Housekeeping routing
         */
      
        Router::group(['prefix' => '/housekeeping', '', 'middleware' => AdminAuthMiddleware::class, 'exceptionHandler' => ExceptionHandler::class], function () {
          
            Router::setDefaultNamespace('\App\Controllers\Admin');
          
            Router::get('/', 'Dashboard@view');
            Router::get('/permissions/get/commands', 'Permissions@getpermissioncommands');

            /**
             *  Controller views
             */
          
            Router::partialGroup('/{controller}/{action}', function ($controller, $action) {
                Router::get('/view/{user}', 'Remote@user', ['defaultParameterRegex' => '[a-zA-Z0-9\d\-_=\?!@:\.,]+']);
                Router::get('/view', ucfirst($controller) . '@' . $action);
                Router::get('/', ucfirst($controller) . '@view');
            });

            /**
             *  API post params
             */
          
            Router::partialGroup('/api/{param1}/{param2}', function ($param1, $param2) {
                Router::post('/', ucfirst($param1) . '@' . $param2)->addMiddleware(PermissionMiddleware::class);
            })->addMiddleware(ValidateMiddleWare::class);

            /**
             *  Search post params
             */
          
            Router::partialGroup('/search/get/{action}', function ($action) {
                Router::get('/', 'Search@' . $action);
            });
          
            Router::get('/assets/admin/js/locale.js', function () {
                header('Content-Type: application/javascript');
                return 'var Locale = ' . json_encode(Locale::get('housekeeping/javascript', true), true) . '';
            });
        });

        Router::start();
    }
}