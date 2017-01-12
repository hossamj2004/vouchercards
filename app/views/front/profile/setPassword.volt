
    <!-- Start of Bootstrap files -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{url('new/bootstrap/css/bootstrap.min.css')}}">

    <!-- Optional theme -->
    <link rel="stylesheet" href="{{url('new/bootstrap/css/bootstrap-theme.min.css')}}">

    <!-- Latest compiled and minified JavaScript -->

    <!-- End of Bootstrap files -->

    <!-- Start of Font Awesome -->
    <link rel="stylesheet" href="{{url('new/font-awesome/css/font-awesome.min.css')}}">
    <!-- End of Font Awesome -->

    <link rel="stylesheet" href="{{url('new/css/stylesheet.css')}}">

{% block content %}

 	{{ content() }}
{%if showForm %}
    <section class='container'>
        <div class="col-lg-12">
            <div class="">
                <h4 >Please insert new password</h4>
                <form class="pure-form" method="POST" >
    <fieldset>
   

        <input type="password" placeholder="Password" name="password" id="password" required>
        <input type="password" placeholder="Confirm Password" id="confirm_password" required>
 <input type="hidden" name="token" value="{{token}}" required>
        <button type="submit" class="pure-button pure-button-primary">Confirm</button>
    </fieldset>
</form>
<script>
	var password = document.getElementById("password")
  , confirm_password = document.getElementById("confirm_password");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>
                <h4></h4>
            </div>
        </div>
    </section>
{% endif %}

{% endblock %}
