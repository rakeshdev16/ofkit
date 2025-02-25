<tr>
    @php
        $tabamTotal = $summary['tabam']['group'] + $summary['tabam']['individual'];
        $matiaTotal = $summary['matia']['group'] + $summary['matia']['individual'];
    @endphp
    <td class="text-center" colspan="2">{{ $loopIteration }}</td>
    <td class="text-center" colspan="2">{{ getChildrenNameById($children['id']) }}</td>
    <td class="text-center total-hours-bg">{{ $summary['matia']['individual'] + $summary['tabam']['individual'] }}</td>
    <td class="text-center total-hours-bg">{{ $summary['matia']['group'] + $summary['tabam']['group'] }}</td>
    <td class="text-center total-hours-bg">{{ ($tabamTotal) + ($matiaTotal) }}</td>
    <td class="text-center tabam-bg">{{ $summary['tabam']['individual'] }}</td>
    <td class="text-center tabam-bg">{{ $summary['tabam']['group'] }}</td>
    <td class="text-center tabam-bg">{{ $tabamTotal }}</td>
    <td class="text-center">{{ $summary['matia']['individual'] }}</td>
    <td class="text-center">{{ $summary['matia']['group'] }}</td>
    <td class="text-center">{{ $matiaTotal }}</td>
</tr>