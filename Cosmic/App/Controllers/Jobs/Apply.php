<?php
namespace App\Controllers\Jobs;

use Core\Locale;
use Core\View;

use App\Models\Community;
use App\Models\Player;

use Library\Json;

class Apply
{
    public function index($id)
    {
        $job = Community::getJob($id);
        if(empty($job)) {
            redirect('/');
        }
      
        View::renderTemplate('Jobs/apply.html', [
            'title' => Locale::get('core/title/jobs/apply'),
            'page'  => 'apply',
            'job'   => $job
        ]);
    }

    public function request()
    {
        $validate = request()->validator->validate([
            'age'               =>   'required|numeric',
            'job_why'           =>   'required',
            'when_monday'       =>   'required',
            'when_tuesday'      =>   'required',
            'when_thursday'     =>   'required',
            'when_wednesday'    =>   'required',
            'when_friday'       =>   'required',
            'when_sunday'       =>   'required',
            'when_saturday'     =>   'required'
            ]);

        if(!$validate->isSuccess()) {
            exit;
        }
      
        $player_id              =   request()->player->id;
        $job_id                 =   input()->post('job_id')->value;
        $firstname              =   input()->post('name')->value;
        $message                =   input()->post('job_why')->value;
        $available_monday       =   input()->post('when_monday')->value;
        $available_tuesday      =   input()->post('when_tuesday')->value;
        $available_wednesday    =   input()->post('when_wednesday')->value;
        $available_thursday     =   input()->post('when_thursday')->value;
        $available_friday       =   input()->post('when_friday')->value;
        $available_saturday     =   input()->post('when_saturday')->value;
        $available_sunday       =   input()->post('when_sunday')->value;
        
      
        $job = Community::getJob($job_id);
        if(empty($job)) {
            response()->json(["status" => "error", "message" => Locale::get('core/notification/something_wrong')]);
        }
        
        Community::addJobApply($job_id, $player_id, \App\Core::filterString($firstname), \App\Core::filterString($message), $available_monday, $available_tuesday, $available_wednesday, $available_thursday, $available_friday,$available_saturday, $available_sunday);
        response()->json(["status" => "success", "message" => Locale::get('website/apply/content_1'), "replacepage" => "jobs/my"]);    
    }
}
