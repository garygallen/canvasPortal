<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>WCPSS Canvas Portal</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom3.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/wcpsscanvas.js"></script>

</head>
<body>

<?php

//include("includes/wakeidm.inc.php");
include_once('includes/functions.php');

$mToken = '5731~7TnfgJe5IFITGKWBkZIKb7mFD1usqVdanrg4XMvt2LvBDwf5QsSNLHZMLDZERVMS';

$testing = 0;

if($testing) {
    include_once('includes/testing.php');
    $mUser = 'gallen@wcpss.net';

    $title = $user_obj['userType'];
    $schoolCode = $user_obj['schoolCode'];
    $department = $user_obj['department'];
    //echo '$title ='.$title;

}
else
{
//$mUser = ''; // this is now set in wakeidm.inc.php
    include("includes/wakeidm.inc.php");

    if(!isset($mUser) && $mUser != '')
    {
         die('Select a valid user');
    }
    $schoolCode = $user_obj->schoolCode;

    if(!$schoolCode)
       $schoolCode = 920304;

    $department = $user_obj->department;
    $title = $user_obj -> userType;

}


$account_info = json_decode(get_account_info());

$account_id = $account_info->id;
?>

<!--Main container div -->
<div class="container main center col-lg-8 col-md-9 col-sm-12 col-xs-12">

    <!--main heading-->
    <h1 class="pg-heading">WCPSS Canvas Portal</h1>

    <!--Main Panel-->
    <div class="panel">

        <!--Panel Header-->
        <div class="panel-heading">

            <!--Panel Header row start-->
            <div class="row">
                <!--sub-heading Heading column-->
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12 underline">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"> <h3 id="user_name"></h3> </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <a id="login_id" style="font-size: 10px;"><?php echo $mUser;?></a>
                        <br><span id="userSchoolName"></span>
                    </div>
                    <span id="schoolCode" hidden><?php echo $schoolCode; ?></span>
                    <span id="userDepartment" hidden><?php echo $department ?></span>
                    <span id="userTitle" hidden><?php echo $title ?></span>
                </div> <!--sub-heading column end tag -->

                <!--column Navigation icons start -->
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                    <div class="nav">
                        <ul class="nav-justified nav-icon">
                            <li><a href="#static" class="pagehelp" data-toggle="modal"><img src="images/question.png" alt="">Help</a></li>
                        </ul>
                    </div>
                </div> <!--column navigation icon ends-->
            </div> <!--panel header row end tag-->

        </div> <!--End of panel header tag-->

        <!--Panel Body -->
        <div class="panel-body">

            <!--Panel school records display starting tag-->
            <div class="row school-record">
                <!--School records heading row start-->
                <div id="courseColHeadings" class="row top record-row col-sm-12" style="border-bottom: 2px solid #d6d6d6">
                    <span id="name" class="col-lg-5 col-md-5 col-sm-5 col-xs-5" asc="asc">Course Name</span>
                    <span id="ColumnCourseCode" class="col-lg-5 col-md-5 col-sm-5 col-xs-5" asc="asc">Course ID</span>
                    <span class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></span>
                </div> <!--Schools record Heading row end-->

                <!--school record data rows start-->

                <!--dummy record-->

                <div id='user_courses'>

                </div>

<!--                <div class="row record-row col-sm-12">-->
<!--                    <span id="first-name1" class="col-lg-5 col-md-5 col-sm-5 col-xs-5">Patricia</span>-->
<!--                    <span id="last-name1" class="col-lg-5 col-md-5 col-sm-5 col-xs-5">Tart</span>-->
<!--                    <div id="wake-id1" class="col-lg-2 col-md-2 col-sm-2 col-xs-2">-->
<!--                        <a class=" dropdown-toggle action-icon glyphicon glyphicon-pencil" role="button" type="button"-->
<!--                           data-toggle="dropdown" data-target="#edit-popup"></a>-->
<!--                        <a class="action-icon glyphicon glyphicon-trash"></a>-->
<!--                    </div>-->
<!--                </div>-->
            </div> <!--Panel School records display ending tag-->


            <div class="row col-sm-12">
                <a id="lnkAddCourse" role="button" class="icon-add" data-toggle="collapse" data-target="#add-slider" >
                    <img src="images/add.png" style="height: 40px"> Add a new manual course
                </a>

                <div id="add-slider" class="collapse action-popup col-lg-12 col-md-12 col-sm-12 col-xs-12">
<!--                    Add course Form-->
                    <form id="course_form" action="#" method="post"
                          class="form-inline center-block col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group col-sm-4">
                            <label>Name of Course</label>
                            <input id="course_name" type="text" name="course[name]" class="form-control capitalize"/>
                            <span><br>e.g. General Biology</span>
                        </div>

                        <div class="form-group col-sm-4">
                            <label>Short Name for Course</label>
                            <input id="course_code" name="course[code]" type="text" class="form-control" />
                            <span><br>e.g. bio101</span>
                            <span style="color: red" id="duplicate_error_add" class="hidden"><br>error, duplicate course id not allowed</span>
                        </div>

                        <div class=" col-sm-4">
                            <label>School/Department</label>
                            <div class="">
                                <select id="school-dept" class="form-control col-xs-12" style="max-width: 100%">

                                </select>
                            </div>
                        </div>

                        <div hidden>
                            <select  id='course_license' name="course[license]" class="form-control col-xs-12">
                                <option></option>
                                <option>private</option>
                                <option>cc_by_nc_nd</option>
                                <option>cc_by_nc_sa</option>
                                <option>cc_by_nc</option>
                                <option>cc_by_nd</option>
                                <option>cc_by_sa</option>
                                <option selected>public_domain</option>
                            </select>
                        </div>

                        <div hidden>
                            <div class="">
                                <select id='course_is_public' name="course[is_public]" class="form-control col-xs-12">
                                    <option></option>
                                    <option selected value="true">true</option>
                                    <option>false</option>
                                </select>
                            </div>
                        </div>

                        <div hidden>
                            <div class="">
                                <select id='enroll_me' name="enroll_me" class="form-control col-xs-12">
                                    <option></option>
                                    <option value="true">true</option>
                                    <option selected value="false">false</option>
                                </select>
                            </div>
                        </div>

                        <div hidden>
                            <select name='usertoken' id='usertoken'>
                                <option selected value="<?php echo $mToken ?>"></option>
                            </select>
                        </div>

                        <input type='hidden' id='userId' name='userId' value=''>


                        <input type='hidden' id='course_id' name='course_id' value='new'>
                        <input type='hidden' id='formfunc' name='formfunc' value='createcourse'>
                        <input type='hidden' name='account_id' value='<?php echo $account_id; ?>'>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 canvas-form-row">
                            <!--Add course submit-->
                            <button id='submit_btn' class="btn btn-primary dropdown-toggle action-icon" role="button"
                                    type="button" data-toggle="dropdown" data-target="#add-popup-preview">PREVIEW</button>
                            <!--Add course Cancel-->
                            <button  data-toggle="collapse" data-target="#add-slider" class="btn btn-default" role="button" type="button">CANCEL</button>
                        </div>

                    </form> <!--Add course form ending-->

                </div> <!--add slider ending tag-->

            </div>

        </div> <!--panel body ending tag-->

        <!--Footer Panel-->
        <div class="panel-footer">
            <div class="bottom-text text-center"><span class="admin-bg">Manual courses</span> are created and managed by each user through the WCPSS Canvas Portal.<br>
            <span class="admin-bg">PowerSchool courses</span> are created and managed automatically from PowerSchool including student enrollments.</div>
        </div> <!--Panel footer ending tag -->

    </div> <!--Main panel ending tag-->

</div> <!--main container div ending tag -->

<!--Edit popup editor starting tag-->
<div id="edit-popup" class="keepopen position-action-popup dropdown col-lg-7 col-md-8 col-sm-10 col-xs-9">
    <div class="dropdown-menu action-popup col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form id="edit_course_form" role="form" action="#" method="post"
              class="form-inline center-block col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group col-sm-4">
                <label>Name of Course</label>
                <input id="edit_course_name" type="text" name='course[name]' class="form-control capitalize" />
                <span><br>e.g. General Biology</span>
            </div>

            <div class="form-group col-sm-4">
                <label>Short Name for Course</label>
                <input id="edit_course_code" name='course[code]' type="text" class="form-control" />
                <span><br>e.g. bio101</span>
                <span style="color: red" id="duplicate_error_edit" class="hidden"><br>error, duplicate course id not allowed</span>
            </div>

            <div class=" col-sm-4">
                <label>School/Department</label>
                <div class="">

                    <select id="edit-school-dept" class="form-control col-xs-12" style="max-width: 100%">

                    </select>

                </div>
            </div>

            <div hidden>
                <div class="">
                    <select id='edit_course_license' name='course[license]' class="form-control col-xs-12">
                        <option></option>
                        <option>private</option>
                        <option>cc_by_nc_nd</option>
                        <option>cc_by_nc_sa</option>
                        <option>cc_by_nc</option>
                        <option>cc_by_nd</option>
                        <option>cc_by_sa</option>
                        <option selected>public_domain</option>
                    </select>
                </div>
            </div>

            <div hidden>
                <div class="">
                    <select id='edit_course_is_public' name='course[is_public]' class="form-control col-xs-12">
                        <option></option>
                        <option selected>true</option>
                        <option>false</option>
                    </select>
                </div>
            </div>

            <div hidden>
                <div class="">
                    <select id='edit_enroll_me' name='enroll_me' class="form-control col-xs-12">
                        <option></option>
                        <option selected>true</option>
                        <option>false</option>
                    </select>
                </div>
            </div>

            <div hidden>
                <select name='usertoken' id='usertoken'>
                    <option selected value="<?php echo $mToken ?>"></option>
                </select>
            </div>

            <input type='hidden' id='edit_course_id' name='course_id' value='new'>
            <input type='hidden' id='edit_formfunc' name='formfunc' value='updatecourse'>
            <input type='hidden' id="account_id" name='account_id' value='<?php echo $account_id; ?>'>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 canvas-form-row">

                <button class="btn btn-primary dropdown-toggle action-icon" role="button"
                        type="button" data-toggle="dropdown" data-target="#edit-popup-preview">PREVIEW</button>

                <button class="btn btn-default" role="button" type="button" onclick="javascript:hideEditPopup()">CANCEL</button>
            </div>

        </form> <!--form ending-->

    </div>
</div> <!--Edit popup editor ending tag-->

<!--delete popup preview starting tag-->
<div id="delete-popup" class="keepopen position-action-popup dropdown col-lg-4 col-md-6 col-sm-7 col-xs-9">
    <div class="dropdown-menu preview-popup col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="delete-caution">
            <img src="images/caution.jpeg">
        </div>

        <span class="display-block"><b><span style="color: red">Caution.</span> This Action cannot be reversed. You are requesting to delete the following course...</b><br><br></span>

        <span id="delete-course-name" class="display-block"></span>
        <span id="delete-course-code" class="display-block"></span>
        <input type='hidden' id='delete_course_id'>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 canvas-form-row">

            <button id="delete_confirm_btn" class="dropdown-toggle btn btn-primary action-icon" role="button"
                    type="button" data-toggle="dropdown">SUBMIT</button>

            <button class="btn btn-default" role="button" type="button" onclick="javascript:hideDeletePopup()">CANCEL</button>

        </div>
    </div>
</div> <!--Delete popup editor ending tag-->

<!--Edit popup preview starting tag-->
<div id="edit-popup-preview" class="keepopen position-popup-preview dropdown col-lg-4 col-md-7 col-sm-6 col-xs-9">
    <div class="dropdown-menu preview-popup col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <span class="display-block"><b>Course will be changed to...</b><br><br></span>
        <span id="preview-course-name" class="display-block"></span>
        <span id="preview-course-id" class="display-block"></span>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 canvas-form-row">

            <button id="edit_submit_btn" class="dropdown-toggle btn btn-primary action-icon" role="button"
                 type="button" data-toggle="dropdown" data-target="#edit-popup-preview">SUBMIT</button>

            <button class="btn btn-default" role="button" type="button" onclick="javascript:hideEditPopupPreview()">CANCEL</button>

        </div>
    </div>
</div> <!--Edit popup editor ending tag-->

<!--add popup preview starting tag-->
<div id="add-popup-preview" class="keepopen position-popup-preview dropdown col-lg-4 col-md-6 col-sm-7 col-xs-9">
    <div class="dropdown-menu preview-popup col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <span class="display-block"><b>Your course will be created as ...</b><br><br></span>
        <span id="preview-course-name-add" class="display-block"></span>
        <span id="preview-course-id-add" class="display-block"></span>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 canvas-form-row">

            <button id="add_submit_btn" class="dropdown-toggle btn btn-primary action-icon" role="button"
                    type="button" data-toggle="dropdown" data-target="#edit-popup-preview">SUBMIT</button>

            <button class="btn btn-default" role="button" type="button" onclick="javascript:hideAddPopupPreview()">CANCEL</button>

        </div>
    </div>
</div> <!--add popup editor ending tag-->

<div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-help-viewer">
        <div class="modal-content">
            <div class="col-md-12" style="background: #FFFFFF">
                <div class="model-details">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true" style="float:right;">×</button>
                    <h3><img class="nav-icon col-md-1 center" src="images/question.png"> Canvas Portal Help</h3>
                    <div class="details">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Course in Canvas</h4>
                                <div class="col-sm-12">
                                    Canvas has two types of courses:
                                    <ol type="1">
                                        <li><u>PowerSchool Courses</u>: courses that are created and managed automatically from PowerSchool including student enrollments.</li>
                                        <li><u>Manual Courses</u>: courses that are created and managed by each user and must be managed through the Canvas Portal. Enrollments are managed from within the course in Canvas.</li>
                                    </ol>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-md-12"><h4>Add a Course <span><img class="nav-icon col-md-1 center" src="images/add.png"> Add a new manual course</span></h4></div>
                                <div class="col-sm-12">
                                    <ul>
                                        <li><b>Course Name:</b> the tile that you want your course to be called.</li>
                                        <li><b>Short Name:</b> a short version of your course name that makes it easy to identify.</li>
                                        <li><b>School/Department:</b> the school that you are assigned.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-md-12"><h4>Edit a Course <i class=" edit-icon glyphicon glyphicon-pencil"></i></h4></div>
                                <div class="col-sm-12">
                                    Course edits are limited to...
                                    <ul>
                                        <li>Course Name</li>
                                        <li>Short Name</li>
                                        <li>School/Department(only for users at multiple schools)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-md-12"><h4>Delete a Course <i class="delete-icon glyphicon glyphicon-trash" style="color: #860038;"></i></h4></div>
                                <div class="col-sm-12">
                                    <ul>
                                        <li>The course will be completely removed from Canvas.</li>
                                        <li>Course content and student data will be lost.</li>
                                        <li>It is a good idea to export your course as a backup before deleting.
                                            <ul style="list-style-type:disc">
                                                <li>Enter the course and click Settings > Expert Course. This will save your course as an .imscc file.</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- model-details -->

            </div><!-- modal-help-right -->
        </div>
    </div>
</div>

<form id="refresh_form" hidden action="<?php $_SERVER['PHP_SELF'] ?>">
    <input id="login_id" name="login_id" value="<?php echo $mUser; ?>"/>
</form>

<?php unset($_POST) ?>

<script type="text/javascript">window.NREUM||(NREUM={});NREUM.info={"beacon":"bam.nr-data.net","licenseKey":"8e23a381b9","applicationID":"7604918","transactionName":"NVxRMRBYVhBXUhBQDAwWcgYWUFcNGVcNVQYRZlcKFVdUDFdV","queueTime":0,"applicationTime":344,"atts":"GRtSR1hCRR4=","errorBeacon":"bam.nr-data.net","agent":""}</script></body>

<script>
    $('#edit-popup').on('hide.bs.dropdown', function (e) {
        var target = $(e.target);
        return !(target.hasClass("keepopen") || target.parents(".keepopen").length);
    });

    $('#delete-popup').on('hide.bs.dropdown', function (e) {
        var target = $(e.target);
        return !(target.hasClass("keepopen") || target.parents(".keepopen").length);
    });

    $('#edit-popup-preview').on('hide.bs.dropdown', function (e) {
        var target = $(e.target);
        if(target.hasClass("keepopen") || target.parents(".keepopen").length){
            return false; // returning false should stop the dropdown from hiding.
        }else{
            return true;
        }
    });

    $('#add-popup-preview').on('hide.bs.dropdown', function (e) {
        var target = $(e.target);
        return !(target.hasClass("keepopen") || target.parents(".keepopen").length);
    });

    $('#add-slider').on('hide.bs.collapse', function (e) {
        hideAddSlider();
    });

    $('#edit-popup-preview').on('show.bs.dropdown', function (e) {

        if (!is_valid_edit_form()){
            return false;
        }
        var loginId = userLogin.split('@');
        var shortCode = $('#edit_course_code');
        var courseId = $('#edit_course_id').val();
        $('#edit_course_code').closest('.form-group').removeClass('has-error');

        $('#duplicate_error_edit').addClass('hidden');
        var today = new Date();
        var yyyy = today.getFullYear();
        var courseCode = 'm.' + tSchoolCode + '.' + loginId[0] + '.' + shortCode.val() + '.' + yyyy;

        var regexp = /^[a-zA-Z0-9-]+$/;
        if (shortCode.val().search(regexp) == -1)
        {
            alert('only alpha numeric and dashes are allow');
            return false;
        }

        if(searchCode(courseCode, courseId)){

            $('#duplicate_error_edit').removeClass('hidden');
            $('#edit_course_code').closest('.form-group').addClass('has-error');
            return false;
        }

        var courseName = $('#edit_course_name');
        var schoolName = $('#edit-school-dept');
        $('#preview-course-name').html(courseName.val());
        $('#preview-course-name').addClass('capitalize');
        $('#preview-course-id').html(courseCode);

    });

    $('#add-popup-preview').on('show.bs.dropdown', function (e) {

        if (!is_valid_form()){
            return false;
        }

        var courseName = $('#course_name');
        var shortCode = $('#course_code');
        var schoolName = $('#school-dept');

        $('#course_code').closest('.form-group').removeClass('has-error');
        $('#course_code').addClass('capitalize');
        $('#duplicate_error_add').addClass('hidden');

        var today = new Date();
        var yyyy = today.getFullYear();

        var loginId = userLogin.split('@');

        var courseCode = 'm.' + tSchoolCode + '.' + loginId[0] + '.' + shortCode.val() + '.' + yyyy;

        var regexp = /^[a-zA-Z0-9-]+$/;
        if (shortCode.val().search(regexp) == -1)
        {
            alert('only alpha numeric and dashes are allow');
            return false;
        }

        if(searchCode(courseCode, -1)){

            $('#duplicate_error_add').removeClass('hidden');
            $('#course_code').closest('.form-group').addClass('has-error');
            return false;
        }

        $('#preview-course-name-add').html(courseName.val());
        $('#preview-course-name-add').addClass('capitalize');
        $('#preview-course-id-add').html(courseCode);


    });

    function hideEditPopup(){
        $('#edit-popup').removeClass("open");
        $('#edit_course_code').closest('.form-group').removeClass('has-error');
        $('#duplicate_error_edit').addClass('hidden');
        hideEditPopupPreview();
        clear_form();
    }

    function hideDeletePopup(){
        $('#delete-popup').removeClass("open");
        $('#delete_course_id').val('');
    }

    function hideAddSlider(){
        $('#add-slider').removeClass("in");
        $('#duplicate_error_add').addClass('hidden');
        $('#course_code').closest('.form-group').removeClass('has-error');
        hideAddPopupPreview();
        clear_form();
    }

    function hideAddPopupPreview(){
        $('#add-popup-preview').removeClass("open");
    }

    function hideEditPopupPreview(){
        $('#edit-popup-preview').removeClass("open");

    }
</script>
</html>