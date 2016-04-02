<?php
	$events = Evento::whereHas('company', function ($query) use ($userId){
		$query->whereHas('user', function ($query) use ($userId){
			$query->where('id', $userId);
		});
	})
	->with('readers')
	->orderBy('updated_at', 'DISC')
	->get();

	/*$eventReaders = EventReader::where('event_id', $device->company_id)
	->whereHas('readers', function ($query) use ($device) {
		$query->where('device_id', $device->id);
	})
	->count();*/

    $androidDevices = Device::whereHas('company', function ($query) {
      $query->whereHas('user', function ($query) {
        $query->where('id', Auth::user()->id);
      });
    })
    ->where('platform_id', 1)
    ->count();

    $iosDevices = Device::whereHas('company', function ($query) {
      $query->whereHas('user', function ($query) {
        $query->where('id', Auth::user()->id);
      });
    })
    ->where('platform_id', 2)
    ->count();

    $totalDevices = $androidDevices + $iosDevices;
?>


@if (count($events) > 0)
            
<div class="panel panel-default">
	<div class="panel-heading">Statistiche</div>
	<div class="panel-body">
		
	
	<div class="row">
		<div class="col-xs-12 col-md-12">
			<p class="text-center"><a href="#" id="appCount" style="color: #3399f3; text-decoration: none;"  data-toggle="tooltip" data-placement="top" title="Il numero di utenti in possesso della tua applicazione, divisi per piattaforma.">Android <span class="badge">{{$androidDevices}}</span><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span><span class="badge">{{$iosDevices}}</span> iOS</a></p>
		</div>
		<div class="col-xs-12 col-md-offset-3 col-md-6">
			<!-- <div class="panel panel-default">
				<div class="panel-heading">Lettura eventi</div>
				<div class="panel-body" id="active_events_list"> -->
					<label for="inputDescription"><h3>Lettura eventi&nbsp;</h3></label><span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right" title="Per ogni evento è indicato il numero di dispositivi che lo hanno letto e il totale di quelli connessi al network." aria-hidden="true"></span>
					<div class="table-responsive" style="max-height: 300px; overflow-y: scroll;">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Data</th>
									<th>Titolo</th>
									<th>Lette/Totale</th>
								</tr>
					      	</thead>
					      	<tbody>
								@foreach ($events as $event)
								<tr id="{{ $event->id }}">
									<td class="col-md-3">
										{{ date("d/m/Y", strtotime($event->activated_at)) }}
									</td>
									<td class="col-md-7">
										{{ $event->title }}
									</td>
									<td class="col-md-2">
										<span class="badge">{{{$event->readers->count()}}}/{{{$totalDevices}}}</span>
									</td>
								</tr>
								@endforeach
					      	</tbody>
						</table>
					</div>
				<!-- </div>
			</div> -->
		</div>
	</div>

	</div>
</div>

@else
	<div class="row">
		<div class="col-xs-12 col-md-12">
			<p class="text-center"><a href="#" id="appCount" style="color: #3399f3; text-decoration: none;"  data-toggle="tooltip" data-placement="top" title="Il numero di utenti in possesso della tua applicazione, divisi per piattaforma.">Android <span class="badge">{{$androidDevices}}</span><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span><span class="badge">{{$iosDevices}}</span> iOS</a></p>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="alert alert-info" role="alert">
				Nessuna statistica disponibile...
			</div>
		</div>
	</div>

	<script type="text/javascript">

      /*$('#new_event_redirect').click(function() {
        $.ajax({ url: 'console/user.active.events.new_event',
           data: {page: 'new_event'},
           type: 'post',
           success: function(output) {
             $('#content').html(output);
            },
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
        });
        $(".nav").find(".active").removeClass("active");
        $('#eventDropdown').parent().addClass("active");
        $('#new_event').parent().addClass("active");
      });*/

    </script>
@endif

	<script type="text/javascript">

		$(function () {
			$('[data-toggle="tooltip"]').tooltip({
				delay: { "show": 700, "hide": 100 }
			});
		});

		$('#appCount').click(function() {
			$.ajax({ url: 'console/appCount/reload',
				data: {page: 'appCount'},
				type: 'post',
				success: function(output) {
					$('#appCount').html(output);
				},
				error: function(output) {
					if (output.status === 401)
						window.location.replace("{{url('/')}}");
				}
			});
		});

    </script>