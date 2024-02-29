<div class="container p-4"
    x-data="{
        type_search: '',
        show: @entangle('show'),
        type_name: @entangle('type_name'),
        types: @entangle('types_frontend'),
        type_id: @entangle('type_id'),
        get filteredTypes() {
            return this.types.filter(type => {
                return type.name.toLowerCase().includes(this.type_search.toLowerCase());
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
        },
        editType(type_id = null) {
            this.type_name = '';
            this.type_id = null;
            if (type_id != null) {
                let temp = this.types.filter(type => {
                    return type.id === type_id;
                })[0];
                this.type_name = temp.name;
                this.type_id = temp.id;
            }
            this.show = true;
        }
    }" x-cloak wire:ignore>
    <div class="row">
        <div class="col-6">
            <h5>Article Types</h5>
        </div>
        <div class="col-6 text-end d-flex align-items-end justify-content-end" x-show.important="!show"
             x-transition @click.prevent="editType()">
            <button class="save-article-button d-flex align-items-center justify-content-center">
                @include('livewire-blog-cms::svg.create_new_svg', ['class' => 'save-publish-article-svg'])
                New Article Type
            </button>
        </div>
    </div>

    <div x-show="show" class="article-result-container mx-auto mt-4" x-transition>
        <div class="w-100 create-category-container p-3">
            <div class="row d-flex align-items-center">
                <div class="col-6 d-flex align-items-center">
                    <h6 class="m-0">New Article Type</h6>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    <button class="delete-article-button d-flex align-items-center justify-content-center"
                            @click.prevent="$wire.deleteType(type_id);"
                            x-show.important="type_id !== null">
                        @include('livewire-blog-cms::svg.delete_category_svg', ['class' => 'delete-category-svg'])
                        <span>Delete Article Type</span>
                    </button>
                    <button class="unpublish-article-button d-flex align-items-center justify-content-center ms-3"
                            @click.prevent="show = false;">
                        @include('livewire-blog-cms::svg.unpublish_document_svg', ['class' => 'unpublish-article-svg'])
                        Close
                    </button>
                </div>
            </div>
            <form>
                <div class="row mt-4">
                    <div class="col-12 form-group">
                        <label class="admin-dashboard-input-title" for="type-name">Article Type Name *</label>
                        <input type="text" class="admin-dashboard-input" id="type-name" x-model="type_name"/>
                        <span class="admin-dashboard-input-sub-text">The name of the article type that will
                            appear next to articles in the article type</span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 d-flex align-items-center justify-content-end w-100">
                        <button class="save-article-button d-flex align-items-center justify-content-center
                        ms-3" @click.prevent="$wire.saveChanges(type_id)">
                            @include('livewire-blog-cms::svg.floppy_disk_svg', ['class' => 'save-publish-article-svg'])
                            <span x-text="(type_id !== null ? 'Edit Article Type' : 'Create Article Type')
                            "></span>
                        </button>
                    </div>
                </div>
            </form>
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
                    <input type="text" class="admin-dashboard-search" x-model="type_search"/>
                </div>
                <div class="d-flex align-content-center justify-content-between w-100">
                    <span class="admin-dashboard-input-sub-text">
                        Search by Article Type Name
                    </span>
                    <span class="admin-dashboard-input-sub-text"
                          x-text="`${filteredTypes.length} Result${filteredTypes.length > 1 ? 's' : ''}`">
                        {x} Results
                    </span>
                </div>
            </div>
        </div>
        <template x-if="filteredTypes.length > 0">
            <template x-for="type in filteredTypes" :key="type.id">
                <template x-if="type.name.toLowerCase().includes(type_search.toLowerCase())">
                    <div class="article-result d-flex my-3">
                        <div class="col-10 py-3 ps-3 latest-post-text-container">
                            <div class="inline article-result-text">
                                <div x-text="`Name: ${type.name}`"></div>
                            </div>
                            <div class="sub-article-result-text">
                                <span x-text="`Created: ${dateTimeFormat(type.created_at)} | Last Edited:
                                ${dateTimeFormat(type.updated_at)}`"></span>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="d-flex w-100 h-100">
                                <div calss="col-12 w-100 h-100">
                                    <button class="admin-edit-post-button d-flex align-items-center
                                justify-content-center type-size-down h-100"
                                            @click.prevent="editType(type.id)">
                                        @include('livewire-blog-cms::svg.edit_post_svg', ['class' => 'view-edit-article-svg'])
                                        Edit Article Type
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </template>

        <template x-if="filteredTypes.length === 0">
            <div class="text-center">No article types found!</div>
        </template>
    </div>
</div>
