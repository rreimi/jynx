<h2>{{ Lang::get('content.contact_full_name') }}</h2>
<p>
    {{ $contact->full_name }}
</p>

<h2>{{ Lang::get('content.contact_distributor') }}</h2>
<p>
    {{ $contact->distributor }}
</p>

<h2>{{ Lang::get('content.contact_email') }}</h2>
<p>
    {{ $contact->email }}
</p>
<h2>{{ Lang::get('content.contact_phone') }}</h2>
<p>
    {{ $contact->phone }}
</p>
<h2>{{ Lang::get('content.contact_city') }}</h2>
<p>
    {{ $contact->city }}
</p>
<h2>{{ Lang::get('content.contact_address') }}</h2>
<p>
    {{ $contact->address }}
</p>
    {{ Form::hidden('contact_id',$contact->id) }}
</div>