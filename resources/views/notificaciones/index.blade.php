<!-- resources/views/notificaciones/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Notificaciones</h1>

    @foreach($notificaciones as $notificacion)
        <div class="notification {{ $notificacion->es_leida ? 'read' : 'unread' }}">
            <p>{{ $notificacion->mensaje }}</p>
            <form action="{{ route('notificaciones.read', $notificacion->id
