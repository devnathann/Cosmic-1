<?php
namespace App\Middleware;

use Core\Locale;
use App\Middleware\AuthMiddleware;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class NotLoggedInMiddleware implements IMiddleware
{
    public function handle(Request $request) : void
    {
        if(!is_null($request->player)) {
            if($request->isAjax()) { 
                response()->json(["title" => "Oeps..", "dialog" => Locale::get('core/dialog/not_logged_in'), "loadpage" => "home"]);
            } else {
                redirect('/');
            }
        }
      
        return;
    }
}