<div class="container-fluid admin-login-main" x-cloak>
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 mx-auto">
            <div class="container admin-login-container">

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        {{ $this->validationFailed($error) }}
                    @endforeach
                @endif

                <div class="row admin-login-container-top">
                    <div class="col-12 text-center">
                        <div>&nbsp</div>
                    </div>
                </div>

                <div class="p-5">
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <span>
                                <img src="{{trim($this->blog_logo) ? trim($this->blog_logo) : 'https://placehold.co/400x100'}}"
                                     alt="logo-light"
                                     class="img-fluid admin-login-container-logo"/>
                            </span>
                        </div>
                    </div>

                    <form id="login" wire:submit.prevent="login">
                        <div class="form-group row">
                            <div class="col-12 mt-2">
                                <div class="input-block pt-3">
                                    <input type="text"
                                           name="username"
                                           id="username"
                                           required
                                           class="form-control"
                                           wire:model.defer="username"
                                           autocomplete="">
                                    <span class="placeholder">
                                    Username
                                </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12 mb-2">
                                <div class="input-block pt-3">
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           required
                                           class="form-control"
                                           wire:model.defer="password"
                                           autocomplete="">
                                    <span class="placeholder">
                                    Password
                                </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <button type="submit"
                                        class="btn colored-button"
                                        wire:target="login"
                                        wire:loading.attr="disabled">
                                    Log In
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
