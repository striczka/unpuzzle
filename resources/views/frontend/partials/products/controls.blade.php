<div class="col s12 control no-padding" id="catalog">
    <div class="col s8 no-padding">
        <select class="right orderBy" form="filter" name="orderBy">
            <option value="price:asc">Сначала дешевые</option>
            <option value="price:desc">Сначала дорогие</option>
            <option value="id:desc" {{ Request::is('new') ? 'selected' : '' }}>Сначала новые</option>
            <option value="id:asc">Сначала старые</option>
            <option value="title:asc">По названию</option>
        </select>
        <span class="right sort">Сортировать:</span>
    </div>
    <div class="col s4 no-padding _pagination" style="position: relative">
        {{--@if(!Request::has('filter'))--}}
            {{--{!! with(new \App\Services\CustomPagination($products))->render() !!}--}}
        {{--@endif--}}
    </div>
    <div class="clearfix"></div>
</div>