<?php
//include_once('logs.php');
require_once('logs.php');

	//define('DEFAULT_TOKEN', '5731~Ca5w7Le0FY7qWI34SqGKXuoLPSWcAcOJ8bvTfD9kZLImz5b8zHCOXzw0XUA7qjOl');
	//define('DEFAULT_TOKEN', '5731~81gNp9EwTQzSAxvU2Lerc0gkL02p3HVQdaqYcoNFFnjYMriVsR9Ku2mXTaP0wxbi');
	define('DEFAULT_TOKEN', '5731~7TnfgJe5IFITGKWBkZIKb7mFD1usqVdanrg4XMvt2LvBDwf5QsSNLHZMLDZERVMS');
	define('CANVAS_SITE', 'https://wcpss.test.instructure.com/api/v1/');

      function getPath(){

        return '/tmp/mylog.txt';
        //return '/var/www/logs/canvasportal/canvasPortal_log.txt';
        //return '/var/www/sites/devlga/public/log.txt';
    }

	function getPathError(){
        return '/tmp/mylog_error.txt';
        //return '/var/www/logs/canvasportal/canvasPortal_errorlog.txt';
        //return '/var/www/sites/devlga/public/errorlog.txt';
    }

    function  logging($func, $result){

		$newline = "\n\n\n";

		$func = $func . "\n\n";

		$logs = new Logging();

		if (preg_match("/error/i", "$result") )
		    $logs->lfile(getPathError());
		else
			$logs->lfile(getPath());


		$logs->lwrite($func . $result . $newline);
        $logs->lclose();

    }

	function getUserIds() {
		return array (
			'mruegg' =>  'mruegg@wcpss.net',
			//'Mark Ruegg' =>  '5731~Ca5w7Le0FY7qWI34SqGKXuoLPSWcAcOJ8bvTfD9kZLImz5b8zHCOXzw0XUA7qjOl',
			'mnilsen' => 'mnilsen@wcpss.net',
//			'Gary Allen' =>  '5731~mMTafoh3fHcVsdfvosgoKWAFQwa7VV5z8wirxVFVrsTO66ueoJeLgWj4xAHvivG9'
			'gallen' =>  'gallen@wcpss.net',
			'ptart' =>  'ptart@wcpss.net'
		);
	}

	function get_tokens()
	{
		$token_array = array (
			'mruegg' =>  '5731~81gNp9EwTQzSAxvU2Lerc0gkL02p3HVQdaqYcoNFFnjYMriVsR9Ku2mXTaP0wxbi',
			//'Mark Ruegg' =>  '5731~Ca5w7Le0FY7qWI34SqGKXuoLPSWcAcOJ8bvTfD9kZLImz5b8zHCOXzw0XUA7qjOl',
			'mnilsen' => '5731~9soP1EHacz2snOL6gnRv6fWwLQOsiX90fC7oydYOEeVm6iHrxeGaEdjvnyhDqjPh',
//			'Gary Allen' =>  '5731~mMTafoh3fHcVsdfvosgoKWAFQwa7VV5z8wirxVFVrsTO66ueoJeLgWj4xAHvivG9'
			'gallen' =>  '5731~7TnfgJe5IFITGKWBkZIKb7mFD1usqVdanrg4XMvt2LvBDwf5QsSNLHZMLDZERVMS',
			'ptart' =>  '5731~7TnfgJe5IFITGKWBkZIKb7mFD1usqVdanrg4XMvt2LvBDwf5QsSNLHZMLDZERVMS'
		);

		return $token_array;
	}

    function get_sub_account_info($postParms, $token=DEFAULT_TOKEN)
    {

        return get_canvas('accounts/'. $postParms['accountId'] . '/sub_accounts', $token, 'per_page=' . $postParms['per_page']);
    }

	function get_account_info($accountId = 'self', $token=DEFAULT_TOKEN)
	{
		return get_canvas('accounts/' . $accountId, $token);
	}

	function get_user_info($login_id, $token=DEFAULT_TOKEN)
	{
		return get_canvas('accounts/self/users', $token.'&search_term='.$login_id);
	}

	function get_course_list($userId, $token=DEFAULT_TOKEN)
	{
		//gets courses
		return get_canvas('users/'. $userId .'/courses', $token);
	}

	function get_course_info($course_id, $token=DEFAULT_TOKEN)
	{
		//echo 'course_id: '.$course_id.'<br />';
		return get_canvas('courses/'.$course_id, $token);
	}

	function get_sub_sis_account($sisId){

		$postParm = 'sis_account_id:' . $sisId;
		return get_canvas('accounts/'.$postParm, DEFAULT_TOKEN);
	}

    function create_course_ajax($post_params)
    {
        //strip out the token from the param list
        $params = array();

        foreach($post_params as $key => $val)
        {
            if($key != 'usertoken')
            {
                $params[$key] = $val;
            }
        }

        $response =  post_canvas('accounts/' . $post_params['account_id'] . '/courses', $params, DEFAULT_TOKEN);

        $enroll_params = array(
            'enrollment[user_id]' => $post_params['user_id'],
            'enrollment[type]' => 'TeacherEnrollment',
            'enrollment[enrollment_state]' => 'active'
        );

        return enrollCourse($response['response'], $enroll_params );
    }

//	function create_course($post_params)
//	{
//		//strip out the token from the param list
//		$params = array();
//
//		foreach($post_params as $key => $val)
//		{
//			if($key != 'usertoken')
//			{
//				$params[$key] = $val;
//			}
//		}
//		return post_canvas('accounts/' . $post_params['account_id'] . '/courses', $params, DEFAULT_TOKEN);
//	}

	function enroll_user($post_params)
	{
		//strip out the token from the param list
		$params = array();

		foreach($post_params as $key => $val)
		{
			if($key != 'usertoken')
			{
				$params[$key] = $val;
			}
		}
		return post_canvas('courses/'. $_POST['courseId'] .'/enrollments', $params, $_POST['usertoken']);
	}

	function create_user_ajax($post_params)
	{
		//strip out the token from the param list
		$params = array();

		foreach($post_params as $key => $val)
		{
			if($key != 'usertoken')
			{
				$params[$key] = $val;
			}
		}
		return post_canvas('accounts/self/users', $params, $post_params['usertoken']);
	}

	function list_users_ajax($post_params) {
		$cmd = 'accounts/self/users';
		$token = $post_params['usertoken'];
		$url = CANVAS_SITE.$cmd.'?access_token='.$token . '&search_term=186222';
		//echo $url;
		$newline = "\n\n\n";
		$func = "get_canvas \n\n";


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_params['search_term']));
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		logging( __FUNCTION__ , $result );

		return $result;

	}

    function enrollCourse($course, $enroll_params){

        $courseId = json_decode($course) -> id;
        return post_canvas('courses/' . $courseId . '/enrollments', $enroll_params, DEFAULT_TOKEN);
    }

	function update_course($post_params)
	{
		//var_export($post_params);
		$course = array(
			'course[name]' => $post_params['course']['name'],
			'course[course_code]' => $post_params['course']['code'],
			'course[is_public]' => $post_params['course']['is_public'] 
		);
		//var_export($course);
		return put_canvas('courses/'.$_POST['course_id'], $course, $post_params['usertoken']);

	}

    function update_course_ajax($post_params)
    {
        //var_export($post_params);
        $course = array(
            'course[name]' => $post_params['course']['name'],
            'course[course_code]' => $post_params['course']['code'],
            'course[is_public]' => $post_params['course']['is_public']
        );
        //var_export($course);
		$res = put_canvas('courses/'.$post_params['course_id'], $course, $post_params['usertoken']);

		$updateParm = array(
			'course_section[name]' => $post_params['course']['name']
		);
		$sections = json_decode(get_section($post_params['course_id']));

		$updatedSection = update_section($sections[0]->id, $updateParm);

		return $res['response'];
    }

	function update_section($sectionId, $updateParms)
	{
		return put_canvas('sections/'. $sectionId, $updateParms, DEFAULT_TOKEN);
	}

	function get_section($courseId)
	{
		$sectionResponse = get_canvas('courses/'. $courseId . '/sections/', DEFAULT_TOKEN);
		return $sectionResponse;
	}

	function delete_course($course_id, $token=DEFAULT_TOKEN)
	{
		$params = array(
			'course_ids[]' => $course_id,
			'event' => 'delete'
		);
		return put_canvas('accounts/self/courses', $params, $token);
	}

	//----- CURL FUNCTIONS -----\\

	function get_canvas($cmd, $token, $req_arg = null)
	{
		$url = CANVAS_SITE.$cmd.'?access_token='.$token . '&'.$req_arg;

		//echo $url;
		$newline = "\n\n\n";
		$func = "get_canvas \n\n";


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		logging( __FUNCTION__ , $result );

		return $result;
	}

	function post_canvas($cmd, $req_args, $token)
	{
		$url = CANVAS_SITE.$cmd.'?access_token='.$token;
		//echo '$url: '.$url.'<br />';
		$newline = "\n\n\n";
		$func = "post_canvas \n\n";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($req_args));
		
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		//echo '$response: '.$response.'<br />';
		//echo '$httpcode: '.$httpcode.'<br />';
		logging( __FUNCTION__ , $response );

		return array(
			'http_code' => $httpcode,
			'response' => $response
		);
	}

	function put_canvas($cmd, $req_args, $token)
	{
		$url = CANVAS_SITE.$cmd.'?access_token='.$token;
		//echo '$url: '.$url.'<br />';
		$newline = "\n\n\n";
		$func = "post_canvas \n\n";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($req_args));
		
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		logging( __FUNCTION__ , $response );

		return array(
			'http_code' => $httpcode,
			'response' => $response
		);
	}