<?php namespace App\Http\Controllers\Error;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_userurl;
use App\Bu_user;
use Session;

class ErrorController extends Controller {

	public function index()
	{
		return view('errors.404');
	}
}
