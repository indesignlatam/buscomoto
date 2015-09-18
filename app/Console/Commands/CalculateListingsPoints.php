<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Listing;

class CalculateListingsPoints extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listings:points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        Listing::with('images', 'features')->chunk(200, function ($listings) {

            $this->info('Listings to update: '.count($listings));
            $this->output->progressStart(count($listings));//Only for laravel 5.1

            foreach ($listings as $listing) {
                // Calculate points
                $points = 0;
                if(isset($listing->images) && count($listing->images) > 0){
                    if(count($listing->images) == 1){
                        $points += 50;
                    }else if(count($listing->images) > 1){
                        $points += 50 + (count($listing->images)-1) * 10;
                    }
                }

                if(count($listing->features) > 0){
                    $points += count($listing->features) * 2;
                }

                if($listing->description && strlen($listing->description) > 30){
                    $points += strlen($listing->description) * 0.2;
                }

                $listing->points = $points;
                // Calculate points

                $listing->save();

                $this->output->progressAdvance();//Only for laravel 5.1
            }
        });
        
        $this->output->progressFinish(); //Only for laravel 5.1
        $this->info('Operation succesful');
    }
}
