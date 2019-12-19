<?php
namespace App\Controllers\Jobs;

use Core\Locale;
use Core\View;

class Apply
{
    public function index()
    {
        View::renderTemplate('Jobs/apply.html', [
            'title' => Locale::get('core/title/jobs/apply'),
            'page'  => 'apply'
        ]);
    }
}