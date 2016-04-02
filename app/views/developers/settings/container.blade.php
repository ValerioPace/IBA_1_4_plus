<div class="panel panel-default">
	<div class="panel-heading">Dati Utente</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12 col-md-4 col-md-offset-4" id="user_settings">
				
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	$(document).ready(function() {
		
		$.ajax({ url: 'console/developers.settings.user_settings',
			type: 'get',
			success: function(output) {
				$('#user_settings').html(output);
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
	});
</script>