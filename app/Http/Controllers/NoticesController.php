<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\PrepareNoticeRequest;
use App\Provider;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;

class NoticesController extends Controller {

	/**
	 * Create a new notices controller instance.
	 */
	public function __construct()
	{
	    $this->middleware('auth');
	}

	/**
	 * Show all notices.
	 * @return string
	 */
	public function index()
	{
	    return 'all notices';
	}

	/**
	 * Show a page to create new notice.
	 * @return \Response
	 */
	public function create()
	{
	    // get list of providers
		$providers = Provider::lists('name','id');
		// load a view to create a new notice
		return view('notices.create', compact('providers'));
	}

	/**
	 * Ask the user to confirm the DMCA that will be delivered.
	 *
	 * @param PrepareNoticeRequest $request
	 * @param Guard $auth
	 * @return \Response
	 */
	public function confirm(PrepareNoticeRequest $request, Guard $auth)
	{
		$template =  $this->compileDmcaTemplate($data = $request->all(), $auth);
		// We use flash because we need it for exactly one page request
		// if more than one, use put
		session()->flash('dmca', $data);
		return view('notices.confirm', compact('template'));
	}

	public function store()
	{
		$data = session()->get('dmca');

//		return \Request::input('template'); // The template itself
		return $data; // Original data
	}

	/**
	 * Compile the DMCA template the form data.
	 *
	 * @param $data
	 * @param Guard $auth
	 * @return mixed
	 */
	private function compileDmcaTemplate($data, Guard $auth)
	{
		$data = $data + [
			'name' => $auth->user()->name,
			'email' => $auth->user()->email,
		];

		return view()->file(app_path('Http/Templates/dmca.blade.php'), $data);
	}


}
