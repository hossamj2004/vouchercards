<table class="actions_table_new">
<tr>
{% if extraButtons['view'] AND  ( router.getActionName() != 'view' AND router.getActionName() != 'View_content') %}
    <td>
    {{ link_to(folderName~"/"~router.getControllerName()~"/View_content/"~resultObj.getFieldValue(modelPrimaryKey), "View",'class':'btn btn-info btn-outline', 'data-toggle':'modal', 'data-target':'#myModal', 'data-remote':'false') }}
    </td>
</div>
{% endif %}

{% if extraButtons['details']  is defined AND extraButtons['details'] AND  router.getActionName() != 'view' %}
    <td>{{ link_to(folderName~"/"~router.getControllerName()~"/view/"~resultObj.getFieldValue(modelPrimaryKey), "Details", 'class':'btn btn-info btn-outline') }}
    
    </td>
{% endif %}

{% if extraButtons['edit']  %}
    <td>{{ link_to(folderName~"/"~router.getControllerName()~"/edit/"~resultObj.getFieldValue(modelPrimaryKey), "Edit",'class':'btn btn-success btn-outline') }}</td>
{% endif %}

{% if extraButtons['delete'] %}
    <td>{{ link_to(folderName~"/"~router.getControllerName()~"/delete/"~resultObj.getFieldValue(modelPrimaryKey), "Delete", 'class':'btn btn-danger btn-outline', 'onclick':'return confirmDelete()') }}</td>
{% endif %}

{# custom buttons for job #}
{% if extraButtons['approve']  is defined AND extraButtons['approve'] %}
    {% if  extraButtons['approve']['appearOnCondition'] is empty
    OR ( resultObj.checkCondition(modelPrimaryKey, extraButtons['approve']['appearOnCondition'])   ) %}
    <td>{{ link_to(folderName~"/"~router.getControllerName()~"/"~extraButtons['approve']['action']~"/"~resultObj.getFieldValue(modelPrimaryKey), "Approve",'class':'btn btn-info btn-outline', 'data-toggle':'modal', 'data-target':'#plain-popup') }}</td>
    {% endif %}
{% endif %}

{% if extraButtons['assign']  is defined AND extraButtons['assign'] %}
    {% if  extraButtons['assign']['appearOnCondition'] is empty
    OR ( resultObj.checkCondition(modelPrimaryKey, extraButtons['assign']['appearOnCondition'])   ) %}
        <td>{{ link_to(folderName~"/"~router.getControllerName()~"/"~extraButtons['assign']['action']~"/"~resultObj.getFieldValue(modelPrimaryKey), "Assign",'class':'btn btn-info btn-outline', 'data-toggle':'modal', 'data-target':'#plain-popup') }}</td>
    {% endif %}
{% endif %}

{# end job #}

{% for key,button in extraButtons %}
    {% if  key !='view' AND key !='delete' AND key !='edit' AND key !='new' AND key !='details' AND key !='approve'AND key !='assign' %}
        {% if button['appearOnCondition'] is empty
        OR ( resultObj.checkCondition(modelPrimaryKey,button['appearOnCondition'])   ) %}
            <td>
                {% if button['action'] != '#' %}
                    {% if button['confirmation'] is defined and  button['confirmation'] == 1 %}
                        {{ link_to(folderName~"/"~router.getControllerName()~"/"~button['action']~"/"~resultObj.getFieldValue(modelPrimaryKey),button['text'], 'class':'btn btn-primary '~button['class'], 'onclick':'return confirmDelete()') }}
                    {% else  %}
                        {{ link_to(folderName~"/"~router.getControllerName()~"/"~button['action']~"/"~resultObj.getFieldValue(modelPrimaryKey),button['text'], 'class':'btn btn-primary '~button['class']) }}
                    {% endif %}
                {% else %}
                    <a href="#"  >
                        {{ button['text'] }}
                    </a>
                {% endif %}
            </td>
        {% endif %}
    {% endif %}
{% endfor %}
</tr>
</table>
