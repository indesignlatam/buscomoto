<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use App\User;
use Carbon;
use Settings;
use Agent;

use App\Models\Appointment;
use App\Models\Listing;

class HomeController extends Controller {

	/**
     * Instantiate a new HomeController instance.
     */
    public function __construct(){
    	//
    }

	public function index(){

		if(Auth::user()->is('admin')){
			// Users dataset
			$users = User::selectRaw('count(*) AS amount, date(created_at) AS created')->where('created_at', '>', Carbon::now()->subDays(30))->groupBy('created')->get();
			$items = [];
			$labels = [];
			foreach ($users as $user) {
				$labels[] 	= Carbon::createFromFormat('Y-m-d', $user->created)->formatLocalized('%a %d %b');
				$items[] 	= $user->amount;
			}
			$datasetUsers = ['label' 		=> 'Registered users by day',
							 'fillColor' 	=> "rgba(220,220,220,0.2)",
            				 'strokeColor' 	=> "rgba(220,220,220,1)",
            				 'pointColor'	=> "rgba(220,220,220,1)",
				             'pointStrokeColor' => "#fff",
				             'pointHighlightFill' => "#fff",
				             'pointHighlightStroke' => "rgba(220,220,220,1)",
				             'data' 		=> $items,
							];
			$datasets[] = $datasetUsers;
			// Graph data
			$data = ['labels' => $labels,
					 'datasets' => $datasets,
					 ];

			// Listings dataset
			$listings = Listing::selectRaw('count(*) AS amount, date(created_at) AS created')->where('created_at', '>', Carbon::now()->subDays(30))->groupBy('created')->get();
			$items = [];
			$labels = [];
			foreach ($listings as $listing) {
				$labels[] 	= Carbon::createFromFormat('Y-m-d', $listing->created)->formatLocalized('%a %d %b');
				$items[] 	= $listing->amount;
			}
			$datasetlistings = [ 'label' 		=> 'Registered users by day',
								 'fillColor' 	=> "rgba(151,187,205,0.2)",
	            				 'strokeColor' 	=> "rgba(151,187,205,1)",
	            				 'pointColor'	=> "rgba(151,187,205,1)",
					             'pointStrokeColor' => "#fff",
					             'pointHighlightFill' => "#fff",
					             'pointHighlightStroke' => "rgba(151,187,205,1)",
					             'data' 		=> $items,
								];
			$datasets = null;
			$datasets[] = $datasetlistings;
			// Graph data
			$data2 = ['labels' => $labels,
					 'datasets' => $datasets,
					 ];


			// Messages dataset
			$messages = Appointment::selectRaw('count(*) AS amount, date(created_at) AS created')->where('created_at', '>', Carbon::now()->subDays(30))->groupBy('created')->get();
			$items = [];
			$labels = [];
			foreach ($messages as $message) {
				$labels[] 	= Carbon::createFromFormat('Y-m-d', $message->created)->formatLocalized('%a %d %b');
				$items[] 	= $message->amount;
			}
			$datasetMessages = [ 'label' 		=> 'Registered users by day',
								 'fillColor' 	=> "rgba(151,187,205,0.2)",
	            				 'strokeColor' 	=> "rgba(151,187,205,1)",
	            				 'pointColor'	=> "rgba(151,187,205,1)",
					             'pointStrokeColor' => "#fff",
					             'pointHighlightFill' => "#fff",
					             'pointHighlightStroke' => "rgba(151,187,205,1)",
					             'data' 		=> $items,
								];
			$datasets = null;
			$datasets[] = $datasetMessages;
			// Graph data
			$data3 = ['labels' => $labels,
					  'datasets' => $datasets,
					 ];


			// Counts
			$listingCount = Listing::count();
			$nIListingCount = Listing::whereNull('image_path')->orWhere('image_path', '')->count();
			$pointsAVG = Listing::avg('points');
			$userCount = User::count();
			$messageCount = Appointment::count();
			

			// Serve correct view for Desktop or mobile
			$view = 'admin.home.home';
			if(Agent::isMobile()){
				$view = 'admin.home.mobile.home';
			}

			return view($view, ['data' 		=> $data,
								'data2'		=> $data2,
								'data3'		=> $data3,
								'counts' 	=> ['listings' 	=> $listingCount,
												'users' 	=> $userCount,
												'messages' 	=> $messageCount,
												'nIListings'=> $nIListingCount,
												'points_avg'=> $pointsAVG,
											 	],
								]);
		}elseif(Auth::user()->confirmed){
			$messages 				= Appointment::remember(Settings::get('query_cache_time_extra_short'))
												  ->leftJoin('listings',
													function($join) {
														$join->on('messages.listing_id', '=', 'listings.id');
													})
												  ->select('messages.id', 'name', 'email', 'phone', 'comments', 'read', 'answered', 'messages.user_id', 'messages.listing_id', 'listings.user_id')
												  ->notAnswered()
												  ->where('listings.user_id', Auth::user()->id)
												  ->orderBy('messages.id', 'DESC')
												  ->with('listing')
												  ->take(8)
												  ->get();

			$notAnsweredMessages 	= Appointment::leftJoin('listings',
													function($join) {
														$join->on('messages.listing_id', '=', 'listings.id');
													})
												  ->select('messages.id', 'read', 'answered', 'messages.user_id', 'messages.listing_id', 'listings.user_id')
												  ->notAnswered()
												  ->where('listings.user_id', Auth::user()->id)
												  ->count();

			$listings 				= Listing::whereRaw('user_id = ? AND featured_expires_at < ? AND featured_expires_at > ?', [Auth::user()->id, Carbon::now()->addDays(5), Carbon::now()])
											 ->orWhereRaw('user_id = ? AND expires_at < ? AND expires_at > ?', [Auth::user()->id, Carbon::now()->addDays(5), Carbon::now()])
											 ->orderBy('expires_at', 'DESC')
											 ->with('messageCount')
											 ->take(8)
											 ->get();

			$listingsAll 			= Listing::remember(Settings::get('query_cache_time_short'))
											 ->where('user_id', Auth::user()->id)
											 ->get();


			$colors = ['#F7464A','#46BFBD','#FDB45C','#949FB1','#4D5360','#FF9500','#52EDC7','#EF4DB6','#4CD964','#FFCC00','#5856D6','#FF3A2D','#C86EDF','#007AFF','#FF2D55','#FF9500','#0BD318',];
			$data 	= [];
			foreach ($listingsAll as $listing) {
				$data[] = [ 'value' 	=> $listing->views,
							'color' 	=> $colors[rand(0, 16)],
							'highlight' => "#FF5A5E",
							'label' 	=> $listing->title.' #'.$listing->code,
						  ];
			}


			// Serve correct view for Desktop or mobile
			$view = 'admin.home.dashboard';
			if(Agent::isMobile()){
				$view = 'admin.home.mobile.dashboard';
			}

			return view($view, ['messages' 	=> $messages,
								'listings' 	=> $listings,
								'listingCount' => count($listingsAll),
								'notAnsweredMessages' => $notAnsweredMessages,
								'data' 		=> $data
								]);
		}
		
		return redirect('/admin/user/not_confirmed');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request){
		//
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id){
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){
		//
		
	}

}