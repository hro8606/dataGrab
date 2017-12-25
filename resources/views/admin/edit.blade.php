@extends('layouts.app')

@push('style')

@endpush

@section('content')
    <div class="container ">
        <div class="row">
            <div class="panel panel-default">

                <form action="/admin/{{$article->id}}" method="POST" enctype="multipart/form-data">

                    {{method_field('PUT')}}
                    {{csrf_field()}}

}
                <div class="form-group">
                    <input class="form-control" id="editTitle" type="text" name="title" value="{{$article->title}}"
                           placeholder=" Edit title">
                </div>
                <div class="form-group">
                        <textarea class="form-control" id="editDesc"  rows="3" style="resize: vertical;" name="description" >{{$article->description}}</textarea>
                </div>

                <div class="form-group">
                    <img src="{{asset('uploads/images/'.$article->main_image)}}" alt="" width="200px" height="auto">
                    <input class="form-control" type="file" id="editImg" name="img" accept="image/*">
                </div>

                <div class="form-group">
                    <input class="form-control" id="editDT" type="text" name="dateTime" value="{{$article->cr_date}}">
                </div>
                <div class="form-group">
                    <input class="form-control" id="editURL" type="text" name="articleUrl" value="{{$article->article_url}}">
                </div>

                <button type="submit" id="editUpdate" class="btn btn-warning btn-lg" style="width: 100%;">Update
                </button>

                </form>
            </div>

        </div>
    </div>

@endsection


@push('scripts')



<script type="text/javascript">

    $(document).ready(function() {

    });

</script>
@endpush







