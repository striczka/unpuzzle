@extends('frontend.layout')

@section('content')
    <section class="game">
        <div class="container">
            <div class="row">
                <h4>Страница игры</h4>
                <div id="game">
                    <div class="code-check">
                        <input id="token" type="hidden" v-model="_token" name="_token" value="{{ csrf_token() }}">
                        <input type="text" name="code" placeholder="enter the code">
                        <input type="submit" v-on="click:getGame($event)" value="Enter">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('bottom-scripts')
    <script src="{!! url('admin/assets/js/vue.js') !!}"></script>
    <script>
        $(function(){
            new Vue({
                el: "#game",

                ready:function(){
                    var vue = this;
                },

                data: {
                    questions: [],
                    gameId: null,
                    token: document.getElementById("token").value
                },

                methods : {

                    getGame: function(event){
                        var vue = this;
                        event.preventDefault();
                        var code=$("[name='code']").val();
                        alert(code);
                        $.ajax({
                            url: '/check-code?code=' + code,
                            method: 'GET'
                        }).done(function(data){
                            if(data){
                                $(".code-check").remove();
                                $("#game").append("<div class='game-wrap'></div>");
                                vue.setFirstQuestion(data, $(".game-wrap"));
                            }
                            else{
                                $(".code-check").append("<p class='red err-note'>Error</p>");
                            }
                        })
                    },
                    setFirstQuestion: function (game, wrap) {
                        wrap.append(game.title);
                    },
                    getImages: function () {
                        var that = this;
                        $.ajax({
                            type: "POST",
                            url: "/dashboard/get-images/" + that.gameId,
                            data: {_token : that.token}
                        }).done(function(images){
                            if(images){
                                that.images = images;
                            }
                        });
                    },
                    getRelatedProducts: function(){
                        var vue = this;
                        //$(this.$$.cover).show();
                        $.post('/dashboard/game-actions/getRelatedProducts', {_token: this.token, gameId: this.gameId})
                                .done(function(games){
                                    vue.relOptions.selected = games;
                                    //$(vue.$$.cover).hide();
                                })
                    },
                    getProducts: function(){
                        var vue = this;
                        $(this.$$.cover).show();
                        //console.log(this.getSelectedProductsIds());
                        $.ajax({
                            dataType: "json",
                            method: "GET",
                            url: '/dashboard/game-actions/getProducts',
                            cache: false,
                            data: {
                                categoryId: vue.relOptions.category,
                                paginate: vue.relOptions.paginate,
                                search: vue.relOptions.search,
                                selected: vue.getSelectedProductsIds(),
                                page: vue.gamesList.pagination.pageToGet
                            },
                            success: function (response) {
                                //console.log(response);
                                vue.gamesList.games = response.data;
                                vue.gamesList.pagination.currentPage = response.current_page;
                                vue.gamesList.pagination.lastPage = response.last_page;
                                if(vue.gamesList.pagination.lastPage < vue.gamesList.pagination.pageToGet) {
                                    vue.gamesList.pagination.pageToGet = vue.gamesList.pagination.lastPage;
                                    vue.getProducts()
                                }

                                $(vue.$$.cover).hide();

                            }
                        });
                    },

                    nextPage: function(event){
                        event.preventDefault();
                        if(this.gamesList.pagination.currentPage != this.gamesList.pagination.lastPage){
                            this.gamesList.pagination.pageToGet = this.gamesList.pagination.currentPage + 1;
                            this.getProducts();
                        }
                    },

                    prevPage: function(event){
                        event.preventDefault();
                        if(this.gamesList.pagination.currentPage != 1) {
                            this.gamesList.pagination.pageToGet = this.gamesList.pagination.currentPage - 1;
                            this.getProducts();
                        }
                    },

                    syncProducts: function(){
                        this.selectedProductsIds = this.getSelectedProductsIds();
                        $.post('/dashboard/game-actions/syncRelated',
                                {
                                    _token: this.token,
                                    ids: this.getSelectedProductsIds(),
                                    gameId: this.gameId
                                })
                    },

                    addProduct: function(event, relProduct){
                        event.preventDefault();
                        this.gamesList.games.$remove(relProduct);
                        this.relOptions.selected.push(relProduct);
                        this.getProducts();
                        this.syncProducts();
                    },
                    removeProduct: function(event, relProduct){
                        event.preventDefault();
                        this.relOptions.selected.$remove(relProduct);
                        this.getProducts();
                        this.syncProducts();
                    },
                    loadImage: function () {
                        var that = this;
                        var uploadInput = $('#image'); // Инпут с файлом

                        //slug = document.getElementById("form-data")._token.value;
                        //console.dir(uploadInput[0].files);
                        for(var property in uploadInput[0].files) {
                            if(!isNaN(property)){
                                var data = new FormData();
                                //console.log(uploadInput[0].files[property]);
                                data.append('file', uploadInput[0].files[property]);
                                data.append('_token', this.token);
                                $.ajax({
                                    url: '/dashboard/upload-image',
                                    type: 'POST',
                                    data: data,
                                    processData: false,
                                    contentType: false,
                                    dataType: 'json'
                                }).done(function(image){
                                    that.images.push(image);
                                    if(that.images.length == 1){
                                        that.setAsThumbnail(image);
                                    }
                                }).fail(function(jqXHR, textStatus, errorThrown){ //replaces .error
                                    console.log("error");
                                    console.dir(arguments);
                                });
                            }
                        }
                        uploadInput.val(null);
                    },

                    removeImage: function(image){
                        var that = this,
                                token = document.getElementById("form-data")._token.value;

                        $.ajax({
                            type: "POST",
                            url: "/dashboard/remove-image/" + image.id,
                            data: {_token : token}
                        }).done(function(){
                            var index = that.images.indexOf(image);
                            if(index > -1)
                                that.images.splice(index, 1);
                        });
                    },
                    setAsThumbnail: function(image) {
                        var vue = this;
                        for(var img in vue.images){
                            vue.images[img].is_thumb = false;
                        }
                        vue.images[vue.images.indexOf(image)].is_thumb = true;
                        $.post("/dashboard/set-thumbnail/" + image.id, {_token: vue.token, gameId : vue.gameId} );
                    },

                    loadPDF: function(){
                        this.PDF = $(this.$$.pdfInput).val().split('\\').pop();
                    },

                    removePDF: function(event){
                        event.preventDefault();
                        var vue = this;
                        $(this.$$.pdfInput).val(null);
                        $.ajax({
                            type: "POST",
                            url: "/dashboard/remove-pdf/" + vue.gameId,
                            data: {_token : vue.token}
                        }).done(function(){
                            vue.PDF = null;
                        });
                    },


                    getFields: function(){
                        var vue = this;
                        $("#filters").addClass('loading');
                        $.get('/dashboard/filters/'+ this.gameId, {category_id: this.category }).done(function(response){
                            $("#filters .inner").html(response);
                            $("#filters").removeClass('loading');
                            vue.initSelectize();
                        })
                    },

                    load3D: function(event){
                        this.flashObject = $(this.$$.flashInput).val().split('\\').pop();
                    },



                    loadVideo: function(event){
                        event.preventDefault();
                        var vue = this;
                        bootbox.prompt("Введите HTML код видео", function(result) {
                            if (result ) {
                                vue.video = result;
                            }
                        });

                    },

                    removeVideo: function(event){
                        event.preventDefault();
                        this.video = null;
                    },

                    removeFlash: function(event){
                        event.preventDefault();
                        var vue = this;
                        $(this.$$.flashInput).val(null);
                        $.ajax({
                            type: "POST",
                            url: "/dashboard/remove-flash/" + vue.gameId,
                            data: {_token : vue.token}
                        }).done(function(){
                            vue.flashObject = null;
                        });
                    },

                    getSelectedProductsIds: function(){
                        var gamesIds = [];
                        this.relOptions.selected.forEach(function(game){
                            gamesIds.push(game.id);
                        });
                        return gamesIds;
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
                    },

                    initSelectize: function(){

                        $('.selectize').selectize({
                            create: true,
                            createOnBlur: true,
                            sortField: 'text'
                        });
                    }
                }
            });
        });
    </script>
@endsection