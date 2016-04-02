<?php

	$users = User::
	where('role_id', 1)
	->whereHas('company.companyData', function () {})
	->where(function ($query1) {
		if (Input::has('search')){
			$query1->where('user_name', 'LIKE', '%'. Input::get('search') .'%')
			->orWhere('email', 'LIKE', '%'. Input::get('search') .'%')
			->orWhereHas('company', function ($query2){
				$query2->where('name', 'LIKE', '%'. Input::get('search') .'%');
			});
		}
	})
	->whereHas('company', function ($query){
		if (Input::has('statusIds'))
			$query->whereIn('company_status_id', Input::get('statusIds'));
	})
	->with('company.activationCode')
	->with('company.companyData.developer')
	->get()
	->sortByDesc('company.company_status_id');

?>

@if (count($users) > 0)

	<div class="table-responsive" style="height: 150px; overflow-y: scroll;">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Nome</th>
					<th>Username</th>
					<th>Email</th>
					<th>Licenza (ID/codice)</th>
					<th>Sviluppatore</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($users as $user)

				<?php
	
					if ($user->company->company_status_id == 5){
						$status = 'Sospeso';
						$label = 'label-warning';
					}
					else if ($user->company->company_status_id == 3){
						$status = 'Revisione';
						$label = 'label-success';
					}
					else if ($user->company->company_status_id == 2){
						$status = 'Sviluppo';
						$label = 'label-info';
					}
					else if ($user->company->company_status_id == 1){
						$status = 'Attivo';
						$label = 'label-danger';
					}

				?>
				<tr id="{{ $user->id }}">
					<td>
						{{ $user->company->name }}
					</td>
					<td>
						{{ $user->user_name }}
					</td>
					<td>
						{{ $user->email }}
					</td>
					<td>
						@if (isset($user->company->activationCode->code))
							{{ $user->company->activationCode->id.' / '.substr($user->company->activationCode->code, 0, 4).'...'.substr($user->company->activationCode->code, -4) }}
						@endif
					</td>
					<td>
						<span class="label label-default">
							{{$user->company->companyData->developer ? $user->company->companyData->developer->name : 'NON ASSEGNATO'}}
						</span>
					</td>
					<td>
						<span class="label {{$label}}">
							{{$status}}
						</span>
					</td>
				</tr>
				@endforeach
	  		</tbody>
		</table>
	</div>

	<script type="text/javascript">

		$(document).ready(function() {
			var row = $('tr:eq(1)');
			$.ajax({ url: 'console/admin.clients.activating.details',
				data: {userId: row.attr('id')},
				type: 'get',
				success: function(output) {
					row.addClass('info');
					$('#details').html(output);
				},
				error: function(output) {
					if (output.status === 401)
						window.location.replace("{{url('/')}}");
				}
			});
		});

		$('tbody > tr').click(function() {
			$('tr').removeClass('info');
			var row = $(this);
			$.ajax({
				url: 'console/admin.clients.activating.details',
				data: {userId: row.attr('id')},
				type: 'get',
				success: function(output) {
					$('tr').removeClass('info');
					row.addClass('info');
					$('#details').html(output);
				},
				error: function(output) {
					if (output.status === 401)
						window.location.replace("{{url('/')}}");
				}
			});
		});
		
		$('tbody > tr').hover(function() {
	        $(this).css('cursor','pointer');
	    });
		
	</script>
@else
	<script type="text/javascript">
		$('#details').html('');
	</script>
	<div class="alert alert-info" role="alert">
		Nessuna procedura di attivazione in corso con i filtri specificati.
	</div>
@endif