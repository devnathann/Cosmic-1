<?php
namespace App\Controllers\Jobs;

use App\Models\Community;

use Core\Locale;
use Core\View;

class Jobs
{
    public function my()
    {
        $jobs = Community::getMyJobApplication(request()->player->id);
      
        View::renderTemplate('Jobs/my.html', [
            'title' => Locale::get('core/title/jobs/index'),
            'page'  => 'jobs',
            'jobs'  => $jobs
        ]);
    }
  
    public function index()
    {
        $jobs = Community::getJobs();
      
        if(request()->player) {
            foreach($jobs as $job) {
                if(Community::getJobApplication($job->id, request()->player->id)) {
                    $job->apply = true;
                }
            }
        }
        
        View::renderTemplate('Jobs/jobs.html', [
            'title' => Locale::get('core/title/jobs/index'),
            'page'  => 'jobs',
            'jobs'  => $jobs
        ]);
    }
}