//var baseURL = "/ahmdsmun000-rest/rest";
//$(document).ajaxSend(function (event, request, settings) {
//    $('#loading-indicator').show();
//});
//$(document).ajaxComplete(
//    function (event, request, settings) {
//        $('#loading-indicator').hide();
//    }
//);
function recursive_menu(menu_obj, array_data, html_string){
    var menu_childs = new Array();
    html_string += '<ul style="display: none;">';
    $.each(array_data, function(key, value){
        if(value.vparent == menu_obj.menuId){
            var menu_child_obj = new Object();
            menu_child_obj.menuId = value.vid;
            menu_child_obj.menuName = value.vtitle;
            if(value.vapplicationId == "null"){
                html_string += '<li class="menu treeview transition">'+
                                    '<a href="#">'+
                                        '<i class="glyphicon glyphicon-eye-open"></i> <span>'+value.vtitle+'</span>'+
                                        '<i class="glyphicon glyphicon-chevron-down icon-menu-expand" style="float:right"></i>'+
                                    '</a>';
                html_string = recursive_menu(menu_child_obj, array_data, html_string);
                html_string += '</li>';
            } else {
                html_string += '<li class="menu transition">'+
                                    '<a data-formid="'+value.vurl+'" href="#">'+
                                        '<i class="glyphicon glyphicon-circle-o">'+
                                        '</i>'+value.vtitle+
                                    '</a>'+
                                '</li>';                
            }
                     
           menu_childs.push(menu_child_obj);
            
        }
    });
    if(menu_childs.length > 0){
        menu_obj.menuChilds = menu_childs;
    }
    html_string += '</ul>';
    return html_string;
}

function _fw_login(user, pass){
    var userData = new Object();
    $('#error-login').hide();
    $('#loading-indicator').show();
    $.ajax
    ({
        type: "POST",
        url: "/jx02/ahmsvipdsh000-pst/rest/ip/dsh001/login",
        contentType: "application/json",
        dataType: 'json',
        async: false,
        headers: {
          "Authorization": "Basic " + btoa(user + ":" + pass)
        },
        data: JSON.stringify(userData)
    })
    .done(function(data){
        if(data.status=="1"){
            var hash = document.location.hash;
            window.location = "dashboard.htm"+hash;
        } else {
            $('#error-login').slideDown();
            $('#loading-indicator').hide();
        }       
    })
    .fail(function(){console.log('login failed')});
}
