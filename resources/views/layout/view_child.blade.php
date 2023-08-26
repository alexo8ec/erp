@extends('layout.view_plantilla')

@section('title',view('layout.view_title'))@show
@section('css',view('layout.view_css'))@show
@section('menu',view('layout.view_menu'))@show
@section('barra_empresa',view('layout.view_barra_empresa'))@show
@section('barra_usuario',view('layout.view_barra_usuario'))@show
@section('contenido',view(config('data.contenido')))@show
@section('chat',view('layout.view_chat'))@show
@section('footer',view('layout.view_footer'))@show
@section('opciones',view('layout.view_opciones'))@show
@section('variables',view('layout.view_variables_generales'))@show
@section('notificaciones',view('layout.view_notificaciones'))@show
@section('js',view('layout.view_js'))@show