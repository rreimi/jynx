@if($contact->full_name)
<h2>{{ Lang::get('content.contact_full_name') }}</h2>
<p>
    {{ $contact->full_name }}
</p>
@endif
@if($contact->distributor)
<h2>{{ Lang::get('content.contact_distributor') }}</h2>
<p>
    {{ $contact->distributor }}
</p>
@endif
<h2>{{ Lang::get('content.contact_email') }}</h2>
<p>
    {{ $contact->email }}
</p>
<h2>{{ Lang::get('content.contact_phone1') }}</h2>
<p>
    {{ $contact->phone }}
</p>
@if($contact->other_phone)
<h2>{{ Lang::get('content.contact_phone2') }}</h2>
<p>
    {{ $contact->other_phone }}
</p>
@endif
@if($contact->state_id)
<h2>{{ Lang::get('content.contact_state') }}</h2>
<p>
    {{ $contact->state->name }}
</p>
@endif
@if($contact->city)
<h2>{{ Lang::get('content.contact_city') }}</h2>
<p>
    {{ $contact->city }}
</p>
@endif
@if($contact->address)
<h2>{{ Lang::get('content.contact_address') }}</h2>
<p>
    {{ $contact->address }}
</p>
@endif
    {{ Form::hidden('contact_id',$contact->id) }}