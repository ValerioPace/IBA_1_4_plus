<?php

	$codes = [];
	
	if (Input::has('batch_id')){
		$batch = ActivationCodeBatch::with('activationCodes')->find(Input::get('batch_id'));
	}

?>

@if (count($batch) > 0)


    <!-- Modal -->
    <div class="modal fade" id="activationCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edita licenza</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        
                        <label for="statusSelector">Imposta un nuovo stato </label>
                        {{ Form::select('statusSelector', ['null' => ''], null, array('id' => 'statusSelector', 'class'=>'form-control')) }}
	                    
                    </div>

                    <div class="form-group" id="codeCustomerName" hidden>
                        {{ Form::text('codeCustomerName', null, array('class'=>'form-control','id'=>'inputCodeCustomerName', 'placeholder'=>'Nominativo')) }}                                
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                    <button id="confirmStateChangeButton" type="button" class="btn btn-primary">Conferma</button>
                </div>
            </div>
        </div>
    </div>

	<div class="table-responsive" style="height: 400px; overflow-y: scroll;">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>N°</th>
					<th>Licenza</th>
					<th>Consegnata a</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($batch->activationCodes as $code)

				<?php

					switch ($code->status_id) {
						case 1:
							$status = 'Disponibile';
							$label = 'label-success';
							break;
						
						case 2:
							$status = '&nbsp;Assegnato';
							$label = 'label-danger';
							break;
						
						case 3:
							$status = '&nbsp;&nbsp;&nbsp;&nbsp;In uso&nbsp;&nbsp;&nbsp;&nbsp;';
							$label = 'label-danger';
							break;
						
						case 4:
							$status = '&nbsp;Annullato&nbsp;';
							$label = 'label-default';
							break;
						
						default:
							# code...
							break;
					}

				?>

				<tr id="{{ $code->id }}" data-batchId="{{ $code->batch_id }}" data-status="{{ $code->status_id }}" data-customerName="{{ $code->customer_name }}">
					<td>
						{{ $code->id }}
					</td>
					<td>
						{{ $code->code }}
					</td>
					<td>
						{{ $code->customer_name }}
					</td>
					<td>
						<span class="label {{$label}}">
							{{ $status }}
						</span>
					</td>
				</tr>
				@endforeach
	  		</tbody>
		</table>
	</div>

	<script type="text/javascript">

		$(document).ready(function() {
			
		});

		$('tbody > tr > td:nth-child(4)').click(function() {
			
			var row = $(this).parent();
			
			select = $('#statusSelector');
			    
			select.find('option').remove();

			customerName = $('#codeCustomerName');

			if (row.data('status') == 1){
				select.append('<option value="2">Assegna</option>').val('2');
				select.append('<option value="4">Annulla</option>').val('4');
				select.val('2');

				customerName.show();
			} else if (row.data('status') == 2) {
				select.append('<option value="4">Annulla</option>').val('4');
				select.val('4');
				
				customerName.hide();
			} else if (row.data('status') == 3) {
				return false;
			} else if (row.data('status') == 4) {
				return false;
			} else
				return false;


	        $('#activationCodeModal').modal('toggle');


	        $('#confirmStateChangeButton').on('click', function() {

	            $('#activationCodeModal').modal('hide');

	            $.ajax({
	            	url: 'admin/activationCodes/setStatus',
	                data: {
                		status: select.val(),
                		code_id: row.attr('id'),
                		customer_name: customerName.children(":first").val(),
	                },
	                type: 'post',
	                success: function(batch_id) {
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
	                },
	                error: function(output) {
	                    if (output.status === 401)
	                        window.location.replace("{{url('/')}}");
	                }
	            });
	            return false;
	        });
		});
		
		$('tbody > tr > td:nth-child(4)').hover(function() {
	        $(this).css('cursor','pointer');
	    });

	    $('#statusSelector').change(function(){

			status = $(this).val();
				
			customerName = $('#codeCustomerName');

			if (status === '2' ) {
				customerName.children(":first").val($(this).data('customerName'));
				customerName.show();
			} else {
				customerName.hide();
			}
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