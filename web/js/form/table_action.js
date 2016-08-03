function ajaxDelete(url)
{
    $.ajax({
        type: "GET",
        url: url,
        success: function(response) {
            document.getElementById(("ajax_html_part")).innerHTML = response;
        }
    });
}

function ajaxSort(url, tableHeader)
{
    $.ajax({
        type: "GET",
        url: url,
        success: function(response) {
            document.getElementById(("ajax_html_part")).innerHTML = response;
            if (tableHeader.data('orderingdirection') == "DESC") {
                tableHeader.data('orderingdirection', 'ASC');
            } else if (tableHeader.data('orderingdirection') == "ASC") {
                tableHeader.data('orderingdirection', 'DESC');
            }
        }
    });
}