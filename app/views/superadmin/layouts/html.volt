<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		{% block title %}
		<title>
			super admin
		</title>
		{% endblock %}
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
		{{javascript_include('sb-admin/AreasTarget/js/bloodhound.js')}}
		{{javascript_include('sb-admin/AreasTarget/js/typeahead.bundle.min.js')}}
		<!-- Start of Bootstrap files -->
		<!-- Latest compiled and minified CSS -->
		{{stylesheet_link('sb-admin/bootstrap/css/bootstrap.css')}}
		<!-- Optional theme -->
		{{stylesheet_link('sb-admin/bootstrap/css/bootstrap-theme.min.css')}}
		<!-- End of Bootstrap files -->
		{{stylesheet_link('sb-admin/AreasTarget/css/bootstrap-tagsinput.css')}}
		{{stylesheet_link('sb-admin/AreasTarget/css/app.css')}}

		{{stylesheet_link('sb-admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css')}}
		{{stylesheet_link('sb-admin/bower_components/datatables-responsive/css/dataTables.responsive.css')}}
		<!-- Custom CSS -->
		{{stylesheet_link('sb-admin/dist/css/sb-admin-2.css')}}
		{{stylesheet_link('library/checkboxes-multicheck/jquery.multicheckbox.css')}}
		{{stylesheet_link('sb-admin/css/jquery.mCustomScrollbar.css')}}
		{{stylesheet_link('sb-admin/css/animate.css')}}
		{{stylesheet_link('library/select2/select2.css')}}
		{{stylesheet_link('sb-admin/css/naala_admin.css')}}



	</head>
	<body  class="full-page">
		<div id="wrapper" class="no-padding col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<!-- /.navbar-top-links -->
			<div class="navbar-default sidebar pull-left" role="navigation" style="position: relative;margin:0px;">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
						{{ partial("includes/side_menu") }}
					</ul>
				</div>
				<!-- /.sidebar-collapse -->
			</div>
			<!-- /.navbar-static-side -->

			<div id="page-wrapper" >
			<!-- Navigation -->
			<div class="row">
			<nav class="navbar navbar-default navbar-static-top row" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header pull-right">
					<a class="navbar-brand logo_new" href="{{ url( superAdminSystem.getHomeLink() ) }}">
						<p class="pull-right">
           				  Admin
           				</p>
					</a>
				</div>
				<!-- /.navbar-header -->


			</nav>
</div>
				{% block content %}
				{% endblock %}
			</div>
			<!-- /#page-wrapper -->
		</div>
		<script>
			function confirmDelete()
			{
				return (confirm('Are you sure ?')) ? true : false;
			}
			(function($)
				{
					$.fn.clickToggle = function(func1, func2)
					{
						var funcs = [func1, func2];
						this.data('toggleclicked', 0);
						this.click(function()
							{
								var data = $(this).data();
								var tc = data.toggleclicked;
								$.proxy(funcs[tc], this)();
								data.toggleclicked = (tc + 1) % 2;
							});
						return this;
					};
				}(jQuery));
			$('#sidebarToggle').clickToggle(function()
				{
					$('.sidebar').animate(
						{
							'margin-left':"-=250px"
						}, 600, function()
						{
							// Animation complete.
						});
					$('#page-wrapper').animate(
						{
							'margin-left':"-=250px"
						}, 600, function()
						{
							// Animation complete.
						});
				}, function()
				{
					$('.sidebar').animate(
						{
							'margin-left':"+=250px"
						}, 600, function()
						{
							// Animation complete.
						});
					$('#page-wrapper').animate(
						{
							'margin-left':"+=250px"
						}, 600, function()
						{
							// Animation complete.
						});
				});


		</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog mCustomScrollbar" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View</h4>
      </div>
      <div class="modal-body">
          <img  class="margin-center" src="{{url('img/483.gif')}}"/>
      </div>
    </div>
  </div>
</div>
		<div class="modal fade" id="plain-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog mCustomScrollbar" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<img  class="margin-center" src="{{url('img/483.gif')}}"/>
					</div>
				</div>
			</div>
		</div>
		<!-- Morris Charts JavaScript -->
		{{javascript_include('sb-admin/bower_components/metisMenu/dist/metisMenu.min.js')}}
		{{javascript_include('sb-admin/bower_components/bootstrap/dist/js/bootstrap.min.js')}}
		<!-- Custom Theme JavaScript -->
		{{javascript_include('sb-admin/bower_components/datatables/media/js/jquery.dataTables.min.js')}}
		{{javascript_include('sb-admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js')}}
		{{javascript_include('sb-admin/bower_components/datatables-responsive/js/dataTables.responsive.js')}}
		{{javascript_include('sb-admin/dist/js/sb-admin-2.js')}}
		{{javascript_include('sb-admin/js/jquery.datetimepicker.js')}}
		{{javascript_include('sb-admin/js/jquery.validationEngine-en.js')}}
		{{javascript_include('sb-admin/js/jquery.validationEngine.js')}}
		{{javascript_include('sb-admin/js/customValidation.js')}}
		{{javascript_include('library/checkboxes-multicheck/jquery.multicheck.js')}}
		{{javascript_include('sb-admin/js/jquery.mCustomScrollbar.concat.min.js')}}
		{{javascript_include('sb-admin/js/classie.js')}}
		{{javascript_include('sb-admin/js/custom_naala_js_new.js')}}


		{{javascript_include('sb-admin/bower_components/raphael/raphael-min.js')}}
		{{javascript_include('sb-admin/bower_components/morrisjs/morris.min.js')}}

		{{javascript_include('library/select2/select2.min.js')}}

			<script>

        // date time picker
        $(".form_datetime").datetimepicker({format:  'Y-m-d h:m:s'});

				$(document).on('click', '#close-preview', function(){
    $('.image-preview').popover('hide');
    // Hover befor close the preview
    $('.image-preview').hover(
        function () {
           $('.image-preview').popover('show');
        },
         function () {
           $('.image-preview').popover('hide');
        }
    );
});

$(function() {
	$('.select2').select2();

    // Create the close button
    var closebtn = $('<button/>', {
        type:"button",
        text: 'x',
        id: 'close-preview',
        style: 'font-size: initial;',
    });
    closebtn.attr("class","close pull-right");
    // Set the popover default content
    $('.image-preview').popover({
        trigger:'manual',
        html:true,
        title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
        content: "There's no image",
        placement:'bottom'
    });
    // Clear event
    $('.image-preview-clear').click(function(){
        $('.image-preview').attr("data-content","").popover('hide');
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text("Browse");
    });
    // Create the preview image
    $(".image-preview-input input:file").change(function (){
        var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        });
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title").text("Change");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);
            img.attr('src', e.target.result);
            $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
        }
        reader.readAsDataURL(file);
    });
});
			</script>

<script>

	var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width())
    });
    return $helper;
},
    updateIndex = function(e, ui) {
        $('td.index', ui.item.parent()).each(function (i) {
            $(this).html(i + 1);
        });
    };

	$(document).ready(function(){
		// date time picker
		$(".datetime").datetimepicker({format: 'Y-m-d h:m:s'});
		//password removal
		$('#password_password').val('');
	});

</script>
		{% block scripts %}
		{% endblock %}
	</body>
</html>
