<tr>
    <td class="text-center">{{ $user->name }}</td>
    <td class="text-center">{{ $summary['individual']+$summary['group']+$summary['staff-meeting']+$summary['tutorial']+$summary['preparation']+$summary['other'] }}</td>
    <td class="text-center">{{ $summary['individual'] }}</td>
    <td class="text-center">{{ $summary['group'] }}</td>
    <td class="text-center">{{ $summary['staff-meeting'] }}</td>
    <td class="text-center">{{ $summary['tutorial'] }}</td>
    <td class="text-center">{{ $summary['preparation'] }}</td>
    <td class="text-center">{{ $summary['other'] }}</td>
</tr>