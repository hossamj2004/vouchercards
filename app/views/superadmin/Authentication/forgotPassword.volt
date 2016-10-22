{% extends "layouts/html.volt" %}
{% block title %}
    <title>Login</title>
{% endblock %}
{% block content %}
    <section class="container">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 ">
                <h3><span>Forgot Password</span></h3>
                {{ content() }}
                {%  if showForm  %}
                <form class="" method="post" id="login">
                    {{ this.forms.get('ForgotPasswordForm').render("email",['class':'validate[required,custom[email]] text-input input_txt','placeholder':'Email', 'onblur':'this.placeholder = "Email"']) }}
                    <input name="csrf" type="hidden" value="{{ security.getToken() }}" >
                    <div class="centered-content">
                        <input type="submit" value="Submit" class="btn btn-info">
                    </div>
                </form>
                {% else  %}
                      <input name="csrf" type="hidden" value="{{ security.getToken() }}" > {#disable resubmit#}
                {% endif %}
            </div>
            <div class="col-lg-3"></div>
        </section>
{% endblock %}
{% block script %}
    <script type="text/javascript">
        $(document).ready(function(){
            jQuery("#login").validationEngine('attach' ,{promptPosition : "topLeft"});
        });
    </script>
{% endblock %}