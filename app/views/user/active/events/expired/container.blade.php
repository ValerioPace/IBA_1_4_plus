<?php
	$events = Evento::onlyTrashed()
	->whereHas('company', function ($query) use ($userId){
		$query->whereHas('user', function ($query) use ($userId){
			$query->where('id', $userId);
		});
	})
	->count();
?>

@if ($events > 0)


	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">Tutti gli eventi scaduti/eliminati</div>
				<div class="panel-body" id="expired_events_list">  <!-- style="min-height: 500; max-height: 500; overflow-y: scroll;" -->
					
				</div>
			</div>
		</div>

		<div class="col-xs-12 col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">Dettaglio evento</div>
				<div class="panel-body" id="expired_event_details">
		
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript" src="dist/js/moment.js"></script>
	<script type="text/javascript" src="dist/js/transition.js"></script>
	<script type="text/javascript" src="dist/js/collapse.js"></script>
	<script type="text/javascript" src="dist/js/bootstrap-datetimepicker.js"></script>
	<link rel="stylesheet" href="dist/css/bootstrap-datetimepicker.css" />

	<script type="text/javascript">	

		$(document).ready(function() {
			$.ajax({ url: 'console/user.active.events.expired.list',
				type: 'get',
				success: function(output) {
					$('#expired_events_list').html(output);
				},
				error: function(output) {
					if (output.status === 401)
						window.location.replace("{{url('/')}}");
				}
			});
		});

		function loadImageByEvent (id){
			var img = $("<img />").attr({'src': '{{url('/')}}/event_image/thumbnail/by_event/'+id+'?'+(new Date).getTime(), 'class': 'img-rounded'}).load(function() {
		        if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
		            alert('Errore nel caricamento dell\'immagine, contattare l\'amministratore.');
		        } else {
		            $("#flyer").html(img);
		        }
		    });

		}

		function loadImageByTag (tag){
			var img = $("<img />").attr({'src': '{{url('/')}}/event_image/thumbnail/by_tag/'+tag+'?'+(new Date).getTime(), 'class': 'img-rounded'}).load(function() {
		        if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
		            alert('Errore nel caricamento dell\'immagine, contattare l\'amministratore.');
		        } else {
		            $("#flyer").html(img);
		        }
		    });

		}
	</script>
@else
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="alert alert-info" role="alert">
				Non sono presenti eventi scaduti o eliminati...
			</div>
		</div>
	</div>
@endif