<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\PrepareNoticeRequest;
use App\Notice;
use App\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NoticesController extends Controller {

	/**
	 * Create a new notices controller instance.
	 */
	public function __construct()
	{
	    $this->middleware('auth');

		parent::__construct();
	}

	/**
	 * Show all notices create by the user.
	 *
	 * @return string
	 */
	public function index()
	{
//	    return Notice::all();
		$notices = $this->user->notices;
		return view('notices.index', compact('notices'));
	}

	/**
	 * Show a page to create new notice.
	 *
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
	 * @return \Response
	 */
	public function confirm(PrepareNoticeRequest $request)
	{
		$template =  $this->compileDmcaTemplate($data = $request->all());
		// We use flash because we need it for exactly one page request
		// if more than one, use put
		session()->flash('dmca', $data);
		return view('notices.confirm', compact('template'));
	}

	/**
	 * Store a new DMCA notice.
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(Request $request)
	{
		$notice = $this->createNotice($request);

		// And then fire off the email
		Mail::queue('emails.dmca', compact('notice'), function($message) use ($notice) {
			$message->from($notice->getOwnerEmail())
					->to($notice->getRecipientEmail())
					->subject('DMCA Notice');
		});

		return redirect('notices');
//		return Notice::first();
	}

	/**
	 * Compile the DMCA template the form data.
	 *
	 * @param $data
	 * @return mixed
	 */
	private function compileDmcaTemplate($data)
	{
		$data = $data + [
			'name' => $this->user->name,
			'email' => $this->user->email,
		];

		return view()->file(app_path('Http/Templates/dmca.blade.php'), $data);
	}

	/**
	 * Create and persist a new DMCA notice.
	 * @param Request $request
	 */
	private function createNotice(Request $request)
	{
// Form data is flashed. Get with session()->get('dmca')
//		$data = session()->get('dmca');
		// Template is in request. Request::input('template')
		// So build up a Notice object (create table too)
		// Persist it with this data.

//		Notice::open($data)
//			->useTemplate($request->input('template'))
//			->save();

//		$notice = Notice::open($data)->useTemplate($request->input('template'));

		$notice = session()->get('dmca') + ['template' => $request->input('template')];

		$notice = $this->user->notices()->create($notice);

		return $notice;
	}


}
