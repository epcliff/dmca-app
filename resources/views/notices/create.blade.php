@extends('app')

@section('content')
    <h1 class="page-heading">Prepare a DMCA Notice</h1>

    {!! Form::open(['method' => 'GET', 'action' => 'NoticesController@confirm']) !!}
        <!-- Provider ID Form Input -->
        <div class="form-group">
            {!! Form::label('provider_id','Who are we sending this to?') !!}
            {!! Form::select('provider_id', $providers, null, ['class' => 'form-control']) !!}
        </div>

        <!-- Infringing Title Form Input -->
        <div class="form-group">
            {!! Form::label('infringing_title','What is the title of the content that is being infringed upon?') !!}
            {!! Form::text('infringing_title', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Infringing Link Form Input -->
        <div class="form-group">
            {!! Form::label('infringing_link','What is the link to where this content is located?') !!}
            {!! Form::text('infringing_link', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Original Link Form Input -->
        <div class="form-group">
            {!! Form::label('original_link','To verify that you own the content, we now need the link to the original content on your server.') !!}
            {!! Form::text('original_link', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Original Description Form Input -->
        <div class="form-group">
            {!! Form::label('original_description','And, finally, it might help to provide some extra information related to this DMCA notice.') !!}
            {!! Form::textarea('original_description', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Preview Notice Form Submit -->
        <div class="form-group">
            {!! Form::submit('Preview Notice', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    {!! Form::close() !!}

    @include('errors.list')

@endsection