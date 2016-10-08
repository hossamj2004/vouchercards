<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    {% block title %}
        <title>    super admin  </title>
    {% endblock %}
    <!-- old css
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <style>
        table td {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
        }
    </style>-->
    <!-- Bootstrap Core CSS -->


    {#       {{stylesheet_link('sb-admin/bower_components/bootstrap/dist/css/bootstrap.min.css')}}#}

    <!-- MetisMenu CSS -->
    {{stylesheet_link('sb-admin/bower_components/metisMenu/dist/metisMenu.min.css')}}

    <!-- Timeline CSS -->
    {{stylesheet_link('sb-admin/dist/css/timeline.css')}}



    <!-- Morris Charts CSS -->
    {{stylesheet_link('sb-admin/bower_components/morrisjs/morris.css')}}

    {{stylesheet_link('sb-admin/css/validationEngine.jquery.css')}}

    <!-- Custom Fonts -->
    {{stylesheet_link('sb-admin/bower_components/font-awesome/css/font-awesome.min.css')}}
    {{stylesheet_link('sb-admin/css/jquery.datetimepicker.css')}}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js" >
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js" >
    <![endif]-->
    <!-- jQuery -->
    {{javascript_include('sb-admin/bower_components/jquery/dist/jquery.min.js')}}

    <!-- Bootstrap Core JavaScript -->
    {#        {{javascript_include('sb-admin/bower_components/bootstrap/dist/js/bootstrap.min.js')}}#}

    <!-- Start of Bootstrap files -->
    <!-- Latest compiled and minified CSS -->
    {{stylesheet_link('sb-admin/bootstrap/css/bootstrap.css')}}
    <!-- Optional theme -->
    {{stylesheet_link('sb-admin/bootstrap/css/bootstrap-theme.min.css')}}
    <!-- End of Bootstrap files -->
    {{stylesheet_link('sb-admin/AreasTarget/css/bootstrap-tagsinput.css')}}
    {{stylesheet_link('sb-admin/AreasTarget/css/app.css')}}
    <!-- Custom CSS -->
    {{stylesheet_link('sb-admin/dist/css/sb-admin-2.css')}}
</head>
<body>
<div id="wrapper">
        {% block content %}
        {% endblock %}
</div>
<script>
    function confirmDelete() {
        return (confirm('Are you sure ?')) ? true : false;
    }
    (function($) {
        $.fn.clickToggle = function(func1, func2) {
            var funcs = [func1, func2];
            this.data('toggleclicked', 0);
            this.click(function() {
                var data = $(this).data();
                var tc = data.toggleclicked;
                $.proxy(funcs[tc], this)();
                data.toggleclicked = (tc + 1) % 2;
            });
            return this;
        };
    }(jQuery));
    $('#sidebarToggle').clickToggle(function(){
        $('.sidebar').animate({'margin-left':"-=250px"}, 600, function() {
            // Animation complete.
        });
        $('#page-wrapper').animate({'margin-left':"-=250px"}, 600, function() {
            // Animation complete.
        });
    }, function() {
        $('.sidebar').animate({'margin-left':"+=250px"}, 600, function() {
            // Animation complete.
        });
        $('#page-wrapper').animate({'margin-left':"+=250px"}, 600, function() {
            // Animation complete.
        });
    });    </script>

<!-- Morris Charts JavaScript -->
{{javascript_include('sb-admin/bower_components/raphael/raphael-min.js')}}
{{javascript_include('sb-admin/bower_components/metisMenu/dist/metisMenu.min.js')}}
{{javascript_include('sb-admin/bower_components/bootstrap/dist/js/bootstrap.min.js')}}
<!-- Custom Theme JavaScript -->
{{javascript_include('sb-admin/dist/js/sb-admin-2.js')}}
{{javascript_include('sb-admin/js/jquery.datetimepicker.js')}}
{{javascript_include('sb-admin/js/jquery.validationEngine-en.js')}}
{{javascript_include('sb-admin/js/jquery.validationEngine.js')}}
{{javascript_include('sb-admin/js/customValidation.js')}}
<script>

    jQuery('input[data-type=dateTime]').datetimepicker({format: 'Y-m-d h:m:s', "setDate": new Date()});
    jQuery('input[data-type=date]').datetimepicker({timepicker:false, format: 'Y-m-d', "setDate": new Date()  });
    $(document).ready(function(){
        var today = new Date();
        var tomorrow = new Date(today.getTime() + 24 * 60 * 60 * 1000);

        $("#end_datetime_txt").datetimepicker({format: 'Y-m-d H:m:s', minDate : tomorrow , minDateTime: tomorrow});
        $("#start_datetime_txt").datetimepicker({format: 'Y-m-d H:m:s', minDate : new Date() ,minDateTime: new Date(),
            onSelect : function( selectedDate ) {
                alert('ksksk');
            }} );
    });
    // #end_date is the ID of end_date input text field
    {#                                        var nextdate = new Date(selectedDate);
                                            nextdate.setDate(nextdate.getDate()+ 1);
                                            var newdate=convertDate(nextdate);
                                            $("#end_datetime_txt").datetimepicker( "option", "minDate", nextdate );
                                            $("#end_datetime_txt").datetimepicker( "option", "minDateTime", nextdate );
                                            $("#end_datetime_txt").val(newdate);#}
    /**
     * handle radio button
     */
    $('input:radio[name*="ToHidden"]').change( function(){
        var hiddenFieldName =   $(this).attr('name').replace("ToHidden[]",'');
        $('input[name='+hiddenFieldName+']').val($(this).val());
    });
    if($('input:radio[name*="ToHidden"]').length > 0) {
        var hiddenFieldName = $('input:radio[name*="ToHidden"]').attr('name').replace("ToHidden[]", '');
        if ($('input[name=' + hiddenFieldName + ']').val() != '')
            $('input:radio[name*="ToHidden"][value=' + $('input[name=' + hiddenFieldName + ']').val() + ']').prop('checked', true);
        else
            $('input[name=' + hiddenFieldName + ']').val($('input:radio[name*="ToHidden"][checked=checked]'));
    }



</script>
{% block scripts %}
{% endblock %}
</body>
</html>