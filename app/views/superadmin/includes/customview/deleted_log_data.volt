<table id="internalActivities{{item["id"]}}" class="table" style="table-layout:fixed;">
    {% for index , item in item["data"]|json_decode   %}
        <tr> <td>{{ index  }} </td> <td> {{ item  }} </td></tr>
    {% endfor  %}
</table>
{# fast solution for show more show list #}
<input type="button" id="seeMoreRecords{{item["id"]}}" value="More">
<input type="button" id="seeLessRecords{{item["id"]}}" value="Less">
<script>
    var trs{{item["id"]}} = $("#internalActivities{{item["id"]}} tr");
    var btnMore{{item["id"]}} = $("#seeMoreRecords{{item["id"]}}");
    var btnLess{{item["id"]}} = $("#seeLessRecords{{item["id"]}}");
    var trsLength{{item["id"]}} = trs{{item["id"]}}.length;
    var currentIndex{{item["id"]}} = 4;

    trs{{item["id"]}}.hide();
    trs{{item["id"]}}.slice(0, 4).show();
    checkButton{{item["id"]}}();

    btnMore{{item["id"]}}.click(function (e) {
        e.preventDefault();
        $("#internalActivities{{item["id"]}} tr").slice(currentIndex{{item["id"]}}, currentIndex{{item["id"]}} + trs{{item["id"]}}.length).show();
        currentIndex{{item["id"]}} = trs{{item["id"]}}.length;
        checkButton{{item["id"]}}();
    });

    btnLess{{item["id"]}}.click(function (e) {
        e.preventDefault();
        $("#internalActivities{{item["id"]}} tr").slice(currentIndex{{item["id"]}} - (trs{{item["id"]}}.length-4), currentIndex{{item["id"]}}).hide();
        currentIndex{{item["id"]}} = 4;
        checkButton{{item["id"]}}();
    });

    function checkButton{{item["id"]}}() {
        var currentLength = $("#internalActivities{{item["id"]}} tr:visible").length;

        if (currentLength >= trsLength{{item["id"]}}) {
            btnMore{{item["id"]}}.hide();
        } else {
            btnMore{{item["id"]}}.show();
        }

        if (trsLength{{item["id"]}} > 4 && currentLength > 4) {
            btnLess{{item["id"]}}.show();
        } else {
            btnLess{{item["id"]}}.hide();
        }

    }
</script>
{# end #}