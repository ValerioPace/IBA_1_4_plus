<?php
	$events = Evento::onlyTrashed()
	->whereHas('company', function ($query) use ($userId){
		$query->whereHas('user', function ($query) use ($userId){
			$query->where('id', $userId);
		});
	})
	->orderBy('updated_at', 'DISC')
	->get();
?>

@if (count($events) > 0)

	<div class="table-responsive" style="max-height: 150px; overflow-y: scroll;">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Data</th>
					<th>Titolo</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($events as $event)
				<tr id="{{ $event->id }}">
					<td>
						{{ date("d/m/Y", strtotime($event->activated_at)) }}
					</td>
					<td>
						{{ $event->title }}
					</td>
				</tr>
				@endforeach
	  		</tbody>
		</table>
	</div>
	<div style="padding-top: 50;">
		<div class="panel panel-default">
			<div class="panel-heading">Locandina</div>
			<div class="panel-body" style="min-height: 170; max-height: 170;">
				<div id="flyer" align="center" style="min-height: 140px;">
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		$(document).ready(function() {
			var row = $('tr:eq(1)');
			$.ajax({ url: 'console/user.active.events.expired.details',
				data: {event_id: row.attr('id')},
				type: 'get',
				success: function(output) {
					row.addClass('info');
					$('#expired_event_details').html(output);
					loadImageByEvent(row.attr('id'));
				},
				error: function(output) {
					if (output.status === 401)
						window.location.replace("{{url('/')}}");
				}
			});
		});

		$('tbody > tr').click(function() {
			$('tr').removeClass('active');
			var row = $(this);
			$("#flyer").html('');
			$.ajax({
				url: 'console/user.active.events.expired.details',
				data: {event_id: row.attr('id')},
				type: 'get',
				success: function(output) {
					$('tr').removeClass('info');
					row.addClass('info');
					$('#expired_event_details').html(output);
					loadImageByEvent(row.attr('id'));
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

      $(document).ready(function() {
        $.ajax({ url: 'console/user.active.events.expired.container',
           data: {page: 'deleted_events'},
           type: 'post',
           success: function(output) {
             $('#content').html(output);
            },
            error: function(output) {
            	if (output.status === 401)
            		window.location.replace("{{url('/')}}");
            }
        });
      });
    </script>
@endif