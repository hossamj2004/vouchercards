{% extends "layouts/html.volt" %}
{% block title %}
    <title>{{modelNameText}}</title>
{% endblock %}
{% block content %}

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-{{modelName}}"></i>
            {{modelNameText}}
            <small>({{totalItems}})</small>
        </h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row {% if  !(orderEnabled > 0) %}  admindisablesort{% endif%}">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ partial("includes/top_links") }}
            </div>

            <!-- /.panel-heading -->
            <div class="panel-body">

                {% if search is defined %}
                    <a href="?search_status=close">
                        <div class="search-close">
                            <i class="fa fa-close"></i>
                        </div>
                    </a>
                {% endif %}

                <div class="dataTable_wrapper">
                    {{ content() }}
                    {% if  page.items|length > 0 %}
                        <table width="100%" class="table table-striped table-bordered table-hover dataTables "  id="dataTables-example" >
                            <thead>
                            <tr>
                                {% for item in fieldsInList %}
                                    <th> {{ item['key'] }}</th>
                                {% endfor %}
                                <th> Actions </th>
                            </tr>
                            </thead>
                            <tbody class="tr_now">
                            {% if page.items is defined %}
                                {% for resultObj in page.items %}
                                    <tr>
                                        {% for key,item in resultObj.getSpecialDataArray(fieldsInList)  %}
                                            <td>{{ item }}</td>
                                        {% endfor %}
                                        <td width="25%">{{ partial("includes/buttons") }}</td>

                                    </tr>
                                {% endfor %}
                            {% endif %}

                            </tbody>
                        </table>

                        {{ partial("includes/pager") }}

                    {% endif %}

                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- Page-Level Demo Scripts - Tables - Use for reference -->
{% endblock %}
