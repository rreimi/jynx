<?php
$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

@if($paginator->getLastPage() > 1)
<div class="row-fluid clear-both">
    <div class="pagination pagination-small pagination-centered">
        <ul>
            {{ $presenter->render() }}
        </ul>
    </div>
</div>
@endif