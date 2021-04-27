@component('mail::message')
# {{ __('Your account will soon be deleted') }}

{{ __('We noticed that it has been a while since you registered for our service and you have yet to verify your email address.') }}

{{ __('As such, your account will be deleted from our system at :automatically_delete_at!', ['automatically_delete_at' => $automaticallyDeleteAt]) }}

{{ __('If you would like to keep this account, please verify your email by clicking the button below.') }}

@component('mail::button', ['url' => $verifyUrl])
{{ __('Verify Email Address') }}
@endcomponent

{{ __('Thanks,') }}
{{ config('app.name') }}

{{ __('If you did not sign up or do not wish to keep your account, you may discard this email.') }}
@endcomponent
