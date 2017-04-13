@extends('layouts.app')

@section('content')

    <div class="content">
        @include('flash::message')
        @if(!isset($_POST['week']))
            <hr>
            <?php function getListDate($y, $m, $w)
            {
                if ($w > 2) {
                    $period = 1;
                } else {
                    $period = 2;
                }

                $totalDay = date('t', mktime(0, 0, 0, $m, 1, $y)); 
                $totalDayWeek = 7;
                if ($w == 4) {
                    $totalDayWeek = $totalDay - 21;
                }
                $listDate;
                for ($i = 1; $i < $totalDayWeek + 1; $i++) {
                    $listDate[] = $y . '-' . $m . '-' . $i;
                }
                return array('period' => $period, 'week' => $w, 'year' => $y, 'listDate' => $listDate);
            }

            function isWeekend($date)
            {
                return (date('N', strtotime($date)) >= 6);
            }

            ?>


            <div class="clearfix"></div>


            {!! Form::open(['route' => 'add_timesheet.create','id'=>'create_timesheet']) !!}

            <div class="clearfix"></div>

            <?php 
            function rupiahFormat($value){
                return number_format($value, 2,',', '.');
            }
            ?>

            <div class="col-md-12">
            

            @if(count($alert)>0)
            <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Approval Notes !</h3>

                  <div class="box-tools pull-right">
                    <span class="label label-danger"></span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>-->
                  </div>
                </div>
                <div class="box-body">
                @section('css')
            @include('layouts.datatables_css')
            @endsection

                {!! $html->table() !!}
            
                </div>
              </div>
            @endif
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">TIMESHEET SUMMARY</h3>
                    </div>
                    <div class="box-body">
                        <table class="summary project table">
                            <tbody>
                            <tr>
                                <th>JABODETABEK</th>
                                <th style="width:100px">HARI</th>
                                <th style="width:100px"></th>
                                <th>JUMLAH</th>
                            </tr>
                            <tr>
                                <td>Tarif Insentif</td>
                                <td rowspan="5">{{$summary['lokal']['count']}} Hari</td>
                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['lokal']['Insentif Project'])}}</td>
                            </tr>
                            <tr>
                                <td>Tarif Transport Lokal</td>

                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['lokal']['Transport Lokal'])}}</td>
                            </tr>
                            <tr>
                                <td>Tarif Insentif Luar Kota</td>

                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['lokal']['Transport Luar Kota'])}}</td>
                            </tr>
                            <tr>

                            </tr>
                            <tr>

                            </tr>
                            <tr>
                                <th>NON LOKAL JAWA</th>
                                <td></td>

                                <td></td>
                            </tr>
                            <tr>
                                <td>Tarif Insentif</td>
                                <td rowspan="5">{{$summary['non_lokal']['count']}} Hari</td>
                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['non_lokal']['Insentif Project'])}}</td>
                            </tr>
                            <tr>
                                <td>Tarif Transport Lokal</td>

                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['non_lokal']['Transport Lokal'])}}</td>
                            </tr>
                            <tr>
                                <td>Tarif Insentif Luar Kota</td>

                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['non_lokal']['Transport Luar Kota'])}}</td>
                            </tr>
                            <tr>

                            </tr>
                            <tr>

                            </tr>
                            <tr>
                                <th>LUAR JAWA DALAM NEGRI</th>

                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Tarif Insentif</td>
                                <td rowspan="5">{{$summary['luar_jawa']['count']}} Hari</td>
                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['luar_jawa']['Insentif Project'])}}</td>
                            </tr>
                            <tr>
                                <td>Tarif Transport Lokal</td>

                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['luar_jawa']['Transport Lokal'])}}</td>
                            </tr>
                            <tr>
                                <td>Tarif Insentif Luar Kota</td>

                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['luar_jawa']['Transport Luar Kota'])}}</td>
                            </tr>
                            <tr>

                            </tr>
                            <tr>

                            </tr>
                            <tr>
                                <th>LUAR NEGRI</th>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Tarif Insentif</td>
                                <td rowspan="3">{{$summary['internasional']['count']}} Hari</td>
                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['internasional']['Insentif Project'])}}</td>
                            </tr>
                            <tr>
                                <td>Tarif Transport Lokal</td>

                                <td></td>
                                <td>Rp. {{rupiahFormat($summary['internasional']['Transport Lokal'])}}</td>
                            </tr>
                            <tr>
                                <td></td>

                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>Tunjangan Bantuan Perumahan</b></td>
                                <td></td>
                                <td>Rp. {!! rupiahFormat($sum_timesheet_insentif) !!}</td>
                                <td>Rp. {!! rupiahFormat($sum_timesheet_insentif) !!}</td>
                            </tr>
                            <tr>
                                <td><b>Fasilitas Transport Proyek Konsultasi Luar Kota</b></td>
                                <td></td>
                                <td>Rp. {!! rupiahFormat($sum_timesheet_transport) !!}</td>
                                <td>Rp. {!! rupiahFormat($sum_timesheet_transport )!!}</td>
                            </tr>
                            <tr>
                                <th>TOTAL</th>

                                <th></th>
                                <th></th>
                                <th>Rp.
                                    {!! rupiahFormat($summary['lokal']['Insentif Project'] + $summary['lokal']['Transport Lokal']+ $summary['lokal']['Transport Luar Kota']+$summary['non_lokal']['Insentif Project']+$summary['non_lokal']['Transport Lokal']+$summary['non_lokal']['Transport Luar Kota']+$summary['luar_jawa']['Insentif Project']+$summary['luar_jawa']['Transport Lokal']+$summary['luar_jawa']['Transport Luar Kota']+$summary['internasional']['Insentif Project']+$summary['internasional']['Transport Lokal']+$sum_timesheet_insentif + $sum_timesheet_transport) !!}
                                </th>
                            </tr>
                            <tr>
                                <th></th>

                                <th></th>
                                <th></th>
                                <!--<th>$ 0.00</th>-->
                            </tr>

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
                                <th>Approval</th>
                            </tr>
                            @foreach ($timesheet_details as $row=>$detail)
                                @if($detail->approval_status == 1 && $detail->selected==1)
                                    <tr {!! isWeekend(date('d-m-Y', strtotime(str_replace(' 00:00:00','',$detail->date)))) ? 'style="background-color: antiquewhite; "' : ''; !!}>
                                        {{ Form::hidden('timesheet['.$row.'][id]', $detail->id) }}
                                        <td class="col-md-1">
                                            {{ Form::checkbox('timesheet['.$row.'][select]', true, $detail->selected,['disabled'=>'']) }}
                                            {{ Form::hidden('timesheet['.$row.'][select]',  $detail->selected) }}
                                            {!! Form::hidden('timesheet['.$row.'][approval_status]', $detail->approval_status) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('timesheet['.$row.'][project]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control ','disabled'=>'disabled']) !!}
                                            {!! Form::hidden('timesheet['.$row.'][project]', $detail->project_id) !!}
                                        </td>
                                        <td>{{date('D , d-m-Y', strtotime(str_replace(' 00:00:00','',$detail->date)))}}{{ Form::hidden('timesheet['.$row.'][date]', str_replace(' 00:00:00','',$detail->date)) }}</td>
                                        <td class="col-xs-4 col-sm-2 col-md-1"><input type="text" name="timesheet[{{$row}}][start]"
                                                   class="form-control timepicker" placeholder="00:00"
                                                   value="{{ $detail->start_time }}" readonly></td>
                                        <td class="col-xs-4 col-sm-2 col-md-1"><input type="text" name="timesheet[{{$row}}][end]"
                                                   class="form-control timepicker" placeholder="00:00"
                                                   value="{{ $detail->end_time }}" readonly></td>

                                        <td>
                                            {!! Form::select('timesheet['.$row.'][lokasi]', [''=>'']+$lokasi, $detail->lokasi, ['class' => 'form-control select2','id'=>'timesheet'.$row.'lokasi','disabled'=>'']) !!}
                                            {!! Form::hidden('timesheet['.$row.'][lokasi]',$detail->lokasi) !!}
                                        </td>
                                        <td class="col-md-2">
                                            {!! Form::select('timesheet['.$row.'][activity]', [''=>'']+$activity, $detail->activity, ['class' => 'form-control select2','id'=>'timesheet'.$row.'activity','onchange'=>'onChangeActivity('.$row.')','disabled'=>'']) !!}
                                            {!! Form::hidden('timesheet['.$row.'][activity]', $detail->activity) !!}
                                            <input type="text" name="timesheet[{{$row}}][activity_other]"
                                                   class="form-control" id="timesheet{{$row}}activity_other"
                                                   value="{{$detail->activity_detail}}" style="display:none;" readonly>
                                        </td>

                                        <td>{!! $detail->status !!}</td>
                                    </tr>
                                @else
                                    <tr {!! isWeekend(date('d-m-Y', strtotime(str_replace(' 00:00:00','',$detail->date)))) ? 'style="background-color: antiquewhite; "' : ''; !!}>
                                        {{ Form::hidden('timesheet['.$row.'][id]', $detail->id) }}
                                        <td class="col-md-1">
                                            {{ Form::checkbox('timesheet['.$row.'][select]', true, $detail->selected) }}                                         
                                        {!! Form::hidden('timesheet['.$row.'][approval_status]', $detail->approval_status) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('timesheet['.$row.'][project]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2']) !!}
                                        </td>
                                        <td>{{date('D , d-m-Y', strtotime(str_replace(' 00:00:00','',$detail->date)))}}{{ Form::hidden('timesheet['.$row.'][date]', str_replace(' 00:00:00','',$detail->date)) }}</td>
                                        <td class="col-xs-4 col-sm-2 col-md-1"><input type="text" name="timesheet[{{$row}}][start]"
                                                   class="form-control timepicker" placeholder="00:00"
                                                   value="{{ $detail->start_time }}"></td>
                                        <td class="col-xs-4 col-sm-2 col-md-1"><input type="text" name="timesheet[{{$row}}][end]"
                                                   class="form-control timepicker" placeholder="00:00"
                                                   value="{{ $detail->end_time }}"></td>

                                        <td>
                                            {!! Form::select('timesheet['.$row.'][lokasi]', [''=>'']+$lokasi, $detail->lokasi, ['class' => 'form-control select2','id'=>'timesheet'.$row.'lokasi']) !!}
                                        </td>
                                        <td class="col-md-2">
                                            {!! Form::select('timesheet['.$row.'][activity]', [''=>'']+$activity, $detail->activity, ['class' => 'form-control select2','id'=>'timesheet'.$row.'activity','onchange'=>'onChangeActivity('.$row.')']) !!}
                                            <input type="text" name="timesheet[{{$row}}][activity_other]"
                                                   class="form-control" id="timesheet{{$row}}activity_other"
                                                   value="{{$detail->activity_detail}}" style="display:none;">
                                        </td>

                                        <td>{!! $detail->status !!}</td>
                                    </tr>
                                @endif

                            @endforeach
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
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th>Approval</th>
                                <th><a href="javascript:void(0);" style="font-size:18px;" id="addInsentif"
                                       title="Add Insentif"><span class="glyphicon glyphicon-plus"></span></a>
                                </th>
                            </tr>
                            @foreach ($timesheet_insentif as $row=>$detail)
                                @if($detail->status != 1)
                                <tr>

                                    <td>
                                    {{ Form::hidden('insentif['.$row.'][guid]', $detail->guid) }}
                                    {{ Form::hidden('insentif['.$row.'][id]', $detail->id) }}
                                    {{ Form::date('insentif['.$row.'][date]', $detail->date, array('class' => 'form-control','data-date-format'=>'dd/mm/yyyy')) }}
                                    <td>
                                        {!! Form::select('insentif['.$row.'][project_id]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2']) !!}
                                    </td>
                                    <td>
                                        {!! Form::select('insentif['.$row.'][lokasi]', [''=>'']+$nonlokal, $detail->Lokasi, ['class' => 'form-control ','onchange'=>'onChangeLocation(this,'.$row.')']) !!}
                                    </td>
                                    <td>
                                    {{ Form::text('insentif['.$row.'][value]', $detail->value, array('class' => 'form-control money','id'=>'insentiv'.$row.'value','readonly'=>'')) }}
                                    <td>
                                        {{ Form::text('insentif['.$row.'][desc]', $detail->keterangan, array('class' => 'form-control')) }}
                                    </td>
                                    <td>{!!$detail->approval!!}</td>
                                    <td><a href="javascript:void(0);" class="remove"><span
                                                    class="glyphicon glyphicon-remove"></span></a></td>
                                </tr>
                                @else
                                <tr>
                                    <td>
                                    {{ Form::hidden('insentif['.$row.'][id]', $detail->id) }}
                                    {{ Form::hidden('insentif['.$row.'][guid]', $detail->guid) }}
                                    {{ Form::date('insentif['.$row.'][date]', $detail->date, array('class' => 'form-control','disabled'=>'','data-date-format'=>'dd/mm/yyyy')) }}
                                    {{ Form::hidden('insentif['.$row.'][date]', $detail->date) }}
                                    <td>
                                    {{ Form::hidden('insentif['.$row.'][project_id]', $detail->project_id) }}
                                    {!! Form::select('insentif['.$row.'][project_id]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2','disabled'=>'']) !!}
                                    </td>
                                    <td>
                                    {{ Form::hidden('insentif['.$row.'][lokasi]', $detail->Lokasi) }}
                                    {!! Form::select('insentif['.$row.'][lokasi]', [''=>'']+$nonlokal, $detail->Lokasi, ['class' => 'form-control ','onchange'=>'onChangeLocation(this,'.$row.')','disabled'=>'']) !!}
                                    </td>
                                    <td>
                                    {{ Form::text('insentif['.$row.'][value]', $detail->value, array('class' => 'form-control money','id'=>'insentiv'.$row.'value','readonly'=>'')) }}
                                    <td>
                                        {{ Form::text('insentif['.$row.'][desc]', $detail->keterangan, array('class' => 'form-control','readonly'=>'')) }}
                                    </td>
                                    <td>{!!$detail->approval!!}</td>
                                    <td><a href="javascript:void(0);" class="remove"><span
                                                    class="glyphicon glyphicon-remove"></span></a></td>
                                </tr>
                                @endif
                            @endforeach
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
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th>File</th>
                                <th>Approval</th>
                                <th><a href="javascript:void(0);" style="font-size:18px;" id="addTransportasi"
                                       title="Add Transportasi"><span class="glyphicon glyphicon-plus"></span></a>
                                </th>
                            </tr>
                            @foreach ($timesheet_transport as $row=>$detail)
                                @if($detail->status != 1)
                                <tr>
                                    <td>
                                    {{ Form::hidden('trans['.$row.'][id]', $detail->id) }}
                                    {{ Form::hidden('trans['.$row.'][guid]', $detail->guid) }}
                                    {{ Form::date('trans['.$row.'][date]', $detail->date, array('class' => 'form-control','data-date-format'=>'dd/mm/yyyy')) }}
                                    <td>
                                        {!! Form::select('trans['.$row.'][project_id]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2']) !!}
                                    </td>
                                    <td>
                                    {{ Form::text('trans['.$row.'][value]', $detail->value, array('class' => 'form-control money')) }}
                                    <td>
                                        {{ Form::text('trans['.$row.'][desc]', $detail->keterangan, array('class' => 'form-control')) }}
                                    </td>
                                    <td>
                                    <center>
                                            <p>
                                                <a href="javascript:changeProfile({{$row}})" style="text-decoration: none;"><i                               class="glyphicon glyphicon-edit"></i> Change</a>&nbsp;&nbsp;
                                                <a href="javascript:removeFile({{$row}})" style="color: red;text-decoration: none;"><i
                                                            class="glyphicon glyphicon-trash"></i>
                                                    Remove</a>&nbsp;&nbsp;
                                                <a target="_blank" href="{{url('dl')}}/{{$detail->file}}" id="dl{{$row}}">{{$detail->file}}</a>
                                            </p>
                                            <input type="text" name="trans[{{$row}}][file]" id="flname{{$row}}" style="display: none" value="{{$detail->file}}">
                                            <input type="file" id="file{{$row}}" onchange="fileChange({{$row}})" style="display: none"/>
                                        </center>
                                    </td>
                                    <td>{!!$detail->approval!!}</td>
                                    <td><a href="javascript:void(0);" class="remove">
                                    <span class="glyphicon glyphicon-remove"></span></a></td>
                                </tr>
                                @else
                                <tr>
                                    <td>
                                    {{ Form::hidden('trans['.$row.'][id]', $detail->id) }}
                                    {{ Form::hidden('trans['.$row.'][guid]', $detail->guid) }}
                                    {{ Form::hidden('trans['.$row.'][date]', $detail->date) }}
                                    {{ Form::date('trans['.$row.'][date]', $detail->date, array('class' => 'form-control','disabled'=>'','data-date-format'=>'dd/mm/yyyy')) }}
                                    <td>
                                    {{ Form::hidden('trans['.$row.'][project_id]', $detail->project_id) }}
                                    {!! Form::select('trans['.$row.'][project_id]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2','disabled'=>'']) !!}
                                    </td>
                                    <td>
                                    {{ Form::hidden('trans['.$row.'][value]', $detail->value) }}
                                    {{ Form::text('trans['.$row.'][value]', $detail->value, array('class' => 'form-control money','readonly'=>'')) }}
                                    <td>
                                        {{ Form::text('trans['.$row.'][desc]', $detail->keterangan, array('class' => 'form-control','readonly'=>'')) }}
                                    </td>
                                    <td>
                                    <center>
                                            <p>
                                                <a target="_blank" href="{{url('dl')}}/{{$detail->file}}" id="dl{{$row}}">{{$detail->file}}</a>
                                            </p>
                                            <input type="text" name="trans[{{$row}}][file]" id="flname{{$row}}" style="display: none" value="{{$detail->file}}">
                                            <input type="file" id="file{{$row}}" onchange="fileChange({{$row}})" style="display: none"/>
                                        </center>
                                    </td>
                                    <td>{!!$detail->approval!!}</td>
                                    <td><a href="javascript:void(0);" class="remove">
                                    <span class="glyphicon glyphicon-remove"></span></a></td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="clearfix"></div>


            {{ Form::hidden('edit', $id) }}
            {{ Form::hidden('month', $timesheet->month) }}
            {{ Form::hidden('year', $timesheet->year) }}
            {{ Form::hidden('week', $timesheet->week) }}
            {{ Form::hidden('period', $timesheet->periode) }}

            <div class="form-group col-sm-12">
            @if($timesheet->action === 'Disimpan')
<!--  {!! Form::submit('Save',['name'=>'action','class' => 'btn btn-primary','id'=>'saveBtn']) !!} -->            @endif
            {!! Form::submit('Submit',['name'=>'action','class' => 'btn btn-primary','id'=>'submitBtn']) !!}
            </div>
            <div class="clearfix"></div>
            {!! Form::close() !!}

        @endif
    </div>


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-masker/1.1.0/vanilla-masker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay_progress.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js"></script>
     @include('layouts.datatables_js')
                {!! $html->scripts() !!}
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
        $(document).ready(function () {
            for (i = 0; i < 10; i++) {
                if (($('#select2-timesheet' + i + 'activity-container').text() === 'IMPLEMENTASI') || ($('#select2-timesheet' + i + 'activity-container').text() === 'MANAGED OPERATION') || ($('#select2-timesheet' + i + 'activity-container').text() === 'IDLE')) {
                    $('#timesheet' + i + 'activity_other').show();
                } else {
                }
            }
            $('.content').find('.select2-container--default').removeAttr("style");
            $('.content').find('.select2-container--default').css('width', '100%');
        })

        function onChangeActivity(id) {
            setTimeout(function () {
                var selected = $("[id*=select2-timesheet" + id + "activity]").text();
                if (selected === 'SUPPORT') {
                    $('#timesheet' + id + 'lokasi').val("UNCLAIMABLE").trigger("change");
                    $('#timesheet' + id + 'lokasi').prop("disabled", true);
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
                   $('#timesheet' + id + 'lokasi').prop("disabled", true);
                }

                else if (selected === 'MANAGED OPERATION') {
                    $('#timesheet' + id + 'activity_other').show();
                    //  $('#timesheet'+id+'lokasi').val("").trigger("change");
                    $('#timesheet' + id + 'lokasi').val("UNCLAIMABLE").trigger("change");
                   $('#timesheet' + id + 'lokasi').prop("disabled", true);
                }
                else {
                    $('#timesheet' + id + 'activity_other').hide();
                    //    $('#timesheet'+id+'lokasi').val("").trigger("change");
                    $('#timesheet' + id + 'lokasi').val("UNCLAIMABLE").trigger("change");
                    $('#timesheet' + id + 'lokasi').prop("disabled", true);
                }

            }, 50);

        }
        $(".timepicker").timepicker({
            showInputs: false,
            showMeridian: false
        });

        function getRowTransport(id) {
            var row = '<tr><input type = "hidden" name="trans[' + id + '][guid]" value="'+generateGuid()+'" />' +
                '   <td><input name="trans[' + id + '][id]" type="hidden" value="' + id + '"><input type="date" data-date-format="dd/mm/yyyy" name="trans[' + id + '][date]" value="{!!date("Y-m-d")!!}" class="form-control" ></td>  ' +
                '   <td><select class="form-control" name="trans[' + id + '][project_id]"  ><option value="" selected="selected"></option>' +
                '<?php
                    foreach ($project as $key => $value) {
                        echo ' <option value="' . $key . '">' . $value . '</option>';
                    }

                    ?>' +
                '</select></td>' +
                '   <td><input type="text" name="trans[' + id + '][value]" class="form-control money"  ></td>  ' +
                '   <td><input type="text" name="trans[' + id + '][desc]" class="form-control"  ></td>  ' +
                                '   <td> '+
                '<center>'+
'                <p>'+
'                    <a href="javascript:changeProfile('+id+')" style="text-decoration: none;"><i                               class="glyphicon glyphicon-edit"></i> Change</a>  '+
'                    <a href="javascript:removeFile('+id+')" style="color: red;text-decoration: none;"><i'+
'                                class="glyphicon glyphicon-trash"></i>'+
'                        Remove</a>  <a target="_blank" href="" id="dl'+id+'"></a>'+
'                </p>'+
'                <input type="text" name="trans['+id+'][file]" id="flname'+id+'" style="display: none;">'+
'                <input type="file" id="file'+id+'" onchange="fileChange('+id+')" style="display: none"/>'+
'            </center>'+
                ' </td><td></td>' +
                '   <td><a href="javascript:void(0);"  class="remove"><span class="glyphicon glyphicon-remove"></span></a></td>  ' +
                '   </tr>  ' +
                '    ';
            return row;
        }

        function getRowInsentif(id) {
            var row = '<tr><input type = "hidden" name="insentif[' + id + '][guid]" value="'+generateGuid()+'" />' +
                '   <td><input name="insentif[' + id + '][id]" type="hidden" value="' + id + '"><input type="date" data-date-format="dd/mm/yyyy" name="insentif[' + id + '][date]" value="{!!date("Y-m-d")!!}" class="form-control"  ></td>  ' +
                '   <td><select class="form-control" name="insentif[' + id + '][project_id]"><option value="" selected="selected"  ></option>' +
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
                '   <td><input type="text" name="insentif[' + id + '][value]"  id="insentiv' + id + 'value" class="form-control money" ></td>  ' +
                '   <td><input type="text" name="insentif[' + id + '][desc]" class="form-control" ></td><td></td>  ' +
                '   <td><a href="javascript:void(0);"  class="remove"><span class="glyphicon glyphicon-remove"></span></a></td>  ' +
                '   </tr>  ' +
                '    ';
            return row;
        }

        $(function () {
            var id ={!! count($timesheet_transport) !!};
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
            var id ={!! count($timesheet_insentif) !!};
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


</script>

@endsection
@endsection

<!--@section('scripts')
           
@endsection-->
