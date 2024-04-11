<?php
require 'vendor/autoload.php';
require 'header.php';
?>

<div class="px-4 my-5 text-center">
    <h1 class="display-5 fw-bold text-body-emphasis">GOVI Browser</h1>
    <h4 class="fw-bold text-secondary">Global Ontology and Vocabulary Index</h4>
    <div class="col-lg-6 mx-auto">
        <p class="lead mt-4">
            Browse the GOVI <a href="https://github.com/k00ni/govi">index</a> easily.
            TODO
        </p>
        <div class="d-grid d-sm-flex justify-content-sm-center mt-5">
            <form action="">
                <div class="input-group w-100">
                    <input type="text" name="search-term" class="form-control" placeholder="What are you interested in?">
                    <button type="button" class="btn btn-primary btn-lg px-4">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>

<hr>

<?php require 'index-list.php'; ?>

<?php
require 'footer.php';