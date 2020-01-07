<?php
namespace App\Controllers\Admin;

use App\Config;
use App\Core;

use App\Models\Forum as Forums;
use App\Models\Player;
use App\Models\Admin;
use App\Models\Log;

use Core\View;

use Library\Json;
use Library\Upload;

use stdClass;

class Forum
{
    public function deleteForum()
    {
        $validate = request()->validator->validate([
            'post'        => 'required|numeric'
        ]);

        if (!$validate->isSuccess()) {
            exit;
        }
      
        Admin::deleteForum(input()->post('post')->value);
        Log::addStaffLog('-1', 'Forum removed: ' . intval(input()->post('post')->value), 'forum');
        echo '{"status":"success","message":"Forum removed succesfully!"}';
    }
  
    public function deleteCategory()
    {
        $validate = request()->validator->validate([
            'post'        => 'required|numeric'
        ]);

        if (!$validate->isSuccess()) {
            exit;
        }
      
        Admin::deleteCategory(input()->post('post')->value);
        Log::addStaffLog('-1', 'Forum category removed: ' . intval(input()->post('post')->value), 'forum');
        echo '{"status":"success","message":"Category removed succesfully!"}';
    }
  
    public function editaddcat()
    {
        $validate = request()->validator->validate([
            'title'       => 'required|max:50',
            'description' => 'required'
        ]);

        if (!$validate->isSuccess()) {
            echo '{"status":"error","message":"Fill in all fields!"}';
            exit;
        }

        $id = input()->post('catId')->value;
        $title = input()->post('title')->value;
        $description = input()->post('description')->value;
        $min_rank = input()->post('min_rank')->value;
        $position = input()->post('position')->value;
      
        if (!empty($id)) {
            Admin::editCategory($id, $title, $description, $min_rank, $position);
            Log::addStaffLog('-1', 'Category edited: ' . $title, 'forum');
            echo '{"status":"success","message":"Category edited successfully!"}';
            exit;
        }

        if (Admin::createCategory($title, $description, $min_rank, $position)) {
            Log::addStaffLog('-1', 'Create category: ' . $title, 'forum');
            echo '{"status":"success","message":"Category created successfully!"}';
        }
    }
  
    public function editadd()
    {
        $validate = request()->validator->validate([
            'title'       => 'required|max:50',
            'description' => 'required'
        ]);

        if (!$validate->isSuccess()) {
            echo '{"status":"error","message":"Fill in all fields!"}';
            exit;
        }

        $id = input()->post('forumId')->value;
        $title = input()->post('title')->value;
        $description = input()->post('description')->value;
        $category = input()->post('category')->value;
        $min_rank = input()->post('min_rank')->value;
        $position = input()->post('position')->value;
      
        $imagePath = input()->post('imagePath')->value ?? null;
      
        if (!empty(input()->file('imagesUpload')->name)) {
            if ($this->imageUpload()) {
                $imagePath = Config::path . '/uploads/forum/' . $this->file->getInfo()->filename;
            }
        }
      
        if (!empty($id)) {
            Admin::editForum($id, $title, $description, $category, $imagePath, $min_rank, $position, Core::convertSlug($title));
            Log::addStaffLog('-1', 'Forum edited: ' . $title, 'forum');
            echo '{"status":"success","message":"Forum edited successfully!"}';
            exit;
        }

        if (Admin::createForum($title, $description, $category, $imagePath, $min_rank, $position, Core::convertSlug($title))) {
            Log::addStaffLog('-1', 'Create forum: ' . $title, 'forum');
            echo '{"status":"success","message":"Forum created successfully!"}';
        }
    }
  
    public function getCategory() 
    {
        $category = Forums::getCategory();
        Json::filter($category, 'desc', 'id');
    }
  
    public function getCategoryById() 
    {
       $validate = request()->validator->validate([
            'post'        => 'required'
        ]);

        if (!$validate->isSuccess()) {
            exit;
        }
      
        $category = Admin::getCategoryById(input()->post('post')->value) ?? new stdClass();
        $category->ranks = Admin::getRanks(true);
        Json::raw($category);
    }
  
    public function getForumById() 
    {
       $validate = request()->validator->validate([
            'post'        => 'required'
        ]);

        if (!$validate->isSuccess()) {
            exit;
        }
      
        $forum = Forums::getForumById(input()->post('post')->value) ?? new stdClass();
        $forum->categories = Forums::getCategory();
        $forum->ranks = Admin::getRanks(true);
      
        Json::raw($forum);
    }
  
    public function getForums() 
    {
        $forums = Admin::getForums();
      
        foreach ($forums as $row) {
            $row->category = Admin::getCategoryById($row->cat_id);
        }

        Json::filter($forums, 'desc', 'id');
    }
  
    public function view()
    {
        View::renderTemplate('Admin/Management/forum.html', [
            'permission' => 'housekeeping_website_forum'
        ]);
    }
  
    protected function imageUpload()
    {
        $this->file = new Upload();

        $this->file->setInput("imagesUpload");
        $this->file->setDestinationDirectory("../public/uploads/forum");
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