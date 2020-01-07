<?php
namespace App\Middleware;

use App\Models\Permission;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class PermissionMiddleware implements IMiddleware
{
    public function handle(Request $request) : void
    {
        if (!in_array(request()->getHeader('http_authorization'), array_column(Permission::get(request()->player->rank), 'permission'))) {
            // add friendly message + logging
            exit;
        }
      
        return;
    }
}