<?php
namespace App\Controllers\Home;

use Core\Locale;
use Core\View;

class Lost
{
    private $data;

    public function index()
    {
        View::renderTemplate('Home/lost.html', [
            'title' => Locale::get('core/title/lost'),
            'page'  => 'lost',
            'data'  => $this->data
        ]);
        exit;
    }
}