<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <div id="app">
        <div v-if="isLogin">你好：</div>
        <div v-else>请登录后操作</div>

    </div>
</body>
<script type="text/javascript" src="{{asset('js/vue.js')}}"></script>
<script>
    var app = new Vue({
        el:'#app',
        data:{
            isLogin:false
        }
    });
</script>
</html>