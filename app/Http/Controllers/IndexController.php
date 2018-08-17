<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
	/**
	 * Display view home
	 *
	 * @return view home
	 */
	public function index()
	{
		return view('home');
	}
}
