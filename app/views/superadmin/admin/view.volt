{% extends "layouts/html.volt" %}
{% block title %}
	<title>View {{ modelNameText }}</title>
{% endblock %}
{% block content %}
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header" >
      <i class="fa fa-{{modelName}}"></i>
			View {{modelNameText}}</h1>
	</div>
	<!-- /.col-lg-12 -->
</div>
<div class="row" id="main-content">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				{{ partial("includes/top_links") }}
			</div>
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
{% endblock %}
