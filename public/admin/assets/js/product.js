new Vue({
    el: "#product",

    ready:function(){
        var vue = this;

        this.getImages();
        setTimeout(function(){
            vue.getFields();
        }, 100);

        this.getRelatedProducts();

        $.get('/dashboard/helpers/translate').done(function(data){
            vue.translate = data;
        });

        setTimeout(function(){
            $("button").prop('disabled', false);
        }, 1000);

        this.getFilterValues();
        this.initSelectize();
    },

    data: {
        images: [],
        disabled: false,
        productId: null,
        category: null,
        token: document.getElementById("form-data")._token.value,
        title: null,
        slug:null,
        PDF: null,
        fields: {},
        flashObject:{},
        video: {},
        filterValues: [],
        productsList:{
            products:{},
            pagination:{
                currentPage: {},
                lastPage: {},
                pageToGet: 1
            }
        },
        relOptions:{
            category:0,
            paginate: 20,
            search: null,
            selected: []
        },
        test: null,
        relatedProducts: null,
        translate: {},
        selectedProductsIds: []
    },

    computed: {

        stringImagesIds: function(){
            var images = [];
            for(var i = 0, len = this.images.length; i < len; i++ ){
                images[i] = this.images[i].id;
            }
            return images.join(',');
        },

        isDisabled: function () {
            if(this.images.length >= 15) {
                return true;

                return false;
            }
        },

        sellPriceRub: function(){
            var price = Math.ceil(this.sellPrice * this.currency / 1000) * 1000;
            if(price > 0) return price;
        },
        rentPriceRub: function(){
            var price = Math.ceil(this.rentPrice * this.currency / 1000) * 1000;
            if(price > 0) return price;
        },
        rentPriceM2Rub: function(){
            var price = Math.ceil(this.rentPriceM2 * this.currency / 10) * 10;
            if(price > 0) return price;
        }

    },
    methods : {


        getFilterValues: function(){
            var vue = this;
            $.ajax({
                url: '/dashboard/values',
                method: 'GET'
            }).done(function(values){
                console.log(values)
                vue.filterValues = values;
            })
        },

        getImages: function () {
            var that = this;
            $.ajax({
                type: "POST",
                url: "/dashboard/get-images/" + that.productId,
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
            $.post('/dashboard/product-actions/getRelatedProducts', {_token: this.token, productId: this.productId})
                .done(function(products){
                    vue.relOptions.selected = products;
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
                url: '/dashboard/product-actions/getProducts',
                cache: false,
                data: {
                    categoryId: vue.relOptions.category,
                    paginate: vue.relOptions.paginate,
                    search: vue.relOptions.search,
                    selected: vue.getSelectedProductsIds(),
                    page: vue.productsList.pagination.pageToGet
                },
                success: function (response) {
                    //console.log(response);
                    vue.productsList.products = response.data;
                    vue.productsList.pagination.currentPage = response.current_page;
                    vue.productsList.pagination.lastPage = response.last_page;
                    if(vue.productsList.pagination.lastPage < vue.productsList.pagination.pageToGet) {
                        vue.productsList.pagination.pageToGet = vue.productsList.pagination.lastPage;
                        vue.getProducts()
                    }

                    $(vue.$$.cover).hide();

                }
            });
        },

        nextPage: function(event){
            event.preventDefault();
            if(this.productsList.pagination.currentPage != this.productsList.pagination.lastPage){
                this.productsList.pagination.pageToGet = this.productsList.pagination.currentPage + 1;
                this.getProducts();
            }
        },

        prevPage: function(event){
            event.preventDefault();
            if(this.productsList.pagination.currentPage != 1) {
                this.productsList.pagination.pageToGet = this.productsList.pagination.currentPage - 1;
                this.getProducts();
            }
        },

        syncProducts: function(){
            this.selectedProductsIds = this.getSelectedProductsIds();
            $.post('/dashboard/product-actions/syncRelated',
                {
                    _token: this.token,
                    ids: this.getSelectedProductsIds(),
                    productId: this.productId
                })
        },

        addProduct: function(event, relProduct){
            event.preventDefault();
            this.productsList.products.$remove(relProduct);
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
            $.post("/dashboard/set-thumbnail/" + image.id, {_token: vue.token, productId : vue.productId} );
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
                url: "/dashboard/remove-pdf/" + vue.productId,
                data: {_token : vue.token}
            }).done(function(){
                vue.PDF = null;
            });
        },


        getFields: function(){
            var vue = this;
            $("#filters").addClass('loading');
            $.get('/dashboard/filters/'+ this.productId, {category_id: this.category }).done(function(response){
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
                url: "/dashboard/remove-flash/" + vue.productId,
                data: {_token : vue.token}
            }).done(function(){
                vue.flashObject = null;
            });
        },

        getSelectedProductsIds: function(){
            var productsIds = [];
            this.relOptions.selected.forEach(function(product){
                productsIds.push(product.id);
            });
            return productsIds;
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