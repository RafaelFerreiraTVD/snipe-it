@component('mail::message')
# {{ trans('mail.hello') }} {{ $target->present()->fullName() }},

{{ trans('mail.new_item_checked') }}

@component('mail::table')

| Image |{{ trans('mail.asset_name') }}|{{ trans('mail.asset_tag') }}| {{ trans('general.manufacturer') }} | {{ trans('general.asset_model') }} | {{ trans('general.model_no') }} | {{ trans('mail.serial') }} |
| ------------- | ------------- | ------------- | ------------- | ------------- | ------------- | ------------- |
| | | | | | | |
@foreach($assets as $asset)
    | <img src="{{ $snipeSettings->show_images_in_email == '1' && $asset->getImageUrl() ? $asset->getImageUrl() : url('/').'/img/default-sm.png'}}" alt="Asset" style="width: 100px;"> | {{ $asset->name ?? ' ' }} | {{ $asset->asset_tag ?? ' ' }} | {{ $asset->manufacturer->name ?? ' ' }} | {{ $asset->model->name ?? ' ' }} | {{ $asset->model_no ?? ' ' }} | {{ $asset->serial ?? ' '}} |
@endforeach

|               |               |
| ------------- | ------------- |
| | |
@if (isset($last_checkout))
    | **{{ trans('mail.checkout_date') }}** | {{ $last_checkout }} |
@endif
@if (isset($expected_checkin))
    | **{{ trans('mail.expecting_checkin_date') }}** | {{ $expected_checkin }} |
@endif
| | |
@foreach($fields as $field)
@if (($item->{ $field->db_column_name() }!='') && ($field->show_in_email) && ($field->field_encrypted=='0'))
| **{{ $field->name }}** | {{ $item->{ $field->db_column_name() } }} |
@endif
@endforeach
| | |
@if ($admin)
| **{{ trans('general.administrator') }}** | {{ $admin->present()->fullName() }} |
@endif
@if ($note)
| **{{ trans('mail.additional_notes') }}** | {{ $note }} |
@endif
@endcomponent

@if (($req_accept == 1) && ($eula!=''))
{{ trans('mail.read_the_terms_and_click') }}
@elseif (($req_accept == 1) && ($eula==''))
{{ trans('mail.click_on_the_link_asset') }}
@elseif (($req_accept == 0) && ($eula!=''))
{{ trans('mail.read_the_terms') }}
@endif

@if ($eula)
@component('mail::panel')
{!! $eula !!}
@endcomponent
@endif

@if ($req_accept == 1)
**[✔ {{ trans('mail.i_have_read') }}]({{ $accept_url }})**
@endif


Thanks,

{{ $snipeSettings->site_name }}

@endcomponent
