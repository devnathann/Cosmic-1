<?php
namespace App\Controllers\Admin;

use App\Models\Admin;
use App\Models\Community;
use App\Models\Player;

use Core\View;
use Library\Json;

class Vacancies
{
    public function delete() 
    {
        $validate = request()->validator->validate([
            'id'  => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }
      
        $jobid = input()->post('id')->value;
        
        $job = Community::getJob($jobid);
      
        if(!empty($job)) {
            Admin::deleteJob($jobid);
            echo '{"status":"success","message":"Vacancies is has been deleted!"}';
            exit;
        }
    }
  
    public function editadd() 
    {
        $validate = request()->validator->validate([
            'job_title'           => 'required',
            'small_description'   => 'required|max:200',
            'full_description'    => 'required'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        $jobid = input()->post('jobid')->value;
        $job_title = input()->post('job_title')->value;
        $small_description = input()->post('small_description')->value;
        $full_description = input()->post('full_description')->value;
      
        $job = Community::getJob($jobid);
      
        if(!empty($job)) {
            Admin::editJob($jobid, $job_title, $small_description, $full_description);
            echo '{"status":"success","message":"Vacancies is edited!"}';
            exit;
        }
      
        Admin::addJob($job_title, $small_description, $full_description);
        echo '{"status":"success","message":"Vacancies is has been added!"}';
    }
  
    public function accept()
    {
        $validate = request()->validator->validate([
            'id'  => 'required|numeric'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }
      
        $jobid = input()->post('id')->value;
        
        $job = Community::getApplicationById($jobid);
      
        if(!empty($job)) {
            Admin::changeJobStatus($jobid);
            echo '{"status":"success","message":"Vacancie changed to closed!"}';
            exit;
        }
    }
  
    public function seejob()
    {
        $this->job = new \stdClass();
        $this->job->job = Community::getApplicationById(input()->post('id')->value);
        Json::raw($this->job);
    } 
  
    public function getjob() 
    {
        $jobs = Community::getJob(input()->post('id')->value);
        Json::raw($jobs);
    }
  
    public function getApplications() 
    {
        $applications = Community::getAllApplications(input()->post('jobid')->value);
        foreach($applications as $application) {
            $application->user_id = Player::getDataById($application->user_id, 'username')->username;
        }
      
        Json::filter($applications, 'desc', 'id');
    }
  
    public function getVacanies() 
    {
        $jobs = Community::getJobs();
        foreach($jobs as $job) {
            $job->applys = count(Community::getJobApplications($job));
        }
      
        Json::filter($jobs, 'desc', 'id');
    }
  
    public function view()
    {
        View::renderTemplate('Admin/Management/vacancies.html', ['permission' => 'housekeeping_permissions']);
    }
}