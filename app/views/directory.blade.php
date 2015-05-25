@extends('layout_home')

@section('sidebar')
    {{ Form::open(array('url' => $formAction, 'method' => 'get', 'class' => '')) }}
        {{ Form::text('q', $searchString, array('class' => 'input-medium required', 'placeholder'=> Lang::get('content.search'))) }}
        <button class="btn btn-medium btn-warning" type="submit">{{Lang::get('content.send')}}</button>
    {{ Form::close() }}

    @include('include.filter_sidebar')
@parent
@stop

@section('content')
<div class="row-fluid">
    <h1>{{Lang::get(isset($isMyDirectory)?'content.my_directory':'content.advertisers')}}@if (!empty($searchString)): {{ $searchString }} @endif</h1>

    <table class="directory-table table">
        <thead>
        <tr>
            <th></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('seller_name')}}">{{Lang::get('content.advertiser_name')}} <i class="{{UrlHelper::getSortIcon('seller_name')}}"></i></a></th>
            <th><a href="{{UrlHelper::fullUrltoogleSort('city')}}">{{Lang::get('content.location')}} <i class="{{UrlHelper::getSortIcon('city')}}"></i></a></th>
            <th>{{Lang::get('content.contact')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (count($advertisers) > 0)
        @foreach ($advertisers as $key => $advertiser)
        <tr id="directory-row-{{$advertiser->id}}">
            <td>
                @if (isset($advertiser->avatar))
                    <img class="adv-logo-small" src="{{ UrlHelper::imageUrl($advertiser->avatar) }}" alt="{{ $advertiser->seller_name }}" />
                @else
                    {{ HTML::image('img/default_image_130.jpg', $advertiser->seller_name, array('class' => 'adv-logo-small')) }}
                @endif
            </td>
            <td class="publisher-name">{{ $advertiser->seller_name }}</td>
            <td>
                {{$advertiser->city}}@if (array_key_exists($advertiser->state_id, $states)), {{ $states[$advertiser->state_id] }} @endif

            </td>
            @if (Auth::check())
            <td>
                {{ $advertiser->email }}<br/>
                {{ $advertiser->phone1 }}<br/>
                {{ $advertiser->phone2 }}<br/>
            </td>
            @else
            <td>
                Registrate
            </td>
            @endif

            <!--            <td> $advertiser->created_at </td>-->
            <td class="directory-options">
                <a rel="tooltip" title="{{Lang::get('content.edit')}}" href="{{URL::to('anunciante/perfil/' . $advertiser->id)}}">
                    {{Lang::get('content.view_profile')}}
                </a>
                <a rel="tooltip" title="{{Lang::get('content.delete')}}" href="{{URL::to('search?seller=' . $advertiser->id)}}">
                    {{Lang::get('content.view_publications')}}
                </a>
                @if (Auth::check())
                {{--*/ $isInMyDirectory = isset($isMyDirectory) || in_array($advertiser->id, $myDirectoryEntries) /*--}}
                <a rel="tooltip" class="remove-to-dir-link-{{$advertiser->id}} {{$isInMyDirectory? "":"hide"}}" title="{{Lang::get('content.delete')}}" nohref onclick="Mercatino.directory.removeFromDirectory('{{$advertiser->id}}', '{{$advertiser->seller_name}}','{{isset($isMyDirectory)? "true":"false"}}')">
                    {{Lang::get('content.remove_from_my_directory')}}
                </a>
                <a rel="tooltip" class="add-to-dir-link-{{$advertiser->id}}  {{$isInMyDirectory? "hide":""}}" title="{{Lang::get('content.delete')}}" nohref onclick="Mercatino.directory.addToDirectory('{{$advertiser->id}}', '{{$advertiser->seller_name}}')">
                    {{Lang::get('content.add_to_my_directory')}}
                </a>
                @endif
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="7"><div class="text-center">{{Lang::get('content.no_elements_to_list')}}</div></td>
        </tr>
        @endif
        </tbody>
    </table>
    {{ $advertisers->appends(Input::only('sort','order'))->links() }}
</div><!--/row-fluid-->
@stop

@section('scripts')
@parent
<script type="text/javascript">
    var Mercatino = Mercatino || {};
    Mercatino.directory = Mercatino.directory || {};

    (function(jQuery, Mercatino){
        var addUrl = '{{ URL::to('mi-directorio/agregar/') }}/';
        var removeUrl = '{{ URL::to('mi-directorio/quitar/') }}/';

        Mercatino.directory.removeFromDirectory = function(publisher, publisherName, isMyDirectory) {
            jQuery.ajax({
                url: removeUrl + publisher,
                type: 'GET',
                success: function(result) {
                    Mercatino.showFlashMessage({title:publisherName, message:"{{Lang::get('content.remove_from_directory_success')}}", type:'success'});
                    if (isMyDirectory === "true") {
                        Mercatino.directory.removeRow('directory-row-' + publisher);
                    } else {
                        Mercatino.directory.swapLinks('add', publisher);
                    }
                },
                error: function(result) {
                    Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.remove_from_directory_error')}}", type:'error'});
                }
            });
        };

        Mercatino.directory.addToDirectory = function(publisher, publisherName) {
            jQuery.ajax({
                url: addUrl + publisher,
                type: 'GET',
                success: function(result) {
                    Mercatino.showFlashMessage({title:publisherName, message:"{{Lang::get('content.add_to_directory_success')}}", type:'success'});
                    Mercatino.directory.swapLinks('remove', publisher);
                },
                error: function(result) {
                    Mercatino.showFlashMessage({title:'', message:"{{Lang::get('content.add_to_directory_error')}}", type:'error'});
                }
            });
        };

        Mercatino.directory.removeRow = function(rowId) {
            jQuery('#' + rowId).remove();
        };

        Mercatino.directory.swapLinks = function(swapTo, publisher) {
            if (swapTo == 'add'){
                jQuery('.remove-to-dir-link-' + publisher).addClass("hide");
                jQuery('.add-to-dir-link-' + publisher).removeClass("hide");
            } else {
                jQuery('.remove-to-dir-link-' + publisher).removeClass("hide");
                jQuery('.add-to-dir-link-' + publisher).addClass("hide");
            }
        }
    }(jQuery, Mercatino));
</script>
@stop