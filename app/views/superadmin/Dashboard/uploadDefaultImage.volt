{% extends "layouts/html.volt" %}
{% block title %}
<title>
	Upload default image
</title>
{% endblock %}
{% block content %}
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
      <i class="fa fa-"></i>
			Upload default image
		</h1>
	</div>
	<!-- /.col-lg-12 -->
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default pull-left col-lg-12 no-padding">

			<div  style="padding:40px 10px">
				<form method="post"  enctype="multipart/form-data" class="validateClientSide col-xs-12 col-sm-12 col-md-12 col-lg-12">
					{{ partial('includes/disable_auto_fill') }}
					<div >
						{{ content() }}
					</div>
					{% for element in this.forms.get('Form') %}
					<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6  container-{{ superAdminSystem.getClass(element) }} ">
						{% if element.getLabel() %}
						<label for="{{ element.getAttribute ('id') }}" class="control-label">
							{{ element.getLabel() }}
						</label>
						{% endif %}
						{{ this.forms.get('Form').render(element.getName(),['class':' form-control text-input input_txt ' ~  superAdminSystem.getValidationClass(element) ]) }}
					</div>
					{% endfor %}
					<input name="csrf" type="hidden" value="{{ security.getToken() }}" >
				</form>
			</div>
		</div>
	</div>
</div>
{% endblock %}
