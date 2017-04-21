@extends('layouts.app')
@inject('date', 'App\Services\DateService')

@section('content')

    @include('flash::message')
    {!! Form::open(['route' => 'add_timesheet.form','data-toggle'=>'validator']) !!}


    <div class="box-body">
        <div class="input-group input-group-sm">
                <span class="input-group-btn">
                    <button type="reset" class="btn" disabled="disabled">Timesheet</button>
                </span>
            {!! Form::select('week',
              [1 => 'Week 1', 2 => 'Week 2', 3 => 'Week 3', 4 => 'Week 4'],
              $date->year,
              ['class' => 'form-control select2', 'id' => 'year', 'style'=>'width: 40%;'])
            !!}

            {!! Form::select('month',
            [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'Juny', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'],
            $date->month,
            ['class' => 'form-control select2', 'id' => 'week', 'style'=>'width: 40%;'])
            !!}

            {!! Form::select('year',
              [2017 => '2017', 2018 => '2018', 2019 => '2019', 2020 => '2020'],
              $date->year,
              ['class' => 'form-control select2', 'id' => 'year', 'style'=>'width: 20%;'])
            !!}
            <span class="input-group-btn">
                    <button type="submit" class="btn btn-info btn-flat">Tampilkan</button>
                </span>
        </div>
    </div>

    </form>



    <!--    <section class="content-header">
            <h1 class="pull-left">Create</h1>
            <h1 class="pull-right">

            </h1>
        </section>-->
    <div class="content">

        @include('flash::message')

        @if(isset($_POST['week']))
            <hr>
            <?php function getListDate($y, $m, $w)
            {
                if ($w > 2) {
                    $period = 2;
                } else {
                    $period = 1;
                }

                $totalDay = date('t', mktime(0, 0, 0, $m, 1, $y)); 
                $totalDayWeek = 7;
                if ($w == 4) {
                    $totalDayWeek = $totalDay - 21;
                }
                switch ($w) {
                    case 1:
                        $d = 1;
                        break;
                    case 2:
                        $d = 8;
                        break;
                    case 3:
                        $d = 15;
                        break;
                    case 4:
                        $d = 22;
                        break;
                }
                $listDate;
                for ($i = 1; $i < $totalDayWeek + 1; $i++) {
                    $listDate[] = $y . '-' . $m . '-' . $d;
                    $d++;
                }

                return array('period' => $period, 'week' => $w, 'year' => $y, 'listDate' => $listDate);

            }

            function isWeekend($date)
            {
                return (date('N', strtotime($date)) >= 6);
            }

            ?>


            <div class="clearfix"></div>


            {!! Form::open(['route' => 'add_timesheet.create','id'=>'create_timesheet','onsubmit'=>'return beforeSubmit();']) !!}

            <div class="clearfix"></div>





            <div class="clearfix"></div>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Timesheet</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                               <th>
                                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                                </th>
                                <th>Proyek</th>
                                <th>Tanggal</th>
                                <th width="70">Start</th>
                                <th width="70">End</th>
                                <th>Lokasi</th>
                                <th>Aktifitas</th>
                                <!--<th>Keterangan</th>-->
                            </tr>
                            <?php
                            if(isset($_POST['week'])){
                            foreach (getListDate($_POST["year"], $_POST["month"], $_POST["week"])['listDate'] as $row=>$date){
                            ?>
                            <tr {!! isWeekend($date) ? 'style="background-color: antiquewhite; "' : ''; !!}>

                                <td class="col-md-1">
                                    {{ Form::checkbox('timesheet['.$row.'][select]', true, false) }}
                                </td>
                                <td class="col-md-3">
                                    {!! Form::select('timesheet['.$row.'][project]', [''=>'']+$project, null, ['class' => 'form-control select2']) !!}
                                </td>
                                <td class="col-md-1">{{date('D , d-m-Y', strtotime($date))}}{{ Form::hidden('timesheet['.$row.'][date]', $date) }}</td>
                                <td class="col-md-1"><input type="text" name="timesheet[{{$row}}][start]"
                                                            class="form-control timepicker" value="08:00"></td>
                                <td class="col-md-1"><input type="text" name="timesheet[{{$row}}][end]"
                                                            class="form-control timepicker" value="17:00"></td>
                                <td>
                                    {!! Form::select('timesheet['.$row.'][lokasi]', [''=>'']+$lokasi, isWeekend($date) ? 'UNCLAIMABLE' : null, ['class' => 'form-control select2','id'=>'timesheet'.$row.'lokasi']) !!}
                                </td>
                                <td class="col-md-2">
                                    {!! Form::select('timesheet['.$row.'][activity]', [''=>'']+$activity, isWeekend($date) ? 'LIBUR' : null , ['class' => 'form-control select2','id'=>'timesheet'.$row.'activity','onchange'=>'onChangeActivity('.$row.')']) !!}
                                    <input type="text" name="timesheet[{{$row}}][activity_other]" class="form-control"
                                           id="timesheet{{$row}}activity_other" style="display:none;">
                                </td>
                            <!--<td><input type="text" name="timesheet[{{$row}}][keterangan]" class="form-control" placeholder="Keterangan"></td>-->
                            </tr>
                            <?php
                            }
                            }
                            ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="clearfix"></div>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bantuan Perumahan</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-hover small-text" id="tb_insentif">
                            <tr class="tr-header">
                                <th>Tanggal</th>
                                <th>Proyek</th>
                                <th>Lokasi</th>
                                <th>Insentif</th>
                                <th>Keterangan</th>
                                <th><a href="javascript:void(0);" style="font-size:18px;" id="addInsentif"
                                       title="Add Insentif"><span class="glyphicon glyphicon-plus"></span></a></th>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="clearfix"></div>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Transport Proyek Konsultasi Luar Kota</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-hover small-text" id="tb_trasnportasi">
                            <tr class="tr-header">
                                <th>Tanggal</th>
                                <th>Proyek</th>
                                <th>Transportasi</th>
                                <th>Keterangan</th>
                                <th>File</th>
                                <th><a href="javascript:void(0);" style="font-size:18px;" id="addTransportasi"
                                       title="Add Transportasi"><span class="glyphicon glyphicon-plus"></span></a></th>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="clearfix"></div>
            @if(isset($_POST['week']))
                {{ Form::hidden('month', $_POST['month']) }}
                {{ Form::hidden('year', $_POST['year']) }}
                {{ Form::hidden('week', $_POST['week']) }}
                {{ Form::hidden('period', getListDate($_POST["year"],$_POST["month"],$_POST["week"])['period']) }}
            @endif
            <div class="form-group col-sm-12">
                {!! Form::submit('Submit',['name'=>'action','class' => 'btn btn-primary','id'=>'submitBtn']) !!}
              <!--  {!! Form::submit('Save',['name'=>'action','class' => 'btn btn-primary','id'=>'saveBtn']) !!} -->
            </div>
            <div class="clearfix"></div>
            {!! Form::close() !!}

        @endif

    </div>


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.8/validator.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay_progress.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js"></script>
    
    <script>
    function generateGuid() {
  var result, i, j;
  result = '';
  for (j = 0; j < 32; j++) {
    if (j == 8 || j == 12 || j == 16 || j == 20)
      result = result + '-';
    i = Math.floor(Math.random() * 16).toString(16).toUpperCase();
    result = result + i;
  }
  return result
}
$(document).ajaxStart(function(){
    $.LoadingOverlay("show");
});
$(document).ajaxStop(function(){
    $.LoadingOverlay("hide");
});
    </script>
    <script>
        function onChangeActivity(id) {
            setTimeout(function () {
                var selected = $("[id*=select2-timesheet" + id + "activity]").text();
                if (selected === 'SUPPORT') {
                    $('#timesheet' + id + 'lokasi').val("UNCLAIMABLE").trigger("change");
                    $('#timesheet'+id+'lokasi').prop("disabled", true);                   
                    $('#timesheet' + id + 'activity_other').hide();
                }
                else if (selected === 'IMPLEMENTASI') {
                    $('#timesheet' + id + 'activity_other').show();
                    //  $('#timesheet'+id+'lokasi').val("").trigger("change");
                    $('#timesheet' + id + 'lokasi').prop("disabled", false);
                }

                 else if (selected === 'IDLE') {
                    $('#timesheet' + id + 'activity_other').show();
                    //  $('#timesheet'+id+'lokasi').val("").trigger("change");
                    $('#timesheet' + id + 'lokasi').val("UNCLAIMABLE").trigger("change");
                    $('#timesheet'+id+'lokasi').prop("disabled", true); 
                }

                else if (selected === 'MANAGED OPERATION') {
                    $('#timesheet' + id + 'activity_other').show();
                    //  $('#timesheet'+id+'lokasi').val("").trigger("change");
                    $('#timesheet' + id + 'lokasi').val("UNCLAIMABLE").trigger("change");
                    $('#timesheet'+id+'lokasi').prop("disabled", true); 
                    
                }

                else {
                    $('#timesheet' + id + 'activity_other').hide();
                    // $('#timesheet'+id+'lokasi').val("").trigger("change");
                   $('#timesheet' + id + 'lokasi').val("UNCLAIMABLE").trigger("change");
                    $('#timesheet'+id+'lokasi').prop("disabled", true); 
                }

            }, 50);

        }

        $(document).ready(function () {
            //  $('#create_timesheet').validator()
            $('.content').find('.select2-container--default').removeAttr("style");
            $('.content').find('.select2-container--default').css('width', '100%');
        })
        $(".timepicker").timepicker({
            showInputs: false,
            showMeridian: false
        });

        function getRowTransport(id) {
            var row = '<tr><input type = "hidden" name="trans[' + id + '][guid]" value="'+generateGuid()+'" />'+
                '   <td><input type="date" data-date-format="dd/mm/yyyy" name="trans[' + id + '][date]" value="{!!date("Y-m-d")!!}" class="form-control" ></td>  ' +
                '   <td><select class="form-control" name="trans[' + id + '][project_id]" ><option value="" selected="selected"></option>' +
                '<?php
                    foreach ($project as $key => $value) {
                        echo ' <option value="' . $key . '">' . $value . '</option>';
                    }

                    ?>' +
                '</select></td>' +
                '   <td><input type="text" name="trans[' + id + '][value]" class="form-control money" ></td>  ' +
                '   <td><input type="text" name="trans[' + id + '][desc]" class="form-control" ></td>  ' +
                '   <td> '+
                '<center>'+
'                <p>'+
'                    <a href="javascript:changeProfile('+id+')" style="text-decoration: none;"><i class="glyphicon glyphicon-edit"></i> Change</a>  '+
'                    <a href="javascript:removeFile('+id+')" style="color: red;text-decoration: none;"><i'+
'                                class="glyphicon glyphicon-trash"></i>'+
'                        Remove</a>  <a target="_blank" href="" id="dl'+id+'"></a>'+
'                </p>'+
'                <input type="text" name="trans['+id+'][file]" id="flname'+id+'" style="display: none;">'+
'                <input type="file" id="file'+id+'" onchange="fileChange('+id+')" style="display: none"/>'+
'            </center>';
                ' </td>  ' +
                '   <td><a href="javascript:void(0);"  class="remove"><span class="glyphicon glyphicon-remove"></span></a></td>  ' +
                '   </tr>  ' +
                '    ';
            return row;
        }

        function getRowInsentif(id) {
            var row = '<tr>' +'<input type = "hidden" name="insentif[' + id + '][guid]" value="'+generateGuid()+'" />'+
                '   <td><input type="date" data-date-format="dd/mm/yyyy" name="insentif[' + id + '][date]" value="{!!date("Y-m-d")!!}" class="form-control" ></td>  ' +
                '   <td><select class="form-control" name="insentif[' + id + '][project_id]"><option value="" selected="selected" ></option>' +
                '<?php
                    foreach ($project as $key => $value) {
                        echo ' <option value="' . $key . '">' . $value . '</option>';
                    }

                    ?>' +
                '</select></td>' +
                '   <td><select class="form-control" name="insentif[' + id + '][lokasi]"  onchange="onChangeLocation(this,' + id + ')"><option value="" selected="selected"></option>' +
                '<?php
                    foreach ($nonlokal as $key => $value) {
                        echo ' <option value="' . $key . '">' . $value . '</option>';
                    }

                    ?>' +
                '</select></td>' +
                '   <td><input type="text" name="insentif[' + id + '][value]" id="insentiv' + id + 'value" class="form-control money" readonly ></td>  ' +
                '   <td><input type="text" name="insentif[' + id + '][desc]" class="form-control" ></td>  ' +
                '   <td><a href="javascript:void(0);"  class="remove"><span class="glyphicon glyphicon-remove"></span></a></td>  ' +
                '   </tr>  ' +
                '    ';
            return row;
        }

        $(function () {
            var id = 0;
            $('#addTransportasi').on('click', function () {
                //  var data = row.appendTo("#tb_trasnportasi");
                // data.find("input").val('');
                // $("#tb_trasnportasi").append(row);
                $(getRowTransport(id)).appendTo("#tb_trasnportasi");
                id++;
                formatCurr();
            });
            $(document).on('click', '.remove', function () {
                var trIndex = $(this).closest("tr").index();
                $(this).closest("tr").remove();
            });
        });

        $(function () {
            var id = 0;
            $('#addInsentif').on('click', function () {
                //   var data = $("#tb_copy tr:eq(1)").clone(true).appendTo("#tb_insentif");
                //   data.find("input").val('');
                $(getRowInsentif(id)).appendTo("#tb_insentif");
                id++;
                formatCurr();
            });
            $(document).on('click', '.remove', function () {
                var trIndex = $(this).closest("tr").index();
                $(this).closest("tr").remove();
            });
        });

        function onChangeLocation(obj, id) {
            //alert(obj.value);
            if (obj.value === 'JAWA') {
                $('#insentiv' + id + 'value').val({{ $bantuan_perumahan['non_lokal'] }})
            } else if (obj.value === 'LUARJAWA') {
                $('#insentiv' + id + 'value').val({{ $bantuan_perumahan['luar_jawa'] }})
            }
            else if (obj.value === 'INTERNATIONAL') {
                $('#insentiv' + id + 'value').val({{ $bantuan_perumahan['internasional'] }})
            }
            formatCurr();
        }

        $(document).ready(function ($) {
            formatCurr();
            $('#submitBtn').click(function(e){
                e.preventDefault();
                $('[disabled]').removeAttr('disabled');
                $('#create_timesheet').append('<input type = "hidden" name="action" value="Submit" />');
                checkActivity();
                $('#create_timesheet').submit();
            });
            $('#saveBtn').click(function(e){
                e.preventDefault();
                $('[disabled]').removeAttr('disabled');
                $('#create_timesheet').append('<input type = "hidden" name="action" value="Save" />');
                checkActivity();
                $('#create_timesheet').submit();
            });
            $("#create_timesheet").submit(function ($) {
            VMasker(document.querySelectorAll(".money")).unMask();
        });
        });

        function formatCurr(){
            $("#create_timesheet").submit(function ($) {
            VMasker(document.querySelectorAll(".money")).unMask();
        });
            VMasker(document.querySelectorAll(".money")).maskMoney({
                // Decimal precision -> "90"
                precision: 0,
                // Decimal separator -> ",90"
                separator: ',',
                // Number delimiter -> "12.345.678"
                delimiter: '.',
                // Money unit -> "R$ 12.345.678,90"
                unit: 'Rp'
            });
        }





    </script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
      <script>
  $(function () {
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $("input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $("input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });
  });
</script>
<script>
    var filename = '';
    function changeProfile(id) {
        $('#file'+id).click();
    }

    function fileChange(id){
        if ($('#file'+id).val() != '') {
            upload(id);
        }        
    }

    function upload(id) {
        var file_data = $('#file'+id).prop('files')[0];
        if(!(file_data.name.split('.').pop()==='pdf' || ( file_data.name.split('.').pop() ==='jpeg' || file_data.name.split('.').pop() ==='jpg'))){
        alert('mohon upload file dengan exstensi jpeg atau pdf');
        return false;
        }
        if((file_data.size/1000000)>3){
        alert('mohon upload file di bawah 3 MB');
        return false;
        }
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajaxSetup({
            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
        });
        $.ajax({
            url: "{{url('uploadfile')}}", // point to server-side PHP script
            data: form_data,
            type: 'POST',
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData: false,
            success: function (data) {
                if (data.fail) {
                    console.log(data.errors['file']);
                }
                else {
                    filename = data;
                    $('#dl'+id).html(data);
                    $('#dl'+id).attr("href", '{{url('dl')}}/' + data);
                    $("#flname"+id).val(data);
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
                $('#dl'+id).html("");
                $('#dl'+id).attr("href", "");
                $('#dl'+id).html("");
                $("#flname"+id).val("");
            }
        });
    }
    function removeFile(id) {
        filename = $("#flname"+id).val();
        if (filename != '')
            if (confirm('Are you sure want to remove profile picture?'))
                $.ajax({
                    url: "{{url('rmvfile')}}/" + filename, // point to server-side PHP script
                    type: 'GET',
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData: false,
                    success: function (data) {
                        $('#dl'+id).html("");
                        $('#dl'+id).attr("href", "");
                        $('#dl'+id).html("");
                        $("#flname"+id).val("");
                        filename = '';
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
    }

    function fileName(path){
    path = path.substring(path.lastIndexOf("/")+ 1);
    return (path.match(/[^.]+(\.[^?#]+)?/) || [])[0];
}

function checkActivity(){
    var selected = [];
$('input:checked').each(function() {
  selected.push($(this).attr('name'));
});

for (var i = 0; i < selected.length; i++) {
  var row = selected[i].replace('][select]', '').replace('timesheet[', '');
  if ($('#timesheet' + row + 'activity').val() == '' || $('#timesheet' + row + 'activity').val() == null) {
    alert('Mohon lengkapi data timesheet yang telah Anda centang');
    throw new Error('Mohon lengkapi data timesheet yang telah Anda centang');
  }
}
}

function beforeSubmit() {
    if (confirm("Are you sure, you want to submit form?")) {
        return true;
    } 
    return false;
}


</script>
@endsection
@endsection


