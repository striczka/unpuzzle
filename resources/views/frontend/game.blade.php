@extends('frontend.layout')

@section('content')
    <section class="game-active-title">
        <div class="container">
            <div class="row center-align">
                <h5 v-if="game != null" class="item-title">@{{ game.title }}</h5>
            </div>
        </div>
    </section>

    <section class="game-logo">
        <div class="container">
            <div class="row center-align">
                <div class="no-padding logo-col game-header-logo @{{ question != null ? 'in-game' : '' }}">
                    <a id="logo-container" href="/" class="brand-logo">
                        <img class="responsive-img" src="/frontend/images/logo.png"/>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="@{{ !!game ? 'down' : '' }} arc">
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
    <section class="@{{ !!game ? 'active' : '' }} game">
        <div class="container relative">
            <div class="row">
                <div class="game-trophy center-align" v-if="game != null">
                    <br>
                    <p class="game-note"></p>
                    <hr>
                    <div id="clockdiv"></div>
                    <div id="trophy">
                        <img v-bind:src="trophy" alt=""/>
                        <hr>
                    </div>
                </div>
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
                        <div v-if="question != null" data-id='@{{ question.id }}' id='question-id'>
                            @{{{ question.question }}}
                        </div>
                        <hr>
                        <div class="hints-bar col s12 no-padding" v-if="question != null">
                            <span class="left need-help enter-answer">Need help? : </span>
                            <span class="hint-links left">
                            <a class="modal-trigger"
                               v-for="hint in hints | orderBy 'order'"
                               v-on:click="getHint(hint, $event)"
                               id="hintTrigger-@{{ hint.id }}"
                               href="#hint-@{{ hint.id }}"><i class="fa fa-star"></i></a>
                            </span>
                            {{--<p class="right-align"><a href="#info" class="modal-trigger">Info</a></p>--}}
                        </div>
                        <hr>
                        <div class="hint" v-for="openHint in openHints">
                            @{{{ openHint.info }}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="game-answer-block col s12">
        <div class="container">
            <div class="row">
                <p class="enter-answer" v-if="question != null">Enter your answer:</p>
                <input class="game-code-input" v-if="question != null" type='text' placeholder='Your answer:'
                       name='answer'
                       @keyup.enter="answerTheQuestion()"/>
                <p v-if="errors.length > 0" class="errors red-text">Errors: @{{ errors }}</p>
            </div>
        </div>
    </section>
    <section class="answer-button-block game-code-submit waves-effect waves-light btn"
             v-if="question != null" v-on:click="answerTheQuestion()">
        <div class="container">
            <div class="row">
                <span>ANSWER</span>
            </div>
        </div>
    </section>
    @inject('settingsProvider', '\App\ViewDataProviders\SettingsDataProvider')
    <section class="game-footer">
        <div class="container">
            <div class="row">
                <div class="left game-footer-logo-wrap">
                    <a href="/">
                        <img class="responsive-img game-footer-logo" src="/frontend/images/logo.png"/>
                    </a>
                </div>
                <div class="right game-footer-info">
                    <p class="muli">Go to <a href="/" class="red-text game-domain">unpuzzleBarcelona.com</a></p>
                    <p class="muli">
                        @if(array_get($settingsProvider->getSettings(),'footer_phone1'))
                            <a class=""
                               href="tel:{{ preg_replace('/[^\d+]+/','',array_get($settingsProvider->getSettings(),'footer_phone1')) }} ">
                                {{ array_get($settingsProvider->getSettings(),'footer_phone1') }}
                            </a>
                        @endif
                        (Support phone)
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="game-yellow" v-if="question == null"></section>
    <div id="info" class="modal">
        <div class="modal-content">
            <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
            <div class="info"></div>
        </div>
    </div>
    <div id="empty" class="modal">
        <div class="modal-content">
            <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
            <div class="info"></div>
        </div>
    </div>
    <style>
        .trophy img{
            max-width: 100%;
        }
        .game-code-submit:hover{
            color:#fff;
        }
        .question-info, #progress-bar, .game-answer-block, .answer-button-block{
            display:none;
        }
        .fa-star:before {
            content: "\f005";
        }
        .hint-links a{
            float:right;
            font-size:22px;
        }
        .hint-links a:first-child{
            color:#ffd242;
        }
        .hint-links a:not(:first-child){
            pointer-events: none;
            color:#d1d6d9;
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
        .col.game-answer-block{
            background: #eceff1;
            padding: 0 0 10px;
        }
        .enter-answer{
            font-family: 'AAvanteBs', sans-serif;
            text-transform: uppercase;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 1px;
            padding: 10px 0;
        }
        .need-help{
            padding: 0 10px 0 0;
            line-height: 25px;
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
            margin-bottom:18px;
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
            min-width:207px;
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
            min-width:207px;
            display: inline-block;
            margin: 10px auto;
            border: 1px solid #990100;
        }
        .answer-button-block{
            background: #990100;
            position:relative;
            width: 100%;
            border-radius: 0;
        }
        .col.game-answer-block input[type=text].game-code-input{
            width:100%;
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
            border-bottom: 1px solid #e8e8e8;
        }
        .game-active-title .item-title{
            padding: 0;
            line-height: 45px;
        }
        .answer-button-block:before{
            content: "\f00c";
            display: inline-block;
            font: normal normal normal 14px/1 FontAwesome;
            color: #fff;
            font-size: 16px;
            width: 47px;
            text-align: center;
            line-height: 47px;
            border-right: 1px solid #fff;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
        .game-yellow:before{
            content:'';
            position:absolute;
            top:5px;
            width:100%;
            height:1px;
            background:#e8e8e8;
        }
        .game-yellow{
            height:53px;
            position:relative;
            background:#ffd242;
        }
        .game-footer{
            border-top: 1px solid #e8e8e8;
            border-bottom: 1px solid #e8e8e8;
            background: #f8f6f6;
            padding:7px 0 5px;
        }
        img.game-footer-logo{
            height: 40px;
        }
        .game-footer-info p{
            font-size:12px;
            line-height: 18px;
            letter-spacing: 0;
        }
        .game-header-logo.in-game {
            margin: 0 auto;
        }
        .game-header-logo{
            margin: 12px auto;
            float:none
        }
        .question-info{
            padding-top:10px
        }
        @media screen and (max-width:600px){
            .game-note {
                max-width: 250px;
                margin: 0 auto;
            }
            .game:not(.active):before {
                background-size: 100% auto;
            }
            [class*='game'] .container {
                width: 86%;
            }
            .game-footer-logo-wrap {
                width:35%
            }
            img.game-footer-logo {
                height: auto;
            }
            .game-active-title .item-title {
                font-size: 22px;
            }
        }
        @media screen and (max-width:350px) {
            .game-footer-info{
                margin-top: 10px;
            }
            .game-footer-logo-wrap, .game-footer-info{
                width: 100%;
                text-align: center;
            }
            img.game-footer-logo {
                height: 50px;
            }
        }
    </style>
@endsection
@section('bottom-scripts')
    <script src="{!! url('admin/assets/js/vue.js') !!}"></script>
    <script>
        $(function(){
            var csrfToken = $('[name="_token"]').attr('content');

            setInterval(refreshToken, 3600000); // 1 hour

            function refreshToken(){
                $.get('refresh-csrf').done(function(data){
                    csrfToken = data; // the new token
                });
            }

            setInterval(refreshToken, 3600000); // 1 hour

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
                    trophy: null,
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
                                $(".answer-button-block").show();
                                $(".game-answer-block").show();
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
                                        $("#progress-bar").remove();
                                        $("#game").remove();
                                        $(".answer-button-block").remove();
                                        $(".game-answer-block").remove();

                                        $(".game-trophy").find("p").html(vue.question.info);

                                       // initializeClock('clockdiv', data);
                                        vue.trophy = vue.game.pdf;
                                        console.log(vue.trophy);
                                        $("#trophy").append("<a href='/' class='game-code-submit " +
                                                "center-align uppercase red'>Try another quest</a>");
                                        //vue.showNote();

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
                            });
                        }
                        else{
                            $("#empty .info").text("Enter the answer");
                            $("#empty").openModal();
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