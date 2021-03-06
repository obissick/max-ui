@extends('layouts.app')

@section('content')
<div class="container container-fluid">
    <div class="flash-message"></div>
    <h2>Services</h2>
    <button id="addservice" name="addservice" class="btn btn-success btn-xs" data-toggle="modal" data-target="#service">Add Service</button>
    <div class="row">
        <div class="table-responsive">
            <br />
            <!-- Table-to-load-the-data Part -->
            <table class="table" id="services-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Router</th>
                        <th>State</th>
                        <th>Total Connections</th>
                        <th>Connections</th>
                        <th>Started</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="services-list" name="services-list">
                    @for ($i = 0; $i < count($services['data']); $i++)
                    <tr id="service{{$services['data'][$i]['id']}}">
                        <td><a href="{{route('services.show', $services['data'][$i]['id'])}}" class="btn btn-primary btn-xs btn-detail service-info" value="{{$services['data'][$i]['id']}}">{{$services['data'][$i]['id']}}</a></td>
                        <td>{{$services['data'][$i]['attributes']['router']}}</td>
                        <td id="state{{$services['data'][$i]['id']}}">{{$services['data'][$i]['attributes']['state']}}</td>
                        <td>{{$services['data'][$i]['attributes']['total_connections']}}</td>
                        <td>{{$services['data'][$i]['attributes']['connections']}}</td>
                        <td>{{$services['data'][$i]['attributes']['started']}}</td>
                        <td id="action{{$services['data'][$i]['id']}}">
                            @if($services['data'][$i]['attributes']['state'] === "Started")
                                <button class="btn btn-warning btn-xs btn-detail stop-service" value="{{$services['data'][$i]['id']}}">Stop</button>  
                            @else
                                <button class="btn btn-success btn-xs btn-detail start-service" value="{{$services['data'][$i]['id']}}">Start</button>
                            @endif
                            <button class="btn btn-info btn-xs btn-detail edit-service" value="{{$services['data'][$i]['id']}}">Edit</button>
                            <button class="btn btn-danger btn-xs btn-delete delete-service" value="{{$services['data'][$i]['id']}}">Delete</button>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    <br />    
    <h2>Monitors</h2>
    <button id="btn-add" name="btn-add" class="btn btn-success btn-xs" data-toggle="modal" data-target="#monitor">Add Monitor</button>
    <div class="row">
        <div class="table-responsive">
            <br />
            <!-- Table-to-load-the-data Part -->
            <table class="table" id="monitors-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Module</th>
                        <th>State</th>
                        <th>Servers</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="monitors-list" name="monitors-list">
                    @for ($i = 0; $i < count($monitors['data']); $i++)
                    <tr id="monitor{{$monitors['data'][$i]['id']}}">
                        <td>{{$monitors['data'][$i]['id']}}</td>
                        <td>{{$monitors['data'][$i]['type']}}</td>
                        <td>{{$monitors['data'][$i]['attributes']['module']}}</td>
                        <td>{{$monitors['data'][$i]['attributes']['state']}}</td>
                        <td>
                            @isset($monitors['data'][$i]['relationships']['servers']['data'])
                                @for($y = 0; $y < count($monitors['data'][$i]['relationships']['servers']['data']); $y++)
                                    {{$monitors['data'][$i]['relationships']['servers']['data'][$y]['id']}} 
                                @endfor
                            @endisset
                        </td>
                        <td>
                            @if($monitors['data'][$i]['attributes']['state'] === "Running")
                                <button class="btn btn-warning btn-xs btn-detail stop-monitor" value="{{$monitors['data'][$i]['id']}}">Stop</button>  
                            @else
                                <button class="btn btn-success btn-xs btn-detail start-monitor" value="{{$monitors['data'][$i]['id']}}">Start</button>
                            @endif
                            <button class="btn btn-info btn-xs btn-detail edit-monitor" value="{{$monitors['data'][$i]['id']}}">Edit</button>
                            <button class="btn btn-danger btn-xs btn-delete delete-monitor" value="{{$monitors['data'][$i]['id']}}">Delete</button>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <!-- End of Table-to-load-the-data Part -->
        <!-- Modal (Pop up when detail button clicked) -->
        <div class="modal fade" id="monitor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Monitor Editor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>                        
                    </div>
                    <div class="modal-body">
                        <form id="addmonitor" name="addmonitor" class="form-horizontal" novalidate="">
                            {{ csrf_field() }}
                            <div class="form-group error">
                                <label for="monitor_id" class="col-sm-3 control-label">Monitor Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="monitor_id" name="monitor_id" placeholder="ID" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="service_type" class="col-sm-3 control-label">Monitor Type</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="monitor_type" name="monitor_type" placeholder="monitors" value="">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="port" class="col-sm-3 control-label">Module</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="module" name="module" placeholder="mariadbmon" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                    <label for="port" class="col-sm-3 control-label">Monitor Interval</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="monitor_interval" name="monitor_interval" placeholder="1000" value="">
                                    </div>
                            </div>
                            <div class="form-group">
                                <label for="monuser" class="col-sm-3 control-label">Monitor User</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="monuser" name="monuser" placeholder="user" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="monpass" class="col-sm-3 control-label">Monitor Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="monpass" name="monpass" placeholder="password" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="port" class="col-sm-3 control-label">Servers</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="servers" name="servers" placeholder="Separate by comma" value="">
                                </div>
                            </div>
                            <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="add-mon" value="add">Save changes</button>
                                    <input type="hidden" id="monitorid" name="monitorid" value="0">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="service" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Service Editor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>                        
                    </div>
                    <div class="modal-body">
                        <form id="addservice" name="addservice" class="form-horizontal" novalidate="">
                            {{ csrf_field() }}
                            <div class="form-group error">
                                <label for="service_id" class="col-sm-3 control-label">Service Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="service_id" name="service_id" placeholder="ID" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="service_type" class="col-sm-3 control-label">Service Type</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="service_type" name="service_type" placeholder="services" value="">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="service_module" class="col-sm-3 control-label">Router Module</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="service_module" name="service_module" placeholder="" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                    <label for="user" class="col-sm-3 control-label">User</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="user" name="user" placeholder="" value="">
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="password" class="col-sm-3 control-label">Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="" value="">
                                    </div>
                            </div>
                            <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="add-service" value="add">Save changes</button>
                                    <input type="hidden" id="serviceid" name="serviceid" value="0">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var table = $('#services-table').DataTable();
        var table = $('#monitors-table').DataTable();
    } );
</script>
@endsection