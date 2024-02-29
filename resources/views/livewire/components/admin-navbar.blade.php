<div class="admin-navbar-main" x-cloak>
    <div class="navbar-vertical">
        <div class="d-flex justify-content-start flex-column align-items-start w-100 h-100">
            <a href="/blog-admin-dashboard" class="p-4">
                <img class="img-fluid" src="{{trim($this->blog_logo) ? trim($this->blog_logo) : 'https://placehold.co/400x100'}}"
                     alt="logo-dark">
            </a>

            <div class="w-100">
                <h6 class="admin-nav-section-header ps-2">Dashboard</h6>
                <div class="admin-detail-text ps-2 admin-nav-divider">Article Overview</div>
                <div class="d-flex flex-column text-center align-items-center justify-content-center w-100">
                    <button class="admin-nav-button" wire:click="send_switch_page('New Article')">
                        @include('livewire-blog-cms::svg.writing_svg', ['class' => 'navbar-svg'])
                        New Article
                    </button>
                    <button class="admin-nav-button" wire:click="send_switch_page('All Articles')">
                        @include('livewire-blog-cms::svg.nine_dots_svg', ['class' => 'navbar-svg'])
                        All Articles
                    </button>
                </div>
            </div>

            <div class="w-100 mt-4">
                <h6 class="admin-nav-section-header ps-2">Categories & Types</h6>
                <div class="admin-detail-text ps-2 admin-nav-divider">Manage Article Organization</div>
                <div class="d-flex flex-column text-center align-items-center justify-content-center w-100">
                    <button class="admin-nav-button" wire:click="send_switch_page('Categories')">
                        @include('livewire-blog-cms::svg.category_svg', ['class' => 'navbar-svg'])
                        Categories
                    </button>
                    <button class="admin-nav-button" wire:click="send_switch_page('Article Types')">
                        @include('livewire-blog-cms::svg.sub_category_svg', ['class' => 'navbar-svg'])
                        Article Types
                    </button>
                </div>
            </div>

            <div class="w-100 mt-4">
                <h6 class="admin-nav-section-header ps-2">Manage Organization</h6>
                <div class="admin-detail-text ps-2 admin-nav-divider">Edit organizational details</div>
                <div class="d-flex flex-column text-center align-items-center justify-content-center w-100">
                    <button class="admin-nav-button" wire:click="send_switch_page('Blog Users')">
                        @include('livewire-blog-cms::svg.user_svg', ['class' => 'navbar-svg'])
                        Blog Users
                    </button>
                    <button class="admin-nav-button" wire:click="send_switch_page('Blog Details')">
                        @include('livewire-blog-cms::svg.company_svg', ['class' => 'navbar-svg'])
                        Blog Details
                    </button>
                </div>
            </div>

            <div class="mt-auto w-100 pb-4">
                <div class="d-flex align-items-center justify-content-center">
                    <button class="admin-nav-button" wire:click="logout">
                        @include('livewire-blog-cms::svg.log_out_svg', ['class' => 'navbar-svg'])
                        Log Out
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
