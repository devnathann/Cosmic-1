<?php
namespace App\Controllers\Jobs;

use Core\Locale;
use Core\View;

class Jobs
{
    public function index()
    {
        View::renderTemplate('Jobs/jobs.html', [
            'title' => Locale::get('core/title/jobs/index'),
            'page'  => 'jobs'
        ]);
    }
}