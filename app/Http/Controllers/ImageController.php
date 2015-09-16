<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Image;
use File;
use Auth;
use Carbon;
use Validator;
use Settings;
use App\Models\Image as ImageModel;
use App\Models\Listing;

class ImageController extends Controller {

	/**
     * Instantiate a new ImageController instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('file_max_upload_size', ['only' => ['store', 'user']]);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
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
		// Get the object requested
		$listing = Listing::find($request->get('listing_id'));

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->user->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Image number limit
		if(!$listing->user->confirmed){
			if(count($listing->images) >= Settings::get('unconfirmed_image_limit', 2)){
				return response()->json(['error' => trans('responses.image_limit'),
										 'image' => null
										 ]);
			}
		}elseif($listing->featured_type && $listing->featured_expires_at && $listing->featured_expires_at > Carbon::now()){
			if(count($listing->images) >= Settings::get('featured_image_limit', 20)){
				return response()->json(['error' => trans('responses.image_limit'),
										 'image' => null
										 ]);
			}
		}else{
			if(count($listing->images) >= Settings::get('free_image_limit', 10)){
				return response()->json(['error' => trans('responses.image_limit'),
										 'image' => null
										 ]);
			}
		}

		// Create an image object
		$image = new ImageModel;

	    $file 	= $request->file("image");
		$name 	= $listing->id.md5($request->get('title') . str_random(40)).'.'.$file->getClientOriginalExtension();
		$input 	= $request->all();
		$input['image_path'] = '/images/listings/full/'.$name;

		if (!$image->validate($input)){
	        return response()->json(['error' 	=> [$image->errors()],
	        	        			 'image' 	=> null
	        						]);
	    }

	    // Move file to temp folder
		if(!$file->move("images/temp", $name)){
			return response()->json(['error' => [trans('responses.error_saving_image')],
									 'image' => null
									]);
		}

		// Get image orientation
		$exif = exif_read_data(public_path().'/images/temp/'.$name, 'IFD0');
		$rotation = 0;
		if(!empty($exif['Orientation'])) {
		    switch($exif['Orientation']) {
		        case 8:
		            $rotation = -90;
		            break;
		        case 3:
		            $rotation = 180;
		            break;
		        case 6:
		            $rotation = 90;
		            break;
		    }
		}

		// Rotate the image if necessary
		$imgSize = null;
		if($rotation != 0){
			$img = Image::open(public_path().'/images/temp/'.$name);
			$img->rotate($rotation);
			$img->save('images/temp/'.$name);
		}


		//Create the color background
		$palette = new \Imagine\Image\Palette\RGB();
		$colorImage = Image::create(new \Imagine\Image\Box(960, 540), $palette->color('#fff'));
		$colorSize = $colorImage->getSize();

		//Open the image
		$uImage = Image::make('/images/temp/'.$name);
		$imageSize = $uImage->getSize();
		$ratio = $imageSize->getWidth() / $imageSize->getHeight();
		$newW = 540*$ratio;
		$uImage = $uImage->thumbnail(new \Imagine\Image\Box($newW, 540));

		// Get new imageSize
		$imageSize = $uImage->getSize();

		// Put watermark
		$watermark 		= Image::open(public_path().'/images/watermark_contrast.png');// Or use watermark.png for color watermark
		$wSize     		= $watermark->getSize();

		// Set watermark if it fits
		$wPut = false;
		if(($imageSize->getWidth() - $wSize->getWidth()-15) > 0 && ($imageSize->getHeight() - $wSize->getHeight()-15) > 0){
			$wPut = true;
			$bottomRight = new \Imagine\Image\Point($imageSize->getWidth() - $wSize->getWidth()-15, $imageSize->getHeight() - $wSize->getHeight()-15);
			$uImage->paste($watermark, $bottomRight);
		}
		
		//Get the center point
		$x = ($colorSize->getWidth() - $imageSize->getWidth())/2;
		$y = ($colorSize->getHeight() - $imageSize->getHeight())/2;
		$centerPoint = new \Imagine\Image\Point($x, $y);

		//Paste the image on the color background and save
		$colorImage->paste($uImage, $centerPoint);

		// If watermark is not set set it now
		if(!$wPut){
			$bottomRight = new \Imagine\Image\Point($colorSize->getWidth() - $wSize->getWidth()-15, $colorSize->getHeight() - $wSize->getHeight()-15);
			$colorImage->paste($watermark, $bottomRight);
		}

		// Save final image
		$colorImage->save('images/listings/full/'.$name);


		
		// Crop image, watermark
		// $img 			= Image::make('/images/temp/'.$name, ['width' => 960, 'height' => 540, 'crop' => true]);
		// $watermark 		= Image::open(public_path().'/images/watermark_contrast.png');// Or use watermark.png for color watermark
		// $size      		= $img->getSize();
		// $wSize     		= $watermark->getSize();
		// $bottomRight 	= new \Imagine\Image\Point($size->getWidth() - $wSize->getWidth()-15, $size->getHeight() - $wSize->getHeight()-15);
		// $img->paste($watermark, $bottomRight);
		// $img->save('images/listings/full/'.$name);

		// Delete the temp file
		File::delete(public_path().'/images/temp/'.$name);

		// Set this image as main if none set on listing
		if(count($listing->images) == 0){
			$listing->image_path = 'images/listings/full/'.$name;
			$listing->save();
		}

		// Create the image
		$image = $image->create($input);

		// Return ajax response with image and success message
		return response()->json(['image' 	=> $image,
								 'success'	=> trans('admin.image_uploaded_succesfuly'),
								 ]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function user($id, Request $request){
		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$id || $id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Create an image object
		$image = new ImageModel;

	    $file 	= $request->file("image");
		$name 	= md5($id).'.'.$file->getClientOriginalExtension();
		$input 	= $request->all();
		$input['image_path'] = '/images/users/'.$name;


		$validator = Validator::make($request->all(), [
            'image' => 'required|image|max:10000',
        ]);

        if ($validator->fails()) {
        	return response()->json(['error' => $validator,
									 'image' => null
									]);
        }


	    // Move file to temp folder
		if(!$file->move("images/temp", $name)){
			return response()->json(['error' => [trans('responses.error_saving_image')],
									 'image' => null
									]);
		}

		// Get image orientation
		$exif = exif_read_data(public_path().'/images/temp/'.$name, 'IFD0');
		$rotation = 0;
		if(!empty($exif['Orientation'])) {
		    switch($exif['Orientation']) {
		        case 8:
		            $rotation = -90;
		            break;
		        case 3:
		            $rotation = 180;
		            break;
		        case 6:
		            $rotation = 90;
		            break;
		    }
		}

		// Rotate the image if necessary
		if($rotation != 0){
			$img = Image::open(public_path().'/images/temp/'.$name);
			$img->rotate($rotation);
			$img->save('images/temp/'.$name);
		}
		
		// Crop image, watermark
		$img 			= Image::make('/images/temp/'.$name, ['width' => 1200, 'height' => 350, 'crop' => true]);
		$watermark 		= Image::open(public_path().'/images/watermark_contrast.png');// Or use watermark.png for color watermark
		$size      		= $img->getSize();
		$wSize     		= $watermark->getSize();
		$bottomRight 	= new \Imagine\Image\Point($size->getWidth() - $wSize->getWidth()-15, $size->getHeight() - $wSize->getHeight()-15);
		$img->paste($watermark, $bottomRight);
		$img->save('images/users/'.$name);

		// Delete the temp file
		File::delete(public_path().'/images/temp/'.$name);


		Auth::user()->image_path = 'images/users/'.$name;
		Auth::user()->save();

		// Return ajax response with image and success message
		return response()->json(['image_path' 	=> Auth::user()->image_path,
								 'success'		=> trans('admin.image_uploaded_succesfuly'),
								 ]);
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
	public function destroy($id, Request $request){
		// Get the object requested
		$image = ImageModel::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$image || $image->listing->user->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Null image_path if deleted image is main image
		if(substr($image->listing->image_path, -40) == substr($image->image_path, -40)){
			$image->listing->image_path = null;
			$image->listing->save();
		}

		// Persist listing after deleting image
		$listing = $image->listing;
		
		// Delete image
		$image->delete();

		// If no image_path and there are mores images set first as image_path
		if($listing->image_path == null && count($listing->images) > 0){
			$listing->image_path = $listing->images->first()->image_path;
			$listing->save();
		}else if(count($listing->images)){
			$listing->image_path = null;
			$listing->save();
		}

		// Return ajax response
		return response()->json(['images_count' => count($listing->images), 
								 'success'		=> trans('admin.image_deleted_succesfuly'),
								 ]);
	}

}