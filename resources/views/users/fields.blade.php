<!-- Nik Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nik', 'Nik:') !!}
    {!! Form::text('nik', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Nama Rekening Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nama_rekening', 'Nama Rekening:') !!}
    {!! Form::text('nama_rekening', null, ['class' => 'form-control']) !!}
</div>

<!-- Rekening Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rekening', 'Rekening:') !!}
    {!! Form::text('rekening', null, ['class' => 'form-control']) !!}
</div>

<!-- Bank Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bank', 'Bank:') !!}
    {!! Form::text('bank', null, ['class' => 'form-control']) !!}
</div>

<!-- Cabang Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cabang', 'Cabang:') !!}
    {!! Form::text('cabang', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>


<!-- Role Field -->
<div class="form-group col-sm-6">
    {!! Form::label('role', 'Role:') !!}
    {!! Form::select('role', $roles, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Department Field -->
<div class="form-group col-sm-6">
    {!! Form::label('department', 'Department:') !!}
    {!! Form::select('department', $departments, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Position Field -->
<div class="form-group col-sm-6">
    {!! Form::label('position', 'Position:') !!}
    {!! Form::select('position', $positions, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Image -->
<div class="form-group col-sm-6">
    {!! Form::label('image', 'Image:') !!}
    {!! Form::file('image', ['class' => 'form-control']) !!}
</div>

<!-- Position Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PjsVP', 'PjsVP:') !!}
    {!! Form::select('pjsvp', ['0'=>'No','1'=>'Yes'], null, ['class' => 'form-control select2']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
</div>
