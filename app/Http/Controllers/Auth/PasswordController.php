<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class PasswordController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest');
        $this->middleware('throttle', ['only' => ['postEmail']]);
        $this->middleware('recaptcha:false', ['only' => 'postEmail']);

        // Translate the password reset email subject
        $this->subject = trans('emails.password_reset_subject');
    }
}
