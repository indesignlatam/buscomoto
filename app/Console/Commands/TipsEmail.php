<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use File, Settings, Carbon, Queue, DB;
use App\Models\Listing;
use App\Jobs\SendTipsEmail;

class TipsEmail extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'mail:tips';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command to send a message with tips to get more views and messages for listings';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(){
		// Email all users that have at least one listing with
		// 15 days from created
		// less than n views
		// OR less than n messages?
		// TODO send one message per user
		$listings = Listing::where('created_at', '>', Carbon::now()->subDays(Settings::get('tips_days_from_created', 15)))
						   ->where('views', '<', Settings::get('tips_min_views', 30))
						   ->with('user')
						   ->get();

		if(count($listings) > 0){
			$this->info('Started sending '.count($listings).' tip emails');
			// $this->output->progressStart(count($listings));//Only for laravel 5.1

			$ids = [];
			foreach ($listings as $listing) {
				if($listing->user->tips_sent_at > Carbon::now()->addDays(30) || in_array($listing->user->id, $ids)){
					$this->info('Sending tip email to '.$listing->user->email);
					Queue::push(new SendTipsEmail($listing));
					
					$ids[] = $listing->user->id;
					// $this->output->progressAdvance();//Only for laravel 5.1
				}
			}

			DB::table('users')
	            ->whereIn('id', $ids)
	            ->update(['tips_sent_at' => Carbon::now()]);
			// $this->output->progressFinish(); //Only for laravel 5.1
			$this->info('Finished sending tip emails');
		}else{
			$this->info('There are no listings to send tips');
		}
	}

}
