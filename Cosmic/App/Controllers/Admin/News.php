<?php
namespace App\Controllers\Admin;

use App\Config;

use App\Models\Admin;
use App\Models\Log;
use App\Models\Player;

use Core\View;

use Library\Json;
use Library\Upload;
use stdClass;

class News
{
    private $data;
    private $file;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function getnews()
    {
        $news = Admin::getNews();

        if (empty($news)) {
            response()->json(["status" => "error", "message" => "We were unable to find any news items"]);
        }

        foreach ($news as $row) {
            $row->author = Player::getDataById($row->author, 'username')->username ?? 'Management';
            $row->timestamp = date('d-M-Y H:i:s', $row->timestamp);
        }

        Json::filter($news, 'desc', 'id');
    }

    public function getcategorys()
    {
        $category = Admin::getNewsCategories();

        if (empty($category)) {
            response()->json(["status" => "error", "message" => "We were unable to find any news categories"]);
        }

        Json::filter($category, 'desc', 'id');
    }

    public function add()
    {
        $validate = request()->validator->validate([
            'title'         => 'required|max:50',
            'short_story'   => 'required|max:200',
            'full_story'    => 'required'
        ]);

        if(!$validate->isSuccess()) {
            return;
        }

        $id = input()->post('newsId')->value ?? 0;

        $title = input()->post('title')->value;
        $short_story = input()->post('short_story')->value;
        $full_story = input()->post('full_story')->value;
        $category = input()->post('category')->value;
        $images = input()->post('images')->value;
        $imagePath = input()->file('imagesUpload')->filename ?? '';

        if (!empty($imagePath)) {
            if ($this->imageUpload()) {
                $imagePath = Config::site['path'] . '/uploads/' . $this->file->getInfo()->filename;
            }
        }

        if ($id == 0) {
            Admin::addNews($title, $short_story, $full_story, $category, $imagePath, $images, request()->player->id);
            Log::addStaffLog('-1', 'News placed: ' . $title, request()->player->id, 'news');
          
            response()->json(["status" => "success", "message" => "News article is posted!"]);
        }

        Admin::editNews($id, $title, $short_story, $full_story, $category, $imagePath, $images, request()->player->id);
        Log::addStaffLog('-1', 'News edit: ' . $title, request()->player->id, 'news');
      
        response()->json(["status" => "success", "message" => "News edit successfully"]);
    }

    public function edit()
    {
        if (empty(input()->post('post')->value)) {
            response()->json(["status" => "error", "message" => "We were unable to find this news item"]);
        }

        $this->data->news = Admin::getNewsById(input()->post('post')->value);
        $this->data->category = Admin::getNewsCategories();
        echo Json::encode($this->data);
    }

    public function remove()
    {
        $news = Admin::removeNews(input()->post('post')->value);

        if (empty($news)) {
            response()->json(["status" => "error", "message" => "We were unable to find this news item"]);
        }

        Log::addStaffLog('-1', 'News removed: ' . input()->post('post')->value, request()->player->id, 'news');  
        response()->json(["status" => "success", "message" => "News removed succesfully!"]);
    }

    public function addcategory()
    {
        $validate = request()->validator->validate([
            'post'          => 'required|max:50'
        ]);

        if(!$validate->isSuccess()) {
            exit;
        }

        Admin::addNewsCategory(input()->post('post')->value);
        Log::addStaffLog('-1', 'News category added: ' . input()->post('post')->value, request()->player->id, 'news');
      
        response()->json(["status" => "success", "message" => "Category successfully added!"]);
    }

    public function editcategory()
    {
        $category = Admin::getNewsCategoryById(input()->post('post')->value);

        if (empty($category)) {
            response()->json(["status" => "error", "message" => "Category does not exists!"]);
        }

        Log::addStaffLog('-1', 'News category edit: ' . $category->category . ' to ' .input()->post('post')->value, request()->player->id, 'news');
        Admin::editNewsCategory($category->id, input()->post('value')->value);
      
        response()->json(["status" => "success", "message" => "Category edit is successfully!"]);
    }

    public function removecategory()
    {
        $category = Admin::getNewsCategoryById(input()->post('post')->value);
        if (empty($category)) { 
            response()->json(["status" => "error", "message" => "Category does not exists!"]);
        }

        Log::addStaffLog('-1', 'News category removed: ' . $category->category, request()->player->id, 'news');
        Admin::removeNewsCategory($category->id);
      
        response()->json(["status" => "success", "message" => "Category removed succesfully!"]);
    }

    public function view()
    {
        View::renderTemplate('Admin/Management/news.html', [
            'permission' => 'housekeeping_website_news'
        ]);
    }

    protected function imageUpload()
    {
        $this->file = new Upload();

        $this->file->setInput("imagesUpload");
        $this->file->setDestinationDirectory("../public/uploads/");
        $this->file->setUploadFunction("copy");
        $this->file->setAllowMimeType("image");
        $this->file->setAutoFilename();
        $this->file->save();

        if ($this->file->getStatus()) {
            return true;
        }

        return false;
    }
}