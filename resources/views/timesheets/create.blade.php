@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Timesheet
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'timesheets.store']) !!}

                    @include('timesheets.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
