<!-- This is the modal -->
<div id="new_message_modal" class="uk-modal" data-focus-on="input:first">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
        <div class="uk-modal-header">
            {{ trans('frontend.contact_vendor') }}
        </div>

        <h3 class="uk-margin-top-remove uk-text-bold">{{ $listing->title }}</h3>
        
        @if(!Cookie::get('listing_message_'.$listing->id) || Cookie::get('listing_message_'.$listing->id) > Carbon::now())
            @if (count($errors) > 0)
                <div class="uk-alert uk-alert-danger" data-uk-alert>
                    <a href="" class="uk-alert-close uk-close"></a>
                    <ul class="uk-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!$listing->user->phone_1 && !$listing->user->phone_2)
                <div class="uk-text-warning">
                    El usuario no tiene ningun telefono registrado. Intenta escribirle un mensaje.
                </div>
            @else
                @if($listing->user->phone_1)
                    <div class="uk-h3">
                        Tel 1: <b class="uk-text-primary">{{ $listing->user->phone_1 }}</b>
                    </div>
                @endif
                @if($listing->user->phone_2)
                    <div class="uk-h3">
                        Tel 2: <b class="uk-text-primary">{{ $listing->user->phone_2 }}</b>
                    </div>
                @endif
            @endif

            <hr>
            

            <form id="send_message" class="uk-form uk-form-horizontal" method="POST" action="{{ url('/appointments') }}">
    			<input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="listing_id" value="{{ $listing->id }}">

                <div class="uk-hidden">
                    <input type="text" name="surname" placeholder="Surname" value="{{ old('surname') }}">
                </div>
                            
                @if(Auth::check())
                    <input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="name" placeholder="{{ trans('admin.name') }}" value="{{ Auth::user()->name }}">
                @else
                    <input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="name" placeholder="{{ trans('admin.name') }}" value="{{ old('name') }}">
                @endif

                <div class="uk-hidden">
                    <input type="text" name="surname" placeholder="Surname" value="{{ old('surname') }}">
                </div>
                
                @if(Auth::check())
                    <input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="phone" placeholder="{{ trans('admin.phone') }}" value="{{ Auth::user()->phone_1 }}">
                @else
                    <input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="text" name="phone" placeholder="{{ trans('admin.phone') }}" value="{{ old('phone') }}">
                @endif

                @if(Auth::check())
                    <input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="email" name="email" placeholder="{{ trans('admin.email') }}" value="{{ Auth::user()->email }}">
                @else
                    <input class="uk-width-large-10-10 uk-margin-small-bottom uk-form-large" type="email" name="email" placeholder="{{ trans('admin.email') }}" value="{{ old('email') }}" onchange="showCaptcha()">
                @endif

                <textarea class="uk-width-1-1 uk-form-large" name="comments" placeholder="Comentarios" rows="5">@if(old('comments')){{ old('comments') }}@else{{ trans('frontend.contact_default_text') }}@endif</textarea>

                @if(!Auth::check() && !Agent::isMobile())
                    <!-- ReCaptcha -->
                    <div class="uk-form-row uk-width-large-10-10 uk-margin-top uk-align-center">
                        <div class="g-recaptcha" data-sitekey="6Ldv5wgTAAAAALT3VR33Xq-9wDLXdHQSvue-JshE"></div>
                        <p class="uk-margin-remove uk-text-muted">{{ trans('admin.recaptcha_help') }}</p>
                    </div>
                    <!-- ReCaptcha -->
                @endif
    		</form>

    		<div class="uk-modal-footer">
    			<button form="send_message" type="submit" class="uk-button uk-button-large uk-button-primary">{{ trans('frontend.contact_button') }}</button>
    	        <a href="" class="uk-button uk-button-large uk-modal-close">{{ trans('admin.cancel') }}</a>
    	    </div>
        @else
            <h3 class="uk-text-primary">{{ trans('frontend.already_contacted_vendor') }}</h3>

            <hr>

            @if(!$listing->user->phone_1 && !$listing->user->phone_2)
                <div class="uk-text-warning">
                    El usuario no tiene ningun telefono registrado. Intenta escribirle un mensaje.
                </div>
            @else
                @if($listing->user->phone_1)
                    <div class="uk-h3">
                        Tel 1: <b id="phone_1">{{ $listing->user->phone_1 }}</b>
                    </div>
                @endif
                @if($listing->user->phone_2)
                    <div class="uk-h3">
                        Tel 2: <b id="phone_2">{{ $listing->user->phone_2 }}</b>
                    </div>
                @endif
            @endif
        @endif
	    
    </div>
</div>