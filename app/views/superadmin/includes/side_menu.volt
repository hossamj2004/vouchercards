<!-- User ID-->
<li>
	<!-- SIDEBAR USERPIC -->
	<div >
		<img style="    padding: 71px 33px;" src="{{ url('img/logo.png') }}">
	</div>
	<!-- END SIDEBAR USERPIC -->
	<!-- SIDEBAR USER TITLE -->
	<div class="profile-usertitle">

		<div class="profile-usertitle-job">
			Admin
		</div>
	</div>
	<!-- END SIDEBAR USER TITLE -->
	<!-- SIDEBAR BUTTONS -->
	<!-- END SIDEBAR BUTTONS -->
</li>
<!-- /User ID-->
<!-- Side Menu -->

<!-- get the parent menu items --->
{% for parentItem in parentMenuItems %}

<li>
	<a
		href="{{url("superadmin/"~parentItem.link)}}">
		<i class="fa fa-{{ parentItem.link}} ">
		</i>
		{{ parentItem.name}}
		{% if parentItem.getSubLinks().count() > 0 %}
		<span class="fa arrow fa-{{ parentItem.link }}">
		</span>
		{% endif %}
	</a>
	{% if parentItem.getSubLinks().count() > 0 %}
	<ul class="nav nav-second-level">
		{% for sublink in parentItem.getSubLinks() %}
		<li>
			<a
				href="{{url("superadmin/"~sublink.link)}}">
				<i class="fa">
				</i>{{ sublink.name }}
			</a>
		</li>
		{% endfor %}
	</ul>
	{% endif %}
</li>
{% endfor %}