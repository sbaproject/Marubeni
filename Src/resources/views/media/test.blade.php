@extends('layouts.master')

@section('title')
test media
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
@endsection

@section('js')
<script src="js/dropzone/dropzone.min.js"></script>
@endsection

@section('content-header')
test media
@endsection

@section('content-breadcrumb')

@endsection

@section('content')

<form action="" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="document">Documents</label>
        <div class="needsclick dropzone" id="document-dropzone">

        </div>
    </div>
    <div>
        <input class="btn btn-danger" type="submit">
    </div>
</form>

<script>
    var uploadedDocumentMap = {};

    Dropzone.options.documentDropzone = {
    url: '{{ route('media.store') }}',
    maxFilesize: 1, // MB
    maxFiles: 3,
    acceptedFiles: 'image/*,pdf',
    //============translate============//
    dictFileTooBig: "{{ __('msg.dropzone.dictFileTooBig') }}",
    //============translate============//
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
      uploadedDocumentMap[file.name] = response.name
    },
    error: function (file, response) {
        console.log(response);
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDocumentMap[file.name]
      }
      $('form').find('input[name="document[]"][value="' + name + '"]').remove()
    },
    // init: function () {
    //   @if(isset($project) && $project->document)
    //     var files =
    //       {!! json_encode($project->document) !!}
    //     for (var i in files) {
    //       var file = files[i]
    //       this.options.addedfile.call(this, file)
    //       file.previewElement.classList.add('dz-complete')
    //       $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
    //     }
    //   @endif
    // }
  }
</script>

@endsection