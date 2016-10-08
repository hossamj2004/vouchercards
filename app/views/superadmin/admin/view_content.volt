<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
      <i class="fa fa-{{modelName}}"></i>
			View {{modelNameText}}
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="    font-family: fantasy;"><span aria-hidden="true">&times;</span></button>
		</h1>
	</div>
	<!-- /.col-lg-12 -->
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="login-form" style="padding:40px 10px">
				{{ content() }}
				<table class="table table-hover">
					<tbody>
					{% for key,item in resultObj.getSpecialDataArray(fieldsInView)  %}
						<tr>
							<td>
								{{ key }}
							</td>
							<td id="{{ key }}_value">
								{{ item }}
							</td>
						</tr>
					{% endfor %}
					</tbody>
				</table>
				<table class="table table-hover">
					<tbody>
					<tr>
						{{ partial("includes/buttons") }}
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
