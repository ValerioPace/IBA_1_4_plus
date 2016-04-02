<?php

class DownloadController extends BaseController {

	public function getGalleryImageFullById($galleryImageId){
		
		$companyImage = CompanyDataImage::find($galleryImageId);

		try {
			$pathToFile = public_path().'/uploads/company_images/gallery/full_size/'.$companyImage->image;
			$image = File::get($pathToFile);
		} catch (Exception $e){
			App::abort(404, 'No image found.');
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));

	}

	public function getGalleryImageThumById($galleryImageId){
		
		$companyImage = CompanyDataImage::find($galleryImageId);

		try {
			$pathToFile = public_path().'/uploads/company_images/gallery/thumbnail/thumb_'.$companyImage->image;
			$image = File::get($pathToFile);
		} catch (Exception $e){
			App::abort(404, 'No image found.');
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));

	}




	public function getLogoImageFullById($companyDataId){
		
		$companyData = CompanyData::find($companyDataId);

		try {
			$pathToFile = public_path().'/uploads/company_images/logo/full_size/'.$companyData->logo;
			$image = File::get($pathToFile);
		} catch (Exception $e){
			App::abort(404, 'No image found.');
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));

	}

	public function getLogoImageThumById($companyDataId){
		
		$companyData = CompanyData::find($companyDataId);

		try {
			$pathToFile = public_path().'/uploads/company_images/logo/thumbnail/thumb_'.$companyData->logo;
			$image = File::get($pathToFile);
		} catch (Exception $e){
			App::abort(404, 'No image found.');
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));

	}




	public function getIconImageFullById($companyDataId){
		
		$companyData = CompanyData::find($companyDataId);

		try {
			$pathToFile = public_path().'/uploads/company_images/icon/full_size/'.$companyData->icon_image;
			$image = File::get($pathToFile);
		} catch (Exception $e){
			App::abort(404, 'No image found.');
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));

	}

	public function getIconImageThumById($companyDataId){
		
		$companyData = CompanyData::find($companyDataId);

		try {
			$pathToFile = public_path().'/uploads/company_images/icon/thumbnail/thumb_'.$companyData->icon_image;
			$image = File::get($pathToFile);
		} catch (Exception $e){
			App::abort(404, 'No image found.');
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));

	}




	public function getCoverImageFullById($companyDataId){
		
		$companyData = CompanyData::find($companyDataId);

		try {
			$pathToFile = public_path().'/uploads/company_images/cover/full_size/'.$companyData->cover_image;
			$image = File::get($pathToFile);
		} catch (Exception $e){
			App::abort(404, 'No image found.');
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));

	}

	public function getCoverImageThumById($companyDataId){
		
		$companyData = CompanyData::find($companyDataId);

		try {
			$pathToFile = public_path().'/uploads/company_images/cover/thumbnail/thumb_'.$companyData->cover_image;
			$image = File::get($pathToFile);
		} catch (Exception $e){
			App::abort(404, 'No image found.');
		}

		return Response::make($image, 200, array('content-type' => 'image/jpg'));

	}

	public function downloadCompanyData ($userId) {

		/*$zipper = new \Chumper\Zipper\Zipper;

		$zipper->make(public_path().'/uploads/company_images/gallery/full_size/test.zip')->folder('test')->add(public_path().'/uploads/company_images/gallery/full_size/20150916180932_25186.jpg');

		$zipper->close();

		return Response::download(public_path().'/uploads/company_images/gallery/full_size/test.zip');*/

		if (Auth::check() && (Auth::user()->role_id == 2 || Auth::user()->role_id == 5) ) {
			
			$user = User::with('company.companyData.images')
			->with('company.companyData.socials')
			->with('company.companyData.youtubeVideos')
			->find($userId);

			$characters = '0123456789';
			$randomString = '';
		    for ($j = 0; $j < 5; $j++) {
		        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		    }

			$fileName = \Carbon\Carbon::now()->format('YmdHis').'_'.$randomString.'.zip';

			$zipper = new MyZipper();

			$zipper->make(public_path().'/uploads/temp/zip/'.$fileName);

			$socials = array();
			foreach ($user->company->companyData->socials as $key => $social) {
				$socials[$social->network] = $social->link;
			}

			$youtubeVideos = array();
			foreach ($user->company->companyData->youtubeVideos as $key => $youtubeVideo) {
				$youtubeVideos[$youtubeVideo->network] = $youtubeVideo->link;
			}

			$textData =
				 "\r\nNome: "					.$user->company->companyData->name
				."\r\nCognome: "				.$user->company->companyData->last_name
				."\r\nTelefono: "				.$user->company->companyData->phone
				."\r\nCellulare: "				.$user->company->companyData->mobile
				."\r\nFax: "					.$user->company->companyData->fax
				."\r\nIndirizzo: "				.$user->company->companyData->address
				."\r\nEmail: "					.$user->company->companyData->email
				."\r\nEmail secondaria: "		.$user->company->companyData->email_2
				."\r\nSito Web: "				.$user->company->companyData->web_site
				."\r\nSito Web secondario: "	.$user->company->companyData->web_site_2
				."\r\nNome Applicazione: "		.$user->company->companyData->app_name
				."\r\nNome Azienda: "			.$user->company->companyData->company_name
				."\r\nTitolo/Nome attività: "	.$user->company->companyData->position
				."\r\nDescrizione Azienda: "	.$user->company->companyData->company_description
				."\r\nSlogan (alto): "			.$user->company->companyData->top_slogan
				."\r\nSlogan (basso): "			.$user->company->companyData->bottom_slogan
				."\r\nFacebook: "				.(array_key_exists('Facebook', $socials) ? $socials['Facebook'] : '')
				."\r\nGoogle+: "				.(array_key_exists('Google+', $socials) ? $socials['Google+'] : '')
				."\r\nTwitter: "				.(array_key_exists('Twitter', $socials) ? $socials['Twitter'] : '')
				."\r\nLinkedin: "				.(array_key_exists('Linkedin', $socials) ? $socials['Linkedin'] : '')
				."\r\nYouTube 1: "				.(array_key_exists('Youtube1', $youtubeVideos) ? $youtubeVideos['Youtube1'] : '')
				."\r\nYouTube 2: "				.(array_key_exists('Youtube2', $youtubeVideos) ? $youtubeVideos['Youtube2'] : '')
				."\r\nYouTube 3: "				.(array_key_exists('Youtube3', $youtubeVideos) ? $youtubeVideos['Youtube3'] : '')
				."\r\nYouTube 4: "				.(array_key_exists('Youtube4', $youtubeVideos) ? $youtubeVideos['Youtube4'] : '')
				."\r\nNote: "					.$user->company->companyData->note
			;

			$zipper->addString('dati.txt', $textData);

			foreach ($user->company->companyData->images as $key => $image) {
				$zipper->folder('immagini/galleria')->add(public_path().'/uploads/company_images/gallery/full_size/'.$image->image);
			}

			if ($user->company->companyData->logo)
				$zipper->folder('immagini')->add(public_path().'/uploads/company_images/logo/full_size/'.$user->company->companyData->logo, 'Logo.jpg');

			if ($user->company->companyData->cover_image)
				$zipper->folder('immagini')->add(public_path().'/uploads/company_images/cover/full_size/'.$user->company->companyData->cover_image, 'Cover.jpg');

			if ($user->company->companyData->icon_image)
				$zipper->folder('immagini')->add(public_path().'/uploads/company_images/icon/full_size/'.$user->company->companyData->icon_image, 'Icona.jpg');


			$zipper->close();

			return Response::download(public_path().'/uploads/temp/zip/'.$fileName);
		}

		return Response::make('Non autenticato!', 401);

	}
}
