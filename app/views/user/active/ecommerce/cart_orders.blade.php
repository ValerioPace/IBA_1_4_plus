<?php  
	$company = Company::where('user_id', $userId)->first();
?>

<div class="embed-responsive embed-responsive-16by9">
  <iframe class="embed-responsive-item" src="http://www.gtechplay.com/{{$company->download_link_tag}}/indexordini.asp"></iframe>
</div>