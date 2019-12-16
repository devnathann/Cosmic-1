<?php
namespace App\Middleware;

use App\Middleware\AuthMiddleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class LoggedInMiddleware implements IMiddleware
{
    public function handle(Request $request) : void
    {     
        if(!isset(request()->player)) {
            if($request->isAjax()) { 
                echo '{"title":"Oeps..","dialog":"Oeps om deze pagina te bezoeken dien je ingelogd te zijn!","loadpage":"home"}';
                exit;
            } else {
                redirect('/');
            }
        }
      
        return;
    }
}