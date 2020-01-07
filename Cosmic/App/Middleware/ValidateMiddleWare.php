<?php
namespace App\Middleware;

use Library\Validate\Validator;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class ValidateMiddleWare implements IMiddleware
{
    public function handle(Request $request) : void
    {
        $request->validator = new Validator($request->getInputHandler()->all());
    }
}