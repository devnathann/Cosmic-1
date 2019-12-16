<?php
namespace App\Controllers\Help;

use Core\Locale;
use Core\View;

class Ticket
{
    public function index()
    {
        View::renderTemplate('Help/new.html', [
            'title'     => Locale::get('core/title/help/new'),
            'page'      => 'help_new'
        ]);
    }
}