var userLogin;
$(document).ready(function() {
    clear_form();
    var userTitle = $('#userTitle').html();

    if (userTitle != 'Contractor' && userTitle != 'Employee' ) {

        $(document.body).hide();

        alert('Currently, you do not have a Canvas account. ' +
            'Contact a trained Canvas Coordinator at your school/department. ' +
            'Find your Canvas Coordinator at http://canvas.wcpss.net/find-coordinator.htm.');

        return;
    }

    $('#usertoken').bind('change', token_change);
    $('.toggle_con').bind('click', toggle_con_click);
    $('#new_course_btn').bind('click', new_course_click);
    $('#add_submit_btn').bind('click', submit_click);
    $('#courseColHeadings').find('span').bind('click', SortCourses);

    //get_user_info();
    userLogin = $('#login_id').html();
    get_user_info_json();
    get_user_courses();
    populateDepartmentsSchools();
});

var user_id;
var courseCodes = [];
var tSchoolCode;
var userCourses;
var maxNumRecords = 50;
var sisId;
var foundAccountId = '';


var populateDepartmentsSchools = function() {

   sisId = $('#schoolCode').html();
    if (sisId != '') {
        sisId = 'm.' + sisId;
        var info = get_account_info_by_sisId(sisId);
        var dept = info.name;
        var openp = dept.indexOf("(");
        var closep = dept.indexOf(")");
        tSchoolCode = dept.slice(openp + 1, closep);
        //alert(tSchoolCode);

        $('#school-dept').append(new Option(info.name, info.id));
        $('#edit-school-dept').append(new Option(info.name, info.id));

        $('#userSchoolName').html(info.name);

    } else {
        var dept = $('#userDepartment').html();
        var info = getSubAccounts(3);
        var Notfound = true;
        for (var i = 0 ; i< info.length; i++) {

            if (info[i].name.substr(0, dept.length) == dept){
                $('#school-dept').append(new Option(info[i].name, info[i].id));
                $('#edit-school-dept').append(new Option(info[i].name, info[i].id));
                $('#userSchoolName').html(info[i].name);

                foundAccountId = info[i].id;
                var deptNum = info[i].name;
                var openp = deptNum.indexOf("(");
                var closep = deptNum.indexOf(")");
                tSchoolCode = deptNum.slice(openp + 1, closep);

                Notfound = false;
            }

        }


        if (Notfound){


            for (var i = 0 ; i< info.length; i++) {

                if (info[i].name == 'Other Department'){

                    //opt.setAttribute('selected', 'selected');
                    $('#school-dept').append(new Option(info[i].name + " (" + info[i].id + ")", info[i].id));
                    $('#edit-school-dept').append(new Option(info[i].name + " (" + info[i].id + ")", info[i].id));
                    $('#userSchoolName').html(info[i].name + " (" + info[i].id + ")");

                    $('#school-dept').val(info[i].id);
                    $('#edit-school-dept').val(info[i].id);

                    tSchoolCode = info[i].id;
                    foundAccountId = info[i].id;
                    Notfound = false;

                } else {
                    $('#school-dept').append(new Option(info[i].name, info[i].id));
                    $('#edit-school-dept').append(new Option(info[i].name, info[i].id));
                }

            }

        }

    }



};

var getSubAccounts = function(accountId) {
    if (typeof accountId === "undefined" || accountId === null) {
        accountId = 1;
    }
    var res = 0;
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'get_sub_accounts',
            accountId: accountId,
            per_page:maxNumRecords
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            res = data;
        }
    });
    return res;
};

var token_change = function () {
    //alert($('#usertoken').val());
    get_user_info();
    get_user_courses();
};

var get_user_info = function () {
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'get_user_info',
            token: $('#usertoken').val()
        },
        //dataType: "json",
        async: false,
        cache: false,
        success: function (data) {

            $('#user_info').html('<pre>' + data + '</pre>');

        }
    });
};

var getAccountInfoById = function(id)
{
    //'m.930342'
    var Id = id;
    var res = 1;
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'get_account',
            token: $('#usertoken').val(),
            accountId: Id
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            res = data;
        }
    });
    return res;
};

var get_account_info_by_sisId = function(sisId)
{
    //'m.930342'
    var sis_Id = sisId;
    var res = 1;
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'get_sub_sis_account',
            token: $('#usertoken').val(),
            sisId: sis_Id
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            res = data;
        }
    });
    return res;
};


var get_user_info_json = function () {
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'get_user_info_json',
            login_id: $('#login_id').html()
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function (data) {
            //alert();
            //$('#debug_panel').html(JSON.stringify(data));
            //alert(data.is_public)

            $('#user_name').html(data[0].name);
            user_id = data[0].id;
            $('#userId').val(data[0].id);

            //var pData = JSON.parse(data);
            //alert(pData);

            // to print all info
            //$('#user_info').html('<pre>' + data + '</pre>');
            //var text = data.toString();
            //obj = new Object;obj = JSON.parse(data);
            //
            //$('#user_name').html(obj.name);
        }
    });
};

var get_user_courses = function () {
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'get_user_courses',
            user_id: user_id
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function (data) {

            //clear_form();
            //$('#course_form_div').hide();
            userCourses = data;
            sortResults('name', true);

        }
    });
};

var SortCourses = function() {
    var id = $(this).attr('id');

    if(id == 'ColumnCourseCode'){
        id = 'course_code'
    }

    var asc = (!$(this).attr('asc')); // switch the order, true if not set
    //alert(asc);
    // set asc="asc" when sorted in ascending order
    $('#courseColHeadings').find('span').each(function() {
        $(this).removeAttr('asc');
    });
    if (asc) $(this).attr('asc', 'asc');

    sortResults(id, asc);
};

function sortResults(prop, asc) {
    userCourses = userCourses.sort(function(a, b) {
        if (asc) return (a[prop] > b[prop]);
        else return (b[prop] > a[prop]);
    });
    showResults();
}

function showResults () {
    var htmstr = '';

    for (var i = 0; i < userCourses.length; i++) {
        var loginId = userLogin.split('@');

        // prevent Canvas created courses from being listed
        // Manual created courses code format :
        // m.dept_num.emai_name.code.year
        // e.g. m.348.mnilsen.bio101.2016

        var tcourseCode = userCourses[i]['course_code']
        if (tcourseCode.match(/m./g)) {

            htmstr += "<div class='row record-row col-sm-12'>";
            htmstr += "<span class='col-lg-5 col-md-5 col-sm-5 col-xs-5'>" + userCourses[i]['name'] + "</span>";
            htmstr += "<span class='col-lg-5 col-md-5 col-sm-5 col-xs-5'>" + userCourses[i]['course_code'] + "</span>";
            htmstr += "<div id='wake-id1' class='col-lg-2 col-md-2 col-sm-2 col-xs-2'>";
            htmstr += "<a class='dropdown-toggle action-icon glyphicon glyphicon-pencil edit_icon' role='button' type='button'"
                + "data-toggle='dropdown' data-target='#edit-popup' data-id='" + userCourses[i]['id'] + "'></a>";
            htmstr += "<a class='dropdown-toggle action-icon glyphicon glyphicon-trash delete_icon' role='button' type='button'"
                + "data-toggle='dropdown' data-id='" + userCourses[i]['id'] + "'></a>";
            htmstr += "</div>";
            htmstr += "</div>";
        }
    }

    for (var i = 0; i < userCourses.length; i++) {
        courseCodes[i] = [];
        courseCodes[i][0] = userCourses[i]['id'];
        courseCodes[i][1] = userCourses[i]['course_code'];
    }


    $('#user_courses').html(htmstr);

    $('.edit_icon').unbind().bind('click', edit_click);
    //$('.delete_icon').unbind().bind('click', delete_click);
    $('.delete_icon').unbind().bind('click', delete_preview_course_info);
    $('#delete_confirm_btn').unbind().bind('click', delete_click);
    $('#edit_submit_btn').unbind().bind('click', submit_edit);

}


var toggle_con_click = function()
{
    var mydiv = $(this).attr('data-tog');
    $('#'+mydiv+'_info').toggle();
    //alert(mydiv);
}

var new_course_click = function()
{
    $('#course_form_div').show();
    $('.new_only').show();
    clear_form();
    $('#formfunc').val('createcourse');
    $('#course_id').val('new');
    $('#submit_btn').html('Add New Course');
}

var clear_form = function()
{
    $('#course_name').val('');
    $('#course_code').val('');
    $('#edit_course_name').val('');
    $('#edit_course_code').val('');
    //$('#course_license').val('');
    //$('#course_is_public').val('');
    //$('#enroll_me').val('');
}

var edit_click = function()
{
    var course_id = $(this).attr('data-id');
    var datastring = '';
    datastring += 'site_id=' + $('#filter_site').val();
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'get_course',
            course_id: course_id,
            token: $('#usertoken').val()
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            //alert(data)
            //$('#debug_panel').html(JSON.stringify(data));
            //alert(data.is_public)
            clear_form();
            //$('#course_form_div').show();
            //$('.new_only').hide();
            $('#edit_course_name').val(data.name);

            var courseCode = data.course_code.split('.');

            $('#edit_course_code').val(courseCode[3]);
            if(data.is_public == true)
                $('#edit_course_is_public').val('true');
            else
                $('#edit_course_is_public').val('false');

            $('#edit_formfunc').val('updatecourse');
            $('#edit_course_id').val(data.id);
            //$('#submit_btn').html('Update Course');
        }
    });
}

var delete_preview_course_info = function()
{
    var course_id = $(this).attr('data-id');
    var datastring = '';
    datastring += 'site_id=' + $('#filter_site').val();
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'get_course',
            course_id: course_id,
            token: $('#usertoken').val()
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            //alert(data)
            //$('#debug_panel').html(JSON.stringify(data));
            //alert(data.is_public)
            clear_form();
            //$('#course_form_div').show();
            //$('.new_only').hide();
            var loginId = userLogin.split('@');
            $('#delete-course-name').html(data.name);
            $('#delete-course-code').html(data.course_code);
            $('#delete_course_id').val(data.id);
            $('#delete-popup').addClass("open");
            //$('#submit_btn').html('Update Course');
        }
    });
}

var searchCode = function(key, ignoreCase) {

    for(var i=0; i<courseCodes.length; i++) {
        if (courseCodes[i][1] == key && courseCodes[i][0] != ignoreCase) {
            return true
        }
    }
    return false;
}

var delete_click = function()
{
    var course_id = $('#delete_course_id').val();
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'delete',
            course_id: course_id,
            token: $('#usertoken').val()
        },
        //dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            //alert(data)
            //
            hideDeletePopup();
            // get_user_courses();
            // location.reload();
            // redirectOnDelete();
            //window.location.replace(location.href);

            $('#refresh_form').submit();

            //var url = location.href + $('#usertoken').val();
            //var form = $('<form action="' + url + '" method="post">' +
            //    '<input type="text" name="api_url" value="' + Return_URL + '" />' +
            //    '</form>');
            //$('body').append(form);
            //form.submit();

        },
        error: function()
        {
            alert('error');
        }
    });

}

var capitalize = function(str){
    var capString = '';
    for (var i=0 ; i<str.length ; i++) {
        if(i == 0 || str.charAt(i-1) == ' '){
            capString += str.charAt(i).toUpperCase();
        } else {
            capString += str.charAt(i);
        }

    }

    return capString;
}

var add_course = function()
{
    var courseCode = $('#preview-course-id-add').html();
    var courseName = capitalize($('#course_name').val());
    var courseLicense = $('#course_license').val();
    var isPublic = $('#course_is_public').val();
    var enroll_me = $('#enroll_me').val();
    var usertoken = $('#usertoken').val();
    var course_id = $('#course_id').val();
    var formfunc = $('#formfunc').val();
    //var account_id = $('#account_id').val();

    if (sisId != '') {
        var account_id = get_account_info_by_sisId(sisId).id;
    } else if (foundAccountId != '') {
        var account_id = foundAccountId;
    }


    var data = {
        course: {name: courseName, course_code: courseCode, license: courseLicense, is_public: isPublic},
        enroll_me: enroll_me,
        usertoken: usertoken,
        course_id: course_id,
        formfunc: formfunc,
        account_id: account_id,
        user_id: user_id
    };
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'createcourse',
            courseInfo: data
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            //alert(data)
            //location.reload();
            get_user_courses();
            hideAddSlider();


        },
        error: function()
        {
            alert('error');
        }
    });

}

var edit_course = function()
{

    var courseName = capitalize($('#edit_course_name').val());
    var courseCode = $('#preview-course-id').html();
    var courseLicense = $('#edit_course_license').val();
    var isPublic = $('#edit_course_is_public').val();
    var enroll_me = $('#edit_enroll_me').val();
    var usertoken = $('#usertoken').val();
    var course_id = $('#edit_course_id').val();
    var formfunc = $('#edit_formfunc').val();
    var account_id = $('#account_id').val();

    var data = {
        course: {name: courseName, code: courseCode, license: courseLicense, is_public: isPublic},
        enroll_me: enroll_me,
        usertoken: usertoken,
        course_id: course_id,
        formfunc: formfunc,
        account_id: account_id
    };
    $.ajax({
        type: "POST",
        url: 'includes/wcpsscanvas_ajax_service.php',
        data: {
            fn: 'editcourse',
            courseInfo: data
        },
        //dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            //alert(data)
            //location.reload();
            get_user_courses();
            hideEditPopup();


        },
        error: function()
        {
            alert('error');
        }
    });

}

var submit_click = function()
{
    if(is_valid_form())
    {
        //$('#course_form').submit();
        add_course();
    }
    return false;
}

var is_valid_form = function() {

    var is_valid = true;
    var error_msg = '';
    $('#course_name').closest('.form-group').removeClass('has-error');
    $('#course_code').closest('.form-group').removeClass('has-error');

    if($('#course_name').val() == '')
    {
        is_valid = false;
        error_msg += "invalid course name\r\n";
        $('#course_name').closest('.form-group').addClass('has-error');
    }

    if($('#course_code').val() == '')
    {
        is_valid = false;
        error_msg += "invalid course code\r\n";
        $('#course_code').closest('.form-group').addClass('has-error');
    }

    if($('#course_is_public').val() == '')
    {
        is_valid = false;
        error_msg += "must specify whether course is public\r\n";
    }

    if($('#formfunc').val() == 'createcourse')
    {
        if($('#course_license').val() == '')
        {
            is_valid = false;
            error_msg += "invalid license\r\n";
        }

        if($('#enroll_me').val() == '')
        {
            is_valid = false;
            error_msg += "must specify whether to be enroll\r\n";
        }
    }
    if(!is_valid)
    {
        alert(error_msg);
    }

    return is_valid;
}

var submit_edit = function()
{
    if(is_valid_edit_form())
    {
        //$('#edit_course_form').submit();
        edit_course();
    }
    return false;
}

var is_valid_edit_form = function() {

    var is_valid = true;
    var error_msg = '';
    $('#edit_course_name').closest('.form-group').removeClass('has-error');
    $('#edit_course_code').closest('.form-group').removeClass('has-error');

    if($('#edit_course_name').val() == '')
    {
        is_valid = false;
        error_msg += "invalid course name\r\n";
        $('#edit_course_name').closest('.form-group').addClass('has-error');
    }

    if($('#edit_course_code').val() == '')
    {
        is_valid = false;
        error_msg += "invalid course code\r\n";
        $('#edit_course_code').closest('.form-group').addClass('has-error');
    }

    if($('#edit_course_is_public').val() == '')
    {
        is_valid = false;
        error_msg += "must specify whether course is public\r\n";
    }

    if(!is_valid)
    {
        alert(error_msg);
    }

    return is_valid;
}
