<?php namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Image;

class PostListingToFacebookPage extends Job implements SelfHandling, ShouldQueue {

	use InteractsWithQueue, SerializesModels;

	protected $listing;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($listing){
		//
		$this->listing = $listing;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(){
		// Post to facebook page
		$page_access_token 		= env('FB_TOKEN');
		$page_id				= env('FB_PAGE_ID');
		$data['picture'] 		= url( Image::url($this->listing->image_path(), ['facebook_share']) );
		$data['link'] 			= url($this->listing->path());
		//$data['message'] 		= "Your message";
		$data['caption'] 		= "Destacados";
		$data['description'] 	= $this->listing->description;
		$data['access_token'] 	= $page_access_token;
		$post_url = 'https://graph.facebook.com/'.$page_id.'/feed';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $post_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$return = curl_exec($ch);
		curl_close($ch);
		// Post to facebook page
	}

}
