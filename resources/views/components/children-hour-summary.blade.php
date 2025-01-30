<tr>
    @php
        $tabamTotal = $summary['tabam']['group'] + $summary['tabam']['individual'];
        $matiaTotal = $summary['matia']['group'] + $summary['matia']['individual'];
    @endphp
    <td class="text-center" colspan="3">{{ getChildrenNameById($children['id']) }}</td>
    <td class="text-center">{{ $summary['matia']['group'] + $summary['tabam']['group'] }}</td>
    <td class="text-center">{{ $summary['matia']['individual'] + $summary['tabam']['individual'] }}</td>
    <td class="text-center">{{ ($tabamTotal) + ($matiaTotal) }}</td>
    <td class="text-center">{{ $summary['matia']['group'] }}</td>
    <td class="text-center">{{ $summary['matia']['individual'] }}</td>
    <td class="text-center">{{ $matiaTotal }}</td>
    <td class="text-center">{{ $summary['tabam']['group'] }}</td>
    <td class="text-center">{{ $summary['tabam']['individual'] }}</td>
    <td class="text-center">{{ $tabamTotal }}</td>
</tr>