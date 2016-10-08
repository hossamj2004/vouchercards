{% if page.limit < totalItems %}

  <div class="pagination">
      <ul>
          <li class="previous">{{ link_to(folderName~"/"~router.getControllerName()~""~(current_params?"?":'')~current_params, "First" ) }}</li>
          <li class="previous">{{ link_to(folderName~"/"~router.getControllerName()~"?page="~page.before~(current_params?"&":'')~current_params, "Previous") }}</li>

          {% if page.total_pages < 5 %}
            {% for i in 1..page.total_pages %}
              <li {% if i==page.current %} class="active" {% endif %} >
                {{ link_to(folderName~"/"~router.getControllerName()~"?page="~i~(current_params?"&":'')~current_params, i) }}
              </li>
            {% endfor %}
          {% else %}
            {% for i in page.current-3..page.current+3 %}
              {% if i > 0 %}
                {% if i <= page.total_pages %}
                  <li {% if i==page.current %} class="active" {% endif %} >
                    {{ link_to(folderName~"/"~router.getControllerName()~"?page="~i~(current_params?"&":'')~current_params, i) }}
                  </li>
                {% endif%}
              {% endif%}
            {% endfor %}
          {% endif %}

          <li class="next">{{ link_to(folderName~"/"~router.getControllerName()~"?page="~page.last~(current_params?"&":'')~current_params, "Last") }}</li>
          <li class="next">{{ link_to(folderName~"/"~router.getControllerName()~"?page="~page.next~(current_params?"&":'')~current_params, "Next") }}</li>
      </ul>
  </div>

{% endif %}

<div id="filter_select_holder">
	<p>Show</p>
	<select id="filter_select" onchange="MM_jumpMenu('parent',this,0)">

    <option value="{{ router.getControllerName()~"?Limit=20"~(current_params?"&":'')~current_params }}" {% if page.limit == 20 %} selected {% endif %} >
      20
    </option>
    <option value="{{ router.getControllerName()~"?Limit=50"~(current_params?"&":'')~current_params }}" {% if page.limit == 50 %} selected {% endif %} >
      50
    </option>
    <option value="{{ router.getControllerName()~"?Limit=100"~(current_params?"&":'')~current_params }}" {% if page.limit == 100 %} selected {% endif %} >
      100
    </option>
    <option value="{{ router.getControllerName()~"?Limit=150"~(current_params?"&":'')~current_params }}" {% if page.limit == 150 %} selected {% endif %} >
      150
    </option>
    <option value="{{ router.getControllerName()~"?Limit=200"~(current_params?"&":'')~current_params }}" {% if page.limit == 200 %} selected {% endif %} >
      200
    </option>

	</select>
	<p>entries</p>
</div>
