<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway';
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
@include('blog.partials.google')
<div class="flex-center position-ref full-height">
@if (Route::has('login'))
<div class="top-right links">
<a href="{{ url('/login') }}">Login</a>
{{--<a href="{{ url('/register') }}">Register</a>--}}
<a href="{{ url('/blog') }}">To My Blog</a>
</div>
@endif

{{--<div class="content">--}}
{{--<div class="title m-b-md">--}}
{{--Laravel--}}
{{--</div>--}}

{{--<div class="links">--}}
{{--<a href="https://laravel.com/docs">Documentation</a>--}}
{{--<a href="https://laracasts.com">Laracasts</a>--}}
{{--<a href="https://laravel-news.com">News</a>--}}
{{--<a href="https://forge.laravel.com">Forge</a>--}}
{{--<a href="https://github.com/laravel/laravel">GitHub</a>--}}
{{--</div>--}}
{{--</div>--}}
</div>

{{-- vue --}}
<div class="container">
    <div class="content">
        <input v-model="newTodo" v-on:keyup.enter="addTodo">
        <ul>
            <li v-for="todo in todos">
                <span>@{{ todo.text }}</span>
                <button v-on:click="removeTodo($index)">X</button>
            </li>
        </ul>
    </div>
</div>

<script src="http://vuejs.org/js/vue.js"></script>

<script>
    new Vue({
        el:'.content',
        data:{
            newTodo:'',
            todos:[
                { text:'new todos'}
            ]
        },
        methods:{
            addTodo:function () {
                var text = this.newTodo.trim()
                if (text){
                    this.todos.push({text:text})
                    this.newTodo=''
                }
            },
            removeTodo:function (index) {
                this.todos.splice(index,1)
            }
        }
    })
</script>

</body>
</html>
