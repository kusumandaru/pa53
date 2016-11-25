@inject('count', 'App\Services\MenuCountService')

<!-- examples roles access -->
@if (Auth::user()->hasRole('Admin|CBS|Consultant|Finance|Manager|PMO|VP'))

<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-user"></i><span>User</span></a>
</li>

<li class="{{ Request::is('approvalHistories*') ? 'active' : '' }}">
    <a href="{!! route('approvalHistories.index') !!}"><i class="fa fa-share-square"></i><span>Approval History</span></a>
</li>

<li class="{{ Request::is('departments*') ? 'active' : '' }}">
    <a href="{!! route('departments.index') !!}"><i class="fa fa-university"></i><span>Department</span></a>
</li>

<li class="{{ Request::is('holidays*') ? 'active' : '' }}">
    <a href="{!! route('holidays.index') !!}"><i class="fa fa-calendar"></i><span>Holiday</span></a>
</li>

<li class="{{ Request::is('leaves*') 
    && !Request::is('leaves/submission*') 
    && !Request::is('leaves/moderation*') ? 'active' : '' }}">
    <a href="{!! route('leaves.index') !!}"><i class="fa fa-plane"></i><span>Leave</span></a>
</li>

<li class="{{ Request::is('projects*') ? 'active' : '' }}">
    <a href="{!! route('projects.index') !!}"><i class="fa fa-laptop"></i><span>Project</span></a>
</li>

<li class="{{ Request::is('roles*') ? 'active' : '' }}">
    <a href="{!! route('roles.index') !!}"><i class="fa fa-object-ungroup"></i><span>Role</span></a>
</li>

<li class="{{ Request::is('sequences*') ? 'active' : '' }}">
    <a href="{!! route('sequences.index') !!}"><i class="fa fa-sort-amount-asc"></i><span>Sequence</span></a>
</li>





<li class="{{ Request::is('timesheets*') ? 'active' : '' }}">
    <a href="{!! route('timesheets.index') !!}"><i class="fa fa-pencil-square-o"></i><span>Timesheet</span></a>
</li>

<li class="{{ Request::is('add_timesheet*') ? 'active' : '' }}">
    <a href="{!! route('add_timesheet.index') !!}"><i class="fa fa-pencil-square-o"></i><span>Create Timesheet</span></a>
</li>
<!--
<li class="{{ Request::is('timesheetDetails*') ? 'active' : '' }}">
    <a href="{!! route('timesheetDetails.index') !!}"><i class="fa fa-calendar-check-o"></i><span>Timesheet Detail</span></a>
</li>
-->
<li class="{{ Request::is('tunjangans*') ? 'active' : '' }}">
    <a href="{!! route('tunjangans.index') !!}"><i class="fa fa-money"></i><span>Tunjangan</span></a>
</li>

<li class="{{ Request::is('tunjanganProjects*') ? 'active' : '' }}">
    <a href="{!! route('tunjanganProjects.index') !!}"><i class="fa fa-money"></i><span>Tunjangan Project</span></a>
</li>

<li class="{{ Request::is('tunjanganPositions*') ? 'active' : '' }}">
    <a href="{!! route('tunjanganPositions.index') !!}"><i class="fa fa-money"></i><span>Tunjangan Position</span></a>
</li>

<li class="{{ Request::is('positions*') ? 'active' : '' }}">
    <a href="{!! route('positions.index') !!}"><i class="fa fa-sitemap"></i><span>Position</span></a>
</li>

<li class="{{ Request::is('audits*') ? 'active' : '' }}">
    <a href="{!! route('audits.index') !!}"><i class="fa fa-shield"></i><span>Audits</span></a>
</li><li class="{{ Request::is('constants*') ? 'active' : '' }}">
    <a href="{!! route('constants.index') !!}"><i class="fa fa-edit"></i><span>Constants</span></a>
</li>


<li class="{{ Request::is('userLeaves*') ? 'active' : '' }}">
    <a href="{!! route('userLeaves.index') !!}"><i class="fa fa-hotel"></i><span>User Leave</span></a>
</li>

<li class="{{ Request::is('leaves/submission*') ? 'active' : '' }}">
    <a href="{!! route('leaves.submission') !!}"><i class="fa fa-hotel"></i><span>Cuti</span></a>
</li>

<li class="{{ Request::is('leaves/moderation*') ? 'active' : '' }}">
    <a href="{!! route('leaves.moderation') !!}"><i class="fa fa-hotel"></i><span>Moderasi Cuti 
            <span class="pull-right-container">
                <span class="label label-primary pull-right">{{$count->leave}}</span>
            </span>
        </span></a>
</li>


@endif
