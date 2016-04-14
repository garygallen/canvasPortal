<?php
include_once('functions.php');
$retdata = '';
switch ( $_POST["fn"] ) {
	case 'enroll_user':
		$retdata = json_encode(enroll_user($_POST));
		break;
	case 'createUser':
		$retdata = json_encode(create_user_ajax($_POST));
		break;
	case 'list_users':
		$retdata = var_export(list_users_ajax($_POST));
		break;
	case 'get_sub_accounts':
		$retdata = get_sub_accounts($_POST);
		break;
	case 'get_sub_sis_account':
		$retdata = get_sub_sis_account($_POST['sisId']);
		break;
	case 'get_account':
		$retdata = get_account_info($_POST['accountId']);
		break;
	case 'get_user_info_json':
		$retdata = get_user_json();
		break;
	case 'get_user_info':
		$retdata = get_user();
		break;
	case 'get_user_courses':
		$retdata = get_user_courses();
		break;
	case 'get_course':
		$retdata = get_course();
		break;
	case 'delete':
		$retdata = delete();
		break;
	case 'createcourse':
//		print_r($_POST['courseInfo']);
		$response = create_course_ajax($_POST['courseInfo']);
//        print_r($response);
		$retdata = $response['response'];
		break;
	case 'editcourse':
//            print_r($_POST['courseInfo']);
		$retdata = update_course_ajax($_POST['courseInfo']);
		break;
	default:
		$ret_array = array('ret_id' => -1, 'ret_msg' => 'error occurred (no function specified)');
		$retdata = json_encode($ret_array);
		break;
}
echo $retdata;

function get_sub_accounts($postParms){
	return get_sub_account_info($postParms);
}

function get_user()
{
	return var_export(json_decode(get_user_info($_POST['token'])));
}

function get_user_json()
{
	return get_user_info($_POST['login_id']);
}

function get_user_courses()
{
	return get_course_list($_POST['user_id']);
}

function get_course()
{
	return get_course_info($_POST['course_id']);
}

function delete()
{
	return json_encode(delete_course($_POST['course_id'], $_POST['token']));
}