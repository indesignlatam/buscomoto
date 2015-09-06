<?php namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail, Settings;
use App\Models\Listing;

class SendTipsEmail extends Job implements SelfHandling, ShouldQueue {

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
		$listing = $this->listing;

		// If mail account not confirmed dont send email
		if($listing->user->confirmed && $listing->user->email_notifications){
			Mail::send('emails.tips', [ 'listing' => $listing, 
										'user' => $listing->user,
									  ], 
			function ($message) use ($listing) {
			    $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
						->to($listing->user->email, $listing->user->name)
			    		->subject(trans('emails.tips_subject'));
			});
		}
		
	}
}
