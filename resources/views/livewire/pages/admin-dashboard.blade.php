<div class="w-100"
     x-data="{ isMobile: window.innerWidth < 992 }"
     @resize.window="isMobile = window.innerWidth < 992"
     x-cloak>
    <div x-show="!isMobile">
        <livewire:blog-admin.components.admin-navbar/>
        <div class="admin-dashboard-main w-100">
            @if($this->current_page == "New Article")
                <livewire:blog-admin.components.new-article/>
            @elseif($this->current_page == "All Articles")
                <livewire:blog-admin.components.all-articles/>
            @elseif($this->current_page == "Categories")
                <livewire:blog-admin.components.categories/>
            @elseif($this->current_page == "Article Types")
                <livewire:blog-admin.components.article-types/>
            @elseif($this->current_page == "Blog Details")
                <livewire:blog-admin.components.blog-details/>
            @elseif($this->current_page == "Blog Users")
                <livewire:blog-admin.components.blog-users/>
            @endif
        </div>
    </div>
    <div x-show="isMobile">
        <livewire:blog-admin.components.mobile-warning/>
    </div>
</div>


