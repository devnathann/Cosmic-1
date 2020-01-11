<?php
namespace App\Controllers\Admin;

use App\Config;
use App\Core;

use App\Models\Admin;
use App\Models\Player;
use App\Models\Log;

use Core\Locale;
use Core\View;

use Library\HotelApi;
use Library\Json;

use stdClass;

class Help
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function getticket()
    {
        $request = Admin::getHelpTicketById(input()->post('post')->value);
        if ($request == null) {
            return Json::encode(["status" => "error", "message" => "No ticket found!"]);
        }

        $request->user = Player::getDataById($request->player_id, array('username','look','last_online'));
        $request->last_online = Core::timediff($request->user->last_online);
        $request->timestamp = Core::timediff($request->timestamp);

        $this->data->logs = Admin::getHelpTicketLogs($request->id);
      
        foreach ($this->data->logs as $row) {
            $row->assistant = Player::getDataById($row->player_id, 'username')->username;
            $row->timestamp = Core::timediff($row->timestamp);
        }

        $this->data->reactions = Admin::getHelpTicketReactions($request->id);
      
        foreach ($this->data->reactions as $row) {
            $row->user = Player::getDataById($row->practitioner_id, array('username','look'));
            $row->timestamp = Core::timediff($row->timestamp);
        }

        $this->data->ticket = $request;
        echo Json::encode($this->data);
    }

    public function gethelptickets()
    {
        $tickets = Admin::getHelpTickets();

        foreach ($tickets as $ticket) {
          
            $ticket->subject = Core::filterString($ticket->subject);
            $ticket->timestamp = Core::timediff($ticket->timestamp);
            $practitioner_id = Admin::getLatestChangeStatus($ticket->id);

            if (!empty($practitioner_id)) {
                $ticket->practitioner = Player::getDataById($practitioner_id->player_id, 'username')->username;
            } else {
                $ticket->practitioner = 'None';
            }
        }

        Json::filter($tickets, 'desc', 'id');
    }

    public function updateticket()
    {
        $id = input()->post('post')->value;
        $action = input()->post('action')->value;

        $ticket = Admin::getHelpTicketById($id);

        if ($ticket == null) {
            return;
        }

        Admin::updateTicketStatus($action, $ticket->id);
        Log::addHelpTicketLog(request()->player->id, $ticket->id, 'CHANGE', $action);

        return Json::encode(["status" => "success", "message" => "Ticket status has been updated!"]);
    }

    public function sendmessage()
    {
        $validate = request()->validator->validate([
            'message'      => 'required|min:2'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $message = input()->post('message')->value;
        $ticket = Admin::getHelpTicketById(input()->post('post')->value);

        if ($ticket == null) {
            exit;
        }

        Admin::sendTicketMessage($message, $ticket->id, request()->player->id);
        Log::addHelpTicketLog(request()->player->id, $ticket->id, 'SEND', 'message');

        if(Config::apiEnabled && request()->player->online) {
            HotelApi::execute('alertuser', array('user_id' => $ticket->player_id, 'message' => 'Er is gereageerd op je helpdesk ticket!'));
        }

        return Json::encode(["status" => "success", "message" => "Succesfully send a message to user"]);
    }

    public function view()
    {
        View::renderTemplate('Admin/Management/help.html', ['permission' => 'housekeeping_website_helptool']);
    }
}