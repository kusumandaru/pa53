@inject('count', 'App\Services\TimesheetCountService')
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Timesheet Finance</h1>
        <br/><br/>

    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
<hr>

        <form class="form-horizontal">
            <div class="form-group">
              <label for="type" class="col-sm-2 control-label">Project</label>   
              <div class="col-sm-7">
               {!! Form::select('type',
                                  $projects,
                                  isset($_GET['subtype']) && isset($_GET['type']) ? $_GET['type'] : $projects,
                                  ['class' => 'form-control select2'])
                                !!}
              </div>
            </div>
            <div class="form-group">
              <label for="subtype" class="col-sm-2 control-label">Periode</label>       
              <div class="col-sm-7">
              {!! Form::select('subtype',
                                  array(''=>'',1 => 1,2 =>2),
                                  isset($_GET['subtype']) && isset($_GET['type']) ? $_GET['subtype'] : '',
                                  ['class' => 'form-control select2'])
                                !!}
                
                
              </div>
              <div class="form-group">
              <button class="btn btn-info btn-flat">submit</button>
                
              </div>
            </div>        
             <div class="form-group">
              <?php if(isset($_GET['subtype']) && isset($_GET['type'])){
    ?>
<a href="{{ URL::route('report.finance.download',['id'=>$_GET['type'],'period'=>$_GET['subtype']]) }}" class="btn btn-default">Excel</a>
<a href="{{ URL::route('report.finance.downloadpdf',['id'=>$_GET['type'],'period'=>$_GET['subtype']]) }}" class="btn btn-default">PDF</a>

<?php } ?>
              <div class="col-sm-5">
              </div>
            </div>    
          </form>      
             <div class="box-body">
             
               @section('css')
            @include('layouts.datatables_css')
            @endsection

                {!! $html->table() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@include('layouts.datatables_js')
               {!! $html->scripts() !!}
    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            //$('.approvalstatus option[value=val2]').attr('selected','selected');
        });

        var Select2Cascade = ( function(window, $) {

    function Select2Cascade(parent, child, url, select2Options) {
        var afterActions = [];
        var options = select2Options || {};

        // Register functions to be called after cascading data loading done
        this.then = function(callback) {
            afterActions.push(callback);
            return this;
        };

        parent.select2(select2Options).on("change", function (e) {

            child.prop("disabled", true);

            var _this = this;
            $.getJSON(url.replace(':parentId:', $(this).val()), function(items) {
                var newOptions = '<option value="">-- Select --</option>';
                for(var id in items) {
                    newOptions += '<option value="'+ id +'">'+ items[id] +'</option>';
                }

                child.select2('destroy').html(newOptions).prop("disabled", false)
                    .select2(options);
                
                afterActions.forEach(function (callback) {
                    callback(parent, child, items);
                });
            });
        });
    }

    return Select2Cascade;

})( window, $);

$(document).ready(function() {
    var select2Options = { width: 'resolve' };
    var apiUrl = '{{ URL::to("/") }}/project_member/:parentId:';
    
    $('select').select2(select2Options);                 
    var cascadLoading = new Select2Cascade($('#type'), $('#subtype'), apiUrl, select2Options);
    cascadLoading.then( function(parent, child, items) {
        // Dump response data
        console.log(items);
    });
});
    </script>
@endsection

