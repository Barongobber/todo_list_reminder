@component('mail::message')
Hello {{ $details['name'] }}. This is your reminder regarding:
 
Todo List Title: {{$details['title']}}

Your Tasks:

@foreach ($details['tasks'] as $val)
    - {{ $val['title'] }}

@endforeach

@endcomponent
