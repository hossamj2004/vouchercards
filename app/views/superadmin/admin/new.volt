{% extends "layouts/html.volt" %}
{% block title %}
<title>
	Create {{ modelNameText }}
</title>
{% endblock %}
{% block content %}
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
      <i class="fa fa-{{modelName}}"></i>
			Create {{modelNameText}}
		</h1>
	</div>
	<!-- /.col-lg-12 -->
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				{{ partial("includes/top_links") }}
			</div>
			<div  style="padding:40px 10px">
				<form method="post"  enctype="multipart/form-data" style="width: 100%">
					{{ partial('includes/disable_auto_fill') }}
					<div >
						{{ content() }}
					</div>
					{% for element in this.forms.get('Form') %}
					<div class="form-group" style="width:100%">
						{% if element.getLabel() %}
						<label for="pwd">
							{{ element.getLabel() }} :
						</label> {% endif %}
						{{ this.forms.get('Form').render(element.getName(),['class':'validate[required] form-control text-input input_txt']) }}
					</div>
					{% endfor %}
					<input name="csrf" type="hidden" value="{{ security.getToken() }}" >

				</form>
			</div>
		</div>
	</div>
</div>
{% endblock %}
