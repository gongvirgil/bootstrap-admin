$(document).ready(function() {
    switchSite($.cookie('site_id'));
    $('#site-select a').click(function(e) {
        currentSiteId = $(this).attr('data-site-id');
        switchSite(currentSiteId);
        window.location.reload();
        e.preventDefault();
    });
    var m = getQuery('m');
    var a = getQuery('a');
    a = a ? a : "index";
    $("#" + m).addClass('active');
    $("#" + a).parent('.collapse').addClass('in');
    $("#" + a).addClass('active').siblings('li').removeClass('active');
})
/*
$(document).ready(function() {
    $('.collapsed').on('click', function(event) {
        $(this).find('i').toggleClass('glyphicon-chevron-left').toggleClass('glyphicon-chevron-down');
    });
});
*/
$(document).ready(function() {
    $(".switch input[type='checkbox']").bootstrapSwitch();
});



function switchSite(SiteId) {
    $('#site-select i').removeClass('glyphicon glyphicon-ok whitespace').addClass('whitespace');
    var current = $('#site-select a[data-site-id=' + SiteId + ']');
    current.find('i').removeClass('whitespace').addClass('glyphicon glyphicon-ok');
    $('#site-name').html(current.attr('data-site-name'));
    $('#site-url').attr('href', current.attr('data-site-url'));
    $.cookie('site_id', SiteId);
}

function getQuery(name) {
    var result = location.search.match(new RegExp("[\?\&]" + name + "=([^\&]+)", "i"));
    if (result == null || result.length < 1) {
        return "";
    }
    return result[1];
}

function _load(url) {
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {},
        beforeSend: function() {
            $("#ajax-load").html('loading......');
        },
        error: function() {
            alert('error');
        },
        success: function(data) {
            var content = $(data).find('div#ajax-load').html();
            $("#ajax-load").html(content);
        }
    })
}
