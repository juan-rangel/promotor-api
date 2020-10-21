@component('mail::message')
# Olá,
Esta é uma notificação solicitada pela equipe de promotores, avisando que existem alguns produtos com estoque físico abaixo do ideal.

@component('mail::panel')
# {{$cliente->nome_fantasia}}
{{$cliente->rua}} - {{$cliente->bairro}}, {{$cliente->cidade}}
@endcomponent

## Lista de produtos com ruptura ou estoque menor que 10:

@component('mail::table')
| Código | Produto | Quantidade |
| ------------- |:-------------:| --------:|
@foreach($produtos as $produto)
{{(int) $produto->sap_cod_produto}} | {{$produto->info_amigavel->descricao}} | {{$produto->estoque_fisico}}
@endforeach
@endcomponent

@component('mail::panel')
Data prevista para a loja receber a visita:
### {{Carbon\Carbon::parse($roteiro->data_execucao)->format('d/m/Y')}}
@endcomponent

@component('mail::subcopy')
Atenciosamente,<br>
Equipe Vitao Alimentos
@endcomponent

@endcomponent