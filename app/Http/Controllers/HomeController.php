<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Setting;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        try{
            
            $threads = json_decode($this->get_request('maxscale/threads'), true);
            $sessions = json_decode($this->get_request('sessions'), true);
            $count = count($sessions['data']);
            $threads_count = count($threads['data']);
            return view('dash.view', compact('count', 'sessions', 'threads_count', 'threads'));

        } catch(\GuzzleHttp\Exception\ConnectException $exception){
            #return view('setting.index')->with('status', 'Issue connecting to MaxScale backend.');
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        }
        /*if($this->get_api_info()){
            $threads = json_decode($this->get_request('maxscale/threads'), true);
            $sessions = json_decode($this->get_request('sessions'), true);
            $count = count($sessions['data']);
            $threads_count = count($threads['data']);

            return view('dash.view', compact('count', 'sessions', 'threads_count', 'threads'));
        }else{
            return view('setting.index');
        }  */
    }
}
