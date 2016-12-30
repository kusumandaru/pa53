@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Timesheet Transport
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'timesheetTransports.store']) !!}

                    @include('timesheet_transports.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
