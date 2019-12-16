<?php
namespace Core;

use App\Middleware\AdminAuthMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\CacheMiddleware;
use App\Middleware\LoggedInMiddleware;
use App\Middleware\NotLoggedInMiddleware;
use App\Middleware\ValidateMiddleWare;
use App\Middleware\PermissionMiddleware;

use Core\Handlers\ExceptionHandler;

use Pecee\SimpleRouter\SimpleRouter as Router;

class Routes extends Router
{
    public static function init()
    {
           
        Router::PartialGroup('/', function () {
          
            Router::setDefaultNamespace('\App\Controllers');

            Router::get('/assets/js/web/user_settings.js', 'Home\Index@configuration');

            Router::get('/', 'Home\Index@index')->setName('index.home')->addMiddleware(CacheMiddleware::class);
            Router::get('/home', 'Home\Index@index')->addMiddleware(CacheMiddleware::class);
            Router::get('/lost', 'Home\Lost@index')->setName('lost');
            Router::get('/disconnect', 'Home\Lost@index')->setName('index.home');
            Router::get('/games/ranking', 'Games\Ranking@index');

            Router::get('/forum', 'Community\Forum@index');
            Router::get('/forum/{slug}', 'Community\Forum@category', ['defaultParameterRegex' => '[\w\-]+']);
            Router::get('/forum/thread/{slug}', 'Community\Forum@topic', ['defaultParameterRegex' => '[\w\-]+']);
            Router::get('/forum/thread/{slug}/page/{page}', 'Community\Forum@topic', ['defaultParameterRegex' => '[\w\-]+']);
          
            Router::get('/profile', 'Home\Profile@profile');
            Router::get('/profile/{user}', 'Home\Profile@profile', ['defaultParameterRegex' => '[a-zA-Z0-9\d\-_=\?!@:\.,]+']);
          
            Router::post('/profile/search', 'Home\Profile@search');

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
                Router::get('/test', 'Client\Client@test');

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


                Router::get('/jobs', 'Jobs\Jobs@index');

                Router::get('/api/player/count', 'Client\Client@count');
              
            });

            /**
             *  Add if page must be cached
             */
          
            Router::group(['middleware' => CacheMiddleware::class, 'exceptionHandler' => ExceptionHandler::class], function () {

                Router::get('/articles', 'Community\Articles@index');
                Router::get('/article/{slug}', 'Community\Articles@index', ['defaultParameterRegex' => '[\w\-]+']);

                Router::get('/community/team', 'Community\Team@index');
                Router::get('/community', 'Community\Community@index');
                Router::get('/community/photos', 'Community\Photos@index');
                Router::get('/community/staff', 'Community\Staff@index');

                Router::get('/community/fansites', 'Community\Fansites@index');

                Router::get('/ruilwaarde', 'Community\Value@index');
                Router::get('/ruilwaarde/{value}', 'Community\Value@index', ['defaultParameterRegex' => '[\w\-]+']);

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
        });


        Router::start();
    }
}