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

                $totalDay = cal_days_in_month(CAL_GREGORIAN, $m, $y);
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

            ?>


            <div class="clearfix"></div>
            {!! Form::open(['route' => ['timesheets.moderation.update'], 'method' => 'patch']) !!}

            <div class="clearfix"></div>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">TIMESHEET APPROVAL SUMMARY</h3>
                        <td></td>
                        </br>
                        <h2 class="box-title">{{$user['name']}}</h2>
                    </div>
                    <div class="box-body">
                        <table class="table summary project">
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
                                <td>
                                    {{ Form::text('', $summary['lokal']['Insentif Project'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            <tr>
                                <td>Tarif Transport Lokal</td>
                                <td></td>
                                <td>
                                    {{ Form::text('', $summary['lokal']['Transport Lokal'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            <tr>
                                <td>Tarif Insentif Luar Kota</td>
                                <td></td>
                                <td>
                                    {{ Form::text('', $summary['lokal']['Transport Luar Kota'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
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
                                <td>
                                    {{ Form::text('', $summary['non_lokal']['Insentif Project'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            <tr>
                                <td>Tarif Transport Lokal</td>
                                <td></td>
                                <td>
                                    {{ Form::text('', $summary['non_lokal']['Transport Lokal'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            <tr>
                                <td>Tarif Insentif Luar Kota</td>
                                <td></td>
                                <td>
                                {{ Form::text('', $summary['non_lokal']['Transport Luar Kota'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
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
                                <td>
                                {{ Form::text('', $summary['luar_jawa']['Insentif Project'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            <tr>
                                <td>Tarif Transport Lokal</td>
                                <td></td>
                                <td>
                                {{ Form::text('', $summary['luar_jawa']['Transport Lokal'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            <tr>
                                <td>Tarif Insentif Luar Kota</td>
                                <td></td>
                                <td>
                                {{ Form::text('', $summary['luar_jawa']['Transport Luar Kota'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            <tr>

                            </tr>
                            <tr>

                            </tr>
                            <tr>
                                <th>LUAR NEGERI</th>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Tarif Insentif</td>
                                <td rowspan="3">{{$summary['internasional']['count']}} Hari</td>
                                <td></td>
                                <td>
                                    {{ Form::text('', $summary['internasional']['Insentif Project'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            <tr>
                                <td>Tarif Transport Lokal</td>
                                <td></td>
                                <td>
                                    {{ Form::text('', $summary['internasional']['Transport Lokal'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>

                                <td></td>
                            </tr>
                            <tr>
                                <td><b>Tunjangan Bantuan Perumahan</b></td>

                                <td rowspan="1">{{$summary['perumahan']['count']}} Hari</td>
                                <td></td>
                                <td>
                                    {{ Form::text('', $summary['perumahan']['total'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            </tr>
                            <tr>
                                <td><b>Fasilitas Transport Proyek Konsultasi Luar Kota</b></td>

                                <td rowspan="1">{{$summary['adcost']['count']}} Hari</td>
                                <td></td>
                                <td>
                                    {{ Form::text('', $summary['adcost']['total'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <td>
                            </tr>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <th></th>
                                <th></th>
                                <th>
                                    {{ Form::text('', $summary['total'], array('class' => 'form-control money','readonly'=>'readonly')) }}
                                <th>
                            </tr>
                            <tr>
                                <th></th>

                                <th></th>
                                <th></th>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <div class="clearfix"></div>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Timesheet</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-hover" style="overflow-x: scroll; overflow-y: hidden;">
                            <tbody>
                            <tr>
                                <th>Proyek</th>
                                <th>Tanggal</th>
                                <th width="75">Start</th>
                                <th width="75">End</th>
                                <th>Lokasi</th>
                                <th>Aktifitas</th>
                                <th>
                                    <button type="button" id="detailcheckbox" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                                </th>
                            </tr>
                            @foreach ($timesheet_details as $row=>$detail)
                                <tr>

                                    <td class="col-md-3">
                                        {!! Form::select('timesheetdetail['.$row.'][project]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2', 'disabled']) !!}
                                    </td>
                                    <td class="col-md-1">{{$detail->id_date}}{{ Form::hidden('timesheetdetail['.$row.'][date]', str_replace(' 00:00:00','',$detail->date)) }}</td>
                                    <td class="col-md-1"><input type="text" name="timesheetdetail[{{$row}}][start]"
                                               class="form-control timepicker" placeholder="00:00"
                                               value="{{ $detail->start_time }}" disabled="true"></td>
                                    <td class="col-md-1"><input type="text" name="timesheetdetail[{{$row}}][end]"
                                               class="form-control timepicker" placeholder="00:00"
                                               value="{{ $detail->end_time }}" disabled="true"></td>

                                    <td class="col-md-2">
                                        {!! Form::select('timesheetdetail['.$row.'][lokasi]', [''=>'']+$lokasi, $detail->lokasi, ['class' => 'form-control select2','id'=>'timesheet'.$row.'lokasi', 'disabled']) !!}
                                    </td>
                                    <td class="col-md-3">
                                        {!! Form::select('timesheetdetail['.$row.'][activity]', [''=>'']+$activity, $detail->activity, ['class' => 'form-control select2','id'=>'timesheet'.$row.'activity','onchange'=>'onChangeActivity('.$row.')', 'disabled']) !!}

                                        <input type="textarea" name="timesheetdetail[{{$row}}][activity_other]"
                                               class="form-control" id="timesheet{{$row}}activity_other"
                                               value="{{$detail->activity_detail}}" style="display:visible;"
                                               disabled="true" title="{{$detail->activity_detail}}">
                                    </td>

                                    @if($detail->approval_status_history != 0 && $approval['role']!=4)
                                        <td class="col-md-1">
                                            {!! $detail->status !!}
                                        </td>
                                    @endif
                                    @if($detail->approval_status_history == 0 && $approval['role']!=4)
                                        <td class="col-md-1">
                                            {{ Form::checkbox('timesheetdetail['.$row.'][choose]', true) }}
                                        </td>
                                    @endif
                                    @if(($detail->approval_status == 0 || $detail->approval_status == 1 || $detail->approval_status == 5 || $detail->approval_status == 6)
                                        && $approval['role']==4)
                                        <td class="col-md-1">
                                            {!! $detail->status !!} |
                                            {{ Form::checkbox('timesheetdetail['.$row.'][choose]', true) }}
                                        </td>
                                    @endif
                                    @if($detail->approval_status_history != 0 && $detail->approval_status_history != 1 && $detail->approval_status_history != 5 && $detail->approval_status_history != 6
                                        && $approval['role']==4)
                                        <td class="col-md-1">
                                            {!! $detail->status !!}
                                        </td>
                                    @endif
                                    {{ Form::hidden('timesheetdetail['.$row.'][transaction_id]', $detail->transaction_id) }}
                                    {{ Form::hidden('timesheetdetail['.$row.'][guid]', $detail->guid) }}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bantuan Perumahan</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-hover small-text" id="tb_insentif">
                            <tr class="tr-header">
                                <th>Tanggal</th>
                                <th>Proyek</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                </th></tr>
                            @foreach ($timesheet_insentif as $row=>$detail)
                                <tr>
                                    <td class="col-md-2">
                                        {{ Form::text('insentif['.$row.'][date]', $detail->id_date, array('class' => 'form-control','disabled')) }}
                                    </td>
                                    <td class="col-md-3">
                                        {!! Form::select('insentif['.$row.'][project_id]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2', 'disabled']) !!}
                                    </td>
                                    <td class="col-md-2">
                                    {{ Form::text('insentif['.$row.'][value]', $detail->value, array('class' => 'form-control money', 'disabled')) }}
                                    <td>
                                    <td class="col-md-3">
                                        {{ Form::text('insentif['.$row.'][desc]', $detail->keterangan, array('class' => 'form-control', 'disabled', 'title' => $detail->keterangan)) }}
                                    </td>
                                    @if($detail->approval_status != 0 && $approval['role']!=4)
                                        <td class="col-md-1">
                                            {!! $detail->approval !!}
                                        </td>
                                    @endif
                                    @if($detail->approval_status == 0 && $approval['role']!=4)
                                        <td class="col-md-1">
                                            {{ Form::checkbox('insentif['.$row.'][choose]', true) }}
                                        </td>
                                    @endif
                                    @if(($detail->approval_status == 0 || $detail->approval_status == 1 || $detail->approval_status == 5 || $detail->approval_status == 6)
                                        && $approval['role']==4)
                                        <td class="col-md-1">
                                            {!! $detail->approval !!} |
                                            {{ Form::checkbox('insentif['.$row.'][choose]', true) }}
                                        </td>
                                    @endif
                                    @if($detail->approval_status_history != 0 && $detail->approval_status_history != 1 && $detail->approval_status_history != 5 && $detail->approval_status_history != 6
                                        && $approval['role']==4)
                                        <td class="col-md-1">
                                            {!! $detail->approval !!}
                                        </td>
                                    @endif
                                    <td class="col-md-1">
                                        {{ Form::hidden('insentif['.$row.'][transaction_id]', $detail->transaction_id) }}
                                        {{ Form::hidden('insentif['.$row.'][guid]', $detail->guid) }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Transport Proyek Konsultasi Luar Kota</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-hover small-text" id="tb_trasnportasi">
                            <tr class="tr-header">
                                <th>Tanggal</th>
                                <th>Proyek</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th>Attachment</th>
                                </th></tr>
                            @foreach ($timesheet_transport as $row=>$detail)
                                <tr>
                                    <td class="col-md-2">
                                        {{ Form::text('trans['.$row.'][date]', $detail->id_date, array('class' => 'form-control','disabled')) }}
                                    </td>
                                    <td class="col-md-3">
                                        {!! Form::select('trans['.$row.'][project_id]', [''=>'']+$project, $detail->project_id, ['class' => 'form-control select2','disabled']) !!}
                                    </td>
                                    <td class="col-md-2">
                                    {{ Form::text('trans['.$row.'][value]', $detail->value, array('class' => 'form-control money','disabled')) }}
                                    <td class="col-md-3">
                                        {{ Form::text('trans['.$row.'][desc]', $detail->keterangan, array('class' => 'form-control','disabled', 'title' => $detail->keterangan)) }}
                                    </td>
                                    <td class="col-md-1">
                                        @if($detail->file!=null)
                                            <a href="{{url('dl')}}/{{$detail->file}}">{{str_limit($detail->file,9)}}</a>
                                        @endif
                                    </td>
                                    @if($detail->approval_status != 0 && $approval['role']!=4)
                                        <td class="col-md-1">
                                            {!! $detail->approval !!}
                                        </td>
                                    @endif
                                    @if($detail->approval_status == 0 && $approval['role']!=4)
                                        <td class="col-md-1">
                                            {{ Form::checkbox('trans['.$row.'][choose]', true) }}
                                        </td>
                                    @endif
                                    @if(($detail->approval_status == 0 || $detail->approval_status == 1 || $detail->approval_status == 5 || $detail->approval_status == 6)
                                        && $approval['role']==4)
                                        <td class="col-md-1">
                                            {!! $detail->approval !!} |
                                            {{ Form::checkbox('trans['.$row.'][choose]', true) }}
                                        </td>
                                    @endif
                                    @if($detail->approval_status_history != 0 && $detail->approval_status_history != 1 && $detail->approval_status_history != 5 && $detail->approval_status_history != 6
                                        && $approval['role']==4)
                                        <td class="col-md-1">
                                            {!! $detail->approval !!}
                                        </td>
                                        @endif
                                    </td>
                                    {{ Form::hidden('trans['.$row.'][transaction_id]', $detail->transaction_id) }}
                                    {{ Form::hidden('trans['.$row.'][guid]', $detail->guid) }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            {{ Form::hidden('userId', $userId) }}
            {{ Form::hidden('timesheetId', $timesheetId) }}

            <div class="form-group col-sm-12">
                @if($approval['role']!=4 && $approvalStatus==0)
                    {!! Form::select('moderation',
                      [1 => 'Approve', 2 => 'Reject'],
                      null,
                      ['class' => 'form-control select2', 'id' => 'moderation'])
                    !!}
                    {{ Form::text('approval_note', null, array('class' => 'form-control', 'style'=>'visibility:hidden', 'id' => 'approval_note', 'placeholder'=>'rejection note')) }}
                    {!! Form::submit('Submit',['name'=>'action','class' => 'btn btn-primary']) !!}
                @endif
                @if($approval['role']==4 && $approvalStatus==0)
                        {!! Form::select('moderation',
                          [1 => 'Approve', 2 => 'Reject',5 => 'On Hold', 6 => 'Over Budget'],
                          null,
                          ['class' => 'form-control select2', 'id' => 'moderation'])
                        !!}
                        {{ Form::text('approval_note', null, array('class' => 'form-control', 'style'=>'visibility:hidden', 'id' => 'approval_note', 'placeholder'=>'rejection note')) }}
                    {!! Form::submit('Submit',['name'=>'action','class' => 'btn btn-primary']) !!}
                @endif
                @if($approval['role']==4 && ($approvalStatus==1 || $approvalStatus==5 || $approvalStatus==6))
                    {!! Form::select('moderation',
                      [4 => 'Paid'],
                      null,
                      ['class' => 'form-control select2', 'id' => 'moderation'])
                    !!}
                    {!! Form::submit('Submit',['name'=>'action','class' => 'btn btn-primary']) !!}
                @endif


                <a href="{!! route('timesheets.moderation') !!}" class="btn btn-success">Back</a>
            </div>
            <div class="clearfix"></div>
            {!! Form::close() !!}

        @endif
    </div>

    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
    <script>
        $(function () {

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

        $('#moderation').change(function() {
            if ($(this).val() == 2 || $(this).val() == 5) {
                $('#approval_note').css('visibility', 'visible');
            } else
            {
                $('#approval_note').css('visibility', 'hidden');
            }

        });

        $('textarea').autoResize();
    </script>
    @endsection

@endsection


