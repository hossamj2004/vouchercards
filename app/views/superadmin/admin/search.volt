<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
          <i class="fa fa-{{modelName}}"></i>
          Search {{modelNameText}}
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="    font-family: fantasy;"><span aria-hidden="true">&times;</span></button>
        </h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="login-form" style="padding:40px 10px">
                <form id="searchFrom" style="width:100%" method="post" action="{{ url(folderName~"/"~router.getControllerName()~"/index"~(current_params?"?":'')~current_params) }}" >
                    <div id="error_message" >{{ content() }}</div>
                    {% for element in this.forms.get('Form') %}
                        <div class="form-group" style="width:100%">
                            {% if element.getLabel() %}<label for="pwd">{{ element.getLabel() }} :</label> {% endif %}
                            {{ this.forms.get('Form').render(element.getName(),['class':'validate[required,minSize[3]]  form-control text-input input_txt']) }}
                        </div>
                    {% endfor %}
                </form>
            </div>
        </div>
    </div>
</div>
<script>/* in search add default value for each select input */
$('#searchFrom select').prepend('<option selected></option>');</script>
