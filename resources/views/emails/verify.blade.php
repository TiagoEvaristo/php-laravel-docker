@component('mail::message')
# Validação do E-mail

<p>Obrigado por se cadastrar no Beto.</p> 
<p>Digite este código para validar o seu e-mail</p>
<p><strong>{{$pin}}</strong></p>
<p>Este código expira en 5 minutos.</p>


Obrigado,<br>
{{ config('app.name') }}
@endcomponent