@extends('backpackcrud::edit')

@section('content')
    {!! Form::open(array('url' => route('crud.item.index').'/'.$entry->getKey(), 'method' => 'put', 'files'=>$crud->hasUploadFields('update', $entry->getKey()))) !!}
    @include('crud::form_content', ['fields' => $fields, 'action' => 'edit', 'entry' => $entry])
    {!! Form::close() !!}
@endsection
