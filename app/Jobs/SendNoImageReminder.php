<?php namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail, Settings;
use App\Models\Listing;

class SendNoImageReminder extends Job implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    private $listing;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Listing $listing){
        //
        $this->listing = $listing;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        //
        $listing    = $this->listing;
        $user       = $listing->user;

        Mail::send('emails.no_image_listing_reminder', ['user' => $user, 'listing' => $listing], function ($message) use ($user) {
            $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
                    ->to($user->email, $user->name)
                    ->subject(trans('emails.no_image_listing_subject'));
        });
    }
}
