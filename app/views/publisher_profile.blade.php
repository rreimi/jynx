@extends('layout_home_no_sidebar')

@section('content')

<div class="row-fluid advertiser-detail">

    <div class="float-right">
        <div id="pub-images-box">
            <img class="adv-logo-big" src="{{ UrlHelper::imageUrl($advertiser->avatar) }}" alt="{{ $advertiser->seller_name }}" />
        </div>
    </div>

    <h1>{{ $advertiser->seller_name }}</h1>
    <div class="triangle"></div>

    <div class="publication-info">

        <div class="control-group">
            <div class="controls">
                <a href="{{ $referer }}" class="btn btn-mini">{{Lang::get('content.previous')}}</a>
            </div>
        </div>

        @if (!empty($advertiser->description))
        <h2 id="basico">{{Lang::get('content.description')}}</h2>
        <p>
            {{ $advertiser->description }}
        </p>
        @endif

        <!-- Categories -->
        <h2 id="sectores">{{Lang::get('content.categories_and_sectors')}}</h2>

        <ul class="categories-form-list">
            @foreach ($products as $cat)
                @if (in_array($cat->id, (array) $advertiser_categories))
                <li>
                       {{ $cat->name }}
                </li>
                @endif
            @endforeach
            @foreach ($services as $cat)
                @if (in_array($cat->id, (array) $advertiser_categories))
                    <li>
                        {{ $cat->name }}
                    </li>
                @endif
            @endforeach
        </ul>

        <h2 id="basico">{{Lang::get('content.information')}}</h2>

        <p class="pub-location">{{Lang::get('content.location')}}:
            @if ($advertiser->state_id) {{ $advertiser->state->name.(($advertiser->city || $advertiser->address)?',':'') }} @endif
            @if ($advertiser->city) {{ $advertiser->city.(($advertiser->address)?',':'') }} @endif
            @if ($advertiser->address) {{ $advertiser->address }} @endif
            @if (!is_null($country)){{ ', ' . $country->country_name }}@endif
        </p>

        <p class="pub-phone">{{Lang::get('content.phone')}}: {{ $advertiser->phone1 }}
            @if ($advertiser->phone2)
                / {{ $advertiser->phone2 }}
            @endif
        </p>

        @if (!empty($advertiser->web))
        <p class="pub-web">{{Lang::get('content.website')}}: <a href="{{ $advertiser->web }}" target="_blank">{{ $advertiser->web }}</a></p>
        @endif

        <p class="pub-email">{{Lang::get('content.user_email')}}: <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>

        @if (count($user->publisher->contacts) > 0)
            <div>
                <h2 class="contacts-title">{{ Lang::get('content.contacts')}}</h2>
                @foreach ($user->publisher->contacts as $contact)
                    @if (!$contact->isMainContact())
                    <div class="contact">
                        <div class="block">
                            @if ($contact->full_name || $contact->distributor)
                                <p class="pub-name">{{ $contact->full_name }}
                                    <b>@if (isset($contact->distributor)) {{ (($contact->full_name)?'- ':''). $contact->distributor }} @endif</b>
                                <p/>
                            @endif
                            @if (Auth::check())
                                <p class="pub-email">{{Lang::get('content.user_email')}}: <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
                            @endif
                        </div>
                        @if (Auth::check())
                            <div class="block">
                                <p class="pub-phone">{{Lang::get('content.phone')}}: {{ $contact->phone }}
                                    @if ($contact->other_phone)
                                        / {{ $contact->other_phone }}
                                    @endif
                                </p>
                                @if ($contact->state_id || $contact->city || $contact->address)
                                    <p class="pub-location">{{Lang::get('content.location')}}:
                                        @if ($contact->state_id) {{ $contact->state->name.(($contact->city || $contact->address)?',':'') }} @endif
                                        @if ($contact->city) {{ $contact->city.(($contact->address)?',':'') }} @endif
                                        @if ($contact->address) {{ $contact->address }} @endif
                                        @if (!is_null($country)){{ ', ' . $country->country_name }}@endif
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                    @endif
                @endforeach
            </div><!--/.contacs-info-->
        @endif
     </div>
</div><!--/row-fluid-->
@stop

@section('scripts')
@parent

@stop
