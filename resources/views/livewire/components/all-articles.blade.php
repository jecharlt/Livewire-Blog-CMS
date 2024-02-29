<div class="container p-4"
x-data="{
         article_search: '',
         selected_category: 'none',
         selected_type: 'none',
         articles: @entangle('articles_frontend'),
         get filteredArticles() {
             return this.articles.filter(article => {
                 let matches_search = article.title.toLowerCase().includes(this.article_search.toLowerCase());
                 let matches_category = this.selected_category === 'none' || article.category.id === this.selected_category;
                 let matches_type = this.selected_type === 'none' || article.article_type.id === this.selected_type;
                 return matches_search && matches_category && matches_type;
             })
         },
         dateTimeFormat(date_time) {
            const date = new Date(date_time);
            const options = {
                year: 'numeric', month: '2-digit', day: '2-digit',
                hour: '2-digit', minute: '2-digit', second: '2-digit',
                hour12: false
            };
            return new Intl.DateTimeFormat('en-US', options).format(date).replace(/(\d{2})\/(\d{2})\/(\d{4}),/, '$3-$1-$2')
        }
     }" wire:ignore>
    <div class="row d-flex align-items-start">
        <div class="col-3">
            <h5>All Articles</h5>
        </div>
        <div class="col-9 text-end d-flex align-items-center justify-content-end">
            <div class="d-flex flex-column align-items-center">
                <label class="admin-dashboard-input-type pb-2">
                    Sort By Category
                </label>
                <select class="admin-dashboard-sort" x-model="selected_category">
                    <option value="none" selected>None</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex flex-column ps-4 align-items-center">
                <label class="admin-dashboard-input-type pb-2">
                    Sort By Article Type
                </label>
                <select class="admin-dashboard-sort" x-model="selected_type">
                    <option value="none" selected>None</option>
                    @foreach($article_types as $article_type)
                        <option value="{{ $article_type->id }}">{{ $article_type->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="article-result-container mx-auto">
        <div class="row my-4">
            <div class="col-12">
                <label class="admin-dashboard-input-title">
                    Search
                </label>
                <div class="search-wrapper">
                    @include('livewire-blog-cms::svg.search_svg', ['class' => 'view-search-article-svg'])
                    <input type="text" class="admin-dashboard-search" x-model="article_search"/>
                </div>
                <div class="d-flex align-content-center justify-content-between w-100">
                    <span class="admin-dashboard-input-sub-text">
                        Search by Article Name
                    </span>
                    <span class="admin-dashboard-input-sub-text"
                    x-text="`${filteredArticles.length} Result${filteredArticles.length > 1 ? 's' : ''}`">{x}
                        Results</span>
                </div>
            </div>
        </div>

        <template x-if="filteredArticles.length > 0">
            <template x-for="article in filteredArticles" :key="article.id">
                <template x-if="article.title.toLowerCase().includes(article_search.toLowerCase())">
                    <div class="article-result d-flex my-3">
                        <div class="col-2">
                            <img :src="article.featured_image || 'https://placehold.co/300x100'" class="img-fluid latest-img"/>
                        </div>
                        <div class="col-8 py-3 latest-post-text-container">
                            <div class="ps-3">
                                <div class="inline article-result-text">
                                    <div x-text="`Title: ${article.title}`"></div>
                                    <div x-text="`Category: ${article.description}`"></div>
                                    <div x-text="`Article Type: ${article.article_type.name}`"></div>
                                </div>
                                <span class="sub-article-result-text">
                                    <span x-text="`Published: ${article.is_published ? 'True' : 'False'}`"></span>
                                    <span x-text="`First Published: ${article.originally_published_at ? article.originally_published_at : 'Never Published'}
                                    | Last Edited: ${dateTimeFormat(article.updated_at)}`" style="display: block;"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="d-flex flex-column w-100 h-100">
                                <div class="col-6 w-100 h-50">
                                    <button class="admin-view-post-button d-flex align-items-center
                                    justify-content-center category-size-down"
                                            @click.prevent="$wire.articleRedirect(article.slug)">
                                        @include('livewire-blog-cms::svg.view_post_svg', ['class' => 'view-edit-article-svg'])
                                        View Article
                                    </button>
                                </div>
                                <div class="col-6 w-100 h-50">
                                    <button class="admin-edit-post-button d-flex align-items-center justify-content-center
                                    category-size-down"
                                            @click.prevent="$wire.editArticle(article.id)">
                                        @include('livewire-blog-cms::svg.edit_post_svg', ['class' => 'view-edit-article-svg'])
                                        Edit Article
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </template>

        <template x-if="filteredArticles.length === 0">
            <div class="text-center">No articles found!</div>
        </template>
    </div>
</div>
