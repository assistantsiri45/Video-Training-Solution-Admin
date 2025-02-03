<select {{ $attributes->merge(['id' => 'country_id', 'name' => 'country_id', 'class' => 'form-control select2 ', 'data-placeholder' => 'Choose country']) }}>
    {{ $slot }}
</select>
<input id="{{ $attributes->get('id') }}-hidden" name="{{ $attributes->get('name') ?: 'country_id' }}_text" type="hidden">

@push('js')
<script>
    $(function() {
        'use strict';

        let id = '{{ $attributes->get('id') }}';
        let placeholder = '{{ $attributes->get('data-placeholder') ?? 'Choose country' }}';

        let $el = $('#'+ id);

        $el.select2({
            placeholder: placeholder,
            ajax: {
                url: '{{route('api.countries.index') }}',
                dataType: 'json',
                delay: 250,
                cache: true,
                data: function(params) {

                    return {
                        search: params.term
                    }
                },
                processResults: function (response) {
                    return {
                        results: $.map(response.data.data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });

        var onChange = function () {
            $('#'+ id + '-hidden').val($el.find('option:selected').text());
        };

        $el.change(onChange);

        onChange();
    });
</script>
@endpush
