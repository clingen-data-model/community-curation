{{-- regular object attribute --}}
@php
    $value = data_get($entry, $column['name']);
@endphp
<span>{{ (array_key_exists('prefix', $column) ? $column['prefix'] : '').number_format($value,
    array_key_exists('decimals', $column) ? $column['decimals'] : 0,
    array_key_exists('dec_point', $column) ? $column['dec_point'] : '.',
    array_key_exists('thousands_sep', $column) ? $column['thousands_sep'] : ','
 ).(array_key_exists('suffix', $column) ? $column['suffix'] : '') }}</span>