<?php
namespace App\Controllers\Admin;

use App\Models\Admin;
use App\Models\Log;
use App\Models\Player;

use Core\View;

use Library\Json;
use stdClass;

class Faq
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function add()
    {
        $validate = request()->validator->validate([
            'title'      => 'required|max:50',
            'story'      => 'required',
            'category'   => 'required|numeric'
        ]);

        if (!$validate->isSuccess()) {
            return;
        }

        $id = input()->post('faqId')->value;

        $title = input()->post('title')->value;
        $story = input()->post('story')->value;
        $category = input()->post('category')->value;

        if (empty($id)) {
            Admin::addFAQ($title, $story, $category, request()->player->id);
            Log::addStaffLog('-1', 'FAQ added: ' . $title, request()->player->id, 'faq');
            return Json::encode(["status" => "success", "message" => "FAQ added successfully!"]);
        }

        $faq = Admin::getFAQById($id);
        if ($faq == null) {
            exit;
        }

        if (Admin::editFAQ($id, $title, $story, $category, request()->player->id)) {
            Log::addStaffLog('-1', 'FAQ edit: ' . $id, request()->player->id, 'faq');
            return Json::encode(["status" => "success", "message" => "FAQ editted successfully!"]);
        }
    }

    public function edit()
    {
        if (!empty(input()->post('post')->value)) {
            $this->data->faq = Admin::getFAQById(input()->post('post')->value);
        }

        $this->data->category = Admin::getFAQCategory();

        echo Json::encode($this->data);
    }

    public function remove()
    {
        $faq = Admin::removeFAQ(input()->post('post')->value);
        Log::addStaffLog('-1', 'FAQ removed: ' . intval(input()->post('post')->value), request()->player->id, 'faq');

        return Json::encode(["status" => "success", "message" => "FAQ removed successfully!"]);
    }

    public function addcategory()
    {
        $validate = request()->validator->validate([
            'post'      => 'required|max:100'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $category = input()->post('post')->value;

        Admin::addFAQCategory($category);
        Log::addStaffLog('-1', 'FAQ Category added: ' . $category, request()->player->id, 'faq');

        return Json::encode(["status" => "success", "message" => "Category successfully added!"]);
}

    public function editcategory()
    {
        $category = Admin::getFAQCategoryById(input()->post('category')->value);

        Log::addStaffLog('-1', 'FAQ Category edit: ' . $category->category . ' to ' . input()->post('value')->value, request()->player->id, 'faq');
        Admin::editFAQCategory(input()->post('category')->value, input()->post('value')->value);

        return Json::encode(["status" => "success", "message" => "Category modified succesfully!"]);
    }

    public function removecategory()
    {
        $category = Admin::getFAQCategoryById(input()->post('post')->value);

        Log::addStaffLog('-1', 'FAQ Category removed: ' . $category->category, request()->player->id,  'faq');
        Admin::removeFAQCategory($category->id);

        return Json::encode(["status" => "success", "message" => "Category removed succesfully!"]);
    }

    public function getfaqs()
    {
        $faq = Admin::getFAQ();

        foreach ($faq as $row) {
            $row->author = Player::getDataById($row->author, 'username')->username;
            $row->timestamp = date('d-M-Y H:i:s', $row->timestamp);
        }

        Json::filter($faq, 'desc', 'id');
    }

    public function getcategorys()
    {
        $category = Admin::getFAQCategory();
        Json::filter($category, 'desc', 'id');
    }

    public function view()
    {
        View::renderTemplate('Admin/Management/faq.html', [
            'permission' => 'housekeeping_website_faq'
        ]);
    }
}