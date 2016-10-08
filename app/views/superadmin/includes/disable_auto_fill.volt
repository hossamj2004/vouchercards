<!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
<div style="visibility: hidden">
<input class="fake-autofill-fields" type="text" name="fakeusernameremembered"/>
<input class="fake-autofill-fields" type="password" name="fakepasswordremembered"/>
</div>
<script>
	//fast hack to disable auto complete on password
	$(".fake-autofill-fields").show();
	// some DOM manipulation/ajax here
	window.setTimeout(function () {
	$(".fake-autofill-fields").hide();
	},350);
</script>