<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            
            $services = json_decode($this->get_request('services'), true);
            $monitors = json_decode($this->get_request('monitors'), true);
            return view('services.services', compact('services', 'monitors'));
            
        } catch(\GuzzleHttp\Exception\ConnectException $exception){
            return view('setting.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        if($this->get_api_info()){
            $service = json_decode($this->get_request('services/'.$id), true);
            $listeners = json_decode($this->get_request('services/'.$id.'/listeners'), true);
            return view('services.servicedetail', compact('service', 'listeners'));
        }else{
            return view('setting.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $type = $request->input('type');
        $this->put_request('services/'.$id.'/'.$type);
        return $this->get_request('services/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = preg_replace('#[ -]+#', '-', $id);
        $this->delete_request('services/'.$id);
    }
    public function create_listener(Request $request, $id)
    {
        
        $listener = $request->input('listener_id');
        $id = preg_replace('#[ -]+#', '-', $id);
        $data = array(
            'data' => [
            'id' => $request->input('listener_id'),
            'type' => $request->input('listener_type') ?: "listeners",
            'attributes' => [
                'parameters' => [
                    'port' => (int) $request->input('port')
                ]
            ]
        ]);
        if(!empty($request->input('address'))) $data['data']['attributes']['parameters']['address'] = $request->input('address'); 
        if(!empty($request->input('protocol'))) $data['data']['attributes']['parameters']['protocol'] = $request->input('protocol');
        if(!empty($request->input('auth'))) $data['data']['attributes']['parameters']['authenticator'] = $request->input('auth');
        if(!empty($request->input('auth_options'))) $data['data']['attributes']['parameters']['authenticator_options'] = $request->input('auth_options');
        if(!empty($request->input('ssl_key'))) $data['data']['attributes']['parameters']['ssl_key'] = $request->input('ssl_key');
        if(!empty($request->input('ssl_cert'))) $data['data']['attributes']['parameters']['ssl_cert'] = $request->input('ssl_cert');
        if(!empty($request->input('ssl_ca_cert'))) $data['data']['attributes']['parameters']['ssl_ca_cert'] = $request->input('ssl_ca_cert');
        if(!empty($request->input('ssl_version'))) $data['data']['attributes']['parameters']['ssl_version'] = $request->input('ssl_version');
        if(!empty($request->input('ssl_depth'))) $data['data']['attributes']['parameters']['ssl_cert_verify_depth'] = $request->input('ssl_depth');

        $res = $this->post_request($data, 'services/'.$id.'/listeners');
        return $this->get_request('services/'.$id.'/listeners'.'/'.$listener);
        
    }
    public function destroy_listener(Request $request, $id)
    {
        try{
            $listener = $request->input('listener');
            $id = preg_replace('#[ -]+#', '-', $id);
            $this->delete_request('services/'.$id.'/listeners'.'/'.$listener);
        } catch(\GuzzleHttp\Exception\ClientException $exception){
            $pos = strpos($exception->getMessage(),"was not created at runtime");
            if($pos === false) {
                
            }
            else {
                $type = 'error';
                $errmessage = "Listener was not created at runtime. Remove listener manually.";
            }
            return response()->json([$type, $errmessage]);
        }
        
    }
}