<?php 

$batches = ActivationCodeBatch::all();

$batchList[0] = 'Crea Nuovo';
$batchList['delimiter'] = '--------';
$lastBatch = 0;
foreach ($batches as $key => $batch) {
	if (!$batch->name)
		$batchList[$batch->id] = $batch->id.') ';
	else if (strlen($batch->name) > 15)
		$batchList[$batch->id] = $batch->id.') '.substr($batch->name, 0, 15).'...';
	else
		$batchList[$batch->id] = $batch->id.') '.$batch->name;
	
	$lastBatch = $batch->id;
}

?>

<form class="form">
	<div class="row">
		<div class="col-xs-1">
			<label for="checkPending">Lotto:&nbsp;&nbsp;</label>
		</div>
		<div class="col-xs-3">
			<div class="form-group">
				{{ Form::select('batch', $batchList, $lastBatch, array('id' => 'batchSelector', 'class'=>'form-control'))}}
			</div>
		</div>
		<div class="col-xs-3 col-xs-offset-1">
			<div class="form-group">
		{{ Form::text('batch_name', null, array('id' => 'batchNameInput', 'class'=>'form-control', 'placeholder'=>'Nome del lotto')) }}
	</div>
		</div>
		<div class="col-xs-2">
			<div class="form-group">
		{{ Form::text('codes_amount', null, array('id' => 'codesAmountInput', 'class'=>'form-control', 'placeholder'=>'Quantità')) }}
	</div>
		</div>
		<div class="col-xs-2">
			<div class="form-group">
		<button id="createNewCodesButton" type="button" class="btn btn-info" data-loading-text="Generazione..." >Genera</button>
	</div>
		</div>
	</div>
	
	<!-- <div id="batchNameBadge" class="form-group" hidden>
		<h4><span class="label label-warning"></span></h4>
	</div> -->
	
	
	
</form>

<script type="text/javascript">

	$(document).ready(function() {
		var batch_id = $('#batchSelector').val();

		if (batch_id != 0) {
			$('#batchNameInput, #codesAmountInput, #createNewCodesButton').hide();
			
			//getBatchName(batch_id);

			loadList(batch_id);
		}
	});

	$('#batchSelector').change(function(){

		batch_id = $(this).val();
		if (batch_id === '0') {
			$('#list').html('');
			//$('#batchNameBadge').hide();
			$('#batchNameInput, #codesAmountInput, #createNewCodesButton').show();
		} else {
			$('#batchNameInput, #codesAmountInput, #createNewCodesButton').hide();
			
			//getBatchName(batch_id);
			
			if (batch_id === 'delimiter')
				$('#list').html('');
			else
				loadList(batch_id);
		}
	});

	$('#createNewCodesButton').on('click', function(){
		$(this).button('loading');

		var name = $("#batchNameInput").val();
		var amount = $("#codesAmountInput").val();

		createNewCodes(name, amount);
	});

	function loadList (batch_id){
		
		$.ajax({ 
			url: 'console/admin.activation_codes.list',
			type: 'get',
			data: {'batch_id' : batch_id},
			success: function(output) {
				$('#list').html(output);
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
	}

	function createNewCodes (name, amount){

		$.ajax({ 
			url: 'admin/activationCodes/new',
			type: 'post',
			data: { 'name' : name, 'amount' : amount},
			success: function(output) {
				$('#content').html(output);
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
	}

	/*function getBatchName (batch_id) {
		$.ajax({ 
			url: 'admin/activationCodes/getBatchName',
			type: 'get',
			data: { 'batch_id' : batch_id},
			success: function(output) {
				if (output){
					$('#batchNameBadge').children().children().text(output);
					$('#batchNameBadge').show();
				} else {
					$('#batchNameBadge').hide();
				}
			},
			error: function(output) {
				if (output.status === 401)
					window.location.replace("{{url('/')}}");
			}
		});
	}*/

</script>