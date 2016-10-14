<!-- Tunjangan Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tunjangan_id', 'Tunjangan Id:') !!}
    {!! Form::text('tunjangan_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Role Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('role_id', 'Role Id:') !!}
    {!! Form::text('role_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('value', 'Value:') !!}
    {!! Form::number('value', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('tunjanganRoles.index') !!}" class="btn btn-default">Cancel</a>
</div>
