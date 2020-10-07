@extends('layouts.app')
@section('styles')
    <link href="{{ asset('plugins\daterangepicker\daterangepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins\select2\dist\css\select2.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins\select2\dist\css\select2-bootstrap.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Temperature Logs</div>
                <div class="card-body">
                    <form action="" method="post" class="form-inline">
                        @csrf
                        <input type="text" name="period" class="form-control form-control-sm mr-md-3 mt-2" style="min-width: 210px;" autocomplete="off" id="period" value="{{$period}}" placeholder="Select Date">
                        <select name="user_id" id="search_user" class="form-control form-control-sm mt-2">
                            <option value="">Select Employee</option>
                            @foreach ($users as $item)
                                <option value="{{$item->id}}" @if($item->id == $user_id) selected @endif>{{$item->name}} [{{$item->employee_id}}]</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm mt-2 ml-2">Search</button>
                        <button type="button" id="btn_reset" class="btn btn-sm btn-danger mt-2 ml-2">Reset</button>
                    </form>
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered">
                            <thead>
                                <th>No</th>
                                <th>Date & Time</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Card ID</th>
                                <th>Temperature</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
                                        <td>{{$item->datetime}}</td>
                                        <td>{{$item->user->employee_id ?? ''}}</td>
                                        <td>{{$item->user->name ?? ''}}</td>
                                        <td>{{$item->card_id}}</td>
                                        <td>{{$item->temperature}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="clearfix mt-2">
                            <div class="float-left" style="margin: 0;">
                                <p>Total <strong style="color: red">{{ $data->total() }}</strong> Entries</p>
                            </div>
                            <div class="float-right" style="margin: 0;">
                                {!! $data->appends([
                                    'user_id' => $user_id,
                                    'period' => $period,
                                ])->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{asset('plugins\daterangepicker\jquery.daterangepicker.min.js')}}"></script>
    <script src="{{asset('plugins\select2\dist\js\select2.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $("#period").dateRangePicker({
                autoClose: false,
            });

            $("#btn_reset").click(function () {
                $("#period").val('');
                $("#search_user").val('').change();
            });

            $('#search_user').wrap('<div class="mt-2"></div>')
            .select2({
                width: 'resolve',
                placeholder: 'Select Employee',
            });
        });
    </script>
@endsection
