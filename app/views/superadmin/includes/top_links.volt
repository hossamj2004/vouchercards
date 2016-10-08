<table width="100%">
	<tr>
		{% if router.getActionName() == 'index' OR router.getActionName() == ''   %}
		{% if  fieldsInSearch|length >0 %}
		<td class="pop_up_adv_search">
			<!-- Button trigger modal -->
			{{ link_to(folderName~"/"~router.getControllerName()~"/search"~(current_params?"?":'')~current_params, "Advanced Search", 'class':'btn btn-primary', 'data-toggle':'modal', 'data-target':'#myModal1') }}
			<!-- Modal -->
			<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog mCustomScrollbar" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">
									&times;
								</span>
							</button>
							<h4 class="modal-title" id="myModalLabel">
								Search
							</h4>
						</div>
						<div class="modal-body">
							<img class="margin-center" src="{{url('img/483.gif')}}" />
						</div>
					</div>
				</div>
			</div>
		</td>
		{% endif %}
		{% else %}
		<td>
			{{ link_to(folderName~"/"~router.getControllerName()~(current_params?"?":'')~current_params, "Go back", 'class':'btn btn-primary') }}
		</td>
		{% endif %}
		{% if router.getActionName() != 'new' and extraButtons['new'] is defined and extraButtons['new'] == 1 %}
		<td align="right">
			{{ link_to(folderName~"/"~router.getControllerName()~"/new"~(current_params?"?":'')~current_params, "Create " ~ modelName , 'class':'btn btn-success') }}
		</td>
		{% endif %}
	</tr>
</table>
