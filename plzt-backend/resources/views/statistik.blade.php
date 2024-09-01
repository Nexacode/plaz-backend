<?php $sum = 0 ?>
<?php $sum_user = 0 ?>

<b>{{$users}}</b>

<table border="1">
<tr><td>id</td><td>Name</td><td>Todo</td><td>Todo offen</td><td>offene Zeit</td><td>Hauptprojekt</td></tr>
@foreach ($projects as $project)
<tr><td>{{$project->id}}</td><td>{{$project->name}}</td><td>{{count($project->todo)}}</td><td>{{count($project->todoopen)}}</td><td>{{$project->todoopenstatistic_sum_calculated_time}}</td><td>{{$project->parentproject->name ?? ''}}</td></tr>

<?php $sum += $project->todoopenstatistic_sum_calculated_time; ?>
@endforeach
<tr><td></td><td></td><td></td><td></td><td>{{$sum}}</td></tr>
<tr><td></td><td></td><td></td><td></td><td>{{$projecttime[0]['project_time']}}</td></tr>
</table>
<br>
<table border="1">
@foreach($users as $user)
<?php $sum_user += $user->time_per_week; ?>
@endforeach
<tr><td></td><td></td><td></td><td>{{$user->name}}</td><td>{{$sum_user}}</td></tr>
</table>
<br><br>
{{$future->format('d.m.Y')}}//{{$weeks}}
<br><br>
<b>{{ $sum }}</b>
<br><br>

<br><br>
<b>{{ count($projects) }}</b>