<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\SendConfirmationEmail;

use Carbon;
use Queue;
use DB;

use App\User;

class NotifyUnconfirmedAccounts extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:unconfirmed_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind users that they havent confirm their account.';

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

        User::Where('confirmed', false)
            ->chunk(200, function ($users){

            $this->info('Started sending '.count($users).' confirmation emails');
            $this->output->progressStart(count($users));//Only for laravel 5.1

            $ids = [];
            foreach ($users as $user) {
                if(!$user->confirmed_at){
                    $this->info('Sending confirmation email to '.$user->email);
                    Queue::push(new SendConfirmationEmail($user));
                    
                    $ids[] = $user->id;
                    $this->output->progressAdvance();//Only for laravel 5.1
                }else if($user->created_at < Carbon::now()->addDays(22) && $user->confirmed_at > Carbon::now()->addDays(10)){
                    $this->info('Sending confirmation email to '.$user->email);
                    Queue::push(new SendConfirmationEmail($user));
                    
                    $ids[] = $user->id;
                    $this->output->progressAdvance();//Only for laravel 5.1
                }else if($user->created_at > Carbon::now()->addDays(22) && $user->confirmed_at > Carbon::now()->addDays(30)){
                    $this->info('Sending confirmation email to '.$user->email);
                    Queue::push(new SendConfirmationEmail($user));
                    
                    $ids[] = $user->id;
                    $this->output->progressAdvance();//Only for laravel 5.1
                }else{
                    $this->info('Not elegible for sending email to '.$user->name);                    
                    $this->output->progressAdvance();//Only for laravel 5.1
                }
            }

            DB::table('users')
                ->whereIn('id', $ids)
                ->update(['confirmed_at' => Carbon::now()]);
            $this->output->progressFinish(); //Only for laravel 5.1
            $this->info('Finished sending confirmation emails');
        });
        
        $this->info('Operation succesful');
    }
    
}
