<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <div id="app">
        <ul>
            <li v-for="item in items">
                @{{item}}
            </li>
        </ul>
    </div>
</body>
<script type="text/javascript" src="{{asset('js/vue.js')}}"></script>
<script>
    var app = new Vue({
        el:'#app',
        data:{
            items:['asd','b','c']
        }
    });
</script>
</html>