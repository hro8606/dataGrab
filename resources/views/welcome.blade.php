@extends('layouts.app')

@push('style')
{{--<link href="{{ asset("css/bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>--}}
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset("/css/jquery.dataTables.min.css")}}" rel="stylesheet" type="text/css"/>

@endpush

@section('content')
    <div class="container-fluid tableDiv">
        <div class="row">
            <div class="panel panel-default">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <td>Id</td>
                        <td>Title</td>
                        <td>Description</td>
                        <td>Image</td>
                        <td>Date</td>
                        <td>Article Url</td>
                    </tr>
                    </thead>
                    <tbody >
                    @foreach($articles as $art)
                        <tr>
                            <td class="">{{$art->id}}</td>
                            <td class="">{{$art->title}}</td>
                            <td class="">{{$art->description}}</td>
                            <td class=""><a href="{{asset('uploads/images/'.$art->main_image)}}" target="_blank"><img src="{{asset('uploads/images/'.$art->main_image)}}" alt="" width="200px" height="auto"></a></td>
                            <td class="">{{$art->cr_date}}</td>
                            <td class=""><a href="{{$art->article_url}}" target="_blank">{{$art->article_url}}</a></td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>




@endsection


@push('scripts')

<script src="{{asset('/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/js/dataTables.bootstrap.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>
@endpush







