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
                    }else if(count($listing->images) > 1 && count($listing->images) < 5){
                        $points += 50 + (count($listing->images)-1) * 10;
                    }else{
                        $points += 80;
                    }
                }

                if(count($listing->features) > 0){
                    $pointsFeatures = count($listing->features) * 2;
                    if($pointsFeatures > 20){
                        $points += 20;
                    }else{
                        $points += $pointsFeatures;
                    }
                }

                if($listing->description && strlen($listing->description) > 30){
                    $pointsDescription = strlen($listing->description) * 0.1;
                    if($pointsDescription > 30){
                        $points += 30;
                    }else{
                        $points += $pointsDescription;
                    }
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
