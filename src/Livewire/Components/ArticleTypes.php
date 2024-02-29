<?php

namespace Jecharlt\LivewireBlogCMS\Livewire\Components;

use Jecharlt\LivewireBlogCMS\Livewire\Pages\AdminDashboard;
use Jecharlt\LivewireBlogCMS\Models\BlogArticleType;
use Exception;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ArticleTypes extends Component
{
    public $show;
    public $type_name;
    public $types;
    public $type_id;
    public $types_frontend;
    public function render()
    {
        return view('livewire-blog-cms::livewire.components.article-types');
    }

    public function mount() {
        $this->types = BlogArticleType::all();
        $this->types_frontend = $this->types->toArray();
    }

    public function send_toast($message, $title, $type) {
        $this->dispatch('trigger_toast', message: $message, title: $title, type: $type)->to(AdminDashboard::class);
    }

    public function saveChanges($type_id) {
        if (!$type_id) {
            $type = new BlogArticleType;
        }
        else {
            $type = BlogArticleType::where('id', $type_id)->first();
        }

        $validator = Validator::make(
            [
                'name' => $this->type_name,
            ],
            [
                'name' => ['required', 'unique:blog_article_types,name,' . $type_id],
            ]
        );

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                $this->send_toast($error, '', 'error');
                return;
            }
        }

        try {
            $save_changes = $type->name ? "saved changes!" : "created article type!";
            $type->name = $this->type_name;
            $type->save();
            $this->type_id = $type->id;
            $this->send_toast("Successfully {$save_changes}", "", "success");
            $this->mount();
        }
        catch (Exception $e) {
            $this->send_toast("Error {$e->getMessage()}", "", "error");
        }
    }

    public function deleteType($type_id) {
        $validator = Validator::make(
            ['type_id' => $type_id],
            ['type_id' => ['required', 'exists:blog_article_types,id']],
            ['exists' => 'The selected article type does not exist.']
        );

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                $this->send_toast($error, '', 'error');
                return;
            }
        }

        try {
            $type = BlogArticleType::where('id', $type_id)->first();
            $type_name = $type->name;
            $type->delete();
            $this->send_toast("Successfully deleted: $type_name", "", "success");
            $this->show = false;
            $this->mount();
        }
        catch (Exception $e) {
            $this->send_toast("An error occurred!", "", "error");
        }
    }
}
