<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\SendNoImageReminder;

use Carbon;
use Queue;
use DB;

use App\Models\Listing;

class NotifyNoImageListings extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:no_image_listings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind users that they havent uploaded images to their listings.';

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
    public function handle(){
        //
        Listing::whereNull('image_path')
               ->orWhere('image_path', '')
               ->chunk(200, function ($listings){

            $this->info('I will process '.count($listings).' listings with no images in this batch');
            $this->output->progressStart(count($listings));//Only for laravel 5.1

            $ids = [];
            foreach ($listings as $listing) {
                if(!$listing->no_image_notified_at){
                    $this->info(' Sending no images reminder email to '.$listing->user->email);
                    Queue::push(new SendNoImageReminder($listing));
                    
                    $ids[] = $listing->id;
                    $this->output->progressAdvance();//Only for laravel 5.1
                }else if($listing->created_at < Carbon::now()->addDays(5) && $listing->no_image_notified_at > Carbon::now()->addDays(2)){
                    $this->info(' Sending no images reminder email to '.$listing->user->email);
                    Queue::push(new SendNoImageReminder($listing));
                    
                    $ids[] = $listing->id;
                    $this->output->progressAdvance();//Only for laravel 5.1
                }else if($listing->created_at > Carbon::now()->addDays(5) && $listing->no_image_notified_at > Carbon::now()->addDays(5)){
                    $this->info(' Sending no images reminder email to '.$listing->user->email);
                    Queue::push(new SendNoImageReminder($listing));
                    
                    $ids[] = $listing->id;
                    $this->output->progressAdvance();//Only for laravel 5.1
                }else{
                    $this->info('Not elegible for sending email to #'.$listing->id);                    
                    $this->output->progressAdvance();//Only for laravel 5.1
                }
            }

            DB::table('listings')
                ->whereIn('id', $ids)
                ->update(['no_image_notified_at' => Carbon::now()]);
            $this->output->progressFinish(); //Only for laravel 5.1
            $this->info('Finished sending no images reminder emails in this batch');
        });
        
        $this->info('Operation ended succesfuly');
    }
}
