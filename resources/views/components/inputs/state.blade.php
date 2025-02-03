<select {{ $attributes->merge(['id' => 'state_id', 'required'=>'required', 'name' => 'state_id', 'class' => 'form-control select2 ', 'data-placeholder' => 'Choose state']) }}>
    {{ $slot }}
</select>
<input  id="{{ $attributes->get('id') }}-hidden" name="{{ $attributes->get('name') ?: 'state_id' }}_text" type="hidden">

@push('js')
<script>
    $(function() {
        'use strict';

        var id = '{{ $attributes->get('id') }}';
        var placeholder = '{{ $attributes->get('data-placeholder') ?? 'Choose country' }}';
        var related = '{{ $attributes->get('related') }}';

        var $el = $('#'+ id);

        $el.select2({
            placeholder: placeholder,
            ajax: {
                url: '{{route('api.states.index')}}',
                data: function (params) {
                    if(related) {
                        var name = $(related).attr('name');
                        params[name] = $(related).val();
                    }

                    params.search = params.term;
                    return params;
                },
                dataType: 'json',
                delay: 250,
                cache: true,
                processResults: function (response, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(response.data.data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        }),
                        pagination: {
                            more: (params.page * response.data.per_page) == response.data.to
                        }
                    };
                }
            }
        });

        var onChange = function () {
            $('#'+ id + '-hidden').val($el.find('option:selected').text());
        };

        $el.change(onChange);

        $(document).on('change', related, function () {
            $el.val(null);
            $el .trigger('change');
        });

        onChange();
    });
</script>
@endpush
