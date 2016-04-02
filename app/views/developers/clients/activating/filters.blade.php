<form class="form-inline">
	<!-- <div class="form-group" style="width: 16%">
		{{ Form::button('Seleziona tutti', array('id' => 'filterToogle', 'class' => 'btn btn-warning')) }}
	</div> -->
	<div class="form-group" style="width: 16%">
	<label for="checkPending">In sospeso</label>
		{{ Form::checkbox('filters[]', 5, true, ['id' => 'checkPending']) }}
	</div>
	<div class="form-group" style="width: 16%">
	<label for="checkRevision">In revisione</label>
		{{ Form::checkbox('filters[]', 3, true, ['id' => 'checkRevision']) }}
	</div>
	<div class="form-group" style="width: 16%">
	<label for="checkDevelop">In sviluppo</label>
		{{ Form::checkbox('filters[]', 2, true, ['id' => 'checkDevelop']) }}
	</div>
	<div class="form-group" style="width: 16%">
		{{ Form::text('search', null, array('id' => 'searchInput', 'class'=>'form-control', 'placeholder'=>'Cerca')) }}
	</div>

	<button id="applyFiltersButton" type="button" class="btn btn-info" style="width: 16%">Applica</button>
</form>

<script type="text/javascript">

	$('#applyFiltersButton').on('click', function(){
		loadList();
	});

	$("#searchInput").keypress(function (e) {
		if (e.which == 13) {
			loadList();
			return false;
		}
	});

	function loadList (){
		var filters = { 'statusIds[]' : [], 'search' : $("#searchInput").val()};
		$(":checked").each(function() {
		  filters['statusIds[]'].push($(this).val());
		});

		$.ajax({ 
			url: 'console/admin.clients.activating.list',
			type: 'get',
			data: filters,
			success: function(output) {
				$('#userList').html(output);
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
	}

</script>