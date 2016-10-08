{% if superAdminSystem.userType != 'visitor' %}

 <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-tasks fa-fw"></i> <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu">
            <li><a href="#"><i class="fa fa-user"></i>
                    {% if  superAdminSystem.userType == 'admin' %}
                        (Admin)
                    {% elseif superAdminSystem.admin  %}
                        {{ superAdminSystem.admin.first_name  }}
                    {% endif %}
                </a>
            </li>
            <li class="divider"></li>
            <li>
                {{ link_to("superadmin/Authentication/logout", "<i class='fa fa-sign-out fa-fw'></i> Logout") }}
            </li>
        </ul>
    </li>
    
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        {% for notification in superAdminSystem.admin.getNotificationWebAdmin(['is_read = 0','limit':5])  %}
                        <li>
                            <a href="{{ url('superadmin/NotificationWebAdmin/view/') }}{{ notification.id }}" >
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>{{  notification.subject }}
                                    <span class="pull-right text-muted small">{{ notification.createdSince()  }}</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        {% else %}
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i>  No new notifications available
                                    <span class="pull-right text-muted small"></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        {% endfor %}
                        <li>
                            <a class="text-center" href="{{ url('superadmin/NotificationWebAdmin') }}">
                                <strong> See All Alerts </strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
{% endif %}
