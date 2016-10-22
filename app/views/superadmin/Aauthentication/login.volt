{% extends "layouts/clear.volt" %}
{% block title %}
    <title>Login</title>
{% endblock %}
{% block content %}
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        {{ content() }}
                        <form role="form"  method="post">
                            <fieldset>
                                <div class="form-group">
                                    {{ this.forms.get('LoginForm').render("email",['class':'validate[required,custom[email]] form-control text-input input_txt']) }}
                                </div>
                                <div class="form-group">
                                    {{ this.forms.get('LoginForm').render("password",['class':'validate[required,minSize[3]]  form-control text-input input_txt']) }}
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <a href="javascript:void(0)" onclick="$(this).closest('form').submit()" class="btn btn-lg btn-success btn-block">Login</a>
                            </fieldset>
                            <input name="csrf" type="hidden" value="{{ security.getToken() }}" >
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block script %}
    <script type="text/javascript">
        $(document).ready(function(){
            jQuery("#login").validationEngine('attach' ,{promptPosition : "topLeft"});
        });
    </script>
{% endblock %}