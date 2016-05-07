@extends('frontend.layout')

@section('content')
    <section class="game-active-title">
        <div class="container">
            <div class="row center-align">
                <h5 v-if="game != null" class="item-title">@{{ game.title }}</h5>
            </div>
        </div>
    </section>
    <section class="@{{ !!question ? 'down' : '' }} arc">
        <div class="container">
            <div class="row center-align">
                <p v-if="question == null" class="game-title muli">Hi!</p>
                <div id="progress-bar">
                     <span v-for="sign in questions"
                           class="@{{ sign.id == question.id ? 'active' : '' }}">@{{ $index + 1 }}</span>
                </div>
            </div>
        </div>
    </section>
    <section class="@{{ !!question ? 'active' : '' }} game">
        <div class="container relative">
            <div class="row">
                <div id="game">
                    <div class="center-align code-check">
                        <p class="uppercase red-text game-subtitle muli">You are about to start<br>
                            your Barcelona adventure
                        </p>
                        <hr>
                        <p class="center-align game-note">Please enter the code that you received
                            by email to the window below</p>
                        <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input class="game-code-input" type="text" name="code"
                               @keyup.enter="getGame($event)"
                               autocomplete="off"
                               placeholder="Enter security code:">
                        <button class="game-code-submit waves-effect waves-light btn"
                                v-on:click="getGame($event)">GO!</button>
                    </div>
                    <div class="question-info relative">
                        <p v-if="question != null" data-id='@{{ question.id }}' id='question-id'>
                            @{{ question.question }}
                        </p>
                        <div class="hints-bar" v-if="question != null">
                            <span>Hints: </span>
                            <span class="hint-links">
                            <a class="modal-trigger"
                               v-for="hint in hints | orderBy 'order'"
                               v-on:click="getHint(hint, $event)"
                               id="hintTrigger-@{{ hint.id }}"
                               href="#hint-@{{ hint.id }}"><i class="fa fa-star"></i></a>
                            </span>
                            {{--<p class="right-align"><a href="#info" class="modal-trigger">Info</a></p>--}}
                        </div>
                        <input v-if="question != null" type='text' placeholder='answer'
                               name='answer'
                               @keyup.enter="answerTheQuestion()"/>
                        <button class="waves-effect waves-light btn"
                                v-if="question != null" v-on:click="answerTheQuestion()">ANSWER</button>
                        <p v-if="errors.length > 0" class="errors red-text">Errors: @{{ errors }}</p>
                        <div class="hint" v-for="openHint in openHints">
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
    <style>
        .question-info, #progress-bar{
            display:none;
        }
        .fa-star:before {
            content: "\f003";
        }
        .hint-links a:not(:first-child){
            pointer-events: none;
            opacity:0.5;
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
        .game:not(.active):before{
            content: "";
            background: url("/images/mailing.png") center bottom no-repeat;
            width: 100%;
            position: absolute;
            height: 100%;
        }
        .game:not(.active){
            position: relative;
            min-height: 440px;
            margin-bottom:15px;
            background: -webkit-linear-gradient(top, #FFD449 0%, #ffefba 80%, #ffefba 90%, #fff 100%);
            background: linear-gradient(top, #FFD449 0%, #ffefba 80%, #ffefba 90%, #fff 100%);
        }
        .arc{
            width: 100%;
            padding-top: 40px;
            background-color: #ffd449;
            border-top-left-radius: 40%;
            border-top-right-radius: 40%;
            border-top: 1px solid #eaeaea;
            border-bottom: 0;
            padding-bottom:6px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .arc.down{
            height: 65px;
            border-bottom-left-radius: 40%;
            border-bottom-right-radius: 40%;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom: 1px solid #eaeaea;
            padding: 17px 0;
        }
        .muli{
            font-family: "muliregular", sans-serif;
        }
        .game-subtitle {
            line-height: 16px;
        }
        .game-title{
            font-size: 28px;
            color: #990100;
        }
        hr{
            border: none;
            width: 60%;
            height: 2px;
            background: url("/frontend/images/hr.png");
        }
        .game-code-submit{
            font-family: 'AvenirNextLTProBold', sans-serif;
            font-size:14px;
            color:#fff;
            line-height: 43px;
            height: 43px;
            border-radius: 4px;
            width: 50%;
            display: inline-block;
            border: 1px solid #990100;
        }
        .game-note{
            font-family:"fregatbold",sans-serif;
            font-size:12px;
            font-style:italic;
            line-height: 16px;
        }
        input[type=text].game-code-input{
            font-family:"fregatbold",sans-serif;
            font-size:12px;
            font-style:italic;
            line-height: 43px;
            height: 43px;
            padding: 0 9px;
            background: #fff;
            border-radius: 4px;
            width: 50%;
            display: inline-block;
            margin: 10px auto;
            border: 1px solid #990100;
        }
        .err-note{
            margin-top: 5px;
        }
        #progress-bar span.active {
            background: #990100;
            color:#fff;
            border-color:#990100;
        }
        #progress-bar span:first-child{
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }
        #progress-bar span:last-child{
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        #progress-bar span{
            display: inline-block;
            height: 30px;
            width: 43px;
            font-size: 12px;
            font-family: "muliregular", sans-serif;
            line-height: 30px;
            background: #fff;
            text-align: center;
            border:1px solid #e8e8e8;
        }
        .game-active-title{
            display: none;
            background: #990100;
        }
        .game-active-title .item-title{
            padding: 0;
            line-height: 45px;
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
                el: "body",

                ready:function(){
                    var vue = this;
                },

                data: {
                    questions: [],
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
                                $(".question-info").show();
                                $("#progress-bar").show();
                                $(".game-active-title").show();
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
                                vue.questions = vue.game.questions;
                            }
                            else{
                                $(".code-check").append("<p class='red-text err-note game-note'>Error! Your code is " +
                                        "incorrect or not active.</p>");
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
                                url: '{{route("check.answer")}}',
                                type: 'POST',
                                data: data,
                                processData: false,
                                contentType: false,
                                dataType: 'json'
                            }).done(function(data){
                                $("#info").find(".info").html(vue.question.info);
                                if(data && data != "") {
                                    vue.openHints = [];
                                    vue.errors = [];
                                    if(typeof data == "string"){
                                        $("#info").find(".info").append("<p>You completed the game. " +
                                                "But you have some time to play it more: </p>");
                                        initializeClock('clockdiv', data);
                                        $("#trophy").append("<a href='/" + vue.game.pdf  +
                                                "' target='_blank'>Маршрут квеста</a> <a class='right' href='" +
                                                "/game'>Пройти заново</a>");
                                        vue.showNote();

                                        vue.question = null;
                                    }
                                    else{
                                        // vue.showNote(data.info);
                                        // $("#info").find(".info").html(e);
                                        vue.showNote();
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
                    showNote: function(){
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