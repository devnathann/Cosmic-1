<?php
namespace App\Controllers\Help;

use Core\Locale;
use Core\View;

use Library\Json;

use stdClass;

class Help
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function helpBySlug($slug)
    {
        $slug_id = explode('-', $slug);
        $article = \App\Models\Help::getById($slug_id[0]);
        if ($article == null) {
            (redirect('/help'));
        }

        $this->data->help = $article;
    }

    public function helpAction()
    {
        $category = \App\Models\Help::getCategories();
        if($category == null) {
            redirect('/');
        }

        foreach ($category as $row) {
            $row->faq = \App\Models\Help::getByCategory($row->id);
        }
        
        $this->data->categories = $category;
    }

    public function index($slug = null)
    {
        if($slug == null) {
            $this->helpAction();
        } else {
            $this->helpBySlug($slug);
        }

        View::renderTemplate('Help/help.html', [
            'title'     => Locale::get('core/title/help/index'),
            'page'      => 'help',
            'data'      => $this->data
        ]);
    }
}