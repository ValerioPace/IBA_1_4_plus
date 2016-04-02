<?php

class UploadController extends BaseController {

	public function uploadGalleryImages(){

		if (Auth::check()){

			if (Auth::user()->role_id == 1) {
				if (!Auth::user()->user_status_id == 3)
					App::abort(403, 'Unauthorized action.');

				$companyData = CompanyData::whereHas('company', function($query) {
					$query->where('user_id', Auth::user()->id);
				})
				->first();
			} else if (Auth::user()->role_id == 2 || Auth::user()->role_id == 5) {
				$companyData = CompanyData::whereHas('company', function($query) {
					$query->where('user_id', Input::get('userId'));
				})
				->first();
			} else {
				echo json_encode(['error'=>'Non autorizzato!']);
				return;
			}

			if (!count($companyData) > 0){
				echo json_encode(['error'=>'Company not found']);
				return;
			}

			if (!Input::hasFile('galleryImages')) {
			    echo json_encode(['error'=>'No files found for upload.']);
			    return;
			}
			
			$images = Input::file('galleryImages');

			$companyImageCount = CompanyDataImage::where('company_data_id', $companyData->id)->count();

			if ($companyImageCount + count($images) >= 21){
				//echo json_encode(['error'=>'Non puoi inserire più di 20 immagini.']);
				return json_encode(['error'=>'Non puoi inserire più di 20 immagini.']);
			}

			$filePath = 'company_images/gallery';
			$routePath = 'companyImages/gallery';

			foreach ($images as $key => $image) {

				$companyImage = new CompanyDataImage;
				$companyImage->company_data_id = $companyData->id;

				$companyImage->image = $this->imageUploader($image, $filePath);

				$companyImage->save();

				$initialPreview[] = '<img src='.url('/').'/'.$routePath.'/full_size/by_id/'.$companyImage->id.'\' class=\'file-preview-image\' alt=\' \' title=\' \'>';

				$initialPreviewConfig[] = array(
					'url' => url('/').'/delete/'.$routePath,
			        'key' => $companyImage->id
				);
			}

			echo json_encode(
				array(
					'initialPreview' => $initialPreview,
					'initialPreviewConfig' => $initialPreviewConfig,
					'append' => true
				)
			);
		} else
			return Response::make('Non autenticato!', 401);
		
	}



	public function uploadLogoImage(){

		if (Auth::check()) {

			if (Auth::user()->role_id == 1) {
				if (!Auth::user()->user_status_id == 3)
					App::abort(403, 'Unauthorized action.');

				$companyData = CompanyData::whereHas('company', function($query) {
					$query->where('user_id', Auth::user()->id);
				})
				->first();
			} else if (Auth::user()->role_id == 2 || Auth::user()->role_id == 5) {
				$companyData = CompanyData::whereHas('company', function($query) {
					$query->where('user_id', Input::get('userId'));
				})
				->first();
			} else {
				echo json_encode(['error'=>'Non autorizzato!']);
				return;
			}
			

			if (!count($companyData) > 0){
				echo json_encode(['error'=>'Company not found']);
				return;
			}

			if (!Input::hasFile('logo')) {
			    echo json_encode(['error'=>'No files found for upload.']);
			    return;
			}
			
			$image = Input::file('logo');

			$filePath = 'company_images/logo';
			$routePath = 'companyImages/logo';

			if ($companyData->logo)
				$this->deleteLogoImage($companyData->logo);

			$companyData->logo = $this->imageUploader($image, $filePath);

			$companyData->save();

			$initialPreview[] = '"<img src='.url('/').'/'.$routePath.'/full_size/by_id/'.$companyData->id.'?'.time().'\' class=\'file-preview-image\' alt=\' \' title=\'logo\'>';

			$initialPreviewConfig[] = array(
				'url' => url('/').'/delete/'.$routePath,
		        'key' => $companyData->id
			);
	

			echo json_encode(
				array(
					'initialPreview' => $initialPreview,
					'initialPreviewConfig' => $initialPreviewConfig,
					'append' => false
				)
			);
		} else
			return Response::make('Non autenticato!', 401);
		
	}



	public function uploadCoverImage(){

		if (Auth::check()){

			if (Auth::user()->role_id == 1) {
				if (!Auth::user()->user_status_id == 3)
					App::abort(403, 'Unauthorized action.');

				$companyData = CompanyData::whereHas('company', function($query) {
					$query->where('user_id', Auth::user()->id);
				})
				->first();
			} else if (Auth::user()->role_id == 2 || Auth::user()->role_id == 5) {
				$companyData = CompanyData::whereHas('company', function($query) {
					$query->where('user_id', Input::get('userId'));
				})
				->first();
			} else {
				echo json_encode(['error'=>'Non autorizzato!']);
				return;
			}

			if (!count($companyData) > 0){
				echo json_encode(['error'=>'Company not found']);
				return;
			}

			if (!Input::hasFile('cover')) {
			    echo json_encode(['error'=>'No files found for upload.']);
			    return;
			}
			
			$image = Input::file('cover');

			$filePath = 'company_images/cover';
			$routePath = 'companyImages/cover';

			if ($companyData->cover_image)
				$this->deleteCoverImage($companyData->cover_image);

			$companyData->cover_image = $this->imageUploader($image, $filePath);

			$companyData->save();

			$initialPreview[] = '<img src='.url('/').'/'.$routePath.'/full_size/by_id/'.$companyData->id.'?'.time().'\' class=\'file-preview-image\' alt=\' \' title=\'Cover\'>';

			$initialPreviewConfig[] = array(
				'url' => url('/').'/delete/'.$routePath,
		        'key' => $companyData->id
			);


			echo json_encode(
				array(
					'initialPreview' => $initialPreview,
					'initialPreviewConfig' => $initialPreviewConfig,
					'append' => false
				)
			);
		} else
			return Response::make('Non autenticato!', 401);
		
	}

    public function uploadPEMCertIos(){
	    if (Auth::check()){
			if (Auth::user()->role_id == 5) {
				
                /*
				$publishedApp = PublishedMobileApp::whereHas('developer', function($query) {
					$query->where('developer_id', Auth::user()->id);
					$query->orderBy('created_at', 'desc');
				})->first();
                */

                $companyTag = Input::get('companyTag');
                $ios_cert_filename = $this->certfileUploader('ios_cert_file', $companyTag);

                echo json_encode(
    				array(
    					'ios_cert_filename' => $ios_cert_filename
    				)
			    );
                
                /*
                if($publishedApp != null){
    				$publishedApp->ios_cert_filename = $ios_cert_filename;
    				$publishedApp->save();
                }
                */
			}   
			else {
				echo json_encode(['error'=>'Non autorizzato!']);
				return;
			}
		}
		else
			return Response::make('Non autenticato!', 401);
    }

	public function uploadIconImage(){

		if (Auth::check()){

			if (Auth::user()->role_id == 1) {
				if (!Auth::user()->user_status_id == 3)
					App::abort(403, 'Unauthorized action.');

				$companyData = CompanyData::whereHas('company', function($query) {
					$query->where('user_id', Auth::user()->id);
				})
				->first();
			} else if (Auth::user()->role_id == 2 || Auth::user()->role_id == 5) {
				$companyData = CompanyData::whereHas('company', function($query) {
					$query->where('user_id', Input::get('userId'));
				})
				->first();
			} else {
				echo json_encode(['error'=>'Non autorizzato!']);
				return;
			}

			if (!count($companyData) > 0){
				echo json_encode(['error'=>'Company not found']);
				return;
			}

			if (!Input::hasFile('icon')) {
			    echo json_encode(['error'=>'No files found for upload.']);
			    return;
			}
			
			$image = Input::file('icon');

			$filePath = 'company_images/icon';
			$routePath = 'companyImages/icon';

			if ($companyData->icon_image)
				$this->deleteIconImage($companyData->icon_image);

			$companyData->icon_image = $this->imageUploader($image, $filePath);

			$companyData->save();

			$initialPreview[] = '<img src='.url('/').'/'.$routePath.'/full_size/by_id/'.$companyData->id.'?'.time().'\' class=\'file-preview-image\' alt=\' \' title=\'Icona\'>';

			$initialPreviewConfig[] = array(
				'url' => url('/').'/delete/'.$routePath,
		        'key' => $companyData->id
			);


			echo json_encode(
				array(
					'initialPreview' => $initialPreview,
					'initialPreviewConfig' => $initialPreviewConfig,
					'append' => false
				)
			);
		} else
			return Response::make('Non autenticato!', 401);
		
	}
	
	public function certfileUploader($requestFileParam, $companyTag){
		$uploadfile = Input::file($requestFileParam);
		$uploadfilePath = $uploadfile->getRealPath();
		$uploadfileName = $uploadfile->getClientOriginalName();
		
        if(!File::exists(public_path() . '/cert/' . $companyTag))
           $result = File::makeDirectory(public_path() . '/cert/' . $companyTag, 0664, true);
        
		$fullfilePath = public_path() . '/cert/' . $companyTag . '/ck.pem';
		
		//copy Certificate from tmp upload directory to certs directory
		copy($uploadfilePath, $fullfilePath);
		
		return $uploadfileName;
	}


	public function imageUploader($image, $filePath){
		
		$initialPreview = array();
		$initialPreviewConfig = array();
		

		$rules = array(
			'image' => 'required|image|mimes:jpeg,png|max:6144'
		);

        $messages = array(
	    	'image.image'    => 'Il file caricato non e\' un immagine.',
	    	'image.mimes'    => 'I formati di immagine supportati sono: jpeg e png.',
	    	'image.max'    => 'La dimensione massima delle immagini e\' di 6MB.'
		);

		$validator = Validator::make(array('image'=> $image), $rules, $messages);

		if ($validator->fails()) {
			echo json_encode(['error' => $validator->messages()->first('image')]);
		    return;
		}

		
		$img = Image::make($image);

	    if ($img->mime() == 'png')
	    	$img->encode('jpg');


		$characters = '0123456789';
		$randomString = '';
	    for ($j = 0; $j < 5; $j++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }

		$fileName = \Carbon\Carbon::now()->format('YmdHis').'_'.$randomString.'.jpg';

		$img->save(public_path().'/uploads/'.$filePath.'/full_size/'.$fileName);
	    
	    $img->fit(500, 500, function ($constraint) {
		    $constraint->upsize();
		});

		$img->save(public_path().'/uploads/'.$filePath.'/thumbnail/thumb_'.$fileName);

		return $fileName;

	}

	public function deleteGalleryImage(){

		if (Auth::check()) {

			if (Auth::user()->role_id == 1) {
				if (!Auth::user()->user_status_id == 3)
					App::abort(403, 'Unauthorized action.');
			} else if (Auth::user()->role_id != 2 && Auth::user()->role_id != 5) {
				App::abort(403, 'Unauthorized action.');
			}
		
			$companyImage = CompanyDataImage::find(Input::get('key'));

			if (!count($companyImage) > 0){
				echo json_encode(['error'=>'Image not found']);
				return;
			}

			File::delete(public_path().'/uploads/company_images/gallery/full_size/'.$companyImage->image);

			File::delete(public_path().'/uploads/company_images/gallery/thumbnail/thumb_'.$companyImage->image);

			$companyImage->delete();

			echo json_encode([]);
			
		} else
			return Response::make('Non autenticato!', 401);
	}

	public function deleteLogoImage($fileName = null){

		if ($fileName){ //si tratta di una chiamta interna

			File::delete(public_path().'/uploads/company_images/logo/full_size/'.$fileName);

			File::delete(public_path().'/uploads/company_images/logo/thumbnail/thumb_'.$fileName);

			return;
		
		} else if (Auth::check()){

			if (Auth::user()->role_id == 1) {
				if (!Auth::user()->user_status_id == 3)
					App::abort(403, 'Unauthorized action.');
			} else if (Auth::user()->role_id != 2 && Auth::user()->role_id != 5) {
				App::abort(403, 'Unauthorized action.');
			}
			
		
			$companyData = CompanyData::find(Input::get('key'));

			if (!count($companyData) > 0){
				echo json_encode(['error'=>'Image not found']);
				return;
			}

			File::delete(public_path().'/uploads/company_images/logo/full_size/'.$companyData->logo);

			File::delete(public_path().'/uploads/company_images/logo/thumbnail/thumb_'.$companyData->logo);

			$companyData->logo = null;

			$companyData->save();

			echo json_encode([]);
			
		}

	}

	public function deleteCoverImage($fileName = null){

		if ($fileName){ //si tratta di una chiamta interna

			File::delete(public_path().'/uploads/company_images/cover/full_size/'.$fileName);

			File::delete(public_path().'/uploads/company_images/cover/thumbnail/thumb_'.$fileName);

			return;
		
		} else if (Auth::check()) {

			if (Auth::user()->role_id == 1) {
				if (!Auth::user()->user_status_id == 3)
					App::abort(403, 'Unauthorized action.');
			} else if (Auth::user()->role_id != 2 && Auth::user()->role_id != 5) {
				App::abort(403, 'Unauthorized action.');
			}
		
			$companyData = CompanyData::find(Input::get('key'));

			if (!count($companyData) > 0){
				echo json_encode(['error'=>'Image not found']);
				return;
			}

			File::delete(public_path().'/uploads/company_images/cover/full_size/'.$companyData->cover_image);

			File::delete(public_path().'/uploads/company_images/cover/thumbnail/thumb_'.$companyData->cover_image);

			$companyData->cover_image = null;

			$companyData->save();

			echo json_encode([]);
			
		} else
			return Response::make('Non autenticato!', 401);
	}

	public function deleteIconImage($fileName = null){

		if ($fileName){ //si tratta di una chiamta interna

			File::delete(public_path().'/uploads/company_images/icon/full_size/'.$fileName);

			File::delete(public_path().'/uploads/company_images/icon/thumbnail/thumb_'.$fileName);

			return;
		
		} else if (Auth::check()) {

			if (Auth::user()->role_id == 1) {
				if (!Auth::user()->user_status_id == 3)
					App::abort(403, 'Unauthorized action.');
			} else if (Auth::user()->role_id != 2 && Auth::user()->role_id != 5) {
				App::abort(403, 'Unauthorized action.');
			}
		
			$companyData = CompanyData::find(Input::get('key'));

			if (!count($companyData) > 0){
				echo json_encode(['error'=>'Image not found']);
				return;
			}

			File::delete(public_path().'/uploads/company_images/icon/full_size/'.$companyData->icon_image);

			File::delete(public_path().'/uploads/company_images/icon/thumbnail/thumb_'.$companyData->icon_image);

			$companyData->icon_image = null;

			$companyData->save();

			echo json_encode([]);
			
		} else
			return Response::make('Non autenticato!', 401);
	}
}
