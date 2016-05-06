@extends('frontend.layout')

@section('content')
    <section class="game">
        <div class="container">
            <div class="row">
                <h4>Прохождение игры</h4>
                <div id="game">
                    <a href="#rules" class="modal-trigger">Читать правила прохождения квеста</a>
                    <div class="code-check">
                        <p>Для начала необходимо ввести уникальный код, который приходит на почту
                            после покупки квеста.
                        </p>
                        <hr>
                        <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="text" name="code" placeholder="enter the code">
                        <button class="waves-effect waves-light btn" v-on="click:getGame($event)">ENTER</button>
                    </div>
                    <div class="question-info relative">
                        <h5 v-if="game != null">@{{ game.title }}</h5>
                        <p v-if="question != null" data-id='@{{ question.id }}' id='question-id'>
                            @{{ question.order }}.
                            @{{ question.question }}
                        </p>
                        <input v-if="question != null" type='text' placeholder='answer' name='answer'/>
                        <button class="waves-effect waves-light btn"
                                    v-if="question != null" v-on="click: answerTheQuestion()">ANSWER</button>
                        <p v-if="errors.length > 0" class="errors red-text">Errors: @{{ errors }}</p>
                        <div class="hints-bar" v-if="question != null">
                            <span>Hints: </span>
                            <a class="modal-trigger"
                               v-repeat="hint: hints | orderBy 'order'"
                               v-on="click : getHint(hint, $event)"
                               id="hintTrigger-@{{ hint.id }}"
                               href="#hint-@{{ hint.id }}"><i class="fa fa-envelope-o"></i></a>
                            {{--<p class="right-align"><a href="#info" class="modal-trigger">Info</a></p>--}}
                        </div>
                        <div class="hint" v-repeat="openHint: openHints">
                            @{{{ openHint.info }}}
                        </div>
                        <hr>
                        <div class="center-align image">
                            <img src="@{{ game.thumbnail[0].path }}" alt="">
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </section>
    <div id="info" class="modal">
        <div class="modal-content">
            <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
            <div class="info"></div>
            <div id="clockdiv"></div>
            <div id="trophy"></div>
        </div>
    </div>
    <div id="rules" class="modal">
        <div class="modal-content">
            <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
            <h4>Правила прохождения квеста</h4>
                <ul>
                    <li>Для начала необходимо ввести уникальный код, который приходит на почту
                        после покупки квеста
                    </li>
                    <li>Активировать код можно в течение 6 месяцев с момента покупки, а на прохождение
                        самого квеста 10 дней
                    </li>
                    <li>После завершения игра может открываться полностью еще в течение суток, после
                        доступ закрывается
                    </li>
                    <li>После прохождения игры, в качестве бонуса открывается окошко со всем пройденным
                        маршрутом, на котором отмечены все пройденные точки. Это картинка, может быть
                        скачана (в pdf или jpg), а также по завершению игры она приходит на электронную
                        почту вместе с предложениями других маршрутов.
                    </li>
                    <li>Дополнительный вариант при выборе опции (при заказе) командная игра: 2 команды
                        соревнуются на время, соответственно идет время. Надо сделать так, чтобы команды
                        знали, кто на каком этапе находится и в конце каждой команде выходило время,
                        за которое они закончили.
                    </li>
                </ul>
        </div>
    </div>
    <style>
        .fa-envelope-o:before {
            content: "\f003";
        }
        .hints-bar{
            position: absolute;
            right: 0;
            top: 0;
        }
        .relative{
            position: relative;
        }
        .hint{
            margin: 5px 0;
            padding: 5px 7px;
            background: rgba(255,165,0,0.5);
        }
        .hints-bar a{
            padding: 0 3px;
        }
        .game{
            min-height: 350px;
        }
    </style>
@endsection
@section('bottom-scripts')
    <script src="{!! url('admin/assets/js/vue.js') !!}"></script>
    <script>
        $(function(){

            function getTimeRemaining(endtime){
                var t = Date.parse(endtime) - Date.parse(new Date());
                var seconds = Math.floor( (t/1000) % 60 );
                var minutes = Math.floor( (t/1000/60) % 60 );
                var hours = Math.floor( (t/(1000*60*60)) % 24 );
                var days = Math.floor( t/(1000*60*60*24) );
                return {
                    'total': t,
                    'days': days,
                    'hours': hours,
                    'minutes': minutes,
                    'seconds': seconds
                };
            }
            function initializeClock(id, endtime){
                var clock = document.getElementById(id);
                var timeinterval = setInterval(function(){
                    var t = getTimeRemaining(endtime);
                    clock.innerHTML = t.hours + ':' + t.minutes + ':' + t.seconds;
                    if(t.total<=0){
                        clearInterval(timeinterval);
                    }
                },1000);
            }
            new Vue({
                el: "#game",

                ready:function(){
                    var vue = this;
                },

                data: {
                    question: null,
                    code: null,
                    game: null,
                    errors:[],
                    hints:[],
                    openHints:[],
                    gameId: null,
                    token: document.getElementById("token").value
                },

                methods : {
                    getHint: function(hint, event){
                        event.preventDefault();
                        var vue = this;
                        $.ajax("/hint/" + hint.id).done(function (data) {
                            vue.openHints.push(data);
                            vue.hints.splice(vue.hints.indexOf(hint), 1);
                        })
                    },
                    getGame: function(event){
                        $(".err-note").remove();
                        var vue = this;
                        event.preventDefault();
                        var code=$("[name='code']").val();
                        //alert(code);
                        $.ajax({
                            url: '/check-code?code=' + code,
                            method: 'GET'
                        }).done(function(e){
                            if(e){
                                $(".code-check").remove();
                                $("#game").append("<div class='game-wrap'></div>");
                                vue.code = e;
                                vue.game = e.product;
                                if(vue.code.question_id != "0"){
                                    vue.setQuestion(vue.code.question);
                                }
                                else{
                                    vue.setQuestion(vue.game.questions[0]);
                                }
                            }
                            else{
                                $(".code-check").append("<p class='red-text err-note'>Error</p>");
                            }
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            console.log("error");
                            console.dir(arguments);
                        });
                    },
                    setQuestion: function (question) {
                        $("[name='answer']").val("");
                        this.question = question;
                        this.hints = question.hints;
                    },
                    answerTheQuestion: function(){
                        var data = new FormData();
                        var vue = this, dataId = $('#question-id').attr("data-id"), answer = $("[name='answer']").val();
                        data.append('data-id', dataId);
                        data.append('answer', answer);
                        data.append('codeId', vue.code.id);
                        data.append('_token', vue.token);
                        if(answer!=""){
                            $.ajax({
                                //url: '/check-answer?data-id=' + dataId +"&answer=" + answer
                                //+ "&codeId=" + vue.code.id,
                                //method: 'GET'
                                url: '/check-answer/',
                                type: 'POST',
                                data: data,
                                processData: false,
                                contentType: false,
                                dataType: 'json'
                            }).done(function(data){
                                if(data && data != "") {
                                    vue.openHints = [];
                                    vue.errors = [];
                                    if(typeof data == "string"){
                                        vue.showNote("You completed the game. " +
                                                "But you have some time to play it more: ");
                                        initializeClock('clockdiv', data);
                                        $("#trophy").append("<a href='/" + vue.game.pdf  +
                                               "' target='_blank'>Маршрут квеста</a> <a class='right' href='" +
                                                "/game'>Пройти заново</a>");
                                        vue.question = null;
                                    }
                                    else{
                                        vue.showNote(data.info);
                                        vue.setQuestion(data);
                                    }
                                }
                                else
                                {
                                    vue.errors.push(answer);
                                }
                            }).fail(function (jqXHR, textStatus, errorThrown) {
                                console.log("error");
                                console.dir(arguments);
                            });;
                        }
                        else{
                            vue.showNote("Enter the answer");
                        }
                    },
                    showNote: function(e){
                      $("#info").find(".info").html(e);
                      $("#info").openModal();
                    },

                    makeSlug: function(event){
                        event.preventDefault();
                        this.slug = this.prepareSlug()
                    },

                    prepareSlug: function () {
                        var answer = '',
                                title = this.title,
                                translate = this.translate;

                        for (var i in title) {
                            if (translate.hasOwnProperty(title[i])) {
                                if (translate[title[i]] !== undefined) {
                                    answer += translate[title[i]];
                                }
                            } else {
                                answer += title[i];
                            }
                        }

                        return answer.toLocaleLowerCase()
                                .replace(/[^a-z0-9-]/, '-')
                                .replace(/-{2,}/g, '-')
                                .replace(/^[\s\uFEFF\xA0-]+|[\s\uFEFF\xA0-]+$/g, '');
                    }
                }
            });
        });
    </script>
@endsection