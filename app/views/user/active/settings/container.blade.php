<div class="panel panel-default">
	<div class="panel-heading">Aggiorna i tuoi dati</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12 col-md-4 col-md-offset-4" id="company_data">
				<!-- <div class="panel panel-default">
					<div class="panel-heading">Settaggi</div>
					<div class="panel-body">
					</div>
				</div> -->
						
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-4 col-md-offset-4" id="user_data">
				
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	$(document).ready(function() {
		$.ajax({ url: 'console/user.active.settings.company_settings',
			type: 'get',
			success: function(output) {
				$('#company_data').html(output);
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
		$.ajax({ url: 'console/user.active.settings.user_settings',
			type: 'get',
			success: function(output) {
				$('#user_data').html(output);
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
	});
</script>