<?php

	$users = User::
	where('role_id', 1)
	->whereHas('company', function ($query){
		$query->whereIn('company_status_id', array(5,3,2));
	})
	->whereHas('company.companyData', function ($query) {
		$query->where('developer_id', Auth::user()->id);
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
					<th>Email</th>
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
						{{ $user->email }}
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
			$.ajax({ url: 'console/developers.clients.activating.details',
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
				url: 'console/developers.clients.activating.details',
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