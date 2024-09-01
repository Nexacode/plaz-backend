<div style="font-size:14px;font:Arial;">
Hallo {{ $user_name }},<br><br>
@if($start != $end)
du hast von {{\Carbon\Carbon::createFromFormat('Y-m-d', $start)->format('d.m.Y')}} - {{\Carbon\Carbon::createFromFormat('Y-m-d', $end)->format('d.m.Y')}} Urlaub beantragt. Der Urlaubsantrag befindet sich im Anhang dieser Mail.<br><br>
@endif
@if($start == $end)
du hast am {{\Carbon\Carbon::createFromFormat('Y-m-d', $start)->format('d.m.Y')}} Urlaub beantragt. Der Urlaubsantrag befindet sich im Anhang dieser Mail.<br><br>
@endif
Sobald der Urlaub genehmigt wurde, erhältst du eine Bestätigung. Bitte beachte, dass es einige Tage
dauern kann, bis du diese Bestätigung erhälst. <br><br>
Mit freundlichen Grüßen
</div>
<div style="font-size:14px;font:Arial;margin-top:5px">
power4-<span style="color:orange;">its</span> GmbH<br>
Am Kirschenäcker 1<br>
36148 Kalbach/Uttrichshausen<br><br>
Tel: +49 (0) 9742 930 209 750<br><br>
Geschäftsführung: Sascha Koziellek<br>
Amtsgericht Fulda: HRB 8019<br>
USt-Ident-Nr. : DE 343 633 067
</div>
<div style="font-size:11px;color:#73818f;margin-top:20px;">
Die übermittelte Information ist nur für die Person oder das Unternehmen bestimmt, an die sie gerichtet ist, und kann vertrauliche oder bevorrechtigte<br> 
Daten enthalten. Jede Art der Überprüfung, Weitersendung oder Verbreitung sowie jede Verwendung der Information durch andere Personen oder Unternehmen als den angegebenen Adressaten ist verboten.<br>
Falls Sie diese Nachricht irrtümlich erhalten haben, setzen Sie sich bitte mit dem Absender in Verbindung und löschen das Material aus jedem Computer.
</div>